<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProjectResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'role' => $this->role,
            'thumbnail_url' => $this->thumbnail_path ? asset('storage/'.$this->thumbnail_path) : null,
            'demo_url' => $this->demo_url,
            'repo_url' => $this->repo_url,
            'started_at' => $this->started_at?->format('Y-m-d'),
            'finished_at' => $this->finished_at?->format('Y-m-d'),
            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
            'published_at' => $this->published_at?->toIso8601String(),
            'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
