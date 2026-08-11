<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManualAttendanceRequest;
use App\Services\ApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeveloperController extends Controller
{
    public function index(): View
    {
        return view('developer');
    }

    public function markAttendance(): View
    {
        return view('attendance.manual');
    }

    public function storeManualAttendance(ManualAttendanceRequest $request, ApiService $api): RedirectResponse
    {
        $validated = $request->safe()->all();

        try {
            $api->manualAttendance(
                $validated['user_id'],
                $validated['date'],
                $validated['action'],
                $validated['time'],
                $validated['latitude'],
                $validated['longitude'],
                $validated['location'],
                $request->file('image')
            );

            return redirect()->route('developer.mark-attendance')->with('success', 'Manual attendance submitted successfully.');
        } catch (RequestException $exception) {
            if ($exception->response?->status() === 401) {
                session()->forget('api.auth');

                return redirect()->route('login')->withErrors(['email' => 'Session expired. Please sign in again.']);
            }

            return back()
                ->withInput($request->except('image'))
                ->withErrors(['image' => data_get($exception->response?->json(), 'message', 'Unable to submit attendance.')]);
        } catch (ConnectionException) {
            return back()
                ->withInput($request->except('image'))
                ->withErrors(['image' => 'Network error while submitting attendance.']);
        }
    }
}