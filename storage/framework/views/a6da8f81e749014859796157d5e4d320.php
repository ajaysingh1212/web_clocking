<style>
    .sidebar.bottom-tabs {
        font-family: 'Poppins', sans-serif;
    }

    /* Desktop: fixed left sidebar */
    @media (min-width: 992px) {
        .sidebar.bottom-tabs {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background: var(--panel);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--border-soft);
            padding: 28px 18px;
            display: flex;
            flex-direction: column;
            gap: 30px;
            z-index: 100;
        }

        .sidebar.bottom-tabs .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: 700;
            font-size: 1.05rem;
        }

        .sidebar.bottom-tabs .brand .material-symbols-rounded {
            width: 40px; height: 40px;
            border-radius: 12px;
            background: var(--grad-main);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            box-shadow: 0 8px 20px rgba(255,90,60,0.35);
        }

        .sidebar.bottom-tabs .nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar.bottom-tabs .nav-link,
        .sidebar.bottom-tabs .nav-fab {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            color: var(--text-muted);
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s ease;
            width: auto;
            height: auto;
            position: static;
            top: auto;
            box-shadow: none;
            background: transparent;
        }

        .sidebar.bottom-tabs .nav-link i,
        .sidebar.bottom-tabs .nav-fab i {
            font-size: 19px;
            width: 20px;
            text-align: center;
        }

        .sidebar.bottom-tabs .nav-link span,
        .sidebar.bottom-tabs .nav-fab span {
            display: inline;
            font-size: 0.92rem;
        }

        .sidebar.bottom-tabs .nav-link:hover {
            background: var(--panel-soft);
            color: #fff;
        }

        .sidebar.bottom-tabs .nav-link.active,
        .sidebar.bottom-tabs .nav-fab {
            background: var(--grad-main);
            color: #fff;
            box-shadow: 0 8px 20px rgba(255,90,60,0.3);
        }

        .sidebar.bottom-tabs .nav-fab-wrap {
            display: contents;
        }
    }

    /* Mobile: fixed bottom tab bar — 5 items, Punch raised in center */
    @media (max-width: 991.98px) {
        .sidebar.bottom-tabs {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 64px;
            background: rgba(13, 18, 46, 0.97);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--border-soft);
            z-index: 900;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.4);
            padding: 0;
        }

        .sidebar.bottom-tabs .brand {
            display: none;
        }

        .sidebar.bottom-tabs .nav {
            display: grid;
            grid-template-columns: 1fr 1fr auto 1fr 1fr;
            align-items: center;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .sidebar.bottom-tabs .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            height: 100%;
            color: var(--text-dim);
            font-size: 0.6rem;
            font-weight: 600;
            padding: 4px 0;
            margin: 0;
            border-radius: 0;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.1;
        }

        .sidebar.bottom-tabs .nav-link i {
            font-size: 17px;
            line-height: 1;
        }

        .sidebar.bottom-tabs .nav-link span {
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .sidebar.bottom-tabs .nav-link.active {
            color: var(--accent-orange);
        }

        .sidebar.bottom-tabs .nav-link.active i {
            background: var(--grad-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Center raised Punch button */
        .sidebar.bottom-tabs .nav-fab-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            gap: 2px;
            height: 100%;
        }

        .sidebar.bottom-tabs .nav-fab {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--grad-main);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            box-shadow: 0 10px 24px rgba(255,90,60,0.45), 0 0 0 4px rgba(13,18,46,0.97);
            transform: translateY(-16px);
            transition: all 0.25s ease;
        }

        .sidebar.bottom-tabs .nav-fab:active {
            transform: translateY(-16px) scale(0.94);
        }

        .sidebar.bottom-tabs .nav-fab-wrap span {
            font-size: 0.6rem;
            font-weight: 600;
            color: var(--accent-orange);
            transform: translateY(-10px);
        }
    }

    @media (max-width: 340px) {
        .sidebar.bottom-tabs .nav-link { font-size: 0.54rem; }
        .sidebar.bottom-tabs .nav-link i { font-size: 15px; }
        .sidebar.bottom-tabs .nav-fab { width: 48px; height: 48px; font-size: 20px; }
    }
</style>

<aside class="sidebar bottom-tabs">
    <a class="brand" href="<?php echo e(route('home')); ?>">
        <span class="material-symbols-rounded">schedule</span>
        <span>EEMOT Clocking</span>
    </a>
    <nav class="nav">
        <a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>">
            <i class="bi bi-house-fill"></i><span>Home</span>
        </a>
        <a class="nav-link <?php echo e(request()->routeIs('report') ? 'active' : ''); ?>" href="<?php echo e(route('report')); ?>">
            <i class="bi bi-file-earmark-bar-graph-fill"></i><span>Report</span>
        </a>

        <div class="nav-fab-wrap">
            <a class="nav-fab <?php echo e(request()->routeIs('punch') ? 'active' : ''); ?>" href="<?php echo e(route('punch')); ?>" aria-label="Punch Now">
                <i class="bi bi-camera-fill"></i>
            </a>
            <span>Punch</span>
        </div>

        <a class="nav-link <?php echo e(request()->routeIs('profile') ? 'active' : ''); ?>" href="<?php echo e(route('profile')); ?>">
            <i class="bi bi-person-circle"></i><span>Profile</span>
        </a>
        <a class="nav-link <?php echo e(request()->routeIs('settings') ? 'active' : ''); ?>" href="<?php echo e(route('settings')); ?>">
            <i class="bi bi-gear-fill"></i><span>Settings</span>
        </a>
    </nav>
</aside><?php /**PATH D:\WebProjects\web_clocking\resources\views/components/navbar.blade.php ENDPATH**/ ?>