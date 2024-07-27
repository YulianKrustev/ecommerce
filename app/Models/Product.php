<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'images',
        'description',
        'quantity',
        'price',
        'on_sale_price',
        'is_active',
        'is_featured',
        'in_stock',
        'meta_keywords',
        'meta_title',
        'meta_description',
        'on_sale',
        'offer_id'
    ];

    protected $casts = [
        'images' => 'array',
        'meta_keywords' => 'array',
        'on_sale' => 'boolean',
    ];

    public function getFirstImageAttribute()
    {
        $reversedImages = array_reverse($this->images);
        return $reversedImages[0] ?? null;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function orderItems():HasMany {
        return $this->hasMany(OrderItem::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('images') && ($model->getOriginal('images') !== null)) {
                $originalImages = $model->getOriginal('images');
                $newImages = $model->images;

                if (is_array($originalImages) && is_array($newImages)) {
                    $imagesToDelete = array_diff($originalImages, $newImages);
                    foreach ($imagesToDelete as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
        });

        static::deleting(function ($model) {
            $originalImages = $model->getOriginal('images');
            if (is_array($originalImages)) {
                foreach ($originalImages as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        });
    }

    public function specialOffers()
    {
        return $this->belongsToMany(SpecialOffer::class, 'offer_product', 'product_id', 'special_offer_id')
            ->withTimestamps();
    }

    public function applySpecialOffer(SpecialOffer $offer)
    {
        if (!$this->on_sale || $this->offer_id == $offer->id) {
            $this->offer_id = $offer->id;
            $this->on_sale = true;
            $this->on_sale_price = $this->calculateDiscountedPrice($offer);
            $this->save();
        }
    }

    public function removeSpecialOffer(SpecialOffer $offer)
    {
        if ($this->offer_id == $offer->id) {
            $this->on_sale = false;
            $this->on_sale_price = null;
            $this->save();
        }

    }

    protected function calculateDiscountedPrice(SpecialOffer $offer)
    {
        if ($offer->discount_percentage) {
            return $this->price - ($this->price * $offer->discount_percentage / 100);
        } elseif ($offer->discount_amount) {
            return $this->price - $offer->discount_amount;
        }

        return $this->price;
    }
}
