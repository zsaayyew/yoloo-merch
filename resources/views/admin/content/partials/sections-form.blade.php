{{--
    ADM - Form section konten satu halaman
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Variabel: $sections (kunci section => label), $contents (koleksi SiteContent per section)
--}}
@foreach ($sections as $sectionKey => $sectionLabel)
    @php($content = $contents->get($sectionKey))

    <section class="rounded-3xl border border-[#181818]/10 bg-white p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-black tracking-tight text-[#181818]">{{ $sectionLabel }}</h2>
                <p class="text-xs text-[#181818]/50">Section <code class="font-mono">{{ $sectionKey }}</code></p>
            </div>

            @include('admin.partials.field-checkbox', [
                'name' => "sections.{$sectionKey}.is_published",
                'label' => 'Tampilkan di website',
                'checked' => $content?->is_published ?? true,
            ])
        </div>

        <div class="mt-4 grid gap-4 lg:grid-cols-2">
            @include('admin.partials.field-input', [
                'name' => "sections.{$sectionKey}.title",
                'label' => 'Judul',
                'value' => $content?->title,
            ])

            @include('admin.partials.field-input', [
                'name' => "sections.{$sectionKey}.subtitle",
                'label' => 'Subjudul',
                'value' => $content?->subtitle,
            ])
        </div>

        <div class="mt-4">
            @include('admin.partials.field-textarea', [
                'name' => "sections.{$sectionKey}.body",
                'label' => 'Isi Teks',
                'value' => $content?->body,
                'rows' => 4,
            ])
        </div>

        <div class="mt-4 grid gap-4 lg:grid-cols-2">
            <div>
                <label for="image-{{ $sectionKey }}" class="block text-sm font-bold text-[#181818]">Gambar (opsional)</label>
                <input id="image-{{ $sectionKey }}" type="file" name="sections[{{ $sectionKey }}][image]"
                       accept="image/jpeg,image/png,image/webp"
                       class="mt-1 w-full rounded-xl border border-[#181818]/20 bg-white px-3 py-2 text-sm text-[#181818] file:mr-3 file:rounded-full file:border-0 file:bg-[#124f53] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white">
                <p class="mt-1 text-xs text-[#181818]/50">JPG, PNG, atau WEBP maksimal 4 MB.</p>

                @error("sections.{$sectionKey}.image")
                    <p class="mt-1 text-xs font-semibold text-[#b3261e]">{{ $message }}</p>
                @enderror
            </div>

            @if ($content?->image_path)
                <div>
                    <p class="text-sm font-bold text-[#181818]">Gambar saat ini</p>
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($content->image_path) }}"
                         alt="Gambar {{ $sectionLabel }}" class="mt-1 h-28 w-full rounded-xl object-cover">
                </div>
            @endif
        </div>
    </section>
@endforeach
