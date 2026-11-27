{{--
    PAGE-FIN-002 - Section 'attachments' (Detail Order / Request)
    Domain  : PRD-05 FIN - Sales, Transaction & Finance (Zhaqtiawan)
    Catatan : File privat (disk local). Tombol unduh aktif setelah route
              admin.attachments.download (AttachmentDownloadController) dibuat.
    Status  : diimplementasikan (daftar); unduh menunggu route.
--}}
<section class="rounded-xl border border-gray-200 bg-white p-4">
    <h2 class="mb-3 text-sm font-semibold uppercase text-gray-500">Lampiran Desain / Referensi</h2>

    @if ($attachments->isEmpty())
        <p class="text-sm text-gray-500">Tidak ada lampiran.</p>
    @else
        <ul class="divide-y divide-gray-100 text-sm">
            @foreach ($attachments as $attachment)
                <li class="flex items-center justify-between gap-3 py-2">
                    <div class="min-w-0">
                        <div class="truncate font-medium text-gray-900">{{ $attachment->original_name }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $attachment->mime_type }} &middot; {{ $attachment->sizeForHumans() }}
                            @if ($attachment->caption) &middot; {{ $attachment->caption }} @endif
                        </div>
                    </div>
                    @if (Route::has('admin.attachments.download'))
                        <a href="{{ route('admin.attachments.download', $attachment) }}"
                            class="shrink-0 font-medium text-blue-700 hover:underline">Unduh</a>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</section>
