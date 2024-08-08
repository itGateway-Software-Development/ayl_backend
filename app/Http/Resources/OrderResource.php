<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => date('d-m-Y', strtotime($this->created_at)),
            'order_no' => $this->order_no,
            'order_status' => $this->status,
            'customer' => $this->name,
            'phone' => $this->phone,
            'order_items' => $this->order_items,
            'delivery_city' => $this->delivery_city,
            'delivery_town' => $this->delivery_town,
            'delivery_charges' => $this->delivery_charges,
            'sub_total' => $this->sub_total,
            'used_point' => $this->used_point,
            'grand_total' => $this->grand_total,
            'delivery_address' => $this->delivery_address,
            'img' => $this->slip_image ? url("/storage/images/").'/'.$this->slip_image : null,
        ];
    }
}
