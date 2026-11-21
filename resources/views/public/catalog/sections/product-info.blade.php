{{-- CAT-FR-004 · Informasi produk · PRD-02 CAT (Haidir) · Status: diimplementasikan. --}}
<header>
    <h1 class="text-4xl font-bold tracking-tight text-zinc-950">{{ $product->name }}</h1>
    @if ($product->description)
        <p class="mt-4 whitespace-pre-line leading-relaxed text-zinc-700">{{ $product->description }}</p>
    @endif
</header>

@php
    $specification = is_string($product->specifications)
        ? (json_decode($product->specifications, true) ?? $product->specifications)
        : $product->specifications;
@endphp

@if (is_array($specification) && $specification !== [])
    <table class="w-full text-left text-sm">
        <caption class="pb-2 text-left text-lg font-semibold">Spesifikasi</caption>
        <tbody class="divide-y divide-zinc-200">
            @foreach ($specification as $label => $detail)
                @if (is_scalar($detail))
                    <tr>
                        <th scope="row" class="py-2 pr-3">{{ is_int($label) ? 'Spesifikasi ' . $loop->iteration : $label }}</th>
                        <td class="py-2">{{ $detail }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@elseif (is_scalar($specification) && $specification !== '')
    <div>
        <h2 class="text-lg font-semibold">Spesifikasi</h2>
        <p class="mt-2 whitespace-pre-line text-zinc-700">{{ $specification }}</p>
    </div>
@endif