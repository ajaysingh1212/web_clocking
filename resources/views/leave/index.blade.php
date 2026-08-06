@extends('layouts.app')

@section('title', 'Leave Overview | EEMOT Clocking PWA')

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
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 14px; }
    .stat-box { background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 14px; padding: 12px; text-align: center; }
    .stat-box .value { display: block; color: #fff; font-size: 1rem; font-weight: 800; margin-bottom: 2px; }
    .stat-box .label { color: var(--text-dim); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; }
    .quick-actions { display: grid; gap: 10px; }
    .quick-link { display: flex; align-items: center; justify-content: space-between; text-decoration: none; background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 14px; padding: 14px 16px; color: #fff; }
    .quick-link span { color: var(--text-dim); font-size: 0.78rem; display: block; margin-top: 2px; }
    .quick-link i { color: var(--accent-orange); font-size: 18px; }
</style>

<div class="leave-shell">
    <section class="leave-hero">
        <div class="leave-hero-icon"><i class="bi bi-calendar2-week-fill"></i></div>
        <div>
            <p class="eyebrow">Leave Management</p>
            <h1>Leave Overview</h1>
            <p>Track your leave status and jump into the right section instantly.</p>
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

        <div class="quick-actions">
            <a href="{{ route('leave.apply') }}" class="quick-link">
                <div>
                    <strong>Apply Leave</strong>
                    <span>Create a new leave request</span>
                </div>
                <i class="bi bi-plus-circle-fill"></i>
            </a>
            <a href="{{ route('leave.history') }}" class="quick-link">
                <div>
                    <strong>Leave History</strong>
                    <span>Review all your past requests</span>
                </div>
                <i class="bi bi-clock-history"></i>
            </a>
        </div>
    </section>
</div>
@endsection