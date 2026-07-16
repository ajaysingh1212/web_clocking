<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings'); // resources/views/settings.blade.php — 'settings.index' nahi
    }

    public function updatePassword()
    {
        // TODO: apna password-update logic yaha likhna, jaise API call ya DB update
    }
}