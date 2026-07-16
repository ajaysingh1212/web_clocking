@extends('layouts.app')

@section('title', 'Report | EEMOT Clocking PWA')

@php
    use Carbon\Carbon;

    $days = collect(data_get($calendar, 'days', []));
    $counts = data_get($calendar, 'counts', []);
    $daysByDate = $days->keyBy('date');

    $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
    $monthLabel = $monthStart->format('F Y');
    $daysInMonth = $monthStart->daysInMonth;
    $leadingBlanks = $monthStart->copy()->startOfMonth()->dayOfWeek; // 0 = Sunday

    $statusMeta = [
        'present'   => ['class' => 'cal-present',  'label' => 'Present'],
        'absent'    => ['class' => 'cal-absent',   'label' => 'Absent'],
        'leave'     => ['class' => 'cal-leave',    'label' => 'Leave'],
        'half_time' => ['class' => 'cal-half',     'label' => 'Half-time'],
        'holiday'   => ['class' => 'cal-holiday',  'label' => 'Holiday'],
        'week_off'  => ['class' => 'cal-weekoff',  'label' => 'Week-off'],
    ];

    // Build calendar cells (leading blanks + days + trailing blanks to complete weeks)
    $cells = [];
    for ($i = 0; $i < $leadingBlanks; $i++) {
        $cells[] = null;
    }
    for ($d = 1; $d <= $daysInMonth; $d++) {
        $date = $monthStart->copy()->day($d)->format('Y-m-d');
        $cells[] = [
            'day' => $d,
            'date' => $date,
            'row' => $daysByDate->get($date),
        ];
    }
    while (count($cells) % 7 !== 0) {
        $cells[] = null;
    }
    $weeks = array_chunk($cells, 7);

    // List view: only days that actually have a status, ascending order (1st date first)
    $listDays = $days->filter(fn ($row) => !empty(data_get($row, 'status')))->sortBy('date')->values();
@endphp

