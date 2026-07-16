@extends('layouts.app')

@section('title', 'Profile | EEMOT Clocking PWA')

@php
    $user = authUser() ?? [];
    $emp = employee() ?? [];
@endphp

@section('content')
<style>
    .profile-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
    }

    .profile-cover {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .profile-cover .avatar-xl {
        width: 68px; height: 68px;
        border-radius: 18px;
        object-fit: cover;
        border: 2px solid var(--border-soft);
    }

    .profile-cover .eyebrow {
        color: var(--accent-orange);
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .profile-cover h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.3rem;
        margin: 2px 0 4px;
    }

    .profile-cover span {
        color: var(--text-muted);
        font-size: 0.82rem;
    }

    .profile-chips {
        display: flex;
        gap: 10px;
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px solid var(--border-soft);
        flex-wrap: wrap;
    }

    .profile-chips span {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 30px;
        padding: 7px 14px;
        font-size: 0.78rem;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .profile-chips i { color: var(--accent-orange); }

    .detail-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .detail-item {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 16px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .detail-item i {
        width: 42px; height: 42px;
        border-radius: 12px;
        background: var(--panel-soft);
        color: var(--accent-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .detail-item span {
        display: block;
        color: var(--text-dim);
        font-size: 0.68rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .detail-item strong {
        color: #fff;
        font-size: 0.88rem;
        word-break: break-word;
    }
</style>

@if($apiError)
    <div class="alert alert-warning">{{ $apiError }}</div>
@endif

<section class="profile-hero">
    <div class="profile-cover">
        <img class="avatar-xl" src="{{ profileImage() ?: asset('images/profile-placeholder.svg') }}" alt="Profile image">
        <div>
            <p class="eyebrow mb-1">{{ data_get($emp, 'employee_code', '-') }}</p>
            <h1>{{ employeeName() }}</h1>
            <span>{{ data_get($emp, 'position') ?? data_get($user, 'degination') ?? '-' }}</span>
        </div>
    </div>
    <div class="profile-chips">
        <span><i class="bi bi-building"></i>{{ branchName() }}</span>
        <span><i class="bi bi-person-badge"></i>{{ data_get($emp, 'department', '-') }}</span>
    </div>
</section>

<section class="detail-list">
    @foreach([
        ['bi-envelope', 'Email', data_get($user, 'email') ?? data_get($emp, 'email')],
        ['bi-telephone', 'Phone', data_get($user, 'number') ?? data_get($user, 'phone') ?? data_get($emp, 'phone')],
        ['bi-geo-alt', 'Address', data_get($user, 'address') ?? data_get($emp, 'address')],
        ['bi-shield-plus', 'Emergency Number', data_get($user, 'emergency_number') ?? data_get($emp, 'emergency_number')],
        ['bi-bullseye', 'Attendance Source', data_get($emp, 'attendance_source')],
        ['bi-clock-history', 'Working Hours', data_get($emp, 'working_hours')],
        ['bi-calendar2-week', 'Work Time', trim((data_get($emp, 'work_start_time', '-') ?: '-').' - '.(data_get($emp, 'work_end_time', '-') ?: '-'))],
        ['bi-calendar-x', 'Weekly Off', data_get($emp, 'weekly_off_day')],
        ['bi-calendar-check', 'Joining Date', data_get($emp, 'date_of_joining')],
        ['bi-droplet', 'Blood Group', data_get($emp, 'blood_group')],
        ['bi-wallet2', 'Net Salary', data_get($emp, 'net_salary')],
        ['bi-buildings', 'Company', data_get($emp, 'company.title') ?? data_get($emp, 'company.legal_name') ?? data_get($emp, 'company_id')],
    ] as [$icon, $label, $value])
        <article class="detail-item">
            <i class="bi {{ $icon }}"></i>
            <div>
                <span>{{ $label }}</span>
                <strong>{{ $value ?: '-' }}</strong>
            </div>
        </article>
    @endforeach
</section>
@endsection