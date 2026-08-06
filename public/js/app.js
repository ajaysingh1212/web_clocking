(function () {
    const loader = document.getElementById('page-loader');
    const forms = document.querySelectorAll('.needs-loader');
    const installButton = document.getElementById('install-button');
    const installLink = document.getElementById('install-link');
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

    function promptInstall() {
        if (!deferredPrompt) {
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
})();