<?php

namespace App\Models;

use App\Services\CatalogService;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'description',
        'full_description',
        'fabric',
        'color',
        'print',
        'size',
        'price',
        'buying_price',
        'discount_price',
        'discount_type',
        'stock',
        'low_stock_alert_quantity',
        'sku',
        'barcode',
        'image',
        'gallery_images',
        'video_url',
        'variations',
        'featured',
        'status',
        'pre_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'gallery_images' => 'array',
            'variations' => 'array',
            'featured' => 'boolean',
            'status' => 'boolean',
            'pre_order' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function productAttributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values', 'product_id', 'attribute_value_id')
            ->withPivot('attribute_id')
            ->withTimestamps();
    }

    public function getFinalPriceAttribute(): float
    {
        if ($this->discount_price) {
            if ($this->discount_type === 'percentage') {
                return round($this->price - ($this->price * $this->discount_price / 100), 2);
            }
            return $this->discount_price;
        }
        return $this->price;
    }

    public function getAvgRatingAttribute(): float
    {
        if (array_key_exists('reviews_avg_rating', $this->attributes)) {
            return round((float) $this->attributes['reviews_avg_rating'], 1);
        }

        return round($this->reviews()->where('status', true)->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        if (array_key_exists('reviews_count', $this->attributes)) {
            return (int) $this->attributes['reviews_count'];
        }

        return $this->reviews()->where('status', true)->count();
    }

    public function isInWishlist(?int $userId = null): bool
    {
        if (! $userId) {
            return false;
        }

        if ($this->relationLoaded('wishlists')) {
            return $this->wishlists->contains('user_id', $userId);
        }

        return $this->wishlists()->where('user_id', $userId)->exists();
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock', '>', 0)
            ->where(function ($q) {
                $q->whereNotNull('low_stock_alert_quantity')
                  ->whereColumn('stock', '<=', 'low_stock_alert_quantity')
                  ->orWhere(function ($q2) {
                      $q2->whereNull('low_stock_alert_quantity')
                         ->where('stock', '<=', 5);
                  });
            });
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('stock', '>', 0)
              ->where(function ($q2) {
                  $q2->whereNotNull('low_stock_alert_quantity')
                     ->whereColumn('stock', '>', 'low_stock_alert_quantity')
                     ->orWhere(function ($q3) {
                         $q3->whereNull('low_stock_alert_quantity')
                            ->where('stock', '>', 5);
                     });
              });
        });
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    public function scopeMatchSort($query, string $sort)
    {
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'stock_asc' => $query->orderBy('stock'),
            'stock_desc' => $query->orderByDesc('stock'),
            'oldest' => $query->oldest(),
            'best_selling' => $query->withCount('orderItems')->orderByDesc('order_items_count'),
            'popular' => $query->withAvg(['reviews' => fn ($q) => $q->where('status', true)], 'rating')->orderByDesc('reviews_avg_rating'),
            default => $query->latest(),
        };
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        }

        $threshold = $this->low_stock_alert_quantity ?? 5;

        if ($this->stock <= $threshold) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_status === 'low_stock';
    }

    protected static function booted(): void
    {
        static::saved(function ($product) {
            if ($product->wasRecentlyCreated
                || $product->wasChanged('brand')
                || $product->wasChanged('fabric')
                || $product->wasChanged('color')) {
                CatalogService::flushFacets();
            }
        });

        static::deleted(fn () => CatalogService::flushFacets());
    }
}
