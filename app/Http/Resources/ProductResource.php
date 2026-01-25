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
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'tags' => $this->tags,
            'variants' => VariantResource::collection($this->whenLoaded('variants')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
