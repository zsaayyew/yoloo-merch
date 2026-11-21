<?php

namespace App\Services\Catalog;

use App\Models\Product;
use Illuminate\Validation\ValidationException;

class ConfigurationPayloadBuilder
{
    public function __construct(
        private CatalogData $catalog,
        private ProductConfigurationValidator $validator,
        private PriceCalculator $calculator,
    ) {}

    public function build(Product $product, array $optionValueIds, int $quantity, bool $forQuotation = false): array
    {
        $current = $this->catalog->products()->find($product->getKey());

        if ($current === null) {
            throw ValidationException::withMessages(['product' => 'Produk ini sudah tidak tersedia.']);
        }

        $pricing = $this->catalog->pricing($current->newCollection([$current]))->get($current->getKey());
        $options = $this->validator->validatedOptions($current, $optionValueIds, $quantity, $pricing);
        $model = $this->catalog->priceModel($current);
        $estimate = null;

        if (! $forQuotation) {
            if (! in_array($model, ['fixed', 'starting_from', 'calculator'], true) || ! $current->allows_direct_purchase) {
                throw ValidationException::withMessages([
                    'product' => 'Produk ini dilayani melalui permintaan penawaran.',
                ]);
            }

            $estimate = $this->calculator->estimateValidated(
                $current, $pricing, array_column($options, 'option_value_id'), $quantity,
            );
        }

        return [
            'product_id' => (int) $current->getKey(),
            'product_name_snapshot' => $current->name,
            'price_model' => $model,
            'options' => $options,
            'quantity' => $quantity,
            'estimated_unit_price' => $estimate['unit_price'] ?? null,
            'estimated_total' => $estimate['total'] ?? null,
            'pricing_version' => $estimate === null ? null : (int) $pricing->version,
        ];
    }
}