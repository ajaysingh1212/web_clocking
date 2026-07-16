(function () {
    const loader = document.getElementById('page-loader');
    const forms = document.querySelectorAll('.needs-loader');
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

    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredPrompt = event;
        const installButton = document.getElementById('install-button');
        if (installButton) {
            installButton.classList.remove('d-none');
            installButton.addEventListener('click', async () => {
                installButton.classList.add('d-none');
                deferredPrompt.prompt();
                await deferredPrompt.userChoice;
                deferredPrompt = null;
            }, { once: true });
        }
    });
})();