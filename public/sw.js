const CACHE_NAME = 'lurah-online-v1';
const ASSETS = [
    '/',
    '/images/LurahOnline-logo.png',
    '/favicon.ico'
];

self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS))
    );
});

self.addEventListener('fetch', e => {
    e.respondWith(
        caches.match(e.request).then(response => response || fetch(e.request))
    );
});
