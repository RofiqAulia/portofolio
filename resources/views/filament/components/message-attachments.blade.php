@php
    $attachments = $record?->getAttachments() ?? [];
@endphp

@if(empty($attachments))
    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada berkas yang dilampirkan pada pesan ini.</p>
@else
    <div class="space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-700">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                Total Lampiran: {{ count($attachments) }} Berkas
            </span>
            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                Total Ukuran: {{ $record->formattedAttachmentSize() }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($attachments as $index => $item)
                @php
                    $isImage = !empty($item['mime']) && str_starts_with($item['mime'], 'image/');
                    $downloadUrl = $record->getAttachmentDownloadUrl($index);
                    $storageUrl = asset('storage/' . $item['path']);
                @endphp
                <div class="flex flex-col justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60 shadow-sm">
                    <div class="flex items-start gap-3">
                        @if($isImage)
                            <a href="{{ $storageUrl }}" target="_blank" class="shrink-0 block w-14 h-14 rounded overflow-hidden border border-gray-200 dark:border-gray-700 bg-black/5">
                                <img src="{{ $storageUrl }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </a>
                        @else
                            <div class="shrink-0 w-12 h-12 rounded bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs uppercase">
                                {{ pathinfo($item['name'], PATHINFO_EXTENSION) ?: 'FILE' }}
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate" title="{{ $item['name'] }}">
                                {{ $item['name'] }}
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ !empty($item['size']) ? $record->formatBytes((int) $item['size']) : '-' }}
                                @if(!empty($item['mime']))
                                    &bull; <span class="font-mono text-[11px]">{{ $item['mime'] }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-2">
                        <a href="{{ $storageUrl }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Buka
                        </a>
                        <a href="{{ $downloadUrl }}" class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-500 shadow-sm transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
