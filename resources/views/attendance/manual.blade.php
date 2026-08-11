@extends('layouts.app')

@section('title', 'Manual Attendance | EEMOT Clocking PWA')

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
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-top: 16px;
    }

    .punch-panel .fields-card {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 18px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .punch-panel .field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .punch-panel .field span {
        color: var(--text-dim);
        font-size: 0.72rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .punch-panel .field input,
    .punch-panel .field select,
    .location-box textarea {
        width: 100%;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 14px;
        color: #fff;
        font-size: 0.95rem;
        font-weight: 600;
        padding: 12px 14px;
        font-family: 'Poppins', sans-serif;
        min-height: 48px;
        box-sizing: border-box;
    }

    .punch-panel .metric select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: linear-gradient(45deg, transparent 50%, #fff 50%),
            linear-gradient(135deg, #fff 50%, transparent 50%);
        background-position: calc(100% - 18px) calc(50% - 6px), calc(100% - 12px) calc(50% - 6px);
        background-size: 6px 6px;
        background-repeat: no-repeat;
        padding-right: 36px;
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

    .location-distance {
        margin-top: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        background: rgba(122, 58, 255, 0.12);
        border: 1px solid rgba(122, 58, 255, 0.35);
        color: #c6b3ff;
        font-size: 0.78rem;
        display: none;
    }

    .location-distance.success {
        background: rgba(58, 200, 130, 0.12);
        border-color: rgba(58, 200, 130, 0.35);
        color: #88f0bb;
    }

    .location-distance.error {
        background: rgba(255, 61, 90, 0.12);
        border-color: rgba(255, 61, 90, 0.35);
        color: #ff8095;
    }

    .location-error {
        margin-top: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        background: rgba(255, 61, 90, 0.1);
        border: 1px solid rgba(255, 61, 90, 0.35);
        color: #ff8095;
        font-size: 0.8rem;
        display: none;
    }

    .location-error.show {
        display: block;
    }

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

    .manual-location-overlay {
        position: fixed;
        inset: 0;
        z-index: 100000;
        background: rgba(3, 6, 16, 0.82);
        backdrop-filter: blur(2px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .manual-location-overlay.show {
        display: flex;
    }

    .manual-location-modal {
        width: min(100%, 760px);
        background: linear-gradient(180deg, rgba(31, 35, 82, 0.98), rgba(12, 16, 34, 0.98));
        border-radius: 20px;
        border: 1px solid var(--border-soft);
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.7);
        overflow: hidden;
    }

    .manual-location-top {
        background: linear-gradient(120deg, rgba(255,122,26,0.13), rgba(122,58,255,0.10));
        padding: 20px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border-soft);
    }

    .manual-location-top h3 {
        margin: 0;
        color: #fff;
        font-size: 1.24rem;
        font-weight: 800;
    }

    .manual-location-top .close-map {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        border: 1px solid var(--border-soft);
        background: transparent;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .manual-location-body {
        padding: 14px;
    }

    .manual-map-frame {
        height: 260px;
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border-soft);
        background: var(--panel-soft);
    }

    .manual-location-readout {
        margin-top: 12px;
        background: var(--panel-soft);
        border-radius: 12px;
        border: 1px solid var(--border-soft);
        padding: 12px;
    }

    .manual-location-readout .readout-head {
        color: var(--text-dim);
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .manual-location-readout .readout-grid {
        display: grid;
        grid-template-columns: minmax(160px, 1fr) minmax(120px, 1fr);
        gap: 10px;
        align-items: stretch;
    }

    .manual-location-readout .readout-cell {
        background: rgba(255,255,255,0.04);
        border-radius: 10px;
        padding: 10px;
        min-width: 0;
    }

    .manual-location-readout .readout-cell span {
        display: block;
        color: var(--text-dim);
        font-size: 0.63rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .manual-location-readout .readout-cell strong {
        display: block;
        color: #fff;
        font-size: 0.76rem;
        margin-top: 4px;
        word-break: break-word;
    }

    .manual-location-readout .readout-address {
        margin-top: 10px;
        background: rgba(255,255,255,0.04);
        border-radius: 10px;
        padding: 10px;
        border: 1px solid rgba(255,255,255,0.06);
    }

    .manual-location-readout .readout-address span {
        display: block;
        color: var(--text-dim);
        font-size: 0.63rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .manual-location-readout .readout-address strong {
        display: block;
        color: #fff;
        font-size: 0.78rem;
        margin-top: 6px;
        line-height: 1.55;
    }

    .manual-map-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding-top: 12px;
    }

    .manual-map-actions .btn {
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 700;
        font-size: 0.84rem;
    }

    .manual-map-actions .btn-cancel {
        background: #eef2f9;
        color: #0a0f2c;
        border: none;
    }

    .manual-map-actions .btn-set {
        background: linear-gradient(120deg, #2a62ff, #5b58ff);
        color: white;
        border: none;
    }
</style>

<section class="punch-hero">
    <div>
        <p class="eyebrow mb-1">Attendance</p>
        <h1>Manual Attendance</h1>
        <span>{{ employeeName() }} / {{ data_get(employee(), 'employee_code', '-') }}</span>
    </div>
    <i class="bi bi-camera-fill"></i>
</section>

<form method="POST" action="{{ route('developer.mark-attendance.store') }}" enctype="multipart/form-data" class="needs-loader punch-form" id="punch-form">
    @csrf

    <section class="capture-card">
        <img id="photo-preview" class="photo-preview" src="{{ asset('images/camera-placeholder.svg') }}" alt="Photo preview">
        <label class="capture-button" for="image">
            <i class="bi bi-image"></i>
            Choose Photo
        </label>
        <input class="visually-hidden" type="file" id="image" name="image" accept="image/*" required>
    </section>

    <section class="punch-panel">
        <label class="form-label">Attendance Details</label>

        <div class="fields-card">
            <div class="field">
                <span>User ID</span>
                <input id="user_id" name="user_id" type="text" class="ghost-input" value="{{ old('user_id', authUserId()) }}" required>
            </div>

            <div class="field">
                <span>Date</span>
                <input id="date" name="date" type="date" class="ghost-input" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>

            <div class="field">
                <span>Action</span>
                <select id="action" name="action" class="ghost-input" required>
                    <option value="" disabled {{ old('action') ? '' : 'selected' }}>Select action</option>
                    <option value="in" {{ old('action') === 'in' ? 'selected' : '' }}>In</option>
                    <option value="out" {{ old('action') === 'out' ? 'selected' : '' }}>Out</option>
                </select>
            </div>

            <div class="field">
                <span>Time</span>
                <input id="time" name="time" type="time" class="ghost-input" value="{{ old('time', date('H:i')) }}" required>
            </div>

            <div class="field">
                <span>Latitude</span>
                <input id="latitude" name="latitude" class="ghost-input" value="{{ old('latitude') }}" readonly required>
            </div>

            <div class="field">
                <span>Longitude</span>
                <input id="longitude" name="longitude" class="ghost-input" value="{{ old('longitude') }}" readonly required>
            </div>
        </div>

        <div class="location-box mt-3"
             data-branch-lat="{{ data_get(branch(), 'latitude') }}"
             data-branch-lng="{{ data_get(branch(), 'longitude') }}"
             data-radius="{{ data_get(employee(), 'attendance_radius_meter', 0) }}">
            <label for="location">Selected Location</label>
            <textarea id="location" name="location" rows="3" readonly required>{{ old('location') }}</textarea>
            <p class="location-status" id="location-status">
                <i class="bi bi-geo-alt"></i> Tap the map icon and choose a location.
            </p>
            <div class="location-distance" id="location-distance"></div>
            @if ($errors->has('location'))
                <div class="location-error show">
                    {{ $errors->first('location') }}
                </div>
            @endif
        </div>

        <div class="action-row" style="margin-top: 18px; gap: 14px;">
            <button class="btn btn-light btn-lg icon-btn" type="button" id="map-location" aria-label="Choose location from map">
                <i class="bi bi-geo-alt-fill"></i>
            </button>
            <button class="btn btn-primary btn-lg" type="submit">
                <i class="bi bi-send-fill"></i>
                Submit Punch
            </button>
        </div>
    </section>
</form>

<div class="manual-location-overlay" id="manualMapModal">
    <div class="manual-location-modal">
        <div class="manual-location-top">
            <h3>Adjust Location</h3>
            <button type="button" class="close-map" id="closeMapModal" aria-label="Close">×</button>
        </div>
        <div class="manual-location-body">
            <div class="manual-map-frame" id="manual-map-container"></div>
            <div class="manual-location-readout">
                <div class="readout-head">Selected Location</div>
                <div class="readout-grid">
                    <div class="readout-cell">
                        <span>Latitude</span>
                        <strong id="manual-lat-readout">--</strong>
                    </div>
                    <div class="readout-cell">
                        <span>Longitude</span>
                        <strong id="manual-lng-readout">--</strong>
                    </div>
                </div>
                <div class="readout-address">
                    <span>Address</span>
                    <strong id="manual-location-readout">--</strong>
                </div>
            </div>
            <div class="manual-map-actions">
                <button class="btn btn-cancel" type="button" id="refreshMapLocation">Refresh</button>
                <button class="btn btn-set" type="button" id="apply-map-location">Set Location</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/punch.js') }}?v={{ filemtime(public_path('js/punch.js')) }}"></script>
    <script>
        window.manualMapApiReady = function () {
            if (typeof window.initManualMapControls === 'function') {
                window.initManualMapControls();
            }
        };
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgRXfXiK8KHfSnKtunSIpGpKNmLNGNUzM&callback=window.manualMapApiReady" async defer></script>
@endpush
