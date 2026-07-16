<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(ApiService $api): View
    {
        $attendance = [];
        $apiError = null;
        $userId = authUserId();

        try {
            if ($userId) {
                $attendance = $api->todayAttendance($userId);
            }
        } catch (RequestException $exception) {
            if ($exception->response?->status() === 401) {
                session()->forget('api.auth');
                abort(redirect()->route('login')->withErrors(['email' => 'Session expired. Please sign in again.']));
            }

            $apiError = data_get($exception->response?->json(), 'message', 'Unable to load today attendance.');
        } catch (ConnectionException) {
            $apiError = 'Network error while loading today attendance.';
        }

        return view('home.index', [
            'attendance' => $attendance,
            'apiError' => $apiError,
            'userId' => $userId,
        ]);
    }
}
