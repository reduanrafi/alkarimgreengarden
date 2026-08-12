<div id="productPreview"
     class="fixed inset-0 z-50 hidden items-center justify-center"
     style="pointer-events: none; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px);">

    <div id="previewCard"
         class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden opacity-0 scale-95 transition-all duration-250 ease-out"
         style="pointer-events: auto; transform-origin: center center;"
         onmouseenter="onPreviewEnter()"
         onmouseleave="onPreviewLeave()">

        {{-- Image --}}
        <div class="relative bg-gradient-to-br from-gray-50 to-gray-100">
            <div id="previewImage" class="aspect-[4/3] flex items-center justify-center text-7xl overflow-hidden">
            </div>
            <button onclick="closePreview()" class="absolute top-3 right-3 bg-white/90 rounded-full p-2 shadow hover:bg-white transition z-10">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Details --}}
        <div class="p-6 space-y-4">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <h3 id="previewName" class="text-xl font-bold text-gray-900 truncate"></h3>
                    <div class="flex items-center gap-3 mt-1">
                        <span id="previewPrice" class="text-2xl font-bold text-gray-900"></span>
                        <span id="previewDiscount" class="text-lg font-medium text-red-400 line-through hidden"></span>
                        <span id="previewCategory" class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"></span>
                    </div>
                </div>
                <div id="previewStock" class="shrink-0"></div>
            </div>

            <div id="previewDetails" class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                <div><span class="text-gray-400">Light:</span> <span id="previewFabric" class="text-gray-700 font-medium"></span></div>
                <div><span class="text-gray-400">Color:</span> <span id="previewColor" class="text-gray-700 font-medium"></span></div>
                <div><span class="text-gray-400">Type:</span> <span id="previewPrint" class="text-gray-700 font-medium"></span></div>
                <div><span class="text-gray-400">Size:</span> <span id="previewSize" class="text-gray-700 font-medium"></span></div>
            </div>

            <div id="previewDescription" class="text-sm text-gray-500 leading-relaxed"></div>

            <button id="previewAddCartBtn" type="button" onclick="addPreviewToCart()" disabled
                    class="w-full px-5 py-3 bg-[#1f5c3f] hover:bg-[#173d2b] text-white text-sm font-semibold rounded-xl transition shadow-sm flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                <span>Add to Cart</span>
            </button>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <a id="previewOrderBtn" href="#" class="flex-1 text-center px-5 py-3 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition shadow-sm">
                    View Details
                </a>
                <a id="previewWishlistBtn" href="#" class="px-4 py-3 border border-gray-200 rounded-xl text-gray-500 hover:text-red-500 hover:border-red-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
let closeTimeout = null;
let mouseOverPreview = false;
let currentCard = null;
let previewAdding = false;

function addPreviewToCart() {
    if (previewAdding) return;
    const el = currentCard;
    if (!el || !el.dataset.id) return;

    const btn = document.getElementById('previewAddCartBtn');
    if (!btn || btn.disabled) return;

    previewAdding = true;
    btn.disabled = true;
    btn.querySelector('span').textContent = 'Adding...';

    const form = new FormData();
    form.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
    form.append('quantity', '1');

    fetch('{{ route('cart.add', ['id' => '__id__']) }}'.replace('__id__', encodeURIComponent(el.dataset.id)), {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: form,
    })
    .then(r => r.json())
    .then(data => {
        previewAdding = false;
        if (btn) btn.disabled = false;
        if (data && data.success) {
            if (btn) btn.querySelector('span').textContent = 'Added!';
            window.GG?.success(data.message || 'Product added to cart!');
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
            window.dispatchEvent(new CustomEvent('open-mini-cart', { detail: data }));
            setTimeout(() => { if (btn) btn.querySelector('span').textContent = 'Add to Cart'; }, 1200);
        } else {
            if (btn) btn.querySelector('span').textContent = 'Add to Cart';
            window.GG?.error((data && data.message) || 'This product is not available.');
        }
    })
    .catch(() => {
        previewAdding = false;
        if (btn) { btn.disabled = false; btn.querySelector('span').textContent = 'Add to Cart'; }
        window.GG?.error('Could not add this item to your cart. Please try again.');
    });
}

