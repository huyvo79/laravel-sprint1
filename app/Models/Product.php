<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'description', 'slug', 'tags', 'status'];

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });

        static::deleting(function ($product) {
            $product->variants()->delete();
        });

        static::restoring(function ($product) {
            $product->variants()->restore();
        });
    }
}
