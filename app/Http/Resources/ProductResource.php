<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $mainImage = $this->media->firstWhere('collection_name', 'product_main_image');
        $images = $this->media->where('collection_name', '!=', 'product_main_image')->map(function($media) {
            return $media->original_url;
        });
        return [
            'id' => $this->id,
            'name' => $this->name,
            'series_id' => $this->series_id,
            'category_id' => $this->category_id,
            'product_info' => $this->product_info,
            'price' => $this->price,
            'm_size_stock' => $this->m_size_stock,
            'lg_size_stock' => $this->lg_size_stock,
            'xl_size_stock' => $this->xl_size_stock,
            'xxl_size_stock' => $this->xxl_size_stock,
            'xxxl_size_stock' => $this->xxxl_size_stock,
            'xxxxl_size_stock' => $this->xxxxl_size_stock,
            'main_image' => $mainImage ? $mainImage->original_url : null,
            'images' => $images
        ];
    }
}
