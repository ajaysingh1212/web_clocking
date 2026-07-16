<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0a0f2c">
    <title>@yield('title', 'EEMOT Clocking PWA')</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        :root {
            --bg-navy: #0a0f2c;
            --panel: rgba(13, 18, 46, 0.92);
            --panel-soft: rgba(255, 255, 255, 0.04);
            --border-soft: rgba(255, 255, 255, 0.08);
            --text-main: #ffffff;
            --text-muted: #8892b0;
            --text-dim: #6b7494;
            --accent-orange: #ff7a1a;
            --accent-pink: #ff3d81;
            --accent-purple: #7a3aff;
            --accent-blue: #3a7bff;
            --grad-main: linear-gradient(120deg, #ff7a1a, #ff3d81, #7a3aff);
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            background: var(--bg-navy);
            font-family: 'Poppins', sans-serif;
            color: var(--text-main);
            min-height: 100%;
        }

        body {
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at 15% 10%, rgba(255,122,26,0.22), transparent 40%),
                radial-gradient(circle at 85% 15%, rgba(122,58,255,0.22), transparent 45%),
                radial-gradient(circle at 25% 90%, rgba(58,123,255,0.18), transparent 45%),
                radial-gradient(circle at 90% 85%, rgba(255,58,129,0.18), transparent 45%);
            filter: blur(70px);
            z-index: 0;
            pointer-events: none;
        }

        .app-shell {
            position: relative;
            z-index: 1;
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            width: 100%;
            padding-bottom: 96px;
        }

        @media (min-width: 992px) {
            .main-content {
                margin-left: 240px;
                padding-bottom: 24px;
            }
        }

        .container-fluid.py-4 {
            padding: 20px 16px 20px !important;
            max-width: 720px;
            margin: 0 auto;
        }

        .page-loader {
            position: fixed;
            inset: 0;
            background: rgba(10, 15, 44, 0.7);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
        }

        a { text-decoration: none; }
    </style>
</head>
<body>
    <div id="page-loader" class="page-loader d-none">
        <div class="spinner-border" style="color:#ff7a1a;" role="status" aria-label="Loading"></div>
    </div>

    <div class="app-shell">
        @if(token())
            @include('components.sidebar')
        @endif

        <main class="main-content">
            @if(token())
                @include('components.navbar')
            @endif

            <div class="container-fluid py-4">
                @include('components.alerts')
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>

    <script>
        (function () {
            const loader = document.getElementById('page-loader');
            if (!loader) return;

            function showLoader() {
                loader.classList.remove('d-none');
            }

            function hideLoader() {
                loader.classList.add('d-none');
            }

            // Hide loader once the page has fully rendered
            window.addEventListener('DOMContentLoaded', hideLoader);
            window.addEventListener('pageshow', hideLoader); // handles back/forward cache too

            // Show loader on any internal link click (skip anchors, new-tab, downloads, external)
            document.addEventListener('click', function (e) {
                const link = e.target.closest('a');
                if (!link) return;

                const href = link.getAttribute('href');
                if (!href) return;
                if (href.startsWith('#')) return;
                if (link.target === '_blank') return;
                if (link.hasAttribute('download')) return;
                if (link.origin && link.origin !== window.location.origin) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

                showLoader();
            });

            // Show loader on any form submit
            document.addEventListener('submit', function () {
                showLoader();
            });

            // Safety: hide loader if user navigates back via browser cache
            window.addEventListener('pagehide', hideLoader);
        })();
    </script>

    @stack('scripts')
</body>
</html>