@extends('layouts.app')

@section('title', 'Penghargaan & Sertifikasi — ' . ($profile->full_name ?? 'M. Rofiq Aulia'))
@section('meta_description', 'Arsip kredensial resmi, hak cipta karya (HKI), sertifikasi kompetensi, dan apresiasi capaian profesional M. Rofiq Aulia.')

@section('content')
<div class="container py-4">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <a href="{{ route('portfolio.home') }}" class="btn-bento-secondary py-2 px-3 fs-6">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
        <span class="badge bg-primary text-white px-3 py-2 fw-bold">
            {{ $certifications->count() }} Kredensial
        </span>
    </div>

    <section class="mb-4">
        <div class="bento-card bento-card-royal p-4 p-md-5">
            <span class="bento-badge mb-2">KREDENSIAL &amp; PRESTASI</span>
            <h1 class="display-5 font-heading mb-3 text-white">
                Dokumentasi Penghargaan &amp; Sertifikasi
            </h1>
            <p class="fs-6 text-white text-opacity-75 mb-0" style="max-width: 720px; line-height: 1.6;">
                Bukti kredensial resmi, hak cipta karya cipta (HKI), lisensi kompetensi, dan apresiasi capaian profesional.
            </p>
        </div>
    </section>

    @if($certifications->isEmpty())
        <div class="text-center py-5 bg-white border text-muted">
            <i class="bi bi-award fs-1 d-block mb-2 opacity-50"></i>
            Belum ada data penghargaan &amp; sertifikasi yang dipublikasikan.
        </div>
    @else
        <div class="row g-4">
            @foreach($certifications as $certification)
                <div class="col-md-6 col-lg-4">
                    @include('portfolio.partials.certification-card', ['certification' => $certification])
                </div>
            @endforeach
        </div>
    @endif

    @foreach($certifications as $certification)
        @include('portfolio.partials.certification-modal', ['certification' => $certification])
    @endforeach

</div>
@endsection
