<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class PostCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'image_alt', 'is_active', 'description', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $casts = [
        'meta_keywords' => 'array',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'post_category_id');
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
}
