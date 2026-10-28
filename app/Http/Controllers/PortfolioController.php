<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateMessage;
use App\Enums\ProjectStatus;
use App\Enums\SkillType;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Certification;
use App\Models\Documentation;
use App\Models\Experience;
use App\Models\Media;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\SkillCategory;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PortfolioController extends Controller
{
    public function index(): View
    {
        $profile = Profile::with(['socialLinks' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])->first();

        $technologies = Technology::orderBy('name')->get();

        $hardSkillCategory = SkillCategory::with(['skills' => fn ($q) => $q->visible()->ordered()])
            ->where('type', SkillType::Hard)
            ->first();

        $softSkillCategory = SkillCategory::with(['skills' => fn ($q) => $q->visible()->ordered()])
            ->where('type', SkillType::Soft)
            ->first();

        $analysisSkillCategory = SkillCategory::with(['skills' => fn ($q) => $q->visible()->ordered()])
            ->where('type', SkillType::Analysis)
            ->first();

        $hardSkillTiers = $this->buildSkillPyramidTiers($hardSkillCategory);
        $softSkillTiers = $this->buildSkillPyramidTiers($softSkillCategory);

        $projects = Project::published()
            ->ordered()
            ->with(['technologies', 'media'])
            ->get();

        $internships = Experience::internships()
            ->published()
            ->ordered()
            ->with(['highlights', 'technologies', 'attachments' => fn ($q) => $q->visible()])
            ->get();

        $organizations = Experience::organizations()
            ->published()
            ->ordered()
            ->with(['highlights', 'technologies', 'attachments' => fn ($q) => $q->visible()])
            ->get();

        $certifications = Certification::published()
            ->ordered()
            ->get();

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('portfolio.index', compact(
            'profile',
            'technologies',
            'hardSkillCategory',
            'softSkillCategory',
            'analysisSkillCategory',
            'hardSkillTiers',
            'softSkillTiers',
            'projects',
            'internships',
            'organizations',
            'certifications',
            'settings',
        ));
    }

    public function project(string $slug): View
    {
        $project = Project::published()
            ->where('slug', $slug)
            ->with([
                'technologies',
                'media',
                'documentations' => fn ($q) => $q->published()->ordered(),
            ])
            ->firstOrFail();

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('portfolio.project', compact('project', 'settings'));
    }

    public function experience(string $slug): View|RedirectResponse
    {
        // Backward compatibility: If numeric ID was requested, redirect 301 to the canonical slug URL
        if (is_numeric($slug)) {
            $expById = Experience::published()->find((int) $slug);
            if ($expById && ! empty($expById->slug)) {
                return redirect()->route('portfolio.experience.show', $expById->slug, 301);
            }
        }

        $experience = Experience::published()
            ->where('slug', $slug)
            ->with(['highlights', 'technologies', 'attachments' => fn ($q) => $q->visible(), 'media'])
            ->firstOrFail();

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('portfolio.experience', compact('experience', 'settings'));
    }

    public function downloadThumbnail(string $slug): StreamedResponse
    {
        $project = Project::published()
            ->where('slug', $slug)
            ->firstOrFail();

        if (! $project->thumbnail_path || ! Storage::disk('public')->exists($project->thumbnail_path)) {
            abort(404, 'Berkas gambar tidak ditemukan.');
        }

        $extension = pathinfo($project->thumbnail_path, PATHINFO_EXTENSION) ?: 'png';
        $filename = Str::slug($project->title).'-preview.'.$extension;

        return Storage::disk('public')->download($project->thumbnail_path, $filename);
    }

    public function downloadMedia(int $id): StreamedResponse
    {
        $media = Media::findOrFail($id);

        if ($media->mediable instanceof Project && $media->mediable->status !== ProjectStatus::Published) {
            abort(404);
        }

        if (! $media->path || ! Storage::disk('public')->exists($media->path)) {
            abort(404, 'Berkas gambar tidak ditemukan.');
        }

        $extension = pathinfo($media->path, PATHINFO_EXTENSION) ?: 'png';
        $baseName = $media->caption ? Str::slug($media->caption) : 'media-'.$media->id;
        $filename = $baseName.'.'.$extension;

        return Storage::disk('public')->download($media->path, $filename);
    }

    public function documentations(Request $request): View
    {
        $profile = Profile::with(['socialLinks' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])->first();
        $settings = SiteSetting::all()->pluck('value', 'key');

        $selectedCategory = $request->query('kategori');
        $selectedType = $request->query('tipe');
        $selectedProject = $request->query('proyek');
        $search = $request->query('q');

        $query = Documentation::published()
            ->with('project')
            ->ordered();

        if ($selectedCategory && $selectedCategory !== 'all') {
            $query->where('category', $selectedCategory);
        }

        if ($selectedType && $selectedType !== 'all') {
            $query->where('type', $selectedType);
        }

        if ($selectedProject && $selectedProject !== 'all') {
            $query->whereHas('project', fn ($q) => $q->where('slug', $selectedProject));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhereHas('project', fn ($pq) => $pq->where('title', 'like', "%{$search}%"));
            });
        }

        $documentations = $query->get();
        $categories = Documentation::published()->distinct()->pluck('category')->filter()->values();
        $projects = Project::published()->orderBy('title')->get(['id', 'title', 'slug']);
        $featuredDocs = Documentation::published()->featured()->ordered()->take(3)->get();

        return view('portfolio.documentations.index', compact(
            'profile',
            'settings',
            'documentations',
            'categories',
            'projects',
            'featuredDocs',
            'selectedCategory',
            'selectedType',
            'selectedProject',
            'search'
        ));
    }

    public function documentationDetail(string $slug): View
    {
        $profile = Profile::with(['socialLinks' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])->first();
        $settings = SiteSetting::all()->pluck('value', 'key');

        $documentation = Documentation::published()
            ->where('slug', $slug)
            ->with('project')
            ->firstOrFail();

        $relatedDocs = Documentation::published()
            ->where('id', '!=', $documentation->id)
            ->where('category', $documentation->category)
            ->take(3)
            ->get();

        return view('portfolio.documentations.show', compact('documentation', 'relatedDocs', 'profile', 'settings'));
    }

    public function previewDocumentation(string $slug, Request $request): Response
    {
        $documentation = Documentation::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $paths = $documentation->getFilePaths();
        if (empty($paths)) {
            if ($documentation->preview_image_path && Storage::disk('public')->exists($documentation->preview_image_path)) {
                $fullPath = Storage::disk('public')->path($documentation->preview_image_path);
                $mimeType = Storage::disk('public')->mimeType($documentation->preview_image_path) ?? 'image/jpeg';

                return response()->file($fullPath, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="'.basename($documentation->preview_image_path).'"',
                ]);
            }

            abort(404, 'Berkas fisik dokumentasi tidak ditemukan.');
        }

        $fileIndex = $request->query('file');
        $targetIndex = 0;
        if ($fileIndex !== null && is_numeric($fileIndex)) {
            $parsed = (int) $fileIndex;
            if (isset($paths[$parsed])) {
                $targetIndex = $parsed;
            }
        }

        $targetPath = $paths[$targetIndex] ?? null;

        if (! $targetPath || ! Storage::disk('public')->exists($targetPath)) {
            abort(404, 'Berkas fisik tidak ditemukan di penyimpanan server.');
        }

        $fullPath = Storage::disk('public')->path($targetPath);
        $mimeType = Storage::disk('public')->mimeType($targetPath) ?? 'application/octet-stream';
        $filename = $documentation->getDownloadFilename($targetIndex);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

    public function downloadDocumentation(string $slug, Request $request): Response
    {
        $documentation = Documentation::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $paths = $documentation->getFilePaths();
        if (empty($paths)) {
            abort(404, 'Berkas dokumentasi tidak ditemukan.');
        }

        // Unduh seluruh berkas dalam satu file ZIP jika diminta atau jika memiliki lebih dari 1 file
        $wantsAll = $request->boolean('all') || ($request->query('file') === null && count($paths) > 1);
        if ($wantsAll && count($paths) > 1 && class_exists('ZipArchive')) {
            $zipFilename = Str::slug($documentation->title).'-arsip-lengkap.zip';
            $tempFile = tempnam(sys_get_temp_dir(), 'doc_zip_');
            $zip = new \ZipArchive;
            if ($zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                foreach ($paths as $idx => $p) {
                    if (Storage::disk('public')->exists($p)) {
                        $fullPath = Storage::disk('public')->path($p);
                        $entryName = ($idx + 1).'-'.basename($p);
                        $zip->addFile($fullPath, $entryName);
                    }
                }
                $zip->close();

                return response()->download($tempFile, $zipFilename)->deleteFileAfterSend(true);
            }
        }

        $fileIndex = $request->query('file');
        $targetIndex = 0;
        if ($fileIndex !== null && is_numeric($fileIndex)) {
            $parsed = (int) $fileIndex;
            if (isset($paths[$parsed])) {
                $targetIndex = $parsed;
            }
        }

        $targetPath = $paths[$targetIndex] ?? null;

        if (! $targetPath || ! Storage::disk('public')->exists($targetPath)) {
            abort(404, 'Berkas fisik tidak ditemukan di penyimpanan server.');
        }

        $filename = $documentation->getDownloadFilename($targetIndex);

        return Storage::disk('public')->download($targetPath, $filename);
    }

    public function sitemap(): Response
    {
        $projects = Project::published()->orderBy('updated_at', 'desc')->get();
        $documentations = Documentation::published()->orderBy('updated_at', 'desc')->get();
        $experiences = Experience::published()->orderBy('updated_at', 'desc')->get();

        $content = view('portfolio.sitemap', compact('projects', 'documentations', 'experiences'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function sendMessage(StoreMessageRequest $request, CreateMessage $createMessage): RedirectResponse
    {
        $createMessage->execute($request->toData());

        return back()->with('success', 'Terima kasih! Pesan Anda telah terkirim dan akan segera saya balas.');
    }

    /**
     * Build dynamic skill pyramid tiers from a skill category.
     *
     * @return Collection<int, array{index: int, label: string, avg_level: ?int, skills: Collection, color: array{from: string, to: string, text: string, bg: string, border: string, icon: string}}>
     */
    private function buildSkillPyramidTiers(?SkillCategory $category): Collection
    {
        if (! $category || $category->skills->isEmpty()) {
            return collect();
        }

        $skills = $category->skills;

        $hasLabels = $skills->pluck('level_label')->filter()->isNotEmpty();

        $grouped = $skills->groupBy(function ($skill) use ($hasLabels) {
            if ($hasLabels && ! empty($skill->level_label)) {
                return trim($skill->level_label);
            }

            $lvl = $skill->level ?? 75;
            if ($lvl >= 92) {
                return 'Expert';
            }
            if ($lvl >= 85) {
                return 'Advanced';
            }
            if ($lvl >= 75) {
                return 'Intermediate';
            }

            return 'Basic';
        });

        $sorted = $grouped->sortByDesc(function ($group) {
            $avg = $group->avg('level');
            if ($avg !== null && $avg > 0) {
                return $avg;
            }

            return -($group->min('sort_order') ?? 0);
        });

        $palette = [
            ['from' => '#4F46E5', 'to' => '#6366F1', 'text' => '#4F46E5', 'bg' => 'rgba(79, 70, 229, 0.1)', 'border' => '#4F46E5', 'icon' => 'bi-trophy-fill'],
            ['from' => '#0284C7', 'to' => '#0EA5E9', 'text' => '#0284C7', 'bg' => 'rgba(2, 132, 199, 0.1)', 'border' => '#0284C7', 'icon' => 'bi-gear-wide-connected'],
            ['from' => '#059669', 'to' => '#10B981', 'text' => '#059669', 'bg' => 'rgba(5, 150, 105, 0.1)', 'border' => '#059669', 'icon' => 'bi-layers-fill'],
            ['from' => '#D97706', 'to' => '#F59E0B', 'text' => '#D97706', 'bg' => 'rgba(217, 119, 6, 0.1)', 'border' => '#D97706', 'icon' => 'bi-lightning-charge-fill'],
            ['from' => '#E11D48', 'to' => '#F43F5E', 'text' => '#E11D48', 'bg' => 'rgba(225, 29, 72, 0.1)', 'border' => '#E11D48', 'icon' => 'bi-star-fill'],
            ['from' => '#7C3AED', 'to' => '#8B5CF6', 'text' => '#7C3AED', 'bg' => 'rgba(124, 58, 237, 0.1)', 'border' => '#7C3AED', 'icon' => 'bi-award-fill'],
        ];

        $tierIndex = 0;

        return $sorted->map(function ($tierSkills, $tierLabel) use (&$tierIndex, $palette) {
            $color = $palette[$tierIndex % count($palette)];
            $avgLevel = $tierSkills->avg('level');
            $tierIndex++;

            $tierSkillsSorted = $tierSkills->sortByDesc('level');
            $cleanNames = $tierSkillsSorted->map(function ($s) {
                $name = $s->name;
                $name = str_replace(
                    [' & Query Optimization', ' & Clean Architecture', ' & Responsive UI'],
                    ['', '', ''],
                    $name
                );

                return Str::limit(trim($name), 16);
            });

            if ($tierSkillsSorted->count() > 2) {
                $summary = '('.$cleanNames->take(2)->implode(', ').', dll)';
            } else {
                $summary = '('.$cleanNames->implode(', ').')';
            }

            return [
                'index' => $tierIndex - 1,
                'label' => $tierLabel,
                'avg_level' => $avgLevel ? (int) round($avgLevel) : null,
                'summary' => $summary,
                'skills' => $tierSkillsSorted,
                'color' => $color,
            ];
        })->values();
    }

    public function downloadCertificationFile(string $slug, int $index = 0): StreamedResponse
    {
        $certification = Certification::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $files = $certification->getFiles();
        if (! isset($files[$index]) || ! Storage::disk('public')->exists($files[$index])) {
            abort(404, 'Berkas dokumen sertifikasi tidak ditemukan.');
        }

        $filePath = $files[$index];
        $filename = $certification->getDownloadFilename($index, 'file');

        return Storage::disk('public')->download($filePath, $filename);
    }

    public function downloadCertificationImage(string $slug, int $index = 0): StreamedResponse
    {
        $certification = Certification::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $images = $certification->getImages();
        if (! isset($images[$index]) || ! Storage::disk('public')->exists($images[$index])) {
            abort(404, 'Berkas gambar sertifikasi tidak ditemukan.');
        }

        $filePath = $images[$index];
        $filename = $certification->getDownloadFilename($index, 'image');

        return Storage::disk('public')->download($filePath, $filename);
    }

    public function downloadMessageAttachment(Request $request, Message $message, int $index = 0): StreamedResponse
    {
        $expectedToken = substr(sha1($message->email.(string) $message->created_at), 0, 16);
        $isAuthorized = $request->hasValidSignature(false)
            || $request->query('token') === $expectedToken
            || auth()->check();

        if (! $isAuthorized) {
            abort(403, 'Tautan unduhan tidak valid atau sudah kedaluwarsa.');
        }

        $attachments = $message->getAttachments();
        $target = $attachments[$index] ?? null;

        if (! $target || empty($target['path']) || ! Storage::disk('public')->exists($target['path'])) {
            abort(404, 'Berkas lampiran pesan tidak ditemukan.');
        }

        $filename = $target['name'] ?? basename($target['path']);

        return Storage::disk('public')->download($target['path'], $filename);
    }
}
