<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

 use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
