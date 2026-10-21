<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\CreateMessage;
use App\Enums\ExperienceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SkillCategoryResource;
use App\Http\Resources\SocialLinkResource;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SkillCategory;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class PortfolioApiController extends Controller
{
    public function profile(): JsonResponse
    {
        $profile = Profile::with(['socialLinks' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])->first();

        if (! $profile) {
            return response()->json(['message' => 'Profil belum tersedia.'], 404);
        }

        return (new ProfileResource($profile))->response();
    }

    public function socialLinks(): AnonymousResourceCollection
    {
        $links = SocialLink::where('is_active', true)->orderBy('sort_order')->get();

        return SocialLinkResource::collection($links);
    }

    public function skills(): AnonymousResourceCollection
    {
        $categories = SkillCategory::with(['skills' => fn ($q) => $q->visible()->ordered()])
            ->orderBy('sort_order')
            ->get();

        return SkillCategoryResource::collection($categories);
    }

    public function projects(): AnonymousResourceCollection
    {
        $projects = Project::published()
            ->ordered()
            ->with(['technologies'])
            ->paginate(10);

        return ProjectResource::collection($projects);
    }

    public function project(string $slug): JsonResponse
    {
        $project = Project::published()
            ->where('slug', $slug)
            ->with(['technologies', 'media'])
            ->first();

        if (! $project) {
            return response()->json(['message' => 'Proyek tidak ditemukan.'], 404);
        }

        return (new ProjectResource($project))->response();
    }

    public function experiences(Request $request): AnonymousResourceCollection
    {
        $query = Experience::published()->ordered()->with(['highlights', 'technologies', 'attachments' => fn ($q) => $q->visible()]);

        if ($request->has('type')) {
            $type = $request->query('type');
            if ($type === ExperienceType::Internship->value) {
                $query->internships();
            } elseif ($type === ExperienceType::Organization->value) {
                $query->organizations();
            }
        }

        $experiences = $query->paginate(10);

        return ExperienceResource::collection($experiences);
    }

    public function experience(string $slug): JsonResponse
    {
        $experience = Experience::published()
            ->with(['highlights', 'technologies', 'attachments' => fn ($q) => $q->visible(), 'media'])
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug);
                if (is_numeric($slug)) {
                    $query->orWhere('id', (int) $slug);
                }
            })
            ->first();

        if (! $experience) {
            return response()->json(['message' => 'Pengalaman tidak ditemukan.'], 404);
        }

        return (new ExperienceResource($experience))->response();
    }

    public function storeMessage(StoreMessageRequest $request, CreateMessage $createMessage): JsonResponse
    {
        $message = $createMessage->execute($request->toData());

        return (new MessageResource($message))
            ->response()
            ->setStatusCode(201);
    }
}
