@extends('layouts.app')

@section('title', 'Leave History | EEMOT Clocking PWA')

@section('content')
<style>
    .leave-shell { display: flex; flex-direction: column; gap: 14px; }
    .leave-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .leave-hero-icon {
        width: 50px; height: 50px; border-radius: 16px; background: var(--grad-main);
        display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff;
        box-shadow: 0 10px 24px rgba(255,90,60,0.3);
    }
    .leave-hero .eyebrow { text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; color: var(--text-dim); font-weight: 600; }
    .leave-hero h1 { color: #fff; font-weight: 800; font-size: 1.28rem; margin: 2px 0 4px; }
    .leave-hero p { color: var(--text-muted); font-size: 0.82rem; margin: 0; }
    .leave-card { background: var(--panel); border: 1px solid var(--border-soft); border-radius: 20px; padding: 18px; }
    .leave-card h3 { color: #fff; font-size: 1rem; font-weight: 700; margin: 0 0 4px; }
    .leave-card .hint { color: var(--text-dim); font-size: 0.78rem; margin-bottom: 14px; }
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 14px; }
    .stat-box { background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 14px; padding: 12px; text-align: center; }
    .stat-box .value { display: block; color: #fff; font-size: 1rem; font-weight: 800; margin-bottom: 2px; }
    .stat-box .label { color: var(--text-dim); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; }
    .history-item { background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 14px; padding: 12px 14px; margin-bottom: 10px; }
    .history-item:last-child { margin-bottom: 0; }
    .history-top { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 6px; }
    .history-top strong { color: #fff; font-size: 0.9rem; }
    .status-pill { border-radius: 999px; padding: 4px 8px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
    .status-approved { background: rgba(34,197,94,0.16); color: #4ade80; }
    .status-pending { background: rgba(245,158,11,0.16); color: #fbbf24; }
    .status-reject { background: rgba(248,113,113,0.16); color: #f87171; }
    .history-meta { color: var(--text-dim); font-size: 0.75rem; margin-bottom: 6px; }
    .history-desc { color: var(--text-muted); font-size: 0.82rem; line-height: 1.45; }
    .bottom-links { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 6px; }
    .bottom-links a { text-decoration: none; color: var(--text-dim); font-size: 0.8rem; }
</style>

@if($apiError ?? null)
    <div class="alert alert-warning">{{ $apiError }}</div>
@endif

<div class="leave-shell">
    <section class="leave-hero">
        <div class="leave-hero-icon"><i class="bi bi-clock-history"></i></div>
        <div>
            <p class="eyebrow">Leave Management</p>
            <h1>Leave History</h1>
            <p>Track all your requests and review statuses over time.</p>
        </div>
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

        <h3>Recent Requests</h3>
        <p class="hint">Your complete leave history appears here.</p>

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

    <div class="bottom-links">
        <a href="{{ route('leave') }}">← Back to overview</a>
        <a href="{{ route('leave.apply') }}">New leave request</a>
    </div>
</div>
@endsection