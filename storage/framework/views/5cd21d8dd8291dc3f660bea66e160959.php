<?php $__env->startSection('title', 'Home | EEMOT Clocking PWA'); ?>

<?php
    $record = data_get($attendance, 'attendance', []);
    $log = data_get($attendance, 'attendance_log', []);
    $punchIn = data_get($record, 'punch_in_time');
    $punchOut = data_get($record, 'punch_out_time');
    $workMinutes = data_get($log, 'total_work_minutes') ?? data_get($record, 'working_minutes');
    $lateMinutes = data_get($log, 'late_by_minutes') ?? data_get($record, 'late_minutes');
    $status = ucfirst((string) (data_get($record, 'status') ?: 'Not marked'));
    $punchImage = imageValue(data_get($record, 'punch_out_image')) ?? imageValue(data_get($record, 'punch_in_image'));
?>

<?php $__env->startSection('content'); ?>
<style>
    .mobile-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
    }

    .hero-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .hero-top .eyebrow {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: var(--text-dim);
        font-weight: 600;
    }

    .hero-top h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.4rem;
        margin: 2px 0 4px;
    }

    .hero-top span {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .avatar-xl {
        width: 56px; height: 56px;
        border-radius: 16px;
        object-fit: cover;
        border: 2px solid var(--border-soft);
    }

    .hero-meta {
        display: flex;
        gap: 14px;
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px solid var(--border-soft);
    }

    .hero-meta > div {
        flex: 1;
        background: var(--panel-soft);
        border-radius: 12px;
        padding: 10px 14px;
    }

    .hero-meta span {
        display: block;
        color: var(--text-dim);
        font-size: 0.68rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .hero-meta strong {
        color: #fff;
        font-size: 0.95rem;
    }

    .status-card {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        backdrop-filter: blur(16px);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
    }

    .section-kicker {
        color: var(--accent-orange);
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .status-card h2 {
        color: #fff;
        font-weight: 700;
        font-size: 1.15rem;
        margin: 2px 0 0;
    }

    .status-pill {
        background: var(--grad-main);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 7px 14px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .time-pair {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .time-pair > div {
        flex: 1;
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 14px;
        padding: 14px;
        text-align: center;
    }

    .time-pair i {
        font-size: 20px;
        color: var(--accent-orange);
        margin-bottom: 6px;
        display: block;
    }

    .time-pair span {
        display: block;
        color: var(--text-dim);
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .time-pair strong {
        color: #fff;
        font-size: 1.05rem;
    }

    .quick-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 16px;
    }

    .metric {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 12px;
        padding: 12px 14px;
    }

    .metric span {
        display: block;
        color: var(--text-dim);
        font-size: 0.68rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .metric strong {
        color: #fff;
        font-size: 0.92rem;
    }

    .action-row {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-row .btn-primary {
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

    .action-row .btn-primary:hover {
        background-position: right center;
        transform: translateY(-2px);
    }

    .action-row .icon-btn {
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

    .info-stack {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .mini-panel {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 16px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .mini-panel i {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: var(--panel-soft);
        color: var(--accent-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .attendance-photo {
        width: 44px; height: 44px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .mini-panel span {
        display: block;
        color: var(--text-dim);
        font-size: 0.68rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .mini-panel strong {
        color: #fff;
        font-size: 0.85rem;
        word-break: break-word;
    }
</style>

<section class="mobile-hero">
    <div class="hero-top">
        <div>
            <p class="eyebrow mb-1">Welcome back</p>
            <h1><?php echo e(employeeName()); ?></h1>
            <span><?php echo e(data_get(employee(), 'employee_code', '-')); ?> / <?php echo e(data_get(employee(), 'department', '-')); ?></span>
        </div>
        <img class="avatar-xl" src="<?php echo e(profileImage() ?: asset('images/profile-placeholder.svg')); ?>" alt="Profile image">
    </div>
    <div class="hero-meta">
        <div>
            <span>Date</span>
            <strong><?php echo e(now()->format('d M Y')); ?></strong>
        </div>
        <div>
            <span>Time</span>
            <strong class="live-clock"><?php echo e(now()->format('h:i A')); ?></strong>
        </div>
    </div>
</section>

<?php if($apiError): ?>
    <div class="alert alert-warning"><?php echo e($apiError); ?></div>
<?php endif; ?>

<section class="status-card attendance-card">
    <div class="d-flex justify-content-between align-items-start gap-3">
        <div>
            <span class="section-kicker">Today</span>
            <h2>Attendance</h2>
        </div>
        <span class="status-pill"><i class="bi bi-check2-circle"></i><?php echo e($status); ?></span>
    </div>

    <div class="time-pair">
        <div>
            <i class="bi bi-arrow-down-left-circle-fill"></i>
            <span>Punch In</span>
            <strong><?php echo e($punchIn ? date('h:i A', strtotime($punchIn)) : '--:--'); ?></strong>
        </div>
        <div>
            <i class="bi bi-arrow-up-right-circle-fill"></i>
            <span>Punch Out</span>
            <strong><?php echo e($punchOut ? date('h:i A', strtotime($punchOut)) : '--:--'); ?></strong>
        </div>
    </div>

    <div class="quick-grid">
        <div class="metric">
            <span>Working Time</span>
            <strong><?php echo e(is_numeric($workMinutes) ? floor($workMinutes / 60).'h '.($workMinutes % 60).'m' : '-'); ?></strong>
        </div>
        <div class="metric">
            <span>Late By</span>
            <strong><?php echo e(is_numeric($lateMinutes) ? $lateMinutes.' min' : '-'); ?></strong>
        </div>
        <div class="metric">
            <span>Expected In</span>
            <strong><?php echo e(data_get($log, 'expected_in', data_get(employee(), 'work_start_time', '-'))); ?></strong>
        </div>
        <div class="metric">
            <span>Expected Out</span>
            <strong><?php echo e(data_get($log, 'expected_out', data_get(employee(), 'work_end_time', '-'))); ?></strong>
        </div>
    </div>

    <div class="action-row">
        <a href="<?php echo e(route('punch')); ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-camera-fill"></i>
            Punch Now
        </a>
        <a href="<?php echo e(route('home')); ?>" class="btn btn-light btn-lg icon-btn" aria-label="Refresh attendance">
            <i class="bi bi-arrow-clockwise"></i>
        </a>
    </div>
</section>

<section class="info-stack">
    <?php if($punchImage): ?>
        <article class="mini-panel">
            <img class="attendance-photo" src="<?php echo e($punchImage); ?>" alt="Attendance photo">
            <div>
                <span>Latest Photo</span>
                <strong><?php echo e($punchOut ? 'Punch out image' : 'Punch in image'); ?></strong>
            </div>
        </article>
    <?php endif; ?>

    <article class="mini-panel">
        <i class="bi bi-geo-alt-fill"></i>
        <div>
            <span>Last Location</span>
            <strong><?php echo e(data_get($record, 'punch_out_location') ?: data_get($record, 'punch_in_location') ?: 'No location yet'); ?></strong>
        </div>
    </article>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Asus\Documents\dev_clock\resources\views/home/index.blade.php ENDPATH**/ ?>