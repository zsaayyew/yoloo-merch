{{--
    PAGE-ADM-001 - Login Admin
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/login. Warna mengikuti palet website publik (teal #14747b,
              #124f53, #58C0C8, ink #181818, krem #f8f8f8).
--}}
@extends('layouts.admin-auth')

@section('title', 'Login Admin')

@section('content')
    <div class="flex min-h-screen items-center justify-center bg-[#f8f8f8] px-4 py-10">
        <div class="w-full max-w-md rounded-3xl border-2 border-[#181818] bg-white p-6 shadow-[6px_6px_0_#181818] sm:p-8">
            <p class="inline-flex rounded-full bg-[#58C0C8]/25 px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-[#124f53]">
                Area Admin
            </p>

            <h1 class="mt-4 text-2xl font-black tracking-tight text-[#181818]">YOLOO Merch Admin</h1>
            <p class="mt-2 text-sm leading-7 text-[#181818]/70">Masuk untuk mengelola dashboard.</p>

            @if (session('status'))
                <p class="mt-4 rounded-2xl border border-[#14747b]/30 bg-[#DDF3F0] p-3 text-sm font-medium text-[#124f53]">
                    {{ session('status') }}
                </p>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-[#181818]">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           autocomplete="username"
                           class="mt-1 block w-full rounded-full border-2 border-[#181818]/20 px-4 py-2.5 text-sm text-[#181818] transition focus:border-[#14747b] focus:outline-none focus:ring-2 focus:ring-[#58C0C8]">
                    @error('email')
                        <p class="mt-1 text-sm font-medium text-[#c75d1d]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-[#181818]">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="mt-1 block w-full rounded-full border-2 border-[#181818]/20 px-4 py-2.5 text-sm text-[#181818] transition focus:border-[#14747b] focus:outline-none focus:ring-2 focus:ring-[#58C0C8]">
                    @error('password')
                        <p class="mt-1 text-sm font-medium text-[#c75d1d]">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm font-medium text-[#181818]/70">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                           class="rounded border-[#181818]/30 text-[#14747b] focus:ring-[#58C0C8]">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full rounded-full bg-[#181818] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#333] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#14747b]">
                    Masuk
                </button>
            </form>
        </div>
    </div>
@endsection
