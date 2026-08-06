<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
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

    public function updatePassword()
    {
        // TODO: apna password-update logic yaha likhna, jaise API call ya DB update
    }
}