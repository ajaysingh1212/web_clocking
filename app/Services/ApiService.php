<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use RuntimeException;

class ApiService
{
    public function login(array $credentials): array
    {
        $response = $this->guest()->post('login', $credentials);

        if (! str_contains((string) $response->header('Content-Type'), 'json')) {
            throw new RuntimeException('Login API is returning a browser security check instead of JSON. Please allow server/API access on the backend domain.');
        }

        return $response->json() ?? [];
    }

    public function todayAttendance(int|string $userId): array
    {
        return $this->request()->get("attendance/today/{$userId}")->json() ?? [];
    }

    public function calendarReport(int|string $userId, string $month): array
    {
        return $this->request()
            ->get("attendance/calendar/{$userId}", ['month' => $month])
            ->json() ?? [];
    }

    public function userDetails(int|string $userId): array
    {
        return $this->request()->get("user-details/{$userId}")->json() ?? [];
    }

    public function punchAttendance(int|string $userId, float|string $latitude, float|string $longitude, string $location, UploadedFile $image): array
    {
        $request = $this->request()->attach(
            'punch_image',
            file_get_contents($image->getRealPath()),
            $image->getClientOriginalName()
        );

        return $request->post('https://new.eemotclocking.in/api/v1/attendance/punch', [
            'user_id' => $userId,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location' => $location,
        ])->json() ?? [];
    }

    public function product(int|string $id): array
    {
        return $this->request()->get("products/{$id}")->json() ?? [];
    }

    private function guest(): PendingRequest
    {
        return Http::baseUrl(config('services.eemot_api.base_url'))
            ->acceptJson()
            ->timeout(config('services.eemot_api.timeout'))
            ->retry(1, 250)
            ->throw(fn (Response $response) => $this->handleFailure($response));
    }

    private function request(): PendingRequest
    {
        return $this->guest()->withToken((string) token());
    }

    private function handleFailure(Response $response): void
    {
        if ($response->status() === 401) {
            Session::forget('api.auth');
        }
    }
}