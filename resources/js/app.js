import './bootstrap';
import Alpine from 'alpinejs';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, Keyboard } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

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
            let timer = null;
            this.$watch('query', value => {
                clearTimeout(timer);
                if (value.length < 2) {
                    this.results = [];
                    this.open = false;
                    return;
                }
                this.loading = true;
                timer = setTimeout(() => {
                    fetch(`/search/suggestions?q=${encodeURIComponent(value)}`)
                        .then(r => r.json())
                        .then(data => {
                            this.results = data;
                            this.open = true;
                            this.loading = false;
                        })
                        .catch(() => { this.loading = false; });
                }, 250);
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
                    if (badge) {
                        badge.textContent = data.count;
                        badge.classList.remove('cart-count-pop');
                        requestAnimationFrame(() => badge.classList.add('cart-count-pop'));
                    }
                    window.GG.success(data.message || 'Added to your cart.');
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                    window.dispatchEvent(new CustomEvent('open-mini-cart', { detail: data }));
                }
            })
            .catch(() => {
                this.loading = false;
                window.GG.error('Could not add this item to your cart. Please try again.');
            });
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
                const icon = btn.querySelector('svg');
                if (data.in_wishlist) {
                    icon?.classList.add('text-red-500', 'fill-red-500');
                    btn.title = 'Remove from wishlist';
                    btn.setAttribute('aria-label', 'Remove from wishlist');
                } else {
                    icon?.classList.remove('text-red-500', 'fill-red-500');
                    btn.title = 'Add to wishlist';
                    btn.setAttribute('aria-label', 'Add to wishlist');
                }
                btn.classList.remove('wishlist-pop');
                requestAnimationFrame(() => btn.classList.add('wishlist-pop'));
                window.GG.success(data.message || 'Wishlist updated.');
            })
            .catch(() => {
                this.loading = false;
                window.GG.error('Could not update your wishlist. Please try again.');
            });
        }
    }));

    Alpine.data('mobileSearch', () => ({
        open: false,
        query: '',
    }));

    Alpine.data('productCatalog', (config = {}) => ({
        q: config.q || '',
        filters: config.filters || {},
        sort: config.sort || 'latest',
        page: config.page || 1,
        total: config.total || 0,
        baseUrl: config.baseUrl || '/products',
        categoryLabels: config.categoryLabels || {},
        loading: false,
        error: false,
        mobileFiltersOpen: false,

        init() {
            const catalog = this.$refs.catalog;

            catalog.addEventListener('click', (e) => {
                const clearBtn = e.target.closest('[data-clear-filters]');
                if (clearBtn) {
                    e.preventDefault();
                    this.clearFilters();
                    return;
                }

                const pageLink = e.target.closest('[data-pagination] a[href]');
                if (pageLink) {
                    e.preventDefault();
                    const url = new URL(pageLink.getAttribute('href'), window.location.origin);
                    const page = url.searchParams.get('page');
                    if (page && Number(page) !== this.page) {
                        this.page = Number(page);
                        this.fetchProducts();
                    }
                }
            });
        },

        get activeFilters() {
            const list = [];

            if (this.q) {
                list.push({ key: 'q', label: `"${this.q}"` });
            }

            if (this.filters.category) {
                const name = this.categoryLabels[this.filters.category] || this.filters.category;
                list.push({ key: 'category', label: `Category: ${name}` });
            }

            if (this.filters.brand) {
                list.push({ key: 'brand', label: `Brand: ${this.filters.brand}` });
            }

            if (this.filters.fabric) {
                list.push({ key: 'fabric', label: `Fabric: ${this.filters.fabric}` });
            }

            if (this.filters.color) {
                list.push({ key: 'color', label: `Color: ${this.filters.color}` });
            }

            if (this.filters.min_price || this.filters.max_price) {
                list.push({
                    key: 'price',
                    label: `Price: $${this.filters.min_price || '0'} - $${this.filters.max_price || 'Any'}`,
                });
            }

            if (this.filters.in_stock) {
                list.push({ key: 'in_stock', label: 'In Stock' });
            }

            if (this.filters.discounted) {
                list.push({ key: 'discounted', label: 'Discounted' });
            }

            return list;
        },

        buildParams() {
            const params = new URLSearchParams();

            if (this.q) params.set('q', this.q);

            Object.entries(this.filters).forEach(([key, value]) => {
                if (value !== '' && value !== null && value !== undefined) {
                    params.set(key, value);
                }
            });

            if (this.sort && this.sort !== 'latest') params.set('sort', this.sort);

            if (this.page > 1) params.set('page', this.page);

            return params;
        },

        async fetchProducts() {
            const params = this.buildParams();
            const query = params.toString();

            this.loading = true;
            this.error = false;

            const url = this.baseUrl + (query ? '?' + query : '');

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                });

                if (!response.ok) throw new Error('Request failed');

                const data = await response.json();
                this.$refs.catalog.innerHTML = data.html;
                this.total = data.total;
                this.loading = false;

                if (window.history.replaceState) {
                    window.history.replaceState({}, '', url);
                }
            } catch (err) {
                this.loading = false;
                this.error = true;
            }
        },

        setFilter(key, value) {
            const next = this.filters[key] === value ? '' : value;
            if (this.filters[key] === next) return;
            this.filters[key] = next;
            this.page = 1;
            this.fetchProducts();
        },

        toggleBool(key) {
            this.filters[key] = this.filters[key] ? '' : '1';
            this.page = 1;
            this.fetchProducts();
        },

        applyPrice() {
            const min = Number(this.filters.min_price);
            const max = Number(this.filters.max_price);
            if ((this.filters.min_price !== '' && (isNaN(min) || min < 0)) ||
                (this.filters.max_price !== '' && (isNaN(max) || max < 0))) {
                window.GG.error('Please enter a valid price range.');
                return;
            }
            if (this.filters.min_price !== '' && this.filters.max_price !== '' && min > max) {
                window.GG.error('Minimum price cannot be greater than maximum price.');
                return;
            }
            this.page = 1;
            this.fetchProducts();
        },

        removeFilter(key) {
            if (key === 'price') {
                delete this.filters.min_price;
                delete this.filters.max_price;
            } else if (key === 'q') {
                this.q = '';
            } else {
                delete this.filters[key];
            }
            this.page = 1;
            this.fetchProducts();
        },

        clearFilters() {
            this.q = '';
            this.filters = {};
            this.sort = 'latest';
            this.page = 1;
            this.fetchProducts();
        },
    }));
});

