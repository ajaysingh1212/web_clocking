@extends('layouts.app')

@section('title', 'Developer Options | EEMOT Clocking PWA')

@section('content')
<style>
    .dev-hero {
        background: linear-gradient(135deg, rgba(255,61,90,0.14), rgba(255,122,26,0.12));
        border: 1px solid rgba(255,61,90,0.3);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dev-hero .eyebrow {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #ff8095;
        font-weight: 700;
    }

    .dev-hero h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.4rem;
        margin: 2px 0 4px;
    }

    .dev-hero span {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .dev-hero > i {
        width: 52px; height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, #ff3d5a, #ff7a1a);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 10px 24px rgba(255,61,90,0.35);
    }

    .dev-banner {
        background: rgba(255, 61, 90, 0.08);
        border: 1.5px solid rgba(255,61,90,0.3);
        border-radius: 14px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: #ff8095;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .settings-panel {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 16px;
    }

    .settings-panel h3 {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 4px;
    }

    .settings-panel p.hint {
        color: var(--text-dim);
        font-size: 0.8rem;
        margin-bottom: 18px;
    }

    .menu-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        background: var(--panel-soft);
        border: 1.5px solid var(--border-soft);
        border-radius: 14px;
        padding: 14px 16px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .menu-item:hover {
        border-color: var(--accent-orange);
        background: rgba(255,122,26,0.06);
        transform: translateY(-1px);
    }

    .menu-item .menu-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .menu-item .menu-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        background: var(--grad-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #fff;
        flex-shrink: 0;
    }

    .menu-item .menu-text strong {
        display: block;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .menu-item .menu-text span {
        display: block;
        color: var(--text-dim);
        font-size: 0.76rem;
        margin-top: 2px;
    }

    .menu-item .menu-chevron {
        color: var(--text-dim);
        font-size: 1rem;
    }

    .danger-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .danger-row div span {
        display: block;
        color: var(--text-dim);
        font-size: 0.8rem;
    }

    .btn-danger-outline {
        border: 1.5px solid #ff3d5a;
        color: #ff3d5a;
        background: transparent;
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-danger-outline:hover {
        background: #ff3d5a;
        color: #fff;
    }
</style>

<section class="dev-hero">
    <div>
        <p class="eyebrow mb-1">Restricted</p>
        <h1>Developer Options</h1>
        <span>{{ employeeName() }} / {{ data_get(employee(), 'employee_code', '-') }}</span>
    </div>
    <i class="bi bi-code-slash"></i>
</section>

<div class="dev-banner">
    <i class="bi bi-shield-lock-fill"></i>
    <span>You are in Developer Mode. Changes here directly affect live data.</span>
</div>

<section class="settings-panel">
    <h3>Correction Tools</h3>
    <p class="hint">Restricted tools for authorized developer use only.</p>

    <div class="menu-list">
        <a href="#" class="menu-item">
            <div class="menu-left">
                <i class="bi bi-person-lines-fill menu-icon"></i>
                <div class="menu-text">
                    <strong>User Details</strong>
                    <span>View &amp; edit raw user records</span>
                </div>
            </div>
            <i class="bi bi-chevron-right menu-chevron"></i>
        </a>

        <a href="#" class="menu-item">
            <div class="menu-left">
                <i class="bi bi-clock-history menu-icon"></i>
                <div class="menu-text">
                    <strong>Attendance Correction</strong>
                    <span>Fix punch-in/out records</span>
                </div>
            </div>
            <i class="bi bi-chevron-right menu-chevron"></i>
        </a>

        <a href="#" class="menu-item">
            <div class="menu-left">
                <i class="bi bi-cash-coin menu-icon"></i>
                <div class="menu-text">
                    <strong>Salary Correction</strong>
                    <span>Adjust salary or payment entries</span>
                </div>
            </div>
            <i class="bi bi-chevron-right menu-chevron"></i>
        </a>

        <a href="#" class="menu-item">
            <div class="menu-left">
                <i class="bi bi-calendar2-x-fill menu-icon"></i>
                <div class="menu-text">
                    <strong>Leave Correction</strong>
                    <span>Modify leave records &amp; balances</span>
                </div>
            </div>
            <i class="bi bi-chevron-right menu-chevron"></i>
        </a>
    </div>
</section>

<section class="settings-panel">
    <div class="danger-row">
        <div>
            <h3 class="mb-1">Exit Developer Mode</h3>
            <span>Return to normal settings</span>
        </div>
        <a href="{{ route('settings') }}" class="btn-danger-outline">
            <i class="bi bi-box-arrow-left"></i>
            Exit
        </a>
    </div>
</section>
@endsection