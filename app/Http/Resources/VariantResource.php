<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'inventory_quantity' => $this->inventory_quantity,
            'options' => [
                'option_1' => $this->option_1,
                'option_2' => $this->option_2,
                'option_3' => $this->option_3,
            ],
        ];
    }
}