window.GG = {
    csrf() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    },
    showToast(message, type = 'success') {
        document.getElementById('gg-toast-region')?.remove();

        const toast = document.createElement('div');
        toast.id = 'gg-toast-region';
        toast.className = type === 'error' ? 'toast toast-error' : 'toast toast-success';
        toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
        toast.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');

        const text = document.createElement('span');
        text.textContent = message;
        toast.appendChild(text);

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'toast-close';
        close.setAttribute('aria-label', 'Dismiss notification');
        close.textContent = '×';
        close.addEventListener('click', () => toast.remove());
        toast.appendChild(close);
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(40px)';
            toast.style.transition = 'all 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },
    error(message) { this.showToast(message, 'error'); },
    success(message) { this.showToast(message, 'success'); },
    async api(url, options = {}) {
        const controller = new AbortController();
        const timer = setTimeout(() => controller.abort(), 30000);
        try {
            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrf(),
                    'Accept': 'application/json',
                    ...(options.headers || {}),
                },
                signal: controller.signal,
                ...options,
            });
            return res;
        } finally {
            clearTimeout(timer);
        }
    },
    friendlyError(err) {
        if (err && err.name === 'AbortError') return 'The request took too long. Please check your connection and try again.';
        return 'A network error occurred. Please check your connection and try again.';
    },
};

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('img.fade-img').forEach(img => {
        if (img.complete) { img.classList.add('img-loaded'); }
        else img.addEventListener('load', () => img.classList.add('img-loaded'), { once: true });
    });

    document.querySelectorAll('img').forEach(img => {
        if (img.dataset.emojiFallback !== undefined) return;
        img.addEventListener('error', function handler() {
            img.removeEventListener('error', handler);
            const fallback = img.dataset.fallback;
            if (fallback) {
                img.remove();
                const holder = document.createElement('div');
                holder.className = 'w-full h-full flex items-center justify-center text-4xl select-none';
                holder.textContent = fallback;
                if (img.parentElement) img.parentElement.appendChild(holder);
            }
        }, { once: true });
    });

    document.querySelectorAll('form[data-ajax]').forEach(form => {
        const btn = form.querySelector('[type="submit"], button[type="button"]');
        form.addEventListener('submit', () => {
            if (btn && !btn.disabled) btn.disabled = true;
            setTimeout(() => { if (btn && form.dataset.ajax !== 'keep-disabled') btn.disabled = false; }, 2500);
        });
    });

    document.addEventListener('error', (e) => {
        const target = e.target;
        if (target && target.tagName === 'IMG' && target.dataset.emojiFallback !== undefined) {
            const holder = document.createElement('div');
            holder.className = 'w-full h-full flex items-center justify-center text-4xl select-none';
            holder.textContent = target.dataset.emojiFallback;
            if (target.parentElement) target.parentElement.appendChild(holder);
            target.remove();
        }
    }, true);

    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!form || form.tagName !== 'FORM') return;
        if (form.hasAttribute('data-ajax') || form.hasAttribute('x-on:submit') || form.hasAttribute('@submit')) return;
        const btn = form.querySelector('[type="submit"]');
        if (btn && !btn.disabled) {
            btn.disabled = true;
            setTimeout(() => { if (document.body.contains(btn)) btn.disabled = false; }, 2500);
        }
    }, true);

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

