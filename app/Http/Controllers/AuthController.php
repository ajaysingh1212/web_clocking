<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\ApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

class AuthController extends Controller
{
    public function login(): View|RedirectResponse
    {
        $this->restoreRememberedSession();

        if (token()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function authenticate(LoginRequest $request, ApiService $api): RedirectResponse
    {
        try {
            $response = $api->login($request->only('email', 'password'));
            $remember = $request->boolean('remember');
            $this->storeApiSession($response, $remember);
            $this->syncUserDetails($api);
            $this->rememberLogin(apiSession(), $remember);

            return redirect()->route('home')->with('success', 'Signed in successfully.');
        } catch (RequestException $exception) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => $this->apiError($exception)]);
        } catch (ConnectionException) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Unable to reach the attendance server. Please try again.']);
        } catch (RuntimeException $exception) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => $exception->getMessage()]);
        }
    }

    public function logout(): RedirectResponse
    {
        session()->forget('api.auth');
        session()->invalidate();
        session()->regenerateToken();
        Cookie::queue(Cookie::forget('eemot_remember'));

        return redirect()->route('login')->with('success', 'Signed out successfully.');
    }

    private function storeApiSession(array $response, bool $remember): array
    {
        $user = data_get($response, 'user', []);
        $employee = data_get($response, 'employee') ?: data_get($user, 'employee', []);

        $payload = [
            'raw' => $response,
            'login_response' => $response,
            'token' => data_get($response, 'token') ?? data_get($response, 'access_token'),
            'user' => $user,
            'employee' => $employee,
            'roles' => data_get($response, 'roles') ?: data_get($user, 'roles', []),
            'branch' => data_get($response, 'branch') ?? data_get($employee, 'branch'),
            'profile_image' => data_get($response, 'profile_image')
                ?? data_get($employee, 'profile_image')
                ?? data_get($employee, 'profile_photo')
                ?? data_get($response, 'user.profile_image')
                ?? data_get($response, 'user.image'),
            'remember' => $remember,
        ];

        if (! $payload['token']) {
            throw ValidationException::withMessages([
                'email' => 'The login API did not return a token.',
            ]);
        }

        session(['api.auth' => $payload]);
        session()->regenerate();

        return $payload;
    }

    private function rememberLogin(array $payload, bool $remember): void
    {
        if ($remember) {
            $value = encrypt(json_encode($payload));
            Cookie::queue(cookie('eemot_remember', $value, now()->addDays(30))->httpOnly()->sameSite('lax'));

            return;
        }

        Cookie::queue(Cookie::forget('eemot_remember'));
    }

    private function restoreRememberedSession(): void
    {
        if (token()) {
            return;
        }

        $remembered = request()->cookie('eemot_remember');

        if (! $remembered) {
            return;
        }

        try {
            $payload = json_decode(decrypt($remembered), true);

            if (! empty($payload['token'])) {
                session(['api.auth' => $payload]);
                session()->regenerate();
            }
        } catch (\Throwable $exception) {
            Cookie::queue(Cookie::forget('eemot_remember'));
        }
    }

    private function syncUserDetails(ApiService $api): void
    {
        $userId = authUserId();

        if (! $userId) {
            return;
        }

        try {
            $details = $api->userDetails($userId);
        } catch (RequestException|ConnectionException) {
            return;
        }

        $user = data_get($details, 'user');

        if (! is_array($user)) {
            return;
        }

        session([
            'api.auth' => array_replace_recursive(apiSession(), [
                'raw' => array_replace_recursive(apiSession('raw', []), ['user_details' => $details]),
                'user_details_response' => $details,
                'user' => $user,
                'employee' => data_get($user, 'employee', apiSession('employee', [])),
                'roles' => data_get($details, 'roles', apiSession('roles', [])),
                'branch' => data_get($user, 'employee.branch', apiSession('branch')),
                'profile_image' => data_get($user, 'image') ?? apiSession('profile_image'),
            ]),
        ]);
    }

    private function apiError(RequestException $exception): string
    {
        $response = $exception->response;
        $json = $response?->json();
        $body = (string) $response?->body();

        if (str_contains($body, 'Checking your browser before accessing') || str_contains($body, 'jschallenge')) {
            return 'API security check is blocking server login. Please whitelist this app server/IP or disable browser challenge for /api/v1 routes.';
        }

        return data_get($json, 'message')
            ?? data_get($json, 'error')
            ?? data_get($json, 'errors.email.0')
            ?? data_get($json, 'errors.password.0')
            ?? 'Login failed. Please check your credentials.';
    }
}
