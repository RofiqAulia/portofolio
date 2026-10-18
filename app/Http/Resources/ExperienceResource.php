<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ExperienceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type?->value ?? $this->type,
            'type_label' => $this->type?->label() ?? null,
            'position' => $this->position,
            'organization_name' => $this->organization_name,
            'location' => $this->location,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'is_current' => $this->is_current,
            'summary' => $this->summary,
            'story' => $this->story,
            'cover_url' => $this->cover_path ? asset('storage/'.$this->cover_path) : null,
            'sort_order' => $this->sort_order,
            'highlights' => ExperienceHighlightResource::collection($this->whenLoaded('highlights')),
            'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
            'attachments' => ExperienceAttachmentResource::collection($this->whenLoaded('attachments')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
