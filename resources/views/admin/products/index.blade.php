{{--
    PAGE-ADM-004 - Produk
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Route /admin/products (ADM-FR-004).
--}}
@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
    <div class="space-y-6 bg-[#f8f8f8] p-4 sm:p-6">
        @include('admin.partials.flash')

        <x-admin.page-header title="Produk"
            description="Produk hanya tampil di katalog publik bila berstatus aktif dan kategorinya aktif.">
            <x-slot:actions>
                <a href="{{ route('admin.products.create') }}"
                   class="inline-flex min-h-10 items-center justify-center rounded-full bg-[#181818] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#333]">
                    Tambah Produk
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="GET" action="{{ route('admin.products.index') }}"
              class="grid gap-3 rounded-3xl border border-[#181818]/10 bg-white p-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label for="q" class="block text-sm font-bold text-[#181818]">Cari produk</label>
                <input id="q" type="search" name="q" value="{{ $search }}"
                       class="mt-1 w-full rounded-xl border border-[#181818]/20 px-3 py-2 text-sm">
            </div>

            @include('admin.partials.field-select', [
                'name' => 'category',
                'label' => 'Kategori',
                'value' => $categoryId ?: '',
                'options' => $categories->pluck('name', 'id')->all(),
                'placeholder' => 'Semua kategori',
            ])

            @include('admin.partials.field-select', [
                'name' => 'status',
                'label' => 'Status',
                'value' => $status,
                'options' => \App\Models\Product::STATUSES,
                'placeholder' => 'Semua status',
            ])

            <div class="flex items-end">
                <button type="submit"
                        class="inline-flex min-h-10 w-full items-center justify-center rounded-full border-2 border-[#181818] px-4 py-2 text-sm font-bold text-[#181818] hover:bg-[#58C0C8]/20">
                    Terapkan Filter
                </button>
            </div>
        </form>

        <section class="overflow-hidden rounded-3xl border border-[#181818]/10 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#181818]/10 text-sm">
                    <thead class="bg-[#f8f8f8]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Model Harga</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#181818]/50">Gambar / Opsi</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-[#181818]/50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#181818]/5">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-bold text-[#181818]">{{ $product->name }}</p>
                                    <p class="font-mono text-xs text-[#181818]/50">{{ $product->slug }}</p>
                                </td>
                                <td class="px-4 py-3 text-[#181818]/70">{{ $product->category?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-[#181818]/70">
                                    {{ \App\Services\Catalog\CatalogData::PRICE_MODELS[$product->price_model] ?? $product->price_model }}
                                </td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                        'bg-[#DDF3F0] text-[#124f53]' => $product->status === \App\Models\Product::STATUS_ACTIVE,
                                        'bg-[#E87830]/20 text-[#181818]' => $product->status === \App\Models\Product::STATUS_DRAFT,
                                        'bg-[#181818]/10 text-[#181818]/60' => $product->status === \App\Models\Product::STATUS_ARCHIVED,
                                    ])>
                                        {{ $product->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-[#181818]/60">
                                    {{ $product->images_count }} gambar &middot; {{ $product->option_groups_count }} grup opsi
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center justify-end gap-3">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="text-sm font-bold text-[#181818] hover:underline">Ubah</a>
                                        <a href="{{ route('admin.products.options.index', $product) }}"
                                           class="text-sm font-bold text-[#124f53] hover:underline">Opsi</a>
                                        <a href="{{ route('admin.pricing.create', $product) }}"
                                           class="text-sm font-bold text-[#124f53] hover:underline">Harga</a>

                                        @if ($product->status !== \App\Models\Product::STATUS_ARCHIVED)
                                            <form method="POST" action="{{ route('admin.products.archive', $product) }}"
                                                  onsubmit="return confirm('Arsipkan produk ini? Produk tidak akan tampil di katalog.')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm font-bold text-[#b3261e] hover:underline">Arsipkan</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-[#181818]/50">
                                    Tidak ada produk yang cocok dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>{{ $products->links() }}</div>
    </div>
@endsection
