

<?php $__env->startSection('title', 'Leave Overview | EEMOT Clocking PWA'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .leave-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }
    .leave-hero .hero-copy {
        flex: 1 1 0;
        min-width: 0;
    }
    .leave-hero-icon {
        width: 48px; height: 48px; border-radius: 16px; background: var(--grad-main);
        display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff;
        box-shadow: 0 10px 24px rgba(255,90,60,0.35);
        cursor: default;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        transition: transform 0.15s ease;
    }
    .leave-hero-icon:active {
        transform: scale(0.94);
    }
    .leave-hero .eyebrow { text-transform: uppercase; font-size: 0.68rem; letter-spacing: 0.5px; color: var(--text-dim); font-weight: 600; margin: 0 0 6px; }
    .leave-hero h1 { color: #fff; font-weight: 800; font-size: 1.3rem; margin: 2px 0 4px; }
    .leave-hero p { color: var(--text-muted); font-size: 0.78rem; margin: 0; }
    .hero-button { display: inline-flex; align-items: center; justify-content: center; min-width: 130px; flex: 1; padding: 10px 16px; border-radius: 16px; font-weight: 700; font-size: 0.9rem; letter-spacing: 0.02em; text-decoration: none; transition: transform 0.15s ease, opacity 0.15s ease; }
    .hero-button.primary { background: var(--grad-main); color: #fff; }
    .hero-button.secondary { background: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.15); }
    .hero-button:hover { transform: translateY(-1px); opacity: 0.95; }
    .leave-shell { display: flex; flex-direction: column; gap: 14px; width: 100%; margin: 0 auto; padding: 0; }
    .overview-grid { display: grid; gap: 18px; width: 100%; }
    .tracker-card, .totals-card { background: var(--panel); border: 1px solid var(--border-soft); border-radius: 20px; padding: 22px; overflow: hidden; }
    .card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
    .card-actions { display: flex; flex-wrap: nowrap; gap: 12px; margin-top: 20px; }
    .card-actions .hero-button { justify-content: center; }
    .summary-top h3 { color: #fff; font-size: 1.05rem; font-weight: 700; margin: 0; }
    .summary-top p { color: var(--text-dim); font-size: 0.84rem; margin: 6px 0 0; max-width: 520px; }
    .summary-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; background: rgba(255,122,26,0.12); color: var(--accent-orange); font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
    .tracker-card h3, .totals-card h3 { color: #fff; font-size: 1.15rem; margin: 0; }
    .tracker-copy, .totals-copy { color: var(--text-dim); font-size: 0.78rem; line-height: 1.5; margin-top: 8px; }
    .stats-row { display: flex; gap: 10px; margin-top: 14px; }
    .stat-box { background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02)); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; padding: 12px; min-height: 88px; flex: 1 1 0; min-width: 0; }
    .stat-box .value { color: #fff; font-size: 1.6rem; font-weight: 800; margin-bottom: 4px; line-height: 1; }
    .stat-box .label { color: var(--text-dim); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.25px; }
    .analytics-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 20px; }
    .analytics-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
    .analytics-title { color: #fff; font-weight: 700; font-size: 0.98rem; margin: 0; }
    .analytics-subtitle { color: var(--text-dim); font-size: 0.82rem; line-height: 1.45; margin: 6px 0 0; }
    .bar-chart { display: grid; gap: 14px; }
    .bar-item { display: grid; grid-template-columns: 96px 1fr 48px; gap: 12px; align-items: center; }
    .bar-label { color: var(--text-dim); font-size: 0.82rem; }
    .bar-track { background: rgba(255,255,255,0.06); border-radius: 999px; overflow: hidden; height: 12px; }
    .bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, rgba(255,122,26,0.95), rgba(122,58,255,0.9)); }
    .bar-value { color: #fff; font-size: 0.78rem; text-align: right; }
    .legend-row { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin-top: 18px; }
    .legend-pill { display: inline-flex; align-items: center; gap: 8px; color: var(--text-dim); font-size: 0.78rem; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--accent-orange); }
    .legend-dot.second { background: rgba(255,255,255,0.45); }
    .legend-dot.third { background: #7a3aff; }
    .monthly-summary { display: grid; gap: 14px; }
    .monthly-summary h3 { color: #fff; font-size: 1rem; margin: 0; }
    .monthly-summary p { color: var(--text-dim); font-size: 0.82rem; margin: 0; }
    .stats-list { display: grid; gap: 12px; }
    .stats-item { display: flex; align-items: center; justify-content: space-between; gap: 14px; background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 16px; padding: 16px; }
    .stats-item strong { color: #fff; font-size: 1rem; }
    .stats-item span { color: var(--text-dim); font-size: 0.82rem; }
    .nav-card { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 20px; border-radius: 18px; border: 1.5px solid var(--border-soft); background: var(--panel-soft); text-decoration: none; transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease; }
    .nav-card:hover { border-color: var(--accent-orange); background: rgba(255,122,26,0.06); transform: translateY(-1px); }
    .nav-info strong { color: #fff; font-size: 1rem; display: block; }
    .nav-info small { color: var(--text-dim); font-size: 0.78rem; }
    .nav-card i { color: var(--accent-orange); font-size: 1.5rem; }
    @media (min-width: 860px) {
        .overview-grid { grid-template-columns: 2fr 1fr; }
    }

    @media (max-width: 860px) {
        .leave-hero { padding: 18px; }
        .card-top { flex-direction: column; }
        .stats-row { grid-template-columns: 1fr; }
        .card-actions { flex-direction: column; }
        .hero-button { width: 100%; }
    }
</style>

<div class="leave-shell">
    <section class="leave-hero">
        <div class="hero-copy">
            <p class="eyebrow">Leave Management</p>
            <h1>Leave Overview</h1>
            <p>Track your leave status and jump into the right section instantly.</p>
        </div>
        <div class="leave-hero-icon"><i class="bi bi-calendar2-week-fill"></i></div>
    </section>

    <div class="overview-grid">
        <section class="totals-card">
            <div class="card-top">
                <div>
                    <h3>Leave totals</h3>
                    <p class="totals-copy">Summary of all leave requests, including pending, approved, and rejected items.</p>
                </div>
            </div>

            <div class="stats-row">
                <div class="stat-box">
                    <span class="value"><?php echo e(data_get($leaveCounts, 'pending', 0)); ?></span>
                    <span class="label">Pending</span>
                </div>
                <div class="stat-box">
                    <span class="value"><?php echo e(data_get($leaveCounts, 'approved', 0)); ?></span>
                    <span class="label">Approved</span>
                </div>
                <div class="stat-box">
                    <span class="value"><?php echo e(data_get($leaveCounts, 'reject', 0)); ?></span>
                    <span class="label">Rejected</span>
                </div>
            </div>

            <div class="card-actions">
                <a href="<?php echo e(route('leave.apply')); ?>" class="hero-button primary">Apply leave</a>
                <a href="<?php echo e(route('leave.history')); ?>" class="hero-button secondary">Leave history</a>
            </div>
        </section>

        <section class="tracker-card">
            <div class="card-top">
                <div>
                    <h3>Paid Leave Tracker</h3>
                    <p class="tracker-copy">Each month you get one paid leave day. Track how much has been used and how much remains.</p>
                </div>
                <span class="summary-badge"><?php echo e(data_get($leaveStats, 'used_paid_leave', 0)); ?>/<?php echo e(data_get($leaveStats, 'paid_leave_limit', 1)); ?> Used</span>
            </div>

            <div class="analytics-card">
                <div class="analytics-row">
                    <div>
                        <p class="analytics-title">Leave usage</p>
                        <p class="analytics-subtitle">A clear view of pay leave used versus remaining balance.</p>
                    </div>
                    <span class="summary-badge"><?php echo e(data_get($leaveStats, 'pending', 0)); ?> pending</span>
                </div>

                <div class="bar-chart">
                    <div class="bar-item">
                        <span class="bar-label">Used</span>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: <?php echo e(min(100, max(0, intval((data_get($leaveStats, 'used_paid_leave', 0) / max(1, data_get($leaveStats, 'paid_leave_limit', 1))) * 100)))); ?>%;"></div>
                        </div>
                        <span class="bar-value"><?php echo e(data_get($leaveStats, 'used_paid_leave', 0)); ?>d</span>
                    </div>
                    <div class="bar-item">
                        <span class="bar-label">Remaining</span>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: <?php echo e(min(100, max(0, intval((data_get($leaveStats, 'remaining_paid_leave', 0) / max(1, data_get($leaveStats, 'paid_leave_limit', 1))) * 100)))); ?>%; background: rgba(255,255,255,0.35);"></div>
                        </div>
                        <span class="bar-value"><?php echo e(data_get($leaveStats, 'remaining_paid_leave', 0)); ?>d</span>
                    </div>
                </div>

                <div class="legend-row">
                    <span class="legend-pill"><span class="legend-dot"></span>Used</span>
                    <span class="legend-pill"><span class="legend-dot second"></span>Remaining</span>
                    <span class="legend-pill"><span class="legend-dot third"></span>Planned</span>
                </div>
            </div>
        </section>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WebProjects\web_clocking\resources\views/leave/index.blade.php ENDPATH**/ ?>