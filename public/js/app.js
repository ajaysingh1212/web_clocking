(function () {
    const loader = document.getElementById('page-loader');
    const forms = document.querySelectorAll('.needs-loader');
    const installButton = document.getElementById('install-button');
    const installLink = document.getElementById('install-link');
    const installCard = document.querySelector('.install-card');
    let deferredPrompt = null;

    forms.forEach((form) => {
        form.addEventListener('submit', () => {
            const spinner = form.querySelector('.spinner-border');
            if (spinner) {
                spinner.classList.remove('d-none');
            }
            if (loader) {
                loader.classList.remove('d-none');
            }
        });
    });

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/service-worker.js');
        });
    }

    function showInstallFallback() {
        if (installButton) {
            installButton.classList.add('d-none');
        }

        if (installCard && installLink) {
            installLink.textContent = 'Open browser menu';
            installLink.setAttribute('title', 'Use browser menu to install');
            installCard.querySelector('.install-help').innerHTML = 'Install from your browser menu: <strong>• Add to Home screen</strong> or <strong>Install app</strong>.';
        }
    }

    function promptInstall() {
        if (!deferredPrompt) {
            showInstallFallback();
            return;
        }

        deferredPrompt.prompt();
        deferredPrompt = null;
    }

    if (installButton) {
        installButton.addEventListener('click', () => {
            installButton.classList.add('d-none');
            promptInstall();
        });
    }

    if (installLink) {
        installLink.addEventListener('click', () => {
            promptInstall();
        });
    }

    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredPrompt = event;
        if (installButton) {
            installButton.classList.remove('d-none');
        }
    });

    window.addEventListener('appinstalled', () => {
        if (installButton) {
            installButton.classList.add('d-none');
        }
        if (installCard && installLink) {
            installLink.textContent = 'Installed';
            installCard.querySelector('.install-help').textContent = 'App installed successfully.';
        }
    });

    window.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible' && !deferredPrompt && installButton) {
            installButton.classList.remove('d-none');
        }
    });
})();