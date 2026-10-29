@php
    $attachments = $record?->getAttachments() ?? [];
@endphp

@if(empty($attachments))
    <div class="flex items-center gap-2 p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/60 text-sm text-gray-500 dark:text-gray-400">
        <x-filament::icon icon="heroicon-o-information-circle" class="w-5 h-5 text-gray-400 shrink-0" style="width: 20px; height: 20px;" />
        <span>Tidak ada berkas yang dilampirkan pada pesan ini.</span>
    </div>
@else
    <style>
        .fi-attachment-card svg {
            width: 14px;
            height: 14px;
            max-width: 14px;
            max-height: 14px;
            flex-shrink: 0;
        }
        .fi-attachment-card .fi-btn-icon svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
        }
        .fi-attachment-icon-wrapper svg {
            width: 22px !important;
            height: 22px !important;
            max-width: 22px !important;
            max-height: 22px !important;
        }
    </style>

    <div class="space-y-3.5">
        <div class="flex flex-wrap items-center justify-between gap-2 pb-2.5 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-primary-50 text-primary-700 dark:bg-primary-950/50 dark:text-primary-300 border border-primary-200/60 dark:border-primary-800/40">
                    <x-filament::icon icon="heroicon-m-paper-clip" class="w-3.5 h-3.5 shrink-0" style="width: 14px; height: 14px;" />
                    {{ count($attachments) }} Berkas Terlampir
                </span>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                <span>Total Ukuran:</span>
                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $record->formattedAttachmentSize() }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
            @foreach($attachments as $index => $item)
                @php
                    $isImage = !empty($item['mime']) && str_starts_with($item['mime'], 'image/');
                    $downloadUrl = $record->getAttachmentDownloadUrl($index);
                    $storageUrl = asset('storage/' . $item['path']);
                    $ext = strtoupper(pathinfo($item['name'] ?? '', PATHINFO_EXTENSION) ?: 'FILE');
                    $mime = $item['mime'] ?? '';
                    $isPdf = $ext === 'PDF' || str_contains($mime, 'pdf');
                    $isDoc = in_array($ext, ['DOC', 'DOCX', 'ODT', 'RTF', 'TXT'], true);
                    $isSheet = in_array($ext, ['XLS', 'XLSX', 'CSV', 'ODS'], true);
                    $isArchive = in_array($ext, ['ZIP', 'RAR', '7Z', 'TAR', 'GZ'], true);
                @endphp

                <div class="fi-attachment-card group relative flex flex-col justify-between p-3.5 rounded-xl border border-gray-200/90 dark:border-gray-700/80 bg-white dark:bg-gray-800/70 shadow-sm hover:shadow-md hover:border-primary-500/40 dark:hover:border-primary-500/40 transition-all duration-200">
                    <div class="flex items-start gap-3">
                        @if($isImage)
                            <a href="{{ $storageUrl }}" target="_blank" class="shrink-0 relative block w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 group/img">
                                <img src="{{ $storageUrl }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-200">
                            </a>
                        @elseif($isPdf)
                            <div class="fi-attachment-icon-wrapper shrink-0 w-12 h-12 rounded-lg bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 flex flex-col items-center justify-center text-red-600 dark:text-red-400">
                                <x-filament::icon icon="heroicon-o-document-text" class="w-5 h-5 shrink-0" style="width: 20px; height: 20px;" />
                                <span class="text-[9px] font-bold tracking-wider uppercase leading-none mt-0.5">PDF</span>
                            </div>
                        @elseif($isDoc)
                            <div class="fi-attachment-icon-wrapper shrink-0 w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800/60 flex flex-col items-center justify-center text-blue-600 dark:text-blue-400">
                                <x-filament::icon icon="heroicon-o-document" class="w-5 h-5 shrink-0" style="width: 20px; height: 20px;" />
                                <span class="text-[9px] font-bold tracking-wider uppercase leading-none mt-0.5">DOC</span>
                            </div>
                        @elseif($isSheet)
                            <div class="fi-attachment-icon-wrapper shrink-0 w-12 h-12 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 flex flex-col items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <x-filament::icon icon="heroicon-o-table-cells" class="w-5 h-5 shrink-0" style="width: 20px; height: 20px;" />
                                <span class="text-[9px] font-bold tracking-wider uppercase leading-none mt-0.5">{{ $ext }}</span>
                            </div>
                        @elseif($isArchive)
                            <div class="fi-attachment-icon-wrapper shrink-0 w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 flex flex-col items-center justify-center text-amber-600 dark:text-amber-400">
                                <x-filament::icon icon="heroicon-o-archive-box" class="w-5 h-5 shrink-0" style="width: 20px; height: 20px;" />
                                <span class="text-[9px] font-bold tracking-wider uppercase leading-none mt-0.5">ZIP</span>
                            </div>
                        @else
                            <div class="fi-attachment-icon-wrapper shrink-0 w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-gray-600 dark:text-gray-300">
                                <x-filament::icon icon="heroicon-o-paper-clip" class="w-5 h-5 shrink-0" style="width: 20px; height: 20px;" />
                                <span class="text-[9px] font-bold tracking-wider uppercase leading-none mt-0.5">{{ substr($ext, 0, 4) }}</span>
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors" title="{{ $item['name'] }}">
                                {{ $item['name'] }}
                            </h4>
                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ !empty($item['size']) ? $record->formatBytes((int) $item['size']) : '-' }}
                                </span>
                                <span>&bull;</span>
                                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-mono font-semibold bg-gray-100 dark:bg-gray-700/80 text-gray-600 dark:text-gray-300">
                                    {{ $ext }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between gap-2">
                        <span class="text-[11px] text-gray-400 dark:text-gray-500 font-mono truncate max-w-[130px]" title="{{ $mime }}">
                            {{ $mime }}
                        </span>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <x-filament::button
                                tag="a"
                                :href="$storageUrl"
                                target="_blank"
                                color="gray"
                                size="xs"
                                icon="heroicon-m-arrow-top-right-on-square"
                            >
                                Buka
                            </x-filament::button>

                            <x-filament::button
                                tag="a"
                                :href="$downloadUrl"
                                color="primary"
                                size="xs"
                                icon="heroicon-m-arrow-down-tray"
                            >
                                Unduh
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
