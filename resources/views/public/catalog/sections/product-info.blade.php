{{-- CAT-FR-004 · Informasi produk · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<header>
    <h1 class="text-balance text-4xl leading-[1.06] font-black tracking-tight text-[#182F32] sm:text-5xl xl:text-6xl">{{ $product->name }}</h1>
    @if ($product->description)
        <p class="mt-4 max-w-prose leading-relaxed whitespace-pre-line text-[#465b5d]">{{ $product->description }}</p>
    @endif
</header>

@php
    $specification = is_string($product->specifications)
        ? (json_decode($product->specifications, true) ?? $product->specifications)
        : $product->specifications;
@endphp

@if (is_array($specification) && $specification !== [])
    <table class="w-full table-fixed border-y border-[#d7ddda] text-left text-sm">
        <caption class="pb-2 text-left text-lg font-bold">Spesifikasi</caption>
        <tbody class="divide-y divide-[#d7ddda]">
            @foreach ($specification as $label => $detail)
                @if (is_scalar($detail))
                    <tr>
                        <th scope="row" class="w-2/5 py-2.5 pr-3 align-top font-medium text-[#465b5d]">{{ is_int($label) ? 'Spesifikasi ' . $loop->iteration : $label }}</th>
                        <td class="py-2.5 align-top font-semibold">{{ $detail }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@elseif (is_scalar($specification) && $specification !== '')
    <div>
        <h2 class="text-lg font-bold">Spesifikasi</h2>
        <p class="mt-2 whitespace-pre-line text-[#465b5d]">{{ $specification }}</p>
    </div>
@endif
