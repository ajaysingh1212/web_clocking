@extends('layouts.app')

@section('title', 'Punch Attendance | EEMOT Clocking PWA')

@section('content')
<style>
    .punch-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .punch-hero .eyebrow {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: var(--text-dim);
        font-weight: 600;
    }

    .punch-hero h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.4rem;
        margin: 2px 0 4px;
    }

    .punch-hero span {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .punch-hero > i {
        width: 52px; height: 52px;
        border-radius: 16px;
        background: var(--grad-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 10px 24px rgba(255,90,60,0.35);
    }

    .capture-card {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        text-align: center;
        margin-bottom: 18px;
    }

    .photo-preview {
        width: 100%;
        max-width: 220px;
        aspect-ratio: 1/1;
        object-fit: cover;
        border-radius: 18px;
        border: 2px dashed var(--border-soft);
        margin: 0 auto 16px;
        display: block;
        background: var(--panel-soft);
    }

    .capture-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--grad-main);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 22px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        box-shadow: 0 10px 22px rgba(255,90,60,0.3);
        transition: all 0.25s ease;
    }

    .capture-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(255,90,60,0.4);
    }

    .punch-panel {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
    }

    .punch-panel .form-label {
        color: var(--text-dim);
        font-size: 0.72rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.4px;
        margin-bottom: 10px;
    }

    .live-clock {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 14px;
    }

    .live-clock .metric {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 12px;
        padding: 10px 12px;
    }

    .live-clock .metric span {
        display: block;
        color: var(--text-dim);
        font-size: 0.66rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .live-clock .metric strong {
        display: block;
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        letter-spacing: 0.3px;
    }

    .punch-panel .quick-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .punch-panel .metric {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 12px;
        padding: 10px 12px;
    }

    .punch-panel .metric span {
        display: block;
        color: var(--text-dim);
        font-size: 0.66rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .ghost-input {
        width: 100%;
        background: transparent;
        border: none;
        color: #fff;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 0;
        font-family: 'Poppins', sans-serif;
    }

    .ghost-input:focus { outline: none; }

    .location-box {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 12px;
        padding: 12px 14px;
    }

    .location-box label {
        display: block;
        color: var(--text-dim);
        font-size: 0.66rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .location-box textarea {
        width: 100%;
        background: transparent;
        border: none;
        color: #fff;
        font-size: 0.85rem;
        resize: none;
        font-family: 'Poppins', sans-serif;
    }

    .location-box textarea:focus { outline: none; }

    .location-status {
        font-size: 0.72rem;
        color: var(--text-dim);
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .location-status.error { color: #ff6b6b; }
    .location-status.success { color: #4ade80; }

    .punch-panel .action-row {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .punch-panel .btn-primary {
        flex: 1;
        background: var(--grad-main);
        background-size: 200% auto;
        border: none;
        border-radius: 14px;
        padding: 14px;
        font-weight: 700;
        font-size: 0.92rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 12px 26px rgba(255,90,60,0.3);
        transition: all 0.3s ease;
    }

    .punch-panel .btn-primary:hover {
        background-position: right center;
        transform: translateY(-2px);
    }

    .punch-panel .icon-btn {
        width: 52px;
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 14px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
</style>

<section class="punch-hero">
    <div>
        <p class="eyebrow mb-1">Attendance</p>
        <h1>Punch Now</h1>
        <span>{{ employeeName() }} / {{ data_get(employee(), 'employee_code', '-') }}</span>
    </div>
    <i class="bi bi-camera-fill"></i>
</section>

<form method="POST" action="{{ route('punch.store') }}" enctype="multipart/form-data" class="needs-loader punch-form" id="punch-form">
    @csrf

    <section class="capture-card">
        <img id="photo-preview" class="photo-preview" src="{{ asset('images/camera-placeholder.svg') }}" alt="Photo preview">
        <label class="capture-button" for="image">
            <i class="bi bi-camera"></i>
            Capture Photo
        </label>
        <input class="visually-hidden" type="file" id="image" name="image" accept="image/*" capture="user" required>
    </section>

    <section class="punch-panel">
        <label class="form-label">Location Details</label>

        <div class="live-clock">
            <div class="metric">
                <span>Date</span>
                <strong id="clock-date">--</strong>
            </div>
            <div class="metric">
                <span>Time</span>
                <strong id="clock-time">--:--:--</strong>
            </div>
        </div>

        <div class="quick-grid">
            <div class="metric">
                <span>Latitude</span>
                <input id="latitude" name="latitude" class="ghost-input" value="{{ old('latitude') }}" readonly required>
            </div>
            <div class="metric">
                <span>Longitude</span>
                <input id="longitude" name="longitude" class="ghost-input" value="{{ old('longitude') }}" readonly required>
            </div>
        </div>

        <div class="location-box mt-3">
            <label for="location">Current Location</label>
            <textarea id="location" name="location" rows="3" readonly required>{{ old('location') }}</textarea>
            <p class="location-status" id="location-status">
                <i class="bi bi-geo-alt"></i> Fetching your location…
            </p>
        </div>

        <div class="action-row">
            <button class="btn btn-light btn-lg icon-btn" type="button" id="refresh-location" aria-label="Refresh location">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
            <button class="btn btn-primary btn-lg" type="submit">
                <i class="bi bi-send-fill"></i>
                Submit Punch
            </button>
        </div>
    </section>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('js/punch.js') }}?v={{ filemtime(public_path('js/punch.js')) }}"></script>
@endpush