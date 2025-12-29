<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray($request)
    {

        $price = (float) $this->price;
        $discountValue = (float) ($this->discount_value ?? 0);

        $finalPrice = $this->discount_type === 'percentage'
            ? $price - ($price * $discountValue / 100)
            : $price - $discountValue;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'duration' => $this->duration,
            'discount_value' => $this->discount_value,
            'discount_type' => $this->discount_type,
            'final_price' => $finalPrice,
            'features' => $this->features, // Already cast to array by Eloquent
        ];
    }
}
