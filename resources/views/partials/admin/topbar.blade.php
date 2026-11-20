{{--
    ADM - Header dashboard (nama pengguna, role, logout)
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
--}}
<header class="flex items-center justify-between border-b border-gray-200 bg-white px-6 py-3">
    <div class="text-sm text-gray-600">
        @auth
            <span class="font-medium text-gray-900">{{ auth()->user()->name }}</span>
            <span class="ml-1 rounded bg-gray-100 px-2 py-0.5 text-xs uppercase tracking-wide text-gray-600">
                {{ auth()->user()->role?->label ?? 'Tanpa role' }}
            </span>
        @endauth
    </div>

    @auth
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-900">Keluar</button>
        </form>
    @endauth
</header>
