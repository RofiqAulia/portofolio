<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateMessage;
use App\Enums\ProjectStatus;
use App\Enums\SkillType;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Documentation;
use App\Models\Experience;
use App\Models\Media;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\SkillCategory;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('portfolio.index', compact(
            'profile',
            'technologies',
            'hardSkillCategory',
            'softSkillCategory',
            'analysisSkillCategory',
            'projects',
            'internships',
            'organizations',
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

    public function experience(int $id): View
    {
        $experience = Experience::published()
            ->with(['highlights', 'technologies', 'attachments' => fn ($q) => $q->visible(), 'media'])
            ->findOrFail($id);

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

    public function sendMessage(StoreMessageRequest $request, CreateMessage $createMessage): RedirectResponse
    {
        $createMessage->execute($request->toData());

        return back()->with('success', 'Terima kasih! Pesan Anda telah terkirim dan akan segera saya balas.');
    }
}
