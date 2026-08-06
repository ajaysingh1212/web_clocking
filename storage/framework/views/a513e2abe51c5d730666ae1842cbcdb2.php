

<?php $__env->startSection('title', 'Settings | EEMOT Clocking PWA'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .settings-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .settings-hero .eyebrow {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: var(--text-dim);
        font-weight: 600;
    }

    .settings-hero h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.4rem;
        margin: 2px 0 4px;
    }

    .settings-hero span {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .settings-hero > i {
        width: 52px; height: 52px;
        border-radius: 16px;
        background: var(--grad-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 10px 24px rgba(255,90,60,0.35);
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        transition: transform 0.15s ease;
    }

    .settings-hero > i:active {
        transform: scale(0.94);
    }

    .settings-panel {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 16px;
    }

    .settings-panel h3 {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 4px;
    }

    .settings-panel p.hint {
        color: var(--text-dim);
        font-size: 0.8rem;
        margin-bottom: 18px;
    }

    .menu-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        background: var(--panel-soft);
        border: 1.5px solid var(--border-soft);
        border-radius: 14px;
        padding: 14px 16px;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        width: 100%;
        text-align: left;
    }

    .menu-item:hover {
        border-color: var(--accent-orange);
        background: rgba(255,122,26,0.06);
        transform: translateY(-1px);
    }

    .menu-item .menu-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .menu-item .menu-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        background: var(--grad-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #fff;
        flex-shrink: 0;
    }

    .menu-item .menu-text strong {
        display: block;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .menu-item .menu-text span {
        display: block;
        color: var(--text-dim);
        font-size: 0.76rem;
        margin-top: 2px;
    }

    .menu-item .menu-chevron {
        color: var(--text-dim);
        font-size: 1rem;
    }

    .danger-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .danger-row div span {
        display: block;
        color: var(--text-dim);
        font-size: 0.8rem;
    }

    .btn-danger-outline {
        border: 1.5px solid #ff3d5a;
        color: #ff3d5a;
        background: transparent;
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .btn-danger-outline:hover {
        background: #ff3d5a;
        color: #fff;
    }

    .app-version {
        text-align: center;
        color: var(--text-dim);
        font-size: 0.75rem;
        margin-top: 6px;
    }

    /* Developer warning modal */
    .dev-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(5, 8, 24, 0.75);
        backdrop-filter: blur(6px);
        z-index: 5000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .dev-modal-backdrop.show {
        display: flex;
    }

    .dev-modal {
        width: 100%;
        max-width: 380px;
        background: var(--panel);
        border: 1.5px solid rgba(255, 61, 90, 0.4);
        border-radius: 22px;
        padding: 28px 24px;
        box-shadow: 0 30px 80px rgba(0,0,0,0.5);
        animation: devModalIn 0.3s ease;
    }

    @keyframes devModalIn {
        from { opacity: 0; transform: translateY(16px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .dev-modal-icon {
        width: 54px; height: 54px;
        border-radius: 16px;
        background: linear-gradient(135deg, #ff3d5a, #ff7a1a);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
        margin: 0 auto 16px;
        box-shadow: 0 10px 24px rgba(255,61,90,0.35);
    }

    .dev-modal h4 {
        color: #fff;
        font-size: 1.05rem;
        font-weight: 700;
        text-align: center;
        margin: 0 0 8px;
    }

    .dev-modal p {
        color: var(--text-dim);
        font-size: 0.82rem;
        text-align: center;
        line-height: 1.5;
        margin: 0 0 20px;
    }

    .dev-modal .form-label {
        color: var(--text-dim);
        font-size: 0.72rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.4px;
        margin-bottom: 8px;
        display: block;
    }

    .dev-modal input {
        width: 100%;
        background: var(--panel-soft);
        border: 1.5px solid var(--border-soft);
        border-radius: 12px;
        padding: 12px 14px;
        color: #fff;
        font-size: 0.9rem;
        margin-bottom: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .dev-modal input:focus {
        background: rgba(255,61,90,0.06);
        border-color: #ff3d5a;
        box-shadow: 0 0 0 4px rgba(255,61,90,0.1);
        outline: none;
    }

    .dev-modal-error {
        color: #ff8095;
        font-size: 0.78rem;
        margin-bottom: 12px;
        display: none;
    }

    .dev-modal-error.show { display: block; }

    .dev-modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 6px;
    }

    .dev-modal-actions button {
        flex: 1;
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-dev-cancel {
        background: var(--panel-soft);
        color: var(--text-muted);
        border: 1.5px solid var(--border-soft) !important;
    }

    .btn-dev-cancel:hover {
        background: rgba(255,255,255,0.08);
    }

    .btn-dev-confirm {
        background: linear-gradient(135deg, #ff3d5a, #ff7a1a);
        color: #fff;
        box-shadow: 0 10px 22px rgba(255,61,90,0.3);
    }

    .btn-dev-confirm:hover {
        transform: translateY(-1px);
        box-shadow: 0 14px 28px rgba(255,61,90,0.4);
    }
</style>

<?php if($apiError ?? null): ?>
    <div class="alert alert-warning"><?php echo e($apiError); ?></div>
<?php endif; ?>

<section class="settings-hero">
    <div>
        <p class="eyebrow mb-1">Account</p>
        <h1>Settings</h1>
        <span><?php echo e(employeeName()); ?> / <?php echo e(data_get(employee(), 'employee_code', '-')); ?></span>
    </div>
    <i class="bi bi-gear-fill" id="settings-gear-icon"></i>
</section>

<section class="settings-panel">
    <h3>Manage</h3>
    <p class="hint">Quick access to user account and reports.</p>

    <div class="menu-list">
        <a href="#" class="menu-item">
            <div class="menu-left">
                <i class="bi bi-bell-fill menu-icon"></i>
                <div class="menu-text">
                    <strong>Notification</strong>
                    <span>View alerts &amp; updates</span>
                </div>
            </div>
            <i class="bi bi-chevron-right menu-chevron"></i>
        </a>

        <a href="#" class="menu-item">
            <div class="menu-left">
                <i class="bi bi-pencil-square menu-icon"></i>
                <div class="menu-text">
                    <strong>Edit Profile</strong>
                    <span>Update your personal information</span>
                </div>
            </div>
            <i class="bi bi-chevron-right menu-chevron"></i>
        </a>

        <a href="#" class="menu-item">
            <div class="menu-left">
                <i class="bi bi-calendar2-plus-fill menu-icon"></i>
                <div class="menu-text">
                    <strong>Apply Leave</strong>
                    <span>Submit a new leave request</span>
                </div>
            </div>
            <i class="bi bi-chevron-right menu-chevron"></i>
        </a>

        <a href="#" class="menu-item">
            <div class="menu-left">
                <i class="bi bi-cash-stack menu-icon"></i>
                <div class="menu-text">
                    <strong>Salary Details</strong>
                    <span>View salary &amp; payment info</span>
                </div>
            </div>
            <i class="bi bi-chevron-right menu-chevron"></i>
        </a>

        <a href="#" class="menu-item">
            <div class="menu-left">
                <i class="bi bi-envelope-fill menu-icon"></i>
                <div class="menu-text">
                    <strong>Email Reports</strong>
                    <span>Get reports sent to your email</span>
                </div>
            </div>
            <i class="bi bi-chevron-right menu-chevron"></i>
        </a>
    </div>
</section>

<section class="settings-panel">
    <div class="danger-row">
        <div>
            <h3 class="mb-1">Log Out</h3>
            <span>End your current session on this device</span>
        </div>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button class="btn-danger-outline" type="submit">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>
        </form>
    </div>
</section>

<p class="app-version">EEMOT Clocking PWA &middot; v1.0.0</p>


<div class="dev-modal-backdrop" id="dev-modal-backdrop">
    <div class="dev-modal">
        <div class="dev-modal-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <h4>Restricted Access</h4>
        <p>This section is for developers only. Entering incorrect credentials or accessing without authorization is not permitted.</p>

        <label class="form-label" for="dev-password">Developer Password</label>
        <input type="password" id="dev-password" placeholder="Enter password" autocomplete="off">
        <div class="dev-modal-error" id="dev-modal-error">Incorrect password. Access denied.</div>

        <div class="dev-modal-actions">
            <button type="button" class="btn-dev-cancel" id="dev-modal-cancel">Cancel</button>
            <button type="button" class="btn-dev-confirm" id="dev-modal-confirm">Unlock</button>
        </div>
    </div>
</div>

<script>
    (function () {
        const gearIcon = document.getElementById('settings-gear-icon');
        const backdrop = document.getElementById('dev-modal-backdrop');
        const cancelBtn = document.getElementById('dev-modal-cancel');
        const confirmBtn = document.getElementById('dev-modal-confirm');
        const passwordInput = document.getElementById('dev-password');
        const errorEl = document.getElementById('dev-modal-error');

        const DEV_PASSWORD = 'Sanket@5513';

        let clickCount = 0;
        let clickTimer = null;

        function openModal() {
            backdrop.classList.add('show');
            errorEl.classList.remove('show');
            passwordInput.value = '';
            setTimeout(() => passwordInput.focus(), 100);
        }

        function closeModal() {
            backdrop.classList.remove('show');
        }

        // Triple-click on the gear icon opens the developer modal
        gearIcon.addEventListener('click', function () {
            clickCount++;

            if (clickTimer) clearTimeout(clickTimer);

            if (clickCount === 3) {
                clickCount = 0;
                openModal();
                return;
            }

            clickTimer = setTimeout(() => {
                clickCount = 0;
            }, 700);
        });

        cancelBtn.addEventListener('click', closeModal);

        backdrop.addEventListener('click', function (e) {
            if (e.target === backdrop) closeModal();
        });

        function tryUnlock() {
            if (passwordInput.value === DEV_PASSWORD) {
                window.location.href = "<?php echo e(route('developer.index')); ?>";
            } else {
                errorEl.classList.add('show');
                passwordInput.value = '';
                passwordInput.focus();
            }
        }

        confirmBtn.addEventListener('click', tryUnlock);
        passwordInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') tryUnlock();
        });
    })();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WebProjects\web_clocking\resources\views/settings.blade.php ENDPATH**/ ?>