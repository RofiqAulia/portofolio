<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProfileResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'headline' => $this->headline,
            'tagline' => $this->tagline,
            'bio' => $this->bio,
            'photo_url' => $this->photo_path ? asset('storage/'.$this->photo_path) : null,
            'cv_url' => $this->cv_path ? asset('storage/'.$this->cv_path) : null,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'quote_text' => $this->quote_text,
            'quote_author' => $this->quote_author,
            'is_open_to_work' => $this->is_open_to_work,
            'social_links' => SocialLinkResource::collection($this->whenLoaded('socialLinks')),
        ];
    }
}
