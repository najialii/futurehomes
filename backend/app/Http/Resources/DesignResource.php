<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'category_label' => $this->getCategories()[$this->category] ?? $this->category,
            'image_url' => $this->image_path ? url('/images/' . $this->image_path) : null,
            'alt_text' => $this->alt_text,
            'display_order' => $this->display_order,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'tags' => $this->tags ?? [],
            'tags_labels' => $this->tags ? collect($this->tags)->map(function ($tag) {
                return $this->getAvailableTags()[$tag] ?? $tag;
            })->toArray() : [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
