{{ '<?xml version="1.0" encoding="UTF-8"?>' }}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ url('/') }}</loc><lastmod>{{ now()->toAtomString() }}</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>
    <url><loc>{{ route('products.index') }}</loc><lastmod>{{ now()->toAtomString() }}</lastmod><changefreq>daily</changefreq><priority>0.9</priority></url>
    <url><loc>{{ route('about') }}</loc><priority>0.7</priority></url>
    <url><loc>{{ route('contact') }}</loc><priority>0.7</priority></url>
    <url><loc>{{ route('faq.index') }}</loc><lastmod>{{ now()->toAtomString() }}</lastmod><priority>0.6</priority></url>
    <url><loc>{{ route('care.index') }}</loc><lastmod>{{ now()->toAtomString() }}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc>{{ route('search') }}</loc><priority>0.5</priority></url>
    @foreach($categories as $cat)
        <url><loc>{{ route('products.category', $cat->slug) }}</loc><lastmod>{{ $cat->updated_at->toAtomString() }}</lastmod><priority>0.8</priority></url>
    @endforeach
    @foreach($products as $product)
        <url><loc>{{ route('products.show', $product->slug) }}</loc><lastmod>{{ $product->updated_at->toAtomString() }}</lastmod><priority>0.6</priority></url>
    @endforeach
    @foreach($careGuides as $guide)
        <url><loc>{{ route('care.show', $guide->slug) }}</loc><lastmod>{{ $guide->updated_at->toAtomString() }}</lastmod><priority>0.7</priority></url>
    @endforeach
    @foreach($faqs as $faq)
        <url><loc>{{ route('faq.index') . '#faq-' . $faq->id }}</loc><lastmod>{{ $faq->updated_at->toAtomString() }}</lastmod><priority>0.4</priority></url>
    @endforeach
</urlset>
