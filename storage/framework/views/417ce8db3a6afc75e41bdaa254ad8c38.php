

<?php $__env->startSection('title', 'Notifications | EEMOT Clocking PWA'); ?>

<?php $__env->startSection('content'); ?>
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
        padding: 18px;
        margin-bottom: 18px;
    }

    .notification-card {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 14px;
        padding: 14px 14px 12px;
        margin-bottom: 12px;
    }

    .notification-card:last-child {
        margin-bottom: 0;
    }

    .notification-card h3 {
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0 0 6px;
    }

    .notification-card .meta {
        color: var(--text-dim);
        font-size: 0.72rem;
        margin-bottom: 8px;
    }

    .notification-card .content {
        color: var(--text-muted);
        font-size: 0.84rem;
        line-height: 1.55;
    }

    .notification-card .content p {
        margin: 0;
    }

    .empty-state {
        text-align: center;
        color: var(--text-dim);
        font-size: 0.9rem;
        padding: 20px 0 8px;
    }
</style>

<?php if($apiError ?? null): ?>
    <div class="alert alert-warning"><?php echo e($apiError); ?></div>
<?php endif; ?>

<section class="notifications-hero">
    <div>
        <p class="eyebrow mb-1">Updates</p>
        <h1>Notifications</h1>
        <span>All recent alerts and updates</span>
    </div>
    <i class="bi bi-bell-fill"></i>
</section>

<section class="notifications-panel">
    <?php if(!empty($notifications)): ?>
        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="notification-card">
                <h3><?php echo e(data_get($notification, 'heading') ?: 'Notification'); ?></h3>
                <div class="meta"><?php echo e(data_get($notification, 'created_at') ?: '-'); ?></div>
                <div class="content">
                    <?php echo data_get($notification, 'content') ?: 'No details available.'; ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="empty-state">No notifications available right now.</div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WebProjects\web_clocking\resources\views/notifications/index.blade.php ENDPATH**/ ?>