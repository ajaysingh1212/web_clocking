@extends('layouts.app')

@section('title', 'Notifications | EEMOT Clocking PWA')

@section('content')
<style>
    .notifications-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .notifications-hero .eyebrow {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: var(--text-dim);
        font-weight: 600;
    }

    .notifications-hero h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.4rem;
        margin: 2px 0 4px;
    }

    .notifications-hero span {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .notifications-hero > i {
        width: 52px; height: 52px;
        border-radius: 16px;
        background: var(--grad-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 10px 24px rgba(255,90,60,0.35);
    }

    .notifications-panel {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 18px;
    }

    .notification-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.04), rgba(255,255,255,0.025));
        border: 1px solid var(--border-soft);
        border-radius: 16px;
        padding: 14px 14px 12px;
        margin-bottom: 12px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.03);
    }

    .notification-card:last-child {
        margin-bottom: 0;
    }

    .notification-card .card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
    }

    .notification-card .card-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: var(--grad-main);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 16px;
        flex-shrink: 0;
        box-shadow: 0 8px 16px rgba(255,90,60,0.2);
    }

    .notification-card h3 {
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0 0 4px;
    }

    .notification-card .meta {
        color: var(--text-dim);
        font-size: 0.72rem;
        margin-top: 2px;
    }

    .notification-card .content {
        color: var(--text-muted);
        font-size: 0.84rem;
        line-height: 1.6;
    }

    .notification-card .content p {
        margin: 0;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--text-dim);
        font-size: 0.9rem;
        padding: 24px 10px 12px;
        gap: 8px;
    }

    .empty-state .empty-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: rgba(255,122,26,0.12);
        border: 1px solid rgba(255,122,26,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #ff9d4d;
    }

    .empty-state strong {
        color: #fff;
        font-size: 0.95rem;
    }
</style>

@if($apiError ?? null)
    <div class="alert alert-warning">{{ $apiError }}</div>
@endif

<section class="notifications-hero">
    <div>
        <p class="eyebrow mb-1">Updates</p>
        <h1>Notifications</h1>
        <span>All recent alerts and updates</span>
    </div>
    <i class="bi bi-bell-fill"></i>
</section>

<section class="notifications-panel">
    @if(!empty($notifications))
        @foreach($notifications as $notification)
            @php
                $heading = data_get($notification, 'heading') ?: 'Notification';
                $createdAt = data_get($notification, 'created_at');
                $content = data_get($notification, 'content');
            @endphp

            <div class="notification-card">
                <div class="card-head">
                    <div>
                        <h3>{{ $heading }}</h3>
                        <div class="meta">{{ $createdAt ?: 'No timestamp available' }}</div>
                    </div>
                    <div class="card-icon">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                </div>
                <div class="content">
                    {!! !empty($content) ? nl2br(e($content)) : 'No details available.' !!}
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-bell-slash-fill"></i>
            </div>
            <strong>You’re all caught up</strong>
            <span>No notifications yet. Pull down to refresh if anything new arrives.</span>
        </div>
    @endif
</section>
@endsection