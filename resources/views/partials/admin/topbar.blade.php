{{--
    ADM - Header dashboard (nama pengguna, role, logout)
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Warna mengikuti palet website publik (teal #14747b, ink #181818).
--}}
<header class="flex items-center justify-between gap-3 border-b border-[#181818]/10 bg-white px-4 py-3 sm:px-6">
    <div class="flex flex-wrap items-center gap-2 text-sm text-[#181818]/70">
        @auth
            <span class="font-bold text-[#181818]">{{ auth()->user()->name }}</span>
            <span class="rounded-full bg-[#DDF3F0] px-2.5 py-1 text-[11px] font-bold uppercase tracking-[0.12em] text-[#124f53]">
                {{ auth()->user()->role?->label ?? 'Tanpa role' }}
            </span>
        @endauth
    </div>

    @auth
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                    class="rounded-full border-2 border-[#181818] px-4 py-1.5 text-sm font-bold text-[#181818] transition hover:bg-[#58C0C8]/20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                Keluar
            </button>
        </form>
    @endauth
</header>