function initProductCarousels(scope = document) {
    scope.querySelectorAll('.product-swiper').forEach((el) => {
        if (el._productSwiper) return;

        el._productSwiper = new Swiper(el, {
            slidesPerView: 1.3,
            spaceBetween: 14,
            navigation: {
                nextEl: el.querySelector('.swiper-button-next'),
                prevEl: el.querySelector('.swiper-button-prev'),
            },
            breakpoints: {
                0:    { slidesPerView: 1.3, spaceBetween: 14 },
                560:  { slidesPerView: 2.2, spaceBetween: 16 },
                860:  { slidesPerView: 3.2, spaceBetween: 18 },
                1100: { slidesPerView: 4,   spaceBetween: 20 },
                1300: { slidesPerView: 5,   spaceBetween: 20 },
            },
        });
    });
}

window.initProductCarousels = initProductCarousels;

function initHomepageSliders(scope = document) {
    scope.querySelectorAll('.hero-slider.swiper').forEach((el) => {
        if (el._heroSwiper) return;

        el._heroSwiper = new Swiper(el, {
            modules: [Navigation, Pagination, Autoplay, Keyboard],
            loop: true,
            speed: 500,
            autoplay: { delay: 2000, disableOnInteraction: false, pauseOnMouseEnter: true },
            keyboard: { enabled: true, onlyInViewport: true },
            allowTouchMove: true,
            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true,
                renderBullet(index, className) {
                    return `<button type="button" class="${className}" aria-label="Go to banner ${index + 1}"></button>`;
                },
            },
            navigation: {
                nextEl: el.querySelector('.swiper-button-next'),
                prevEl: el.querySelector('.swiper-button-prev'),
            },
        });
    });

    scope.querySelectorAll('.homepage-carousel-banner.swiper').forEach((el) => {
        if (el._promoSwiper) return;

        el._promoSwiper = new Swiper(el, {
            modules: [Navigation, Pagination, Autoplay],
            loop: true,
            speed: 700,
            autoplay: { delay: 2000, disableOnInteraction: false },
            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: el.querySelector('.swiper-button-next'),
                prevEl: el.querySelector('.swiper-button-prev'),
            },
        });
    });
}

window.initHomepageSliders = initHomepageSliders;

document.addEventListener('DOMContentLoaded', () => {
    initProductCarousels();
    initHomepageSliders();
});

Alpine.start();
