import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// PWA Registration
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .catch(err => console.error('SW failed', err));
    });
}
