const CACHE_NAME = 'eemot-clocking-v2';
const OFFLINE_URL = '/offline';
const CORE_ASSETS = [
    OFFLINE_URL,
    '/css/app.css',
    '/js/app.js',
    '/js/punch.js',
    '/images/icon-192.svg',
    '/images/icon-512.svg',
    '/images/profile-placeholder.svg',
    '/images/camera-placeholder.svg',
    '/images/product-placeholder.svg'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(CORE_ASSETS))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(
            keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
        ))
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                const clone = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                return response;
            })
            .catch(async () => {
                const cached = await caches.match(event.request);
                if (cached) {
                    return cached;
                }

                if (event.request.mode === 'navigate') {
                    return caches.match(OFFLINE_URL);
                }

                return Response.error();
            })
    );
});
