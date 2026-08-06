

<?php $__env->startSection('title', 'Leave History | EEMOT Clocking PWA'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .leave-shell { display: flex; flex-direction: column; gap: 14px; }
    .leave-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .leave-hero .hero-copy {
        max-width: calc(100% - 68px);
    }
    .leave-hero-icon {
        width: 52px; height: 52px; border-radius: 16px; background: var(--grad-main);
        display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff;
        box-shadow: 0 10px 24px rgba(255,90,60,0.35);
        cursor: default;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        transition: transform 0.15s ease;
    }
    .leave-hero-icon:active {
        transform: scale(0.94);
    }
    .leave-hero .eyebrow { text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; color: var(--text-dim); font-weight: 600; margin: 0 0 6px; }
    .leave-hero h1 { color: #fff; font-weight: 800; font-size: 1.4rem; margin: 2px 0 4px; }
    .leave-hero p { color: var(--text-muted); font-size: 0.8rem; margin: 0; }
    .leave-card { background: var(--panel); border: 1px solid var(--border-soft); border-radius: 20px; padding: 18px; }
    .leave-card h3 { color: #fff; font-size: 1rem; font-weight: 700; margin: 0 0 4px; }
    .leave-card .hint { color: var(--text-dim); font-size: 0.78rem; margin-bottom: 14px; }
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 16px; }
    .stat-box { background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 16px; padding: 14px; text-align: center; }
    .stat-box .value { display: block; color: #fff; font-size: 1rem; font-weight: 800; margin-bottom: 4px; }
    .stat-box .label { color: var(--text-dim); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px; }
    .history-item { background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 14px; padding: 12px 14px; margin-bottom: 10px; }
    .history-item:last-child { margin-bottom: 0; }
    .history-top { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 6px; }
    .history-top strong { color: #fff; font-size: 0.9rem; }
    .status-pill { border-radius: 999px; padding: 4px 8px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
    .status-approved { background: rgba(34,197,94,0.16); color: #4ade80; }
    .status-pending { background: rgba(245,158,11,0.16); color: #fbbf24; }
    .status-reject { background: rgba(248,113,113,0.16); color: #f87171; }
    .history-meta { color: var(--text-dim); font-size: 0.75rem; margin-bottom: 6px; }
    .history-desc { color: var(--text-muted); font-size: 0.82rem; line-height: 1.45; }
    .action-buttons { display: grid; gap: 12px; margin-top: 12px; }
    .action-button { display: flex; align-items: center; justify-content: space-between; padding: 16px 18px; border-radius: 14px; border: 1.5px solid var(--border-soft); background: var(--panel-soft); color: #fff; text-decoration: none; font-weight: 700; transition: transform 0.15s ease, border-color 0.15s ease; }
    .action-button:hover { border-color: var(--accent-orange); background: rgba(255,122,26,0.06); transform: translateY(-1px); }
    .action-button span { color: var(--text-dim); font-size: 0.78rem; display: block; margin-top: 4px; font-weight: 500; }
    .action-button i { color: var(--accent-orange); font-size: 18px; }
</style>

<?php if($apiError ?? null): ?>
    <div class="alert alert-warning"><?php echo e($apiError); ?></div>
<?php endif; ?>

<div class="leave-shell">
    <section class="leave-hero">
        <div class="hero-copy">
            <p class="eyebrow">Leave Management</p>
            <h1>Leave History</h1>
            <p>Track all your requests and review statuses over time.</p>
        </div>
        <div class="leave-hero-icon"><i class="bi bi-clock-history"></i></div>
    </section>

    <section class="leave-card">
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

        <h3>Recent Requests</h3>
        <p class="hint">Your complete leave history appears here.</p>

        <?php if(!empty($leaveRequests)): ?>
            <?php $__currentLoopData = $leaveRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="history-item">
                    <div class="history-top">
                        <strong><?php echo e(data_get($leave, 'title') ?: 'Leave Request'); ?></strong>
                        <span class="status-pill status-<?php echo e(data_get($leave, 'status', 'pending')); ?>"><?php echo e(data_get($leave, 'status_label') ?: ucfirst((string) data_get($leave, 'status', 'Pending'))); ?></span>
                    </div>
                    <div class="history-meta">
                        <?php echo e(data_get($leave, 'date_from')); ?> to <?php echo e(data_get($leave, 'date_to')); ?>

                    </div>
                    <div class="history-desc">
                        <?php echo e(data_get($leave, 'description') ?: 'No additional note provided.'); ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="history-item">
                <div class="history-desc">No leave history available yet.</div>
            </div>
        <?php endif; ?>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WebProjects\web_clocking\resources\views/leave/history.blade.php ENDPATH**/ ?>