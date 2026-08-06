<?php

namespace App\Http\Controllers;

use App\Http\Requests\PunchRequest;
use App\Services\ApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
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
            $this->validateAttendanceRadius(
                (float) $validated->latitude,
                (float) $validated->longitude
            );

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
        } catch (ValidationException $exception) {
            return back()
                ->withInput($request->except('image'))
                ->withErrors($exception->errors());
        }
    }

    private function validateAttendanceRadius(float $latitude, float $longitude): void
    {
        $employee = employee();
        $branch = branch();
        $radius = (int) data_get($employee, 'attendance_radius_meter', 0);

        if ($radius <= 0 || ! is_array($branch)) {
            return;
        }

        $branchLatitude = (float) data_get($branch, 'latitude', 0);
        $branchLongitude = (float) data_get($branch, 'longitude', 0);

        if ($branchLatitude === 0.0 || $branchLongitude === 0.0) {
            return;
        }

        $distance = $this->distanceInMeters(
            $latitude,
            $longitude,
            $branchLatitude,
            $branchLongitude
        );

        if ($distance > $radius) {
            throw ValidationException::withMessages([
                'location' => 'You are outside the allowed attendance radius of '.$radius.' meters from the branch location.',
            ]);
        }
    }

    private function distanceInMeters(float $latitude, float $longitude, float $branchLatitude, float $branchLongitude): float
    {
        $earthRadius = 6371000;

        $latDelta = deg2rad($latitude - $branchLatitude);
        $lonDelta = deg2rad($longitude - $branchLongitude);
        $lat1 = deg2rad($latitude);
        $lat2 = deg2rad($branchLatitude);

        $a = sin($latDelta / 2) * sin($latDelta / 2)
            + cos($lat1) * cos($lat2) * sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}