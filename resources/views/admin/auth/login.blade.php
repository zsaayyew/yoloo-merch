{{--
    PAGE-ADM-001 - Login Admin
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/login.
--}}
@extends('layouts.admin-auth')

@section('title', 'Login Admin')

@section('content')
    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md rounded-lg bg-white p-8 shadow">
            <h1 class="text-xl font-semibold text-gray-900">YOLOO Merch Admin</h1>
            <p class="mt-1 text-sm text-gray-600">Masuk untuk mengelola dashboard.</p>

            @if (session('status'))
                <p class="mt-4 rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                    {{ session('status') }}
                </p>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           autocomplete="username"
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                           class="rounded border-gray-300 text-gray-900 focus:ring-gray-500">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full rounded bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                    Masuk
                </button>
            </form>
        </div>
    </div>
@endsection
