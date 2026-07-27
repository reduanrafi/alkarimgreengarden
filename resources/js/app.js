import './bootstrap';
import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);
window.Chart = Chart;

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('searchDropdown', () => ({
        open: false,
        query: '',
        results: [],
        loading: false,
        selectedIndex: -1,
        show: false,
        init() {
            this.$watch('query', value => {
                if (value.length < 2) {
                    this.results = [];
                    this.open = false;
                    return;
                }
                this.loading = true;
                fetch(`/search/suggestions?q=${encodeURIComponent(value)}`)
                    .then(r => r.json())
                    .then(data => {
                        this.results = data;
                        this.open = true;
                        this.loading = false;
                    })
                    .catch(() => { this.loading = false; });
            });
        },
        select(index) {
            const item = this.results[index];
            if (item) window.location.href = item.url;
        },
        keydown(e) {
            if (e.key === 'ArrowDown') {
                this.selectedIndex = Math.min(this.selectedIndex + 1, this.results.length - 1);
            } else if (e.key === 'ArrowUp') {
                this.selectedIndex = Math.max(this.selectedIndex - 1, 0);
            } else if (e.key === 'Enter' && this.selectedIndex >= 0) {
                this.select(this.selectedIndex);
            } else if (e.key === 'Escape') {
                this.open = false;
            }
        }
    }));

    Alpine.data('cartQty', () => ({
        loading: false,
        update(form) {
            this.loading = true;
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'PATCH',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData,
            }).then(() => { this.loading = false; location.reload(); }).catch(() => { this.loading = false; });
        }
    }));

    Alpine.data('addToCart', () => ({
        loading: false,
        submit(form) {
            this.loading = true;
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData,
            })
            .then(r => r.json())
            .then(data => {
                this.loading = false;
                if (data.success) {
                    const badge = document.getElementById('cartCount');
                    if (badge) badge.textContent = data.count;
                    if (data.message) {
                        const toast = document.createElement('div');
                        toast.className = 'toast toast-success';
                        toast.textContent = data.message;
                        document.body.appendChild(toast);
                        setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(40px)'; toast.style.transition = 'all 0.3s ease-out'; setTimeout(() => toast.remove(), 300); }, 3000);
                    }
                }
            })
            .catch(() => { this.loading = false; });
        }
    }));

    Alpine.data('wishlistToggle', () => ({
        loading: false,
        toggle(form, btn) {
            this.loading = true;
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData,
            })
            .then(r => r.json())
            .then(data => {
                this.loading = false;
                if (data.in_wishlist) {
                    btn.querySelector('svg').classList.add('text-red-500', 'fill-red-500');
                } else {
                    btn.querySelector('svg').classList.remove('text-red-500', 'fill-red-500');
                }
                if (data.message) {
                    const toast = document.createElement('div');
                    toast.className = 'toast toast-success';
                    toast.textContent = data.message;
                    document.body.appendChild(toast);
                    setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(40px)'; toast.style.transition = 'all 0.3s ease-out'; setTimeout(() => toast.remove(), 300); }, 3000);
                }
            })
            .catch(() => { this.loading = false; });
        }
    }));

    Alpine.data('mobileSearch', () => ({
        open: false,
        query: '',
    }));
});

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.scroll-fade-in').forEach(el => observer.observe(el));

    document.querySelectorAll('[data-toast]').forEach(el => {
        setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateX(40px)'; el.style.transition = 'all 0.3s ease-out'; setTimeout(() => el.remove(), 300); }, 4000);
    });
});
