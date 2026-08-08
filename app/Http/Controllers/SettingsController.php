<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Carbon\Carbon;
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
        $viewData = $this->leaveViewData($api);
        $response = [];

        try {
            $response = $api->leaveTypes();
        } catch (\Throwable) {
            $response = [];
        }

        $viewData['leaveTypes'] = collect(data_get($response, 'data', []))->all();

        return view('leave.apply', $viewData);
    }

    public function leaveHistory(ApiService $api): View
    {
        return view('leave.history', $this->leaveViewData($api));
    }

    public function documents(): View
    {
        $documents = [
            [
                'title' => 'Offer Letter',
                'description' => 'Legal offer document',
                'file' => '#',
            ],
            [
                'title' => 'Appointment Letter',
                'description' => 'Official appointment letter',
                'file' => '#',
            ],
            [
                'title' => 'Experience Letter',
                'description' => 'Experience certificate',
                'file' => '#',
            ],
            [
                'title' => 'Identity Card',
                'description' => 'Official ID card document',
                'file' => '#',
            ],
            [
                'title' => 'Salary Slip',
                'description' => 'Monthly salary slip document',
                'file' => '#',
            ],
            [
                'title' => 'Policy Letter',
                'description' => 'Company policy document',
                'file' => route('settings.policy.download'),
            ],
        ];

        return view('settings.documents', compact('documents'));
    }

    public function policyDownload(): \Illuminate\Http\Response
    {
        $pdfUrl = 'https://new.eemotclocking.in/terms/policy.pdf';

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(30)->get($pdfUrl);
        } catch (\Throwable $exception) {
            abort(404, 'Policy document is not available right now.');
        }

        if (! $response->successful()) {
            abort(404, 'Policy document is not available right now.');
        }

        return response($response->body(), 200)
            ->header('Content-Type', $response->header('Content-Type') ?: 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="policy.pdf"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }

    public function salarySlipDownload(Request $request, ApiService $api)
    {
        $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2000'],
        ]);

        $userId = (int) ($request->input('user_id') ?: authUserId() ?: 10);
        $month = (int) $request->input('month');
        $year = (int) $request->input('year');

        try {
            $response = $api->salarySlip($userId, $month, $year);
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Unable to download salary slip. Please try again later.');
        }

        if (! $response->successful()) {
            return back()->withInput()->with('error', 'Unable to download salary slip for the selected date.');
        }

        $fileName = sprintf('salary-slip-%s-%s-%s.pdf', $userId, $month, $year);

        return response($response->body(), 200)
            ->header('Content-Type', $response->header('Content-Type') ?: 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }

    public function salaryDetails(Request $request, ApiService $api): View
    {
        $defaultMonth = now()->subMonth()->format('Y-m');
        $selectedMonth = $request->query('month', $defaultMonth);

        [$year, $month] = explode('-', $selectedMonth) + [null, null];
        $salaryData = [];
        $apiError = null;
        $userId = authUserId();

        if (! $userId) {
            $apiError = 'Unable to determine your account. Please login again.';
        } elseif (! $year || ! $month) {
            $apiError = 'Invalid month selected. Please choose a valid month and year.';
        } else {
            try {
                $response = $api->salaryDetails($userId, (int) $month, (int) $year);

                if (! data_get($response, 'status', false)) {
                    $apiError = data_get($response, 'message', 'Unable to load salary details.');
                } else {
                    $salaryData = data_get($response, 'data', []);
                }
            } catch (\Throwable $exception) {
                $apiError = 'Unable to load salary details. Please try again later.';
            }
        }

        return view('settings.salary', [
            'selectedMonth' => $selectedMonth,
            'salaryData' => $salaryData,
            'apiError' => $apiError,
        ]);
    }

    public function emailReports(): View
    {
        $defaultMonth = now()->format('Y-m');

        return view('settings.email_reports', [
            'defaultMonth' => $defaultMonth,
            'userEmail' => data_get(authUser(), 'email', ''),
        ]);
    }

    public function sendEmailReport(Request $request, ApiService $api)
    {
        $request->validate([
            'month' => ['required', 'date_format:Y-m'],
        ]);

        $userId = authUserId();

        if (! $userId) {
            return back()->with('error', 'Unable to determine your account. Please login again.');
        }

        // Use logged-in user's email (fall back to employee email)
        $email = data_get(authUser(), 'email') ?: data_get(employee(), 'email');

        if (! $email) {
            return back()->withInput()->with('error', 'No email is available for your account. Please update your profile.');
        }

        [$year, $month] = explode('-', $request->input('month'));

        $apiResponse = [];

        try {
            $apiResponse = $api->sendMonthlyAttendanceReport(
                $userId,
                (int) $month,
                (int) $year,
                $email
            );
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Unable to send report. Please try again later.');
        }

        $apiMessage = data_get($apiResponse, 'message', 'Unable to send report.');

        if (! data_get($apiResponse, 'success', false)) {
            return back()->withInput()->with('error', $apiMessage);
        }

        return back()->with('success', $apiMessage);
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

        $leaveRequests = collect(data_get($response, 'data', []));
        $month = Carbon::now();

        $monthlyRequests = $leaveRequests->filter(function ($request) use ($month) {
            if (! isset($request['date_from'])) {
                return false;
            }

            return Carbon::parse($request['date_from'])->isSameMonth($month);
        });

        $monthlyApproved = $monthlyRequests->where('status', 'approved')->count();
        $monthlyPending = $monthlyRequests->where('status', 'pending')->count();
        $monthlyApplied = $monthlyRequests->count();
        $paidLeaveLimit = 1;
        $remainingPaidLeave = max(0, $paidLeaveLimit - $monthlyApproved);

        return [
            'leaveRequests' => $leaveRequests->all(),
            'leaveCounts' => data_get($response, 'counts', []),
            'leaveStats' => [
                'current_month' => $month->format('F'),
                'approved' => $monthlyApproved,
                'pending' => $monthlyPending,
                'applied' => $monthlyApplied,
                'paid_leave_limit' => $paidLeaveLimit,
                'used_paid_leave' => $monthlyApproved,
                'remaining_paid_leave' => $remainingPaidLeave,
            ],
            'apiError' => data_get($response, 'message') && ! data_get($response, 'success') ? data_get($response, 'message') : null,
        ];
    }

    public function storeLeave(Request $request, ApiService $api): RedirectResponse
    {
        $request->validate([
            'leave_type' => ['required', 'string'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $userId = authUserId();

        if (! $userId) {
            return back()->withInput()->with('error', 'Unable to determine your account. Please login again.');
        }

        $payload = [
            'user_id' => $userId,
            'leave_type_id' => $request->input('leave_type'),
            'description' => $request->input('description', ''),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'status' => 'pending',
        ];

        try {
            $response = $api->submitLeave($payload);
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Could not submit leave request. Please try again later.');
        }

        $apiMessage = data_get($response, 'message')
            ?: data_get($response, 'error')
            ?: 'Unable to submit leave request.';

        if (! data_get($response, 'success', false)) {
            return back()->withInput()->with('error', $apiMessage);
        }

        return redirect()->route('leave')->with('success', $apiMessage);
    }

    public function updatePassword()
    {
        // TODO: apna password-update logic yaha likhna, jaise API call ya DB update
    }
}