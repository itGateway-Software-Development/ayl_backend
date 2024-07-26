<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public function productMainImage()
    {
        return collect($this->getMedia('product_main_image'));
    }

    public function productDetailImages()
    {
        return collect($this->getMedia('product_detail_images'));
    }

    public function series() {
        return $this->belongsTo(Series::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

}
