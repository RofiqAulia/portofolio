<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SkillCategoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type?->value ?? $this->type,
            'type_label' => $this->type?->label() ?? null,
            'sort_order' => $this->sort_order,
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
        ];
    }
}
