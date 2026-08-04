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

// Page Loader & Image Lazy Loading
document.addEventListener('DOMContentLoaded', () => {
    // 1. Native Lazy Loading for all images
    document.querySelectorAll('img').forEach(img => {
        if (!img.hasAttribute('loading')) {
            img.setAttribute('loading', 'lazy');
        }
    });

    // 2. Page Loader Bar
    let bar = document.getElementById('page-loader-bar');
    if (!bar) {
        bar = document.createElement('div');
        bar.id = 'page-loader-bar';
        document.body.prepend(bar);
    }

    setTimeout(() => { bar.style.width = '70%'; }, 10);

    const finishLoading = () => {
        bar.style.width = '100%';
        setTimeout(() => {
            bar.style.opacity = '0';
            setTimeout(() => { bar.style.width = '0%'; bar.style.opacity = '1'; }, 400);
        }, 300);
    };

    if (document.readyState === 'complete') finishLoading();
    else window.addEventListener('load', finishLoading);

    // Trigger loader on navigation links and forms
    document.addEventListener('click', e => {
        const a = e.target.closest('a');
        if (a && a.href && a.href.startsWith(window.location.origin) && !a.getAttribute('href').startsWith('#') && a.target !== '_blank') {
            bar.style.opacity = '1';
            bar.style.width = '40%';
        }
    });

    document.addEventListener('submit', () => {
        bar.style.opacity = '1';
        bar.style.width = '50%';
    });
});

// 3. SweetAlert2 Integration
const swalScript = document.createElement('script');
swalScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
swalScript.onload = () => {
    // Flash message handler
    const flashEl = document.getElementById('flash-success-data');
    if (flashEl && flashEl.dataset.message) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: flashEl.dataset.message,
            confirmButtonColor: '#0047AB',
            timer: 3500
        });
    }

    // Form confirm handler
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form.dataset.confirm && !form.dataset.confirmed) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: form.dataset.confirm,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D83D3D',
                cancelButtonColor: '#656565',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        }
    });
};
document.head.appendChild(swalScript);
