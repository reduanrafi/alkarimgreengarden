<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'short_description',
        'type',
        'display_order',
        'image',
        'button_text',
        'redirect_url',
        'background_color',
        'status',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'display_order' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now()->startOfDay());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now()->startOfDay());
            });
    }

    public function scopeHero($query)
    {
        return $query->where('type', 'hero_banner');
    }

    public function scopeCarousel($query)
    {
        return $query->where('type', 'homepage_carousel');
    }

    public function scopeFixed($query)
    {
        return $query->where('type', 'homepage_fixed');
    }

    public function scopeEspeciallyForYou($query)
    {
        return $query->where('type', 'especially_for_you');
    }

    protected static function booted(): void
    {
        static::saving(function ($banner) {
            if ($banner->status && !in_array($banner->type, ['hero_banner', 'homepage_carousel', 'especially_for_you'])) {
                static::where('id', '!=', $banner->id)
                    ->where('type', $banner->type)
                    ->update(['status' => false]);
            }
        });
    }
}