@section('content')
<style>
    .report-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .report-hero .eyebrow {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: var(--text-dim);
        font-weight: 600;
    }

    .report-hero h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.4rem;
        margin: 2px 0 4px;
    }

    .report-hero span {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .report-hero > i {
        width: 52px; height: 52px;
        border-radius: 16px;
        background: var(--grad-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 10px 24px rgba(255,90,60,0.35);
    }

    /* Tabs */
    .report-tabs {
        display: flex;
        gap: 4px;
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 16px;
        padding: 5px;
        margin-bottom: 16px;
    }

    .tab-btn {
        flex: 1;
        border: none;
        background: transparent;
        color: var(--text-dim);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 11px 8px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .tab-btn.active {
        background: var(--grad-main);
        color: #fff;
        box-shadow: 0 8px 18px rgba(255,90,60,0.28);
    }

    /* Legend */
    .legend-panel {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 18px;
        padding: 16px;
        margin-bottom: 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        justify-content: center;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 0.74rem;
        color: var(--text-dim);
        font-weight: 600;
    }

    .legend-dot {
        width: 13px; height: 13px;
        border-radius: 5px;
        flex-shrink: 0;
    }

    /* Month bar */
    .month-bar {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 16px;
        padding: 14px 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .month-bar strong {
        color: #fff;
        font-size: 0.92rem;
        font-weight: 700;
    }

    .month-bar input[type="month"] {
        background: var(--panel-soft);
        border: 1.5px solid var(--border-soft);
        border-radius: 10px;
        padding: 8px 10px;
        color: #fff;
        font-size: 0.82rem;
        color-scheme: dark;
    }

    /* Calendar */
    .calendar-card {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .calendar-title {
        text-align: center;
        color: #fff;
        font-weight: 800;
        font-size: 1.1rem;
        margin-bottom: 16px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }

    .dow {
        text-align: center;
        font-size: 0.66rem;
        color: var(--text-dim);
        font-weight: 700;
        text-transform: uppercase;
        padding-bottom: 8px;
    }

    .dow.sun { color: #ff3d5a; }

    .cal-cell {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
    }

    .cal-cell.cal-blank { visibility: hidden; }

    .cal-cell.cal-none {
        background: var(--panel-soft);
        color: var(--text-dim);
        font-weight: 600;
        border: 1px solid var(--border-soft);
    }

    .cal-present  { background: #3ac882; }
    .cal-absent   { background: #ff3d5a; }
    .cal-leave    { background: #2f8fff; }
    .cal-half     { background: #ffb020; }
    .cal-holiday  { background: #b13cff; }
    .cal-weekoff  { background: #64748b; }

    /* List view */
    .report-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .report-item {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 16px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .report-item .item-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .report-item .date-block { display: flex; flex-direction: column; }

    .report-item .date-block span {
        color: var(--text-dim);
        font-size: 0.68rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .report-item .date-block strong { color: #fff; font-size: 0.9rem; }

    .report-item .times { display: flex; gap: 16px; }
    .report-item .times div { text-align: center; }

    .report-item .times span {
        display: block;
        color: var(--text-dim);
        font-size: 0.62rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .report-item .times strong { color: #fff; font-size: 0.82rem; }

    .report-item .status-pill {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 30px;
        white-space: nowrap;
    }

    .status-present { background: rgba(58, 200, 130, 0.12); color: #6fe3ad; }
    .status-late    { background: rgba(255, 190, 60, 0.12); color: #ffcc66; }
    .status-absent  { background: rgba(255, 61, 90, 0.12); color: #ff8095; }
    .status-leave   { background: rgba(47, 143, 255, 0.12); color: #7fbcff; }
    .status-half    { background: rgba(255, 176, 32, 0.12); color: #ffcf85; }
    .status-holiday { background: rgba(177, 60, 255, 0.12); color: #d199ff; }
    .status-weekoff { background: rgba(100, 116, 139, 0.15); color: #a3aebc; }

    .location-stack {
        display: flex;
        flex-direction: column;
        gap: 8px;
        border-top: 1px solid var(--border-soft);
        padding-top: 10px;
    }

    .location-row {
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .location-row i {
        color: var(--accent-orange);
        font-size: 0.85rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .location-row .loc-text {
        display: flex;
        flex-direction: column;
    }

    .location-row .loc-text span {
        color: var(--text-dim);
        font-size: 0.62rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .location-row .loc-text strong {
        color: var(--text-muted);
        font-size: 0.76rem;
        font-weight: 500;
        line-height: 1.35;
        word-break: break-word;
    }

    .empty-state {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 40px 24px;
        text-align: center;
    }

    .empty-state i {
        font-size: 36px;
        color: var(--text-dim);
        margin-bottom: 14px;
        display: block;
    }

    .empty-state strong { color: #fff; font-size: 0.98rem; display: block; margin-bottom: 6px; }
    .empty-state span { color: var(--text-dim); font-size: 0.82rem; }

    @media (max-width: 380px) {
        .report-item .times { width: 100%; justify-content: space-between; }
    }
</style>

@if($apiError ?? null)
    <div class="alert alert-warning">{{ $apiError }}</div>
@endif

<section class="report-hero">
    <div>
        <p class="eyebrow mb-1">Attendance</p>
        <h1>Report</h1>
        <span>{{ employeeName() }} / {{ data_get(employee(), 'employee_code', '-') }}</span>
    </div>
    <i class="bi bi-file-earmark-bar-graph-fill"></i>
</section>

<div class="report-tabs">
    <button type="button" class="tab-btn active" data-tab="days" onclick="showReportTab('days', this)">Attendance Days</button>
    <button type="button" class="tab-btn" data-tab="list" onclick="showReportTab('list', this)">Attendance List</button>
</div>

<div class="legend-panel">
    <div class="legend-item"><span class="legend-dot cal-present"></span> Present</div>
    <div class="legend-item"><span class="legend-dot cal-absent"></span> Absent</div>
    <div class="legend-item"><span class="legend-dot cal-leave"></span> Leave</div>
    <div class="legend-item"><span class="legend-dot cal-half"></span> Half-time</div>
    <div class="legend-item"><span class="legend-dot cal-holiday"></span> Holiday</div>
    <div class="legend-item"><span class="legend-dot cal-weekoff"></span> Week-off</div>
</div>

<form method="GET" action="{{ route('report') }}" id="monthForm">
    <div class="month-bar">
        <strong>Monthly Report</strong>
        <input type="month" name="month" value="{{ $month }}" onchange="document.getElementById('monthForm').submit()">
    </div>
</form>

<div id="tab-days">
    <section class="calendar-card">
        <div class="calendar-title">{{ $monthLabel }}</div>

        <div class="calendar-grid">
            <div class="dow sun">Sun</div>
            <div class="dow">Mon</div>
            <div class="dow">Tue</div>
            <div class="dow">Wed</div>
            <div class="dow">Thu</div>
            <div class="dow">Fri</div>
            <div class="dow">Sat</div>

            @foreach($weeks as $week)
                @foreach($week as $cell)
                    @if(is_null($cell))
                        <div class="cal-cell cal-blank"></div>
                    @else
                        @php
                            $rowStatus = strtolower((string) data_get($cell, 'row.status', ''));
                            $meta = $statusMeta[$rowStatus] ?? null;
                            $cellClass = $meta['class'] ?? 'cal-none';
                        @endphp
                        <div class="cal-cell {{ $cellClass }}" title="{{ $meta['label'] ?? 'No data' }}">
                            {{ str_pad($cell['day'], 2, '0', STR_PAD_LEFT) }}
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </section>
</div>

<div id="tab-list" style="display: none;">
    @if($listDays->isNotEmpty())
        <section class="report-list">
            @foreach($listDays as $row)
                @php
                    $rStatus = strtolower((string) data_get($row, 'status', ''));
                    $pillClass = match($rStatus) {
                        'absent' => 'status-absent',
                        'leave' => 'status-leave',
                        'half_time' => 'status-half',
                        'holiday' => 'status-holiday',
                        'week_off' => 'status-weekoff',
                        default => 'status-present',
                    };
                    $pillLabel = $statusMeta[$rStatus]['label'] ?? ucfirst($rStatus ?: 'Not marked');
                    $inLoc = data_get($row, 'punch_in_location');
                    $outLoc = data_get($row, 'punch_out_location');
                @endphp
                <article class="report-item">
                    <div class="item-top">
                        <div class="date-block">
                            <span>Date</span>
                            <strong>{{ data_get($row, 'date') ?? '-' }}</strong>
                        </div>
                        <div class="times">
                            <div>
                                <span>In</span>
                                <strong>{{ data_get($row, 'punch_in_time') ? date('h:i A', strtotime(data_get($row, 'punch_in_time'))) : '--:--' }}</strong>
                            </div>
                            <div>
                                <span>Out</span>
                                <strong>{{ data_get($row, 'punch_out_time') ? date('h:i A', strtotime(data_get($row, 'punch_out_time'))) : '--:--' }}</strong>
                            </div>
                        </div>
                        <span class="status-pill {{ $pillClass }}">{{ $pillLabel }}</span>
                    </div>

                    @if($inLoc || $outLoc)
                        <div class="location-stack">
                            @if($inLoc)
                                <div class="location-row">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <div class="loc-text">
                                        <span>In Location</span>
                                        <strong>{{ $inLoc }}</strong>
                                    </div>
                                </div>
                            @endif
                            @if($outLoc)
                                <div class="location-row">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <div class="loc-text">
                                        <span>Out Location</span>
                                        <strong>{{ $outLoc }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </article>
            @endforeach
        </section>
    @else
        <section class="empty-state">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <strong>No report data available</strong>
            <span>Your attendance history will appear here once records are found.</span>
        </section>
    @endif
</div>

<script>
    function showReportTab(tab, btn) {
        document.getElementById('tab-days').style.display = tab === 'days' ? 'block' : 'none';
        document.getElementById('tab-list').style.display = tab === 'list' ? 'block' : 'none';
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
</script>
@endsection