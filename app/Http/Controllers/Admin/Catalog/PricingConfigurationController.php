<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Services\Catalog\CatalogData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingConfigurationController extends Controller
{
    /**
     * Display the pricing configurations of every product (PAGE-ADM-004, ADM-FR-006).
     */
    public function index(): View
    {
        $products = Product::query()
            ->with(['category', 'optionGroups.values'])
            ->with(['pricingConfigurations' => fn ($query) => $query->limit(5)])
            ->orderBy('name')
            ->get();

        return view('admin.pricing.index', [
            'products' => $products,
            'priceModels' => CatalogData::PRICE_MODELS,
        ]);
    }

    /**
     * Show the form for creating a new pricing version.
     */
    public function create(Product $product): View
    {
        $product->load(['optionGroups.values']);
        $latest = $product->pricingConfigurations()->first();

        return view('admin.pricing.form', [
            'product' => $product,
            'pricing' => null,
            'priceModels' => CatalogData::PRICE_MODELS,
            'config' => (array) ($latest?->config ?? []),
            'basePrice' => $latest?->base_price,
            'effectiveDate' => now()->toDateString(),
            'version' => ((int) $product->pricingConfigurations()->max('version')) + 1,
        ]);
    }

    /**
     * Store a new pricing version for the given product.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request);

        $product->pricingConfigurations()->create([
            'price_model' => $data['price_model'],
            'status' => $data['status'],
            'effective_date' => $data['effective_date'],
            'version' => ((int) $product->pricingConfigurations()->max('version')) + 1,
            'base_price' => $data['base_price'],
            'config' => $data['config'],
        ]);

        return redirect()
            ->route('admin.pricing.index')
            ->with('status', 'Versi harga baru untuk "'.$product->name.'" berhasil disimpan.');
    }

    /**
     * Show the form for editing the given pricing configuration.
     */
    public function edit(PricingConfiguration $pricing): View
    {
        $pricing->load('product.optionGroups.values');

        return view('admin.pricing.form', [
            'product' => $pricing->product,
            'pricing' => $pricing,
            'priceModels' => CatalogData::PRICE_MODELS,
            'config' => (array) ($pricing->config ?? []),
            'basePrice' => $pricing->base_price,
            'effectiveDate' => $pricing->effective_date?->toDateString(),
            'version' => $pricing->version,
        ]);
    }

    /**
     * Update the given pricing configuration.
     */
    public function update(Request $request, PricingConfiguration $pricing): RedirectResponse
    {
        $data = $this->validated($request);

        $pricing->update([
            'price_model' => $data['price_model'],
            'status' => $data['status'],
            'effective_date' => $data['effective_date'],
            'base_price' => $data['base_price'],
            'config' => $data['config'],
        ]);

        return redirect()
            ->route('admin.pricing.index')
            ->with('status', 'Konfigurasi harga versi '.$pricing->version.' berhasil diperbarui.');
    }

    /**
     * Toggle the status of the given pricing configuration.
     */
    public function toggle(PricingConfiguration $pricing): RedirectResponse
    {
        $isActive = $pricing->status === PricingConfiguration::STATUS_ACTIVE;

        $pricing->update([
            'status' => $isActive ? PricingConfiguration::STATUS_INACTIVE : PricingConfiguration::STATUS_ACTIVE,
        ]);

        return redirect()
            ->route('admin.pricing.index')
            ->with('status', $isActive
                ? 'Konfigurasi harga dinonaktifkan.'
                : 'Konfigurasi harga diaktifkan.');
    }

    /**
     * Validate the pricing payload and build the calculator config.
     *
     * @return array{price_model: string, status: string, effective_date: string, base_price: int, config: array<string, mixed>}
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'price_model' => ['required', 'string', 'in:'.implode(',', array_keys(CatalogData::PRICE_MODELS))],
            'status' => ['required', 'in:active,inactive'],
            'effective_date' => ['required', 'date'],
            'base_price' => ['required', 'integer', 'min:0', 'max:999999999999'],
            'minimum_quantity' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'maximum_quantity' => ['nullable', 'integer', 'min:1', 'max:1000000', 'gte:minimum_quantity'],
            'extra_fee' => ['nullable', 'integer', 'min:0', 'max:999999999999'],
            'minimum_total' => ['nullable', 'integer', 'min:0', 'max:999999999999'],
            'tiers' => ['nullable', 'array', 'max:10'],
            'tiers.*.min' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'tiers.*.max' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'tiers.*.discount_per_unit' => ['nullable', 'integer', 'min:0', 'max:999999999999'],
            'surcharges' => ['nullable', 'array'],
            'surcharges.*' => ['nullable', 'integer', 'min:0', 'max:999999999999'],
            'matrix' => ['nullable', 'array', 'max:10'],
            'matrix.*.ids' => ['nullable', 'array'],
            'matrix.*.ids.*' => ['integer', 'exists:option_values,id'],
            'matrix.*.additional_price' => ['nullable', 'integer', 'min:0', 'max:999999999999'],
        ]);

        $tiers = collect($data['tiers'] ?? [])
            ->filter(fn ($row) => filled($row['min'] ?? null))
            ->map(fn ($row) => [
                'min' => (int) $row['min'],
                'max' => filled($row['max'] ?? null) ? (int) $row['max'] : null,
                'discount_per_unit' => (int) ($row['discount_per_unit'] ?? 0),
            ])
            ->values()
            ->all();

        $surcharges = collect($data['surcharges'] ?? [])
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (int) $value)
            ->all();

        $matrix = collect($data['matrix'] ?? [])
            ->filter(fn ($row) => filled($row['additional_price'] ?? null) && filled($row['ids'] ?? null))
            ->map(fn ($row) => [
                'option_value_ids' => array_values(array_map('intval', $row['ids'])),
                'additional_price' => (int) $row['additional_price'],
            ])
            ->values()
            ->all();

        $config = array_filter([
            'minimum_quantity' => filled($data['minimum_quantity'] ?? null) ? (int) $data['minimum_quantity'] : null,
            'maximum_quantity' => filled($data['maximum_quantity'] ?? null) ? (int) $data['maximum_quantity'] : null,
            'extra_fee' => filled($data['extra_fee'] ?? null) ? (int) $data['extra_fee'] : null,
            'minimum_total' => filled($data['minimum_total'] ?? null) ? (int) $data['minimum_total'] : null,
            'quantity_tiers' => $tiers,
            'option_surcharges' => $surcharges,
            'matrix' => $matrix,
        ], fn ($value) => $value !== null && $value !== []);

        return [
            'price_model' => $data['price_model'],
            'status' => $data['status'],
            'effective_date' => $data['effective_date'],
            'base_price' => (int) $data['base_price'],
            'config' => $config,
        ];
    }
}
