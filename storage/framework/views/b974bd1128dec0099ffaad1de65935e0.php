

<?php $__env->startSection('title', 'Apply Leave | EEMOT Clocking PWA'); ?>

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
    .leave-form-grid { display: grid; gap: 12px; }
    .leave-form-grid label { color: var(--text-dim); font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
    .leave-form-grid input, .leave-form-grid textarea { width: 100%; background: var(--panel-soft); border: 1.5px solid var(--border-soft); border-radius: 12px; padding: 12px 14px; color: #fff; font-size: 0.9rem; font-family: 'Poppins', sans-serif; }
    .leave-type-select {
        width: 100%;
        background: rgba(255,255,255,0.06);
        border: 1.5px solid rgba(255,255,255,0.16);
        border-radius: 12px;
        padding: 12px 14px;
        color: #fff;
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: linear-gradient(45deg, transparent 50%, rgba(255,255,255,0.75) 50%), linear-gradient(135deg, rgba(255,255,255,0.75) 50%, transparent 50%);
        background-position: calc(100% - 18px) calc(50% - 3px), calc(100% - 10px) calc(50% - 3px);
        background-size: 6px 6px;
        background-repeat: no-repeat;
        cursor: pointer;
        transition: border-color 0.15s ease, background 0.15s ease;
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
        min-width: 0;
    }
    .leave-type-select option {
        color: #fff;
        background: rgba(15, 22, 55, 0.98);
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
        padding: 8px 10px;
    }
    .leave-type-select::-ms-expand {
        display: none;
    }
    @media (max-width: 560px) {
        .leave-type-select {
            font-size: 0.84rem;
            padding: 10px 12px;
        }
    }
    .leave-form-grid textarea { min-height: 96px; resize: vertical; }
    .leave-form-grid input:focus, .leave-type-select:focus, .leave-form-grid textarea:focus { border-color: var(--accent-orange); background: rgba(255,122,26,0.06); outline: none; box-shadow: 0 0 0 4px rgba(255,122,26,0.1); }
    .leave-actions { display: flex; gap: 10px; margin-top: 4px; }
    .btn-primary-leave { flex: 1; border: none; border-radius: 12px; padding: 12px 14px; color: #fff; font-weight: 700; background: var(--grad-main); box-shadow: 0 10px 24px rgba(255,90,60,0.3); }
    .btn-secondary-leave { flex: 1; border: 1.5px solid var(--border-soft); border-radius: 12px; padding: 12px 14px; background: transparent; color: var(--text-muted); font-weight: 600; }
    .action-buttons { display: grid; gap: 12px; margin-top: 12px; }
    .action-button { display: flex; align-items: center; justify-content: space-between; padding: 16px 18px; border-radius: 14px; border: 1.5px solid var(--border-soft); background: var(--panel-soft); color: #fff; text-decoration: none; font-weight: 700; transition: transform 0.15s ease, border-color 0.15s ease; }
    .action-button:hover { border-color: var(--accent-orange); background: rgba(255,122,26,0.06); transform: translateY(-1px); }
    .action-button span { color: var(--text-dim); font-size: 0.78rem; display: block; margin-top: 4px; font-weight: 500; }
    .action-button i { color: var(--accent-orange); font-size: 18px; }
</style>

<div class="leave-shell">
    <section class="leave-hero">
        <div class="hero-copy">
            <p class="eyebrow">Leave Management</p>
            <h1>Apply Leave</h1>
            <p>Submit a new leave request with a polished form designed for quick use.</p>
        </div>
        <div class="leave-hero-icon"><i class="bi bi-send-fill"></i></div>
    </section>

    <section class="leave-card">
        <h3>New Leave Request</h3>
        <p class="hint">Fill in the details and send your request.</p>

        <form method="POST" action="<?php echo e(route('leave.store')); ?>" class="leave-form-grid">
            <?php echo csrf_field(); ?>
            <div class="select-field">
                <label for="leave_type">Leave Type</label>
                <select id="leave_type" name="leave_type" class="leave-type-select" required>
                    <option value="">Select leave type</option>
                    <?php $__currentLoopData = data_get($leaveTypes, [], []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leaveType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e(data_get($leaveType, 'id')); ?>" <?php echo e(old('leave_type') === (string) data_get($leaveType, 'id') ? 'selected' : ''); ?>>
                            <?php echo e(data_get($leaveType, 'name')); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WebProjects\web_clocking\resources\views/leave/apply.blade.php ENDPATH**/ ?>