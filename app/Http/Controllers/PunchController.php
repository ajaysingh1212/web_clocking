<?php

namespace App\Http\Controllers;

use App\Http\Requests\PunchRequest;
use App\Services\ApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PunchController extends Controller
{
    public function index(): View
    {
        return view('attendance.punch');
    }

    public function store(PunchRequest $request, ApiService $api): RedirectResponse
    {
        $userId = authUserId();

        if (! $userId) {
            return back()->withErrors(['action' => 'Unable to identify the logged-in user from the API session.']);
        }

        $validated = $request->safe();

        try {
            $api->punchAttendance(
                $userId,
                $validated->latitude,
                $validated->longitude,
                $validated->location,
                $request->file('image')
            );

            return redirect()->route('home')->with('success', 'Attendance submitted successfully.');
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