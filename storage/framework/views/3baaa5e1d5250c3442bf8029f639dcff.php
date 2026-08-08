

<?php $__env->startSection('title', 'Documents | EEMOT Clocking PWA'); ?>

<?php $__env->startSection('content'); ?>
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
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }
    .docs-hero > div:first-child {
        flex: 1 1 0;
        min-width: 0;
    }
    .docs-hero h1 { color: #fff; font-weight: 800; font-size: 1.15rem; margin: 0 0 4px; }
    .docs-hero p { color: var(--text-muted); font-size: 0.78rem; margin: 0; max-width: 75%; line-height: 1.5; }
    .docs-hero-icon {
        width: 48px; height: 48px; border-radius: 16px; background: var(--grad-main);
        display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff;
        flex-shrink: 0;
    }
    .docs-panel { background: var(--panel); border: 1px solid var(--border-soft); border-radius: 20px; padding: 18px; }
    .docs-panel h3 { color: #fff; font-size: 1rem; font-weight: 700; margin: 0 0 12px; }
    .doc-list { display: grid; gap: 12px; }
    .doc-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; background: var(--panel-soft); border: 1px solid var(--border-soft); border-radius: 16px; padding: 16px 18px; }
    .doc-info strong { color: #fff; display: block; font-size: 0.96rem; margin-bottom: 4px; }
    .doc-info span { color: var(--text-dim); font-size: 0.8rem; }
    .doc-link {
        min-width: 120px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 14px;
        padding: 10px 14px;
        background: linear-gradient(135deg, #ff7a1a, #ff3d81, #7a3aff);
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        transition: transform 0.15s ease, opacity 0.15s ease;
    }
    .doc-link:hover { transform: translateY(-1px); opacity: 0.95; }

    .salary-slip-modal {
        position: fixed;
        inset: 0;
        z-index: 1500;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(6, 10, 31, 0.82);
        backdrop-filter: blur(6px);
    }

    .salary-slip-modal.show {
        display: flex;
    }

    .salary-slip-modal-panel {
        width: min(100%, 560px);
        background: linear-gradient(180deg, rgba(35, 41, 85, 0.98), rgba(17, 22, 53, 0.98));
        border: 1px solid var(--border-soft);
        border-radius: 24px;
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.55);
        overflow: hidden;
    }

    .salary-slip-modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-soft);
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
    }

    .salary-slip-modal-header h3 {
        margin: 0;
        color: #fff;
        font-size: 1rem;
        font-weight: 800;
    }

    .salary-slip-modal-body {
        padding: 20px;
    }

    .salary-slip-modal-title {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .salary-slip-modal-body .doc-title-row {
        color: var(--text-muted);
        font-size: 0.78rem;
        margin-bottom: 14px;
    }

    .salary-slip-modal-body label {
        display: block;
        color: #e9edf9;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .salary-slip-modal-body select {
        width: 100%;
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 12px;
        color: #fff;
        padding: 12px 14px;
        font-size: 0.84rem;
        outline: none;
    }

    .salary-slip-modal-body select option {
        color: #10152b;
    }

    .salary-slip-modal-body .field-wrap {
        margin-bottom: 14px;
    }

    .salary-slip-modal-footer {
        padding: 16px 20px 20px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        background: rgba(255,255,255,0.03);
    }

    .salary-slip-modal-footer .btn {
        border: none;
        border-radius: 999px;
        padding: 10px 20px;
        font-size: 0.78rem;
        font-weight: 800;
        min-width: 112px;
    }

    .salary-slip-modal-footer .btn-secondary {
        background: linear-gradient(135deg, #838b9a, #565e75);
        color: #fff;
    }

    .salary-slip-modal-footer .btn-primary {
        background: linear-gradient(135deg, #ff7a1a, #ff3d81, #7a3aff);
        color: #fff;
    }

    .salary-slip-modal-footer .btn-primary i {
        margin-right: 4px;
    }
</style>

<div class="docs-shell">
    <section class="docs-hero">
        <div>
            <p class="eyebrow">Documents</p>
            <h1>Company Documents</h1>
            <p>Download your legal letters, salary slips, and policy files in one place.</p>
        </div>
        <div class="docs-hero-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
    </section>

    <section class="docs-panel">
        <h3>Available Documents</h3>
        <div class="doc-list">
            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="doc-item">
                    <div class="doc-info">
                        <strong><?php echo e($document['title']); ?></strong>
                        <span><?php echo e($document['description']); ?></span>
                    </div>
                    <?php if($document['title'] === 'Salary Slip'): ?>
                        <a href="#salarySlipModal" class="doc-link salary-slip-open">
                            <i class="bi bi-download"></i>
                            Download
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e($document['file']); ?>" class="doc-link" download>
                            <i class="bi bi-download"></i>
                            Download
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
</div>

<div class="salary-slip-modal" id="salarySlipModal" aria-hidden="true">
    <div class="salary-slip-modal-panel" role="dialog" aria-modal="true" aria-labelledby="salarySlipModalTitle">
        <div class="salary-slip-modal-header">
            <h3 id="salarySlipModalTitle">Salary Slip</h3>
        </div>

        <form method="GET" action="<?php echo e(route('settings.salary-slip.download')); ?>" data-no-loader="true">
            <div class="salary-slip-modal-body">
                <div class="salary-slip-modal-title">Choose Salary Slip Date</div>
                <div class="doc-title-row">Generate your salary slip for the selected month.</div>

                <input type="hidden" name="user_id" value="10">

                <div class="field-wrap">
                    <label for="salarySlipMonth">Month</label>
                    <select id="salarySlipMonth" name="month" required>
                        <?php for($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo e($m); ?>" <?php echo e($m === 7 ? 'selected' : ''); ?>><?php echo e(date('F', mktime(0, 0, 0, $m, 1))); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="field-wrap">
                    <label for="salarySlipYear">Year</label>
                    <select id="salarySlipYear" name="year" required>
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
            </div>

            <div class="salary-slip-modal-footer">
                <button type="button" class="btn btn-secondary salary-slip-close">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-download"></i>
                    Download
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    (function () {
        const modal = document.getElementById('salarySlipModal');
        if (!modal) return;

        const openTrigger = document.querySelector('[href="#salarySlipModal"]');
        const closeButtons = modal.querySelectorAll('[data-dismiss], .salary-slip-close');

        function openModal() {
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeModal() {
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        }

        if (openTrigger) {
            openTrigger.addEventListener('click', function (event) {
                event.preventDefault();
                openModal();
            });
        }

        closeButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                closeModal();
            });
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('show')) {
                closeModal();
            }
        });
    })();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WebProjects\web_clocking\resources\views/settings/documents.blade.php ENDPATH**/ ?>