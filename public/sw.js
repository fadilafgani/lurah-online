const CACHE_NAME = 'kades-online-v2';
const ASSETS = [
    '/manifest.json',
    '/images/KaDesOnline-logo.png',
    '/favicon.ico'
];

self.addEventListener('install', e => {
    self.skipWaiting();
    e.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS))
    );
});

self.addEventListener('activate', e => {
    e.waitUntil(
        caches.keys().then(keys => Promise.all(
            keys.map(key => {
                if (key !== CACHE_NAME) {
                    return caches.delete(key);
                }
            })
        )).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', e => {
    // Navigasi HTML dan manifest selalu utamakan jaringan (Network First)
    if (e.request.mode === 'navigate' || e.request.url.includes('manifest.json')) {
        e.respondWith(
            fetch(e.request).catch(() => caches.match(e.request))
        );
        return;
    }

    e.respondWith(
        caches.match(e.request).then(response => response || fetch(e.request))
    );
});
