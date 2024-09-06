<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
        'zip_code',
        'district'
    ];

    public function order():BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function getFullNameAttribute():string {
        return "{$this->first_name} {$this->last_name}";
    }
}
