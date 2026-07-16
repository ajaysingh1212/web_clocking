<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request, ApiService $api): View
    {
        $calendar = [];
        $apiError = null;
        $userId = authUserId();
        $month = $request->query('month', now()->format('Y-m'));

        try {
            if ($userId) {
                $calendar = $api->calendarReport($userId, $month);
            }
        } catch (RequestException $exception) {
            if ($exception->response?->status() === 401) {
                session()->forget('api.auth');
                abort(redirect()->route('login')->withErrors(['email' => 'Session expired. Please sign in again.']));
            }

            $apiError = data_get($exception->response?->json(), 'message', 'Unable to load calendar report.');
        } catch (ConnectionException) {
            $apiError = 'Network error while loading calendar report.';
        }

        return view('report', [
            'calendar' => $calendar,
            'month' => $month,
            'apiError' => $apiError,
            'userId' => $userId,
        ]);
    }
}