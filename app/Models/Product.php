<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
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
        'on_sale'
    ];

    protected $casts = [
        'images' => 'array',
        'meta_keywords' => 'array'
    ];

    public function category():BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function orderItems():HasMany {
        return $this->hasMany(OrderItem::class);
    }
}
