{{--
    Component : <x-ordering.cart-badge />
    Domain    : PRD-03 ORD (Raisyah), dipakai navbar PRD-01 CP
    Catatan   : Tautan ke keranjang + jumlah item (ORD-FR-001). Jumlah dihitung di server.
    Status    : diimplementasikan.
--}}
@inject('cartService', \App\Services\Ordering\CartService::class)
@php($cartCount = $cartService->count(request()))

<a href="{{ route('cart.index') }}" {{ $attributes->merge(['class' => 'relative inline-flex items-center gap-2']) }}
    aria-label="Keranjang, {{ $cartCount }} produk">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
    </svg>
    @if ($cartCount > 0)
        <span class="absolute -right-2 -top-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-xs font-bold text-white">
            {{ $cartCount > 99 ? '99+' : $cartCount }}
        </span>
    @endif
</a>
