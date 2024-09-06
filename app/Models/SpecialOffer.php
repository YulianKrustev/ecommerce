<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SpecialOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'discount_amount',
        'discount_percentage',
        'is_active',
        'start_date',
        'end_date',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug',
        'image_alt'
    ];

    protected $casts = [
        'meta_keywords' => 'array',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product', 'special_offer_id', 'product_id')
            ->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'offer_category', 'special_offer_id', 'category_id')
            ->withTimestamps();
    }

    protected static function booted()
    {
        static::created(function ($offer) {
            $offer->applySpecialOffer();
        });

        static::updated(function ($offer) {
            $offer->applySpecialOffer();
        });

    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('image') && ($model->getOriginal('image') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('image'));
            }
        });

        static::deleting(function ($model) {
            if ($model->getOriginal('image') !== null) {
                Storage::disk('public')->delete($model?->getOriginal('image'));
            }
        });
    }

    public function applySpecialOffer()
    {
        if ($this->categories()->exists()) {
            foreach ($this->categories as $category) {
                foreach ($category->products as $product) {

                    if ($this->is_active ) {
                        $product->applySpecialOffer($this);
                    } else {
                        $product->removeSpecialOffer($this);
                    }
                }
            }
        }

        if ($this->products()->exists()) {
            foreach ($this->products as $product) {

                if ($this->is_active ) {
                    $product->applySpecialOffer($this);
                } else {
                    $product->removeSpecialOffer($this);
                }
            }
        }
    }
}
