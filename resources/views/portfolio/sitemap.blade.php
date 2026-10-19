{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Halaman Utama -->
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <!-- Arsip Dokumentasi -->
    <url>
        <loc>{{ route('portfolio.documentations.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Detail Proyek -->
    @foreach($projects as $project)
        <url>
            <loc>{{ route('portfolio.project.show', $project->slug) }}</loc>
            <lastmod>{{ $project->updated_at?->tz('UTC')->toAtomString() ?? now()->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    <!-- Detail Dokumentasi & Blueprint -->
    @foreach($documentations as $doc)
        <url>
            <loc>{{ route('portfolio.documentations.show', $doc->slug) }}</loc>
            <lastmod>{{ $doc->updated_at?->tz('UTC')->toAtomString() ?? now()->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
