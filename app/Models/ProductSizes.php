<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSizes extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_id',
        'product_id',
        'quantity',
    ];

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }

    public function order():BelongsTo{
        return $this->belongsTo( Size::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}
