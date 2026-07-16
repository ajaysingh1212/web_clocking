<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(ApiService $api): View
    {
        $apiError = null;
        $userId = authUserId();

        try {
            if ($userId) {
                $details = $api->userDetails($userId);
                $user = data_get($details, 'user');

                if (is_array($user)) {
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
            }
        } catch (RequestException $exception) {
            if ($exception->response?->status() === 401) {
                session()->forget('api.auth');
                abort(redirect()->route('login')->withErrors(['email' => 'Session expired. Please sign in again.']));
            }

            $apiError = data_get($exception->response?->json(), 'message', 'Unable to refresh profile details.');
        } catch (ConnectionException) {
            $apiError = 'Network error while refreshing profile details.';
        }

        return view('profile.index', compact('apiError'));
    }
}
