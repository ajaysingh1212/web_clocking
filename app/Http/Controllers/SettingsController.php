<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('settings');
    }

    public function notifications(ApiService $api): View
    {
        $response = $api->notifications();
        $notifications = data_get($response, 'data', []);

        return view('notifications.index', [
            'notifications' => $notifications,
            'apiError' => data_get($response, 'message') && ! data_get($response, 'status') ? data_get($response, 'message') : null,
        ]);
    }

    public function leaveLanding(ApiService $api): View
    {
        return view('leave.index', $this->leaveViewData($api));
    }

    public function leaveApply(ApiService $api): View
    {
        return view('leave.apply', $this->leaveViewData($api));
    }

    public function leaveHistory(ApiService $api): View
    {
        return view('leave.history', $this->leaveViewData($api));
    }

    private function leaveViewData(ApiService $api): array
    {
        $userId = authUserId();
        $response = [];

        if ($userId) {
            try {
                $response = $api->leaveRequestsByUser($userId);
            } catch (\Throwable) {
                $response = [];
            }
        }

        return [
            'leaveRequests' => data_get($response, 'data', []),
            'leaveCounts' => data_get($response, 'counts', []),
            'apiError' => data_get($response, 'message') && ! data_get($response, 'success') ? data_get($response, 'message') : null,
        ];
    }

    public function storeLeave(Request $request): RedirectResponse
    {
        $request->validate([
            'leave_type' => ['required', 'string'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        return back()->with('success', 'Leave request form is ready. API integration will be wired next.');
    }

    public function updatePassword()
    {
        // TODO: apna password-update logic yaha likhna, jaise API call ya DB update
    }
}