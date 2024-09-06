<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'image', 'image_alt', 'is_active', 'is_featured', 'meta_title', 'meta_description', 'meta_keywords', 'content', 'post_category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'post_product');
    }

    protected $casts = [
        'meta_keywords' => 'array',
    ];

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
}
