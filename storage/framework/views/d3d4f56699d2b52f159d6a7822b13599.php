

<?php $__env->startSection('title', 'Salary Details | EEMOT Clocking PWA'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .salary-hero {
        background: linear-gradient(135deg, rgba(255,122,26,0.12), rgba(122,58,255,0.12));
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 18px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 14px;
    }
    .salary-hero-copy {
        min-width: 0;
    }
    .salary-hero-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: var(--grad-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #fff;
        flex-shrink: 0;
    }
    .salary-hero .eyebrow {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: var(--text-dim);
        font-weight: 600;
        margin: 0 0 6px;
    }
    .salary-hero h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.4rem;
        margin: 0;
    }
    .salary-hero p {
        color: var(--text-muted);
        font-size: 0.8rem;
        margin: 4px 0 0;
        max-width: 540px;
    }
    .salary-hero-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: var(--grad-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #fff;
    }
    .salary-panel {
        background: var(--panel);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 22px;
        margin-bottom: 18px;
    }
    .salary-panel h3 {
        color: #fff;
        font-size: 1rem;
        margin: 0 0 12px;
    }
    .salary-panel p {
        color: var(--text-dim);
        font-size: 0.82rem;
        margin: 0;
    }
    .salary-form {
        display: grid;
        gap: 12px;
        margin-bottom: 20px;
    }
    .salary-form-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 12px;
        align-items: end;
    }
    .salary-form-field {
        min-width: 0;
    }
    .salary-form label {
        color: var(--text-dim);
        font-size: 0.8rem;
        font-weight: 700;
        display: block;
        margin-bottom: 6px;
    }
    .salary-form input,
    .salary-form select {
        width: 100%;
        padding: 12px 14px;
        border-radius: 14px;
        border: 1.5px solid var(--border-soft);
        background: var(--panel-soft);
        color: #fff;
        font-size: 0.95rem;
    }
    .salary-form button {
        min-width: 140px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #ff7a1a, #ff3d81, #7a3aff);
        color: #fff;
        font-weight: 700;
        padding: 12px 18px;
        cursor: pointer;
        transition: transform 0.15s ease, opacity 0.15s ease;
    }
    .salary-form button:hover {
        transform: translateY(-1px);
    }
    .salary-grid {
        display: grid;
        gap: 16px;
    }
    .salary-card {
        background: var(--panel-soft);
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 20px;
    }
    .salary-card h4 {
        color: #fff;
        margin: 0 0 10px;
        font-size: 1rem;
    }
    .salary-card .salary-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        margin-bottom: 8px;
    }
    .salary-card .salary-row:last-child {
        margin-bottom: 0;
    }
    .salary-card .label {
        color: var(--text-dim);
        font-size: 0.8rem;
    }
    .salary-card .value {
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
    }
    .salary-summary {
        display: grid;
        gap: 16px;
    }
    .salary-summary .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
        background: rgba(255,122,26,0.18);
    }
    .salary-summary .status-pill.pending { background: rgba(255, 154, 0, 0.18); }
    .salary-summary .status-pill.paid { background: rgba(43, 194, 146, 0.18); }
    .salary-summary .status-pill.other { background: rgba(117, 97, 255, 0.18); }
    .empty-state {
        color: var(--text-dim);
        padding: 18px;
        border-radius: 18px;
        background: var(--panel-soft);
    }
    @media (min-width: 860px) {
        .salary-grid { grid-template-columns: 2fr 1fr; }
    }
</style>

<?php if($apiError): ?>
    <div class="alert alert-warning"><?php echo e($apiError); ?></div>
<?php endif; ?>

<section class="salary-hero">
    <div class="salary-hero-copy">
        <p class="eyebrow">Salary</p>
        <h1>Salary Details</h1>
        <p>Review your latest salary breakdown, payment status, and net take-home amount for the selected month.</p>
    </div>
    <div class="salary-hero-icon"><i class="bi bi-cash-stack"></i></div>
</section>

