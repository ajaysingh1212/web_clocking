@extends('layouts.app')

@section('title', 'Email Reports | EEMOT Clocking PWA')

@section('content')
<style>
    .docs-shell { display: flex; flex-direction: column; gap: 14px; }

    .docs-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 18px 20px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 14px;
        flex-wrap: nowrap;
    }

    .docs-hero > div:first-child {
        flex: 1 1 0;
        min-width: 0;
    }

    .docs-hero h1 { color: #fff; font-weight: 800; font-size: 1.15rem; margin: 0 0 4px; }
    .docs-hero p { color: var(--text-muted); font-size: 0.78rem; margin: 0; max-width: 75%; line-height: 1.5; }
    .docs-hero .eyebrow { text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-dim); font-weight: 600; font-size: 0.75rem; margin-bottom: 6px; }

    .docs-hero-icon {
        width: 48px; height: 48px; border-radius: 16px; background: var(--grad-main);
        display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff;
        flex-shrink: 0;
        margin-left: auto;
    }

    .docs-panel { background: var(--panel); border: 1px solid var(--border-soft); border-radius: 20px; padding: 18px; }
    .docs-panel h3 { color: #fff; font-size: 1rem; font-weight: 700; margin: 0 0 12px; }
    .docs-panel p { color: var(--text-dim); font-size: 0.9rem; margin: 0 0 18px; }

    .doc-list { display: grid; gap: 12px; }
    .doc-item {
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 16px; padding: 16px 18px;
    }

    .doc-info strong { color: #fff; display: block; font-size: 0.96rem; margin-bottom: 4px; }
    .doc-info span { color: var(--text-dim); font-size: 0.8rem; }

    .doc-link {
        min-width: 120px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        border-radius: 14px; padding: 10px 14px; background: linear-gradient(135deg, #ff7a1a, #ff3d81, #7a3aff);
        color: #fff; font-weight: 700; text-decoration: none; transition: transform 0.15s ease, opacity 0.15s ease;
    }

    .doc-link:hover { transform: translateY(-1px); opacity: 0.95; }

    .form-grid { display: grid; gap: 16px; }
    .form-field { display: grid; gap: 10px; }
    .form-field label { color: var(--text-dim); font-size: 0.78rem; font-weight: 700; letter-spacing: 0.3px; }
    .form-field input,
    .form-field select { width: 100%; border: 1.5px solid var(--border-soft); border-radius: 14px; background: var(--panel-soft); color: #fff; padding: 12px 14px; font-size: 0.95rem; height: 48px; box-sizing: border-box; }

    /* Styled select with right chevron and proper padding */
    .form-field select.styled-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        /* white chevron SVG */
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20'><path fill='%23FFFFFF' d='M5 7l5 5 5-5z'/></svg>");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 18px 18px;
        padding-right: 50px;
        background-color: var(--panel-soft);
        height: 48px;
        box-sizing: border-box;
        color: #fff;
    }

    /* Attempt to style native option dropdowns to match dark theme. Note: many browsers limit option styling; for perfect consistency a custom dropdown is needed. */
    .form-field select.styled-select option {
        background: var(--panel);
        color: #fff;
    }
    .form-field select.styled-select option:checked {
        background: var(--grad-main);
        color: #fff;
    }
    .form-field select.styled-select option:hover {
        background: rgba(255,255,255,0.04);
        color: #fff;
    }

    /* Hide IE/Edge default arrow */
    .form-field select.styled-select::-ms-expand { display: none; }

    /* Make month input match height; use native calendar icon */
    .form-field input[type="month"] {
        padding-right: 18px;
        height: 48px;
        box-sizing: border-box;
        background-color: var(--panel-soft);
        color: #fff;
    }

    /* Leave native calendar indicator enabled so the picker opens on supported browsers */

    .button-row { display: grid; gap: 12px; margin-top: 8px; }
    .button-primary { width: 100%; border: none; border-radius: 14px; background: var(--grad-main); color: #fff; font-weight: 700; padding: 14px 18px; cursor: pointer; transition: transform 0.2s ease; margin-top: 6px; }
    .button-primary:hover { transform: translateY(-1px); }

    .note-box { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 16px; color: var(--text-dim); font-size: 0.84rem; }

    @media (max-width: 640px) {
        .docs-hero p { max-width: 100%; }
    }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="docs-shell">
    <section class="docs-hero">
        <div>
            <p class="eyebrow">Email Reports</p>
            <h1>Send Report</h1>
            <p>Select a month and email, then send the attendance report directly to your inbox.</p>
        </div>
        <div class="docs-hero-icon"><i class="bi bi-envelope-check-fill"></i></div>
    </section>

    <section class="docs-panel">
        <h3>Report Settings</h3>
        <p>Select the month and confirm the recipient email address. Attendance report delivery is supported now.</p>

        <form method="POST" action="{{ route('settings.email-reports.send') }}" id="emailReportForm">
            @csrf
            <div class="form-grid">
                <div class="form-field">
                    <label for="month">Month</label>
                    <div id="monthContainer">
                        <input id="month" type="month" name="month" value="{{ old('month', $defaultMonth) }}" required>
                    </div>
                </div>
            </div>

            <div class="button-row">
                <button type="submit" id="sendReportBtn" class="button-primary">Send Attendance Report</button>
                <div class="note-box" id="reportNote">You can send the attendance report to your registered email. You can only send it once every 5 minutes.</div>
            </div>
        </form>

        <script>
            (function(){
                const btn = document.getElementById('sendReportBtn');
                const cooldown = 5 * 60 * 1000; // 5 minutes

                function startCountdown(remaining){
                    btn.disabled = true;
                    const end = Date.now() + remaining;
                    update();

                    function update(){
                        const ms = end - Date.now();
                        if (ms <= 0){
                            btn.disabled = false;
                            btn.textContent = 'Send Report';
                            localStorage.removeItem('lastReportSent');
                            return;
                        }
                        const s = Math.ceil(ms/1000);
                        const m = Math.floor(s/60);
                        const sec = s % 60;
                        btn.textContent = `Wait ${m}:${sec.toString().padStart(2,'0')}`;
                        requestAnimationFrame(update);
                    }
                }

                // check stored timestamp
                const last = parseInt(localStorage.getItem('lastReportSent') || '0', 10);
                if (last && Date.now() - last < cooldown){
                    startCountdown(cooldown - (Date.now() - last));
                }

                // on submit, set timestamp immediately and start countdown
                document.getElementById('emailReportForm').addEventListener('submit', function(){
                    localStorage.setItem('lastReportSent', Date.now());
                    startCountdown(cooldown);
                });

                // if server returned success (after redirect), ensure timestamp is set
                @if(session('success'))
                    localStorage.setItem('lastReportSent', Date.now());
                @endif
            })();
        </script>
        <script>
            (function(){
                // Fallback for browsers that don't support input[type=month]
                const monthContainer = document.getElementById('monthContainer');
                if (!monthContainer) return;

                const test = document.createElement('input');
                test.setAttribute('type', 'month');
                if (test.type === 'month') {
                    // supported; nothing to do
                    return;
                }

                const now = new Date();
                const currentYear = now.getFullYear();
                const currentMonth = String(now.getMonth() + 1).padStart(2, '0');
                const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

                const yearStart = currentYear - 5;
                const yearEnd = currentYear + 2;

                let html = '<div class="month-selects" style="display:flex;gap:10px">';
                html += '<select id="month_select" style="flex:1;padding:12px;border-radius:14px;background:var(--panel-soft);border:1.5px solid var(--border-soft);color:#fff">';
                months.forEach((m, idx) => {
                    const val = String(idx+1).padStart(2,'0');
                    html += `<option value="${val}">${m}</option>`;
                });
                html += '</select>';
                html += '<select id="year_select" style="width:110px;padding:12px;border-radius:14px;background:var(--panel-soft);border:1.5px solid var(--border-soft);color:#fff">';
                for (let y = yearStart; y <= yearEnd; y++) html += `<option value="${y}">${y}</option>`;
                html += '</select>';
                // hidden month input to submit value as YYYY-MM
                html += `<input type="hidden" id="month" name="month" value="${currentYear}-${currentMonth}">`;
                html += '</div>';

                monthContainer.innerHTML = html;
                document.getElementById('month_select').value = currentMonth;
                document.getElementById('year_select').value = currentYear;

                function syncMonth(){
                    const m = document.getElementById('month_select').value;
                    const y = document.getElementById('year_select').value;
                    document.getElementById('month').value = `${y}-${m}`;
                }

                document.getElementById('month_select').addEventListener('change', syncMonth);
                document.getElementById('year_select').addEventListener('change', syncMonth);
            })();
        </script>
    </section>
</div>
@endsection