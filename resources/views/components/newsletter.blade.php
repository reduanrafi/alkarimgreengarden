<section class="newsletter">
    <div class="gg-container">
        <h2>{{ setting('newsletter_heading', 'Get plant care tips in your inbox') }}</h2>
        <p>{{ setting('newsletter_subtext', 'Join our newsletter. No spam — just seasonal advice and first access to new arrivals.') }}</p>
        <div x-data="{
            email: '',
            error: '',
            message: '',
            unsubscribeUrl: '',
            submitting: false,
            submit() {
                if (this.submitting) return;
                if (!this.email.trim()) { this.error = 'Please enter your email address.'; this.message = ''; return; }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email.trim())) { this.error = 'Please enter a valid email address.'; this.message = ''; return; }
                this.error = '';
                this.submitting = true;
                fetch('{{ route('newsletter.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                    },
                    body: new URLSearchParams({ email: this.email.trim() }),
                })
                .then(r => r.json())
                .then(data => {
                    this.submitting = false;
                    if (data.success) {
                        this.email = '';
                        this.message = data.message;
                        this.unsubscribeUrl = data.unsubscribe_url || '';
                    } else {
                        this.error = data.message || 'Something went wrong. Please try again.';
                    }
                })
                .catch(() => {
                    this.submitting = false;
                    this.error = 'A network error occurred. Please try again.';
                });
            }
        }">
            <form class="newsletter-form" @submit.prevent="submit" novalidate>
                <input type="email" name="email" x-model="email" placeholder="Enter your email address" required>
                <button type="submit" class="gg-btn-primary" :disabled="submitting">
                    <span x-show="!submitting">Subscribe</span>
                    <span x-show="submitting">Subscribing…</span>
                </button>
            </form>
            <p x-show="error" x-cloak x-text="error" class="text-red-300 text-sm mt-3"></p>
            <p x-show="message" x-cloak x-text="message" class="text-green-300 text-sm mt-3"></p>
            <p x-show="unsubscribeUrl" x-cloak class="text-sm mt-2">
                <a :href="unsubscribeUrl" class="text-green-100 underline hover:text-white">Want to unsubscribe? Click here.</a>
            </p>
        </div>
    </div>
</section>
