<?php

namespace App\Services\Catalog;

use App\Models\PricingConfiguration;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class PriceCalculator
{
    public function __construct(
        private CatalogData $catalog,
        private ProductConfigurationValidator $validator,
    ) {}

    public function estimate(Product $product, array $optionValueIds, int $quantity): array
    {
        $pricing = $this->catalog->pricing($product->newCollection([$product]))->get($product->getKey());
        $options = $this->validator->validatedOptions($product, $optionValueIds, $quantity, $pricing);

        return $this->estimateValidated($product, $pricing, array_column($options, 'option_value_id'), $quantity);
    }

    public function estimateValidated(
        Product $product,
        ?PricingConfiguration $pricing,
        array $optionValueIds,
        int $quantity,
    ): array {
        $model = $this->catalog->priceModel($product);
        if (! in_array($model, ['fixed', 'starting_from', 'calculator'], true)
            || ! $this->catalog->pricingMatches($product, $pricing)) {
            $this->unavailable();
        }

        $config = $this->catalog->json($pricing->config);
        if ($config === null && ($model === 'calculator' || $pricing->config !== null)) {
            $this->unavailable();
        }

        $config ??= [];
        $base = $this->money($pricing->base_price);
        $surcharge = $model === 'calculator' ? $this->surcharge($config, $optionValueIds) : 0;
        $tier = $this->tier($config, $quantity);
        $discount = $this->money($tier['discount_per_unit'] ?? 0);
        $extras = $this->money($config['extra_fee'] ?? 0);
        $minimumTotal = $this->money($config['minimum_total'] ?? 0);

        $unitBeforeDiscount = $this->money($base + $surcharge);
        if ($discount > $unitBeforeDiscount) {
            $this->unavailable();
        }

        $unit = $unitBeforeDiscount - $discount;
        $subtotal = $this->money($unit * $quantity);
        $totalBeforeMinimum = $this->money($subtotal + $extras);
        $total = max($minimumTotal, $totalBeforeMinimum);

        // Pertahankan ketepatan nilai Rupiah setelah respons JSON dibaca JavaScript.
        if ($total > 9_007_199_254_740_991) {
            $this->unavailable('Jumlah di luar rentang tarif. Silakan minta penawaran.');
        }

        return [
            'unit_price' => $unit,
            'total' => $total,
            'breakdown' => [
                'base_price' => $base,
                'option_surcharge' => $surcharge,
                'quantity_tier' => $tier,
                'discount_per_unit' => $discount,
                'extra_fee' => $extras,
                'minimum_total' => $minimumTotal,
                'minimum_total_adjustment' => max(0, $minimumTotal - $totalBeforeMinimum),
            ],
            'is_estimate' => true,
        ];
    }

    private function surcharge(array $config, array $ids): int
    {
        $surcharges = $config['option_surcharges'] ?? [];
        if (! is_array($surcharges)) {
            $this->unavailable();
        }

        $total = 0;
        foreach ($ids as $id) {
            $total = $this->money($total + $this->money($surcharges[$id] ?? 0));
        }

        if (! array_key_exists('matrix', $config)) {
            return $total;
        }

        if (! is_array($config['matrix'])) {
            $this->unavailable();
        }

        sort($ids);
        $matches = [];
        foreach ($config['matrix'] as $row) {
            if (! is_array($row) || ! is_array($row['option_value_ids'] ?? null)) {
                $this->unavailable();
            }

            $matrixIds = array_map(fn ($id) => $this->money($id), $row['option_value_ids']);
            sort($matrixIds);
            if ($ids === $matrixIds) {
                $matches[] = $this->money($row['additional_price'] ?? null);
            }
        }

        if (count($matches) !== 1) {
            $this->unavailable('Tarif untuk kombinasi ini belum tersedia. Silakan minta penawaran.');
        }

        return $this->money($total + $matches[0]);
    }

    private function tier(array $config, int $quantity): ?array
    {
        $tiers = $config['quantity_tiers'] ?? [];
        if (! is_array($tiers)) {
            $this->unavailable();
        }

        $minimum = $this->money($config['minimum_quantity'] ?? 1);
        $maximum = $this->money($config['maximum_quantity'] ?? PHP_INT_MAX);
        if ($minimum < 1 || $maximum < $minimum) {
            $this->unavailable();
        }

        if ($quantity > $maximum) {
            $this->unavailable('Jumlah di luar rentang tarif. Silakan minta penawaran.');
        }

        $validated = [];
        foreach ($tiers as $tier) {
            if (! is_array($tier)) {
                $this->unavailable();
            }

            $min = $this->money($tier['min'] ?? null);
            $max = isset($tier['max']) ? $this->money($tier['max']) : null;
            if ($min < 1 || ($max !== null && $min > $max)) {
                $this->unavailable();
            }

            $validated[] = [
                'min' => $min,
                'max' => $max,
                'discount_per_unit' => $this->money($tier['discount_per_unit'] ?? null),
            ];
        }

        usort($validated, fn (array $a, array $b) => $a['min'] <=> $b['min']);
        $previousMax = 0;
        $match = null;

        foreach ($validated as $tier) {
            if ($tier['min'] <= $previousMax) {
                $this->unavailable();
            }

            $previousMax = $tier['max'] ?? PHP_INT_MAX;
            if ($quantity >= $tier['min'] && $quantity <= $previousMax) {
                $match = $tier;
            }
        }

        if ($validated !== [] && $quantity > $previousMax) {
            $this->unavailable('Jumlah di luar rentang tarif. Silakan minta penawaran.');
        }

        return $match;
    }

    private function money(mixed $value): int
    {
        return $this->catalog->rupiah($value) ?? $this->unavailable();
    }

    private function unavailable(string $message = 'Tarif belum tersedia. Silakan minta penawaran.'): never
    {
        throw ValidationException::withMessages(['pricing' => $message]);
    }
}
