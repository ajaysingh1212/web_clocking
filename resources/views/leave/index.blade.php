@extends('layouts.app')

@section('title', 'Leave | EEMOT Clocking PWA')

@section('content')
<style>
    .leave-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 16px;
    }

    .leave-hero .eyebrow {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: var(--text-dim);
        font-weight: 600;
    }

    .leave-hero h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.35rem;
        margin: 2px 0 4px;
    }

    .leave-hero p {
        color: var(--text-muted);
        font-size: 0.84rem;
        margin: 0;
    }

    .leave-card {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 14px;
    }

    .leave-card h3 {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 4px;
    }

    .leave-card .hint {
        color: var(--text-dim);
        font-size: 0.78rem;
        margin-bottom: 14px;
    }

    .leave-form-grid {
        display: grid;
        gap: 12px;
    }

    .leave-form-grid label {
        color: var(--text-dim);
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .leave-form-grid input,
    .leave-form-grid select,
    .leave-form-grid textarea {
        width: 100%;
        background: var(--panel-soft);
        border: 1.5px solid var(--border-soft);
        border-radius: 12px;
        padding: 12px 14px;
        color: #fff;
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
    }

    .leave-form-grid textarea {
        min-height: 90px;
        resize: vertical;
    }

    .leave-form-grid input:focus,
    .leave-form-grid select:focus,
    .leave-form-grid textarea:focus {
        border-color: var(--accent-orange);
        background: rgba(255,122,26,0.06);
        outline: none;
        box-shadow: 0 0 0 4px rgba(255,122,26,0.1);
    }

    .leave-actions {
        display: flex;
        gap: 10px;
        margin-top: 8px;
    }

    .btn-primary-leave {
        flex: 1;
        border: none;
        border-radius: 12px;
        padding: 12px 14px;
        color: #fff;
        font-weight: 700;
        background: var(--grad-main);
        box-shadow: 0 10px 24px rgba(255,90,60,0.3);
    }

    .btn-secondary-leave {
        flex: 1;
        border: 1.5px solid var(--border-soft);
        border-radius: 12px;
        padding: 12px 14px;
        background: transparent;
        color: var(--text-muted);
        font-weight: 600;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 14px;
    }

    .stat-box {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 14px;
        padding: 12px;
        text-align: center;
    }

    .stat-box .value {
        display: block;
        color: #fff;
        font-size: 1rem;
        font-weight: 800;
        margin-bottom: 2px;
    }

    .stat-box .label {
        color: var(--text-dim);
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .history-item {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 10px;
    }

    .history-item:last-child {
        margin-bottom: 0;
    }

    .history-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
    }

    .history-top strong {
        color: #fff;
        font-size: 0.9rem;
    }

    .status-pill {
        border-radius: 999px;
        padding: 4px 8px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .status-approved {
        background: rgba(34,197,94,0.16);
        color: #4ade80;
    }

    .status-pending {
        background: rgba(245,158,11,0.16);
        color: #fbbf24;
    }

    .status-reject {
        background: rgba(248,113,113,0.16);
        color: #f87171;
    }

    .history-meta {
        color: var(--text-dim);
        font-size: 0.75rem;
        margin-bottom: 6px;
    }

    .history-desc {
        color: var(--text-muted);
        font-size: 0.82rem;
        line-height: 1.45;
    }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($apiError ?? null)
    <div class="alert alert-warning">{{ $apiError }}</div>
@endif

<section class="leave-hero">
    <p class="eyebrow">Leave Management</p>
    <h1>Apply &amp; Track Leave</h1>
    <p>Submit new leave requests and review your complete leave history in one place.</p>
</section>

<section class="leave-card">
    <h3>New Leave Request</h3>
    <p class="hint">Quick form for your next leave application.</p>

    <form method="POST" action="{{ route('leave.store') }}" class="leave-form-grid">
        @csrf
        <div>
            <label for="leave_type">Leave Type</label>
            <select id="leave_type" name="leave_type" required>
                <option value="">Select leave type</option>
                <option value="casual">Casual Leave</option>
                <option value="sick">Sick Leave</option>
                <option value="annual">Annual Leave</option>
            </select>
        </div>

        <div>
            <label for="date_from">From</label>
            <input id="date_from" name="date_from" type="date" required>
        </div>

        <div>
            <label for="date_to">To</label>
            <input id="date_to" name="date_to" type="date" required>
        </div>

        <div>
            <label for="description">Reason / Note</label>
            <textarea id="description" name="description" placeholder="Write the reason for your leave"></textarea>
        </div>

        <div class="leave-actions">
            <button class="btn-secondary-leave" type="reset">Reset</button>
            <button class="btn-primary-leave" type="submit">Submit Leave</button>
        </div>
    </form>
</section>

<section class="leave-card">
    <div class="stats-row">
        <div class="stat-box">
            <span class="value">{{ data_get($leaveCounts, 'pending', 0) }}</span>
            <span class="label">Pending</span>
        </div>
        <div class="stat-box">
            <span class="value">{{ data_get($leaveCounts, 'approved', 0) }}</span>
            <span class="label">Approved</span>
        </div>
        <div class="stat-box">
            <span class="value">{{ data_get($leaveCounts, 'reject', 0) }}</span>
            <span class="label">Rejected</span>
        </div>
    </div>

    <h3>Leave History</h3>
    <p class="hint">Recent requests with status and dates.</p>

    @if(!empty($leaveRequests))
        @foreach($leaveRequests as $leave)
            <div class="history-item">
                <div class="history-top">
                    <strong>{{ data_get($leave, 'title') ?: 'Leave Request' }}</strong>
                    <span class="status-pill status-{{ data_get($leave, 'status', 'pending') }}">{{ data_get($leave, 'status_label') ?: ucfirst((string) data_get($leave, 'status', 'Pending')) }}</span>
                </div>
                <div class="history-meta">
                    {{ data_get($leave, 'date_from') }} to {{ data_get($leave, 'date_to') }}
                </div>
                <div class="history-desc">
                    {{ data_get($leave, 'description') ?: 'No additional note provided.' }}
                </div>
            </div>
        @endforeach
    @else
        <div class="history-item">
            <div class="history-desc">No leave history available yet.</div>
        </div>
    @endif
</section>
@endsection