<section class="salary-panel">
    <h3>Choose month</h3>
    <form class="salary-form" method="GET" action="<?php echo e(route('settings.salary')); ?>">
        <div class="salary-form-row">
            <div class="salary-form-field">
                <label for="month">Month &amp; year</label>
                <input id="month" name="month" type="month" value="<?php echo e($selectedMonth); ?>">
            </div>
            <button type="submit">Load salary</button>
        </div>
    </form>

    <?php
        $salary = $salaryData ?: [];
        $monthName = $salary ? date('F Y', strtotime(sprintf('%s-01', $selectedMonth))) : date('F Y', strtotime(sprintf('%s-01', $selectedMonth)));
    ?>

    <?php if(empty($salary)): ?>
        <div class="empty-state">
            No salary data available for <?php echo e($monthName); ?>. Please select another month if needed.
        </div>
    <?php else: ?>
        <div class="salary-grid">
            <div class="salary-card">
                <h4>Summary</h4>
                <div class="salary-row"><span class="label">Employee</span><span class="value"><?php echo e(data_get($salary, 'employee_name', '-')); ?></span></div>
                <div class="salary-row"><span class="label">Employee ID</span><span class="value"><?php echo e(data_get($salary, 'employee_id', '-')); ?></span></div>
                <div class="salary-row"><span class="label">Salary month</span><span class="value"><?php echo e(data_get($salary, 'month', '-')); ?>/<?php echo e(data_get($salary, 'year', '-')); ?></span></div>
                <div class="salary-row"><span class="label">Status</span><span class="value"><span class="status-pill <?php echo e(strtolower(data_get($salary, 'status', 'other'))); ?>"><?php echo e(data_get($salary, 'status', '-')); ?></span></span></div>
                <div class="salary-row"><span class="label">Generated on</span><span class="value"><?php echo e(data_get($salary, 'generated_at', '-')); ?></span></div>
            </div>

            <div class="salary-card salary-summary">
                <h4>Attendance</h4>
                <div class="salary-row"><span class="label">Working days</span><span class="value"><?php echo e(data_get($salary, 'working_days', '-')); ?></span></div>
                <div class="salary-row"><span class="label">Present days</span><span class="value"><?php echo e(data_get($salary, 'present_days', '-')); ?></span></div>
                <div class="salary-row"><span class="label">Valid Sundays</span><span class="value"><?php echo e(data_get($salary, 'valid_sundays', '-')); ?></span></div>
                <div class="salary-row"><span class="label">Absent days</span><span class="value"><?php echo e(data_get($salary, 'absent_days', '-')); ?></span></div>
                <div class="salary-row"><span class="label">Paid leaves</span><span class="value"><?php echo e(data_get($salary, 'paid_leaves', '-')); ?></span></div>
                <div class="salary-row"><span class="label">Half days</span><span class="value"><?php echo e(data_get($salary, 'half_days', '-')); ?></span></div>
            </div>

            <div class="salary-card salary-summary">
                <h4>Pay breakdown</h4>
                <div class="salary-row"><span class="label">Basic</span><span class="value">₹<?php echo e(number_format((float) data_get($salary, 'basic', 0), 2)); ?></span></div>
                <div class="salary-row"><span class="label">HRA</span><span class="value">₹<?php echo e(number_format((float) data_get($salary, 'hra', 0), 2)); ?></span></div>
                <div class="salary-row"><span class="label">Allowance</span><span class="value">₹<?php echo e(number_format((float) data_get($salary, 'allowance', 0), 2)); ?></span></div>
                <div class="salary-row"><span class="label">Bonus</span><span class="value">₹<?php echo e(number_format((float) data_get($salary, 'bonus', 0), 2)); ?></span></div>
                <div class="salary-row"><span class="label">Deductions</span><span class="value">₹<?php echo e(number_format((float) data_get($salary, 'deductions', 0), 2)); ?></span></div>
            </div>

            <div class="salary-card">
                <h4>Net pay</h4>
                <div class="salary-row"><span class="label">Gross salary</span><span class="value">₹<?php echo e(number_format((float) data_get($salary, 'gross_salary', 0), 2)); ?></span></div>
                <div class="salary-row"><span class="label">Net salary</span><span class="value">₹<?php echo e(number_format((float) data_get($salary, 'net_salary', 0), 2)); ?></span></div>
                <div class="salary-row"><span class="label">Total paid</span><span class="value">₹<?php echo e(number_format((float) data_get($salary, 'total_paid', 0), 2)); ?></span></div>
                <div class="salary-row"><span class="label">Remaining salary</span><span class="value">₹<?php echo e(number_format((float) data_get($salary, 'remaining_salary', 0), 2)); ?></span></div>
            </div>

            <div class="salary-card">
                <h4>Notes</h4>
                <p><?php echo e(data_get($salary, 'remarks', 'No remarks available.')); ?></p>
            </div>
        </div>
    <?php endif; ?>
</section>

<script>
    (function(){
        const monthInput = document.getElementById('month');
        if (!monthInput) return;

        const test = document.createElement('input');
        test.setAttribute('type', 'month');
        if (test.type === 'month') {
            return;
        }

        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = String(now.getMonth() + 1).padStart(2, '0');
        const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        const yearStart = currentYear - 5;
        const yearEnd = currentYear + 2;
        let html = '<div style="display:flex;gap:10px;flex-wrap:wrap">';
        html += '<select id="month_select" style="flex:1 1 180px;padding:12px;border-radius:14px;background:var(--panel-soft);border:1.5px solid var(--border-soft);color:#fff">';
        months.forEach((m, idx) => {
            const value = String(idx + 1).padStart(2,'0');
            html += `<option value="${value}">${m}</option>`;
        });
        html += '</select>';
        html += '<select id="year_select" style="width:140px;padding:12px;border-radius:14px;background:var(--panel-soft);border:1.5px solid var(--border-soft);color:#fff">';
        for (let y = yearStart; y <= yearEnd; y++) {
            html += `<option value="${y}">${y}</option>`;
        }
        html += '</select>';
        html += '</div>';

        const container = monthInput.parentNode;
        monthInput.style.display = 'none';
        const fallback = document.createElement('div');
        fallback.innerHTML = html;
        container.insertBefore(fallback, monthInput);

        const monthSelect = fallback.querySelector('#month_select');
        const yearSelect = fallback.querySelector('#year_select');
        const [selectedYear, selectedMonth] = monthInput.value.split('-');

        if (selectedMonth) {
            monthSelect.value = selectedMonth;
        }
        if (selectedYear) {
            yearSelect.value = selectedYear;
        }

        function syncValue() {
            monthInput.value = `${yearSelect.value}-${monthSelect.value}`;
        }

        monthSelect.addEventListener('change', syncValue);
        yearSelect.addEventListener('change', syncValue);
    })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WebProjects\web_clocking\resources\views/settings/salary.blade.php ENDPATH**/ ?>