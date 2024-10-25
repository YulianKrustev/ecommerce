<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CarouselItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'alt_text',
        'image_path',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('image_path') && ($model->getOriginal('image_path') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('image_path'));
            }
        });

        static::deleting(function ($model) {
            if ($model->getOriginal('image_path') !== null) {
                Storage::disk('public')->delete($model?->getOriginal('image_path'));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
