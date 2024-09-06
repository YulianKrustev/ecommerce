<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'discount_amount',
        'discount_percentage',
        'expires_at',
        'is_active',
        'usage_limit',
        'usage_count',
        'min_order_value',
        'single_use'
    ];

    public function isValid()
    {
        return $this->is_active &&
            ($this->expires_at === null || $this->expires_at->isFuture()) &&
            ($this->usage_limit === null || $this->usage_count < $this->usage_limit);
    }

    public function useVoucher()
    {
        $this->increment('usage_count');
        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) {
            $this->is_active = false;
        }
        $this->save();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_voucher')
            ->withTimestamps();
    }
}
