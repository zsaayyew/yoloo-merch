{{-- PAGE-CP-001: disembunyikan sampai ADM menyediakan data kolaborasi yang published. --}}
@if (!empty($collaborations) && $collaborations->isNotEmpty())
    <section aria-labelledby="home-collaboration-title" class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div><p class="text-sm font-extrabold uppercase tracking-[0.16em] text-[#c75d1d]">Berkarya bersama</p><h2 id="home-collaboration-title" class="mt-2 text-3xl font-black text-[#181818]">Kolaborasi</h2></div>
                <a href="{{ route('reviews') }}#collaborations" class="font-bold text-[#124f53] underline decoration-[#58C0C8] decoration-2 underline-offset-4">Lihat kolaborasi <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>
@endif
