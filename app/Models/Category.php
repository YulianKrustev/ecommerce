<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

 use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'image_alt',
        'is_active',
        'description',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'meta_keywords' => 'array'
    ];
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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

            if ($model->getOriginal('image') !== null)
            Storage::disk('public')->delete($model?->getOriginal('image'));
        } );
    }
}
