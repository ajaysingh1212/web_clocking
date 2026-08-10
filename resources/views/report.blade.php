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
        cursor: pointer;
    }

    .cal-cell.cal-blank { visibility: hidden; }

    .cal-cell.has-detail:hover,
    .cal-cell.has-detail:focus {
        outline: 2px solid rgba(255,255,255,0.75);
        transform: translateY(-2px);
    }

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

    .report-day-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(3, 6, 10, 0.78);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 14px;
        z-index: 2000;
    }

    .report-day-modal-backdrop.show {
        display: flex;
    }

    .report-day-modal {
        width: min(440px, 100%);
        background: linear-gradient(180deg, rgba(30, 34, 50, 0.98), rgba(27, 29, 39, 0.98));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        overflow: hidden;
        color: #fff;
        box-shadow: 0 14px 36px rgba(0, 0, 0, 0.75);
    }

    .report-day-modal-header {
        padding: 14px 18px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: linear-gradient(135deg, #402473 0%, #2b2b54 100%);
    }

    .report-day-modal.report-status-present .report-day-modal-header {
        background: linear-gradient(135deg, #3ac882 0%, #19b96f 100%);
    }

    .report-day-modal.report-status-absent .report-day-modal-header {
        background: linear-gradient(135deg, #ff3d5a 0%, #bb1d39 100%);
    }

    .report-day-modal.report-status-leave .report-day-modal-header {
        background: linear-gradient(135deg, #2f8fff 0%, #254eaf 100%);
    }

    .report-day-modal.report-status-half_time .report-day-modal-header,
    .report-day-modal.report-status-half-time .report-day-modal-header {
        background: linear-gradient(135deg, #ffb020 0%, #b96d00 100%);
    }

    .report-day-modal.report-status-holiday .report-day-modal-header {
        background: linear-gradient(135deg, #b13cff 0%, #6f20b7 100%);
    }

    .report-day-modal.report-status-week_off .report-day-modal-header,
    .report-day-modal.report-status-week-off .report-day-modal-header {
        background: linear-gradient(135deg, #64748b 0%, #455061 100%);
    }

    .report-day-modal.report-status-none .report-day-modal-header {
        background: linear-gradient(135deg, #4a5268 0%, #2f3442 100%);
    }

    .report-day-modal-header h3 {
        margin: 0;
        font-size: 1.14rem;
        line-height: 1.12;
        font-weight: 800;
        color: #fff;
    }

    .report-day-modal-close {
        border: 0;
        background: transparent;
        color: #aeb6d1;
        font-size: 26px;
        line-height: 1;
        cursor: pointer;
        padding: 2px 8px;
        border-radius: 8px;
    }

    .report-day-modal-close:hover {
        color: #fff;
        background: rgba(255,255,255,0.08);
    }

    .report-day-modal-body {
        padding: 14px;
        background: linear-gradient(180deg, rgba(30, 33, 43, 0.98), rgba(27, 31, 42, 0.98));
    }

    .report-day-modal-status-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .report-day-modal-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.66rem;
        color: #fff;
        background: var(--grad-main);
        border: 1px solid rgba(255,255,255,0.12);
    }

    .report-day-modal-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.62rem;
        letter-spacing: 0.55px;
        border: 1px solid rgba(255,255,255,0.12);
    }

    .report-day-modal-type-self {
        background: rgba(58, 200, 130, 0.14);
        color: #72f0a8;
        border-color: rgba(58, 200, 130, 0.34);
    }

    .report-day-modal-type-admin {
        background: rgba(255, 184, 77, 0.14);
        color: #ffd46a;
        border-color: rgba(255, 184, 77, 0.34);
    }

    .report-day-modal-type-manual {
        background: rgba(112, 151, 255, 0.14);
        color: #a8c2ff;
        border-color: rgba(112, 151, 255, 0.34);
    }

    .report-day-modal-type-default {
        background: rgba(255,255,255,0.08);
        color: var(--text-dim);
        border-color: rgba(255,255,255,0.12);
    }

    .report-day-modal-body .modal-date-row {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        justify-content: space-between;
    }

    .report-day-modal-body .modal-date-row .text-muted {
        font-size: 0.72rem;
        color: var(--text-dim);
    }

    .report-day-modal-body .badge {
        font-size: 0.62rem;
        padding: 4px 9px;
        border-radius: 999px;
        background: var(--panel-soft);
        color: var(--text-dim);
    }

    .report-day-modal-body .modal-detail-grid {
        margin-top: 10px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 9px;
    }

    .report-day-modal-body .modal-detail-panel {
        border-radius: 10px;
        border: 1px solid var(--border-soft);
        background: var(--panel-soft);
        padding: 9px;
        min-height: 58px;
    }

    .report-day-modal-body .modal-detail-panel .label {
        color: var(--text-dim);
        font-size: 0.58rem;
        text-transform: uppercase;
        letter-spacing: 0.65px;
    }

    .report-day-modal-body .modal-detail-panel .value {
        margin-top: 4px;
        display: block;
        color: #fff;
        font-size: 0.68rem;
        line-height: 1.28;
        word-break: break-word;
    }

    .report-day-modal-body .modal-image-wrap {
        margin-top: 10px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 9px;
    }

    .report-day-modal-body .modal-image-card {
        border-radius: 10px;
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        padding: 8px;
        min-height: 120px;
    }

    .report-day-modal-body .modal-image-card .img-title {
        color: var(--text-dim);
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.55px;
    }

    .report-day-modal-body .modal-image-card img {
        width: 100%;
        height: 110px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.14);
        background: #111;
    }

    .report-day-modal-body .modal-image-card .no-image {
        color: var(--text-dim);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 110px;
        border-radius: 8px;
        border: 1px dashed rgba(255,255,255,0.24);
        font-size: 0.62rem;
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
                            $calendarRow = data_get($cell, 'row');
                            $rowJson = $calendarRow ? json_encode($calendarRow) : null;
                            $hasDetail = !empty($calendarRow) && !empty(data_get($calendarRow, 'status'));
                        @endphp
                        <div class="cal-cell {{ $cellClass }} {{ $hasDetail ? 'has-detail' : '' }}" title="{{ $meta['label'] ?? 'No data' }}" tabindex="0" role="button"
                             data-report-date="{{ data_get($cell, 'date') }}"
                             data-report-status="{{ data_get($calendarRow, 'status') ?? '' }}"
                             data-report-row='{{ $rowJson }}'
                             @if(!$hasDetail) aria-disabled="true" @endif>
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

<div class="report-day-modal-backdrop" id="reportDayModalBackdrop">
    <div class="report-day-modal" role="dialog" aria-modal="true" aria-labelledby="reportDayModalTitle">
        <div class="report-day-modal-header">
            <div>
                <h3 id="reportDayModalTitle">Attendance Detail</h3>
            </div>
            <button type="button" class="report-day-modal-close" id="reportDayModalClose" aria-label="Close">&times;</button>
        </div>
        <div class="report-day-modal-body">
            <div id="reportDayModalContent">
                <div class="text-muted">Loading...</div>
            </div>
        </div>
    </div>
</div>

<script>
    function showReportTab(tab, btn) {
        document.getElementById('tab-days').style.display = tab === 'days' ? 'block' : 'none';
        document.getElementById('tab-list').style.display = tab === 'list' ? 'block' : 'none';
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function formatDateHuman(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString + 'T00:00:00');
        if (Number.isNaN(date.getTime())) return dateString;
        return date.toLocaleDateString(undefined, {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }

    function formatTime(value) {
        if (!value) return '--:--';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return value;
        return date.toLocaleTimeString(undefined, {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function openReportDayModal(detail) {
        const modal = document.getElementById('reportDayModalBackdrop');
        const modalPanel = modal ? modal.querySelector('.report-day-modal') : null;
        const content = document.getElementById('reportDayModalContent');
        const title = document.getElementById('reportDayModalTitle');
        if (!modal || !modalPanel || !content || !title || !detail) {
            return;
        }

        const status = String(detail.status || 'not_marked').toLowerCase();
        const statusLabels = {
            'present': 'Present',
            'absent': 'Absent',
            'leave': 'Leave',
            'half_time': 'Half Time',
            'holiday': 'Holiday',
            'week_off': 'Week Off'
        };

        const modalStatusClass = {
            'present': 'report-status-present',
            'absent': 'report-status-absent',
            'leave': 'report-status-leave',
            'half_time': 'report-status-half_time',
            'half-time': 'report-status-half_time',
            'holiday': 'report-status-holiday',
            'week_off': 'report-status-week_off',
            'week-off': 'report-status-week_off'
        };

        modalPanel.classList.remove(
            'report-status-present',
            'report-status-absent',
            'report-status-leave',
            'report-status-half_time',
            'report-status-holiday',
            'report-status-week_off',
            'report-status-none'
        );

        if (status && modalStatusClass[status]) {
            modalPanel.classList.add(modalStatusClass[status]);
        } else {
            modalPanel.classList.add('report-status-none');
        }

        title.textContent = formatDateHuman(detail.date || detail.day || '');

        if (!detail.status) {
            content.innerHTML = `
                <div class="report-day-modal-status-row">
                    <div class="report-day-modal-status">No activity</div>
                </div>
                <div class="modal-detail-grid">
                    <div class="modal-detail-panel">
                        <span class="label">Punch In</span>
                        <span class="value">--:--</span>
                    </div>
                    <div class="modal-detail-panel">
                        <span class="label">Punch Out</span>
                        <span class="value">--:--</span>
                    </div>
                </div>
            `;
            modal.classList.add('show');
            return;
        }

        const punchInImage = detail.punch_in_image || '';
        const punchOutImage = detail.punch_out_image || '';
        const punchInTime = detail.punch_in_time ? detail.punch_in_time : '--:--';
        const punchOutTime = detail.punch_out_time ? detail.punch_out_time : '--:--';
        const punchInLocation = detail.punch_in_location || 'No location captured';
        const punchOutLocation = detail.punch_out_location || 'No out location captured';
        const label = statusLabels[status] || status.replace(/_/g, ' ');
        const typeValue = String(detail.type || 'self').toLowerCase();
        const typeMap = {
            'self': { label: 'Self', className: 'report-day-modal-type-self' },
            'admin': { label: 'Admin', className: 'report-day-modal-type-admin' },
            'manual': { label: 'Manual', className: 'report-day-modal-type-manual' },
            'manual_entry': { label: 'Manual', className: 'report-day-modal-type-manual' },
            'employee': { label: 'Self', className: 'report-day-modal-type-self' }
        };

        const typeInfo = typeMap[typeValue] || {
            label: typeValue ? typeValue.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : 'Self',
            className: 'report-day-modal-type-default'
        };

        content.innerHTML = `
            <div class="report-day-modal-status-row">
                <div class="report-day-modal-status">${label}</div>
                <div class="report-day-modal-type ${typeInfo.className}">${typeInfo.label}</div>
            </div>
            <div class="modal-detail-grid">
                <div class="modal-detail-panel">
                    <span class="label">Punch In Time</span>
                    <span class="value">${punchInTime}</span>
                </div>
                <div class="modal-detail-panel">
                    <span class="label">Punch Out Time</span>
                    <span class="value">${punchOutTime}</span>
                </div>
                <div class="modal-detail-panel">
                    <span class="label">Punch In Location</span>
                    <span class="value">${punchInLocation}</span>
                </div>
                <div class="modal-detail-panel">
                    <span class="label">Punch Out Location</span>
                    <span class="value">${punchOutLocation}</span>
                </div>
            </div>
            <div class="modal-image-wrap">
                <div class="modal-image-card">
                    <div class="img-title">Punch In Image</div>
                    ${punchInImage ? `<img src="${punchInImage}" alt="Punch in image">` : `<div class="no-image">No punch-in image</div>`}
                </div>
                <div class="modal-image-card">
                    <div class="img-title">Punch Out Image</div>
                    ${punchOutImage ? `<img src="${punchOutImage}" alt="Punch out image">` : `<div class="no-image">No punch-out image</div>`}
                </div>
            </div>
        `;

        modal.classList.add('show');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('reportDayModalBackdrop');
        const closeButton = document.getElementById('reportDayModalClose');

        document.querySelectorAll('[data-report-row]').forEach(function (cell) {
            const row = cell.getAttribute('data-report-row');
            if (!row || row === 'null') {
                return;
            }

            const parsed = JSON.parse(row);
            if (!parsed || !parsed.date || !parsed.status) {
                return;
            }

            cell.addEventListener('click', function () {
                openReportDayModal(parsed);
            });

            cell.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openReportDayModal(parsed);
                }
            });
        });

        if (closeButton && modal) {
            closeButton.addEventListener('click', function () {
                modal.classList.remove('show');
            });
        }

        if (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.classList.remove('show');
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && modal.classList.contains('show')) {
                    modal.classList.remove('show');
                }
            });
        }
    });
</script>
@endsection