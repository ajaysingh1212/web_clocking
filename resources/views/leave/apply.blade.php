@extends('layouts.app')

@section('title', 'Apply Leave | EEMOT Clocking PWA')

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
    .leave-form-grid { display: grid; gap: 12px; }
    .leave-form-grid label { color: var(--text-dim); font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
    .leave-form-grid input, .leave-form-grid select, .leave-form-grid textarea { width: 100%; background: var(--panel-soft); border: 1.5px solid var(--border-soft); border-radius: 12px; padding: 12px 14px; color: #fff; font-size: 0.9rem; font-family: 'Poppins', sans-serif; }
    .leave-form-grid textarea { min-height: 96px; resize: vertical; }
    .leave-form-grid input:focus, .leave-form-grid select:focus, .leave-form-grid textarea:focus { border-color: var(--accent-orange); background: rgba(255,122,26,0.06); outline: none; box-shadow: 0 0 0 4px rgba(255,122,26,0.1); }
    .leave-actions { display: flex; gap: 10px; margin-top: 4px; }
    .btn-primary-leave { flex: 1; border: none; border-radius: 12px; padding: 12px 14px; color: #fff; font-weight: 700; background: var(--grad-main); box-shadow: 0 10px 24px rgba(255,90,60,0.3); }
    .btn-secondary-leave { flex: 1; border: 1.5px solid var(--border-soft); border-radius: 12px; padding: 12px 14px; background: transparent; color: var(--text-muted); font-weight: 600; }
    .bottom-links { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 6px; }
    .bottom-links a { text-decoration: none; color: var(--text-dim); font-size: 0.8rem; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="leave-shell">
    <section class="leave-hero">
        <div class="leave-hero-icon"><i class="bi bi-send-fill"></i></div>
        <div>
            <p class="eyebrow">Leave Management</p>
            <h1>Apply Leave</h1>
            <p>Submit a new leave request with a polished form designed for quick use.</p>
        </div>
    </section>

    <section class="leave-card">
        <h3>New Leave Request</h3>
        <p class="hint">Fill in the details and send your request.</p>

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

    <div class="bottom-links">
        <a href="{{ route('leave') }}">← Back to overview</a>
        <a href="{{ route('leave.history') }}">View history</a>
    </div>
</div>
@endsection