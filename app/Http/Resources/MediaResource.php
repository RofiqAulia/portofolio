<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class MediaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type?->value ?? $this->type,
            'url' => asset('storage/'.$this->path),
            'caption' => $this->caption,
            'sort_order' => $this->sort_order,
        ];
    }
}