function showPreview(el) {
    clearTimeout(closeTimeout);

    if (currentCard === el) return;

    currentCard = el;

    const preview = document.getElementById('productPreview');
    const card = document.getElementById('previewCard');

    document.getElementById('previewName').textContent = el.dataset.name;

    const priceFormatted = el.dataset.priceFormatted || '{{ getCurrencySymbol() }}' + parseFloat(el.dataset.price || 0).toFixed({{ getCurrencySymbol() === '৳' ? 0 : 2 }});
    const discountFormatted = el.dataset.discountFormatted || '';
    document.getElementById('previewPrice').textContent = priceFormatted;
    const discountEl = document.getElementById('previewDiscount');
    if (discountFormatted) {
        discountEl.textContent = priceFormatted;
        discountEl.classList.remove('hidden');
        document.getElementById('previewPrice').textContent = discountFormatted;
    } else {
        discountEl.classList.add('hidden');
    }

    document.getElementById('previewFabric').textContent = el.dataset.fabric || '-';
    document.getElementById('previewColor').textContent = el.dataset.color || '-';
    document.getElementById('previewPrint').textContent = el.dataset.print || '-';
    document.getElementById('previewSize').textContent = el.dataset.size || '-';

    const catName = el.dataset.categoryName || '';
    document.getElementById('previewCategory').textContent = catName;
    document.getElementById('previewCategory').classList.toggle('hidden', !catName);

    const desc = el.dataset.description || '';
    document.getElementById('previewDescription').textContent = desc;
    document.getElementById('previewDescription').classList.toggle('hidden', !desc);

    const stock = parseInt(el.dataset.stock);
    const stockEl = document.getElementById('previewStock');
    const addBtn = document.getElementById('previewAddCartBtn');
    if (stock > 0) {
        stockEl.innerHTML = '<span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> In Stock</span>';
        if (addBtn) {
            addBtn.disabled = false;
            addBtn.querySelector('span').textContent = 'Add to Cart';
        }
    } else {
        stockEl.innerHTML = '<span class="inline-flex items-center gap-1 text-xs font-medium text-red-500 bg-red-50 px-2.5 py-1 rounded-full">Out of Stock</span>';
        if (addBtn) {
            addBtn.disabled = true;
            addBtn.querySelector('span').textContent = 'Out of Stock';
        }
    }

    const imgDiv = document.getElementById('previewImage');
    const imageUrl = el.dataset.image;
    if (imageUrl) {
        imgDiv.innerHTML = '<img src="' + imageUrl + '" alt="' + el.dataset.name + '" class="w-full h-full object-contain p-4">';
    } else {
        const emojiMap = {
            'mens-t-shirt': '👕',
            'womens-t-shirt': '👚',
            'bags': '👜',
        };
        imgDiv.innerHTML = '<div class="text-7xl select-none">' + (emojiMap[el.dataset.categorySlug || ''] || '✨') + '</div>';
    }

    const slug = el.dataset.slug;
    document.getElementById('previewOrderBtn').href = '/products/' + slug;
    document.getElementById('previewWishlistBtn').href = '{{ route("wishlist.index") }}';

    preview.classList.remove('hidden');
    requestAnimationFrame(() => {
        preview.classList.add('flex');
        requestAnimationFrame(() => {
            card.classList.remove('opacity-0', 'scale-95');
            card.classList.add('opacity-100', 'scale-100');
        });
    });
}

function onCardLeave(el) {
    clearTimeout(closeTimeout);
    closeTimeout = setTimeout(() => {
        if (!mouseOverPreview) {
            closePreview();
        }
    }, 300);
}

function onPreviewEnter() {
    mouseOverPreview = true;
    clearTimeout(closeTimeout);
}

function onPreviewLeave() {
    mouseOverPreview = false;
    clearTimeout(closeTimeout);
    closeTimeout = setTimeout(() => {
        if (!mouseOverPreview) {
            closePreview();
        }
    }, 300);
}

function closePreview() {
    clearTimeout(closeTimeout);
    const preview = document.getElementById('productPreview');
    const card = document.getElementById('previewCard');
    card.classList.remove('opacity-100', 'scale-100');
    card.classList.add('opacity-0', 'scale-95');
    setTimeout(() => {
        preview.classList.add('hidden');
        preview.classList.remove('flex');
        currentCard = null;
        mouseOverPreview = false;
    }, 250);
}

document.addEventListener('click', function (e) {
    const preview = document.getElementById('productPreview');
    const card = document.getElementById('previewCard');
    if (preview.classList.contains('flex') && !card.contains(e.target)) {
        closePreview();
    }
}, true);

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closePreview();
    }
});
</script>
