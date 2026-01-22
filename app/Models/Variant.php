<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
    use SoftDeletes; 

    protected $fillable = [
        'product_id', 'title', 'price', 'position', 
        'compare_at_price', 'option_1', 'option_2', 
        'option_3', 'inventory_quantity', 'image_url'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::saving(function ($variant) {
            $options = array_filter([$variant->option_1, $variant->option_2, $variant->option_3]);
            $variant->title = implode(' / ', $options);
        });
    }
}
