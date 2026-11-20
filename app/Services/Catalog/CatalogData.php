<?php

namespace App\Services\Catalog;

use App\Models\Category;
use App\Models\OptionGroup;
use App\Models\OptionValue;
use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Models\ProductImage;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CatalogData
{
    public const PRICE_MODELS = [
        'fixed' => 'Harga tetap',
        'starting_from' => 'Harga mulai dari',
        'calculator' => 'Kalkulator estimasi',
        'quotation' => 'Minta penawaran',
    ];

    public function categories(): Builder
    {
        $query = Category::query();

        return ($query->hasNamedScope('active') ? $query->active() : $query->where('is_active', true))
            ->orderBy('sort_order')->orderBy('name')->orderBy('id');
    }

    public function products(): Builder
    {
        $query = Product::query();

        return ($query->hasNamedScope('active') ? $query->active() : $query->where('status', 'active'))
            ->whereIn('category_id', $this->categories()->reorder()->select('id'));
    }

    public function product(string $slug): ?Product
    {
        return $this->products()->where('slug', $slug)->first();
    }

    public function images(Collection $products): Collection
    {
        return ProductImage::query()->whereIn('product_id', $products->modelKeys())
            ->orderByDesc('is_primary')->orderBy('sort_order')->orderBy('id')
            ->get()->groupBy('product_id');
    }

    public function groups(Product $product): Collection
    {
        return OptionGroup::query()->where('product_id', $product->getKey())
            ->orderBy('sort_order')->orderBy('id')->get();
    }

    public function values(Collection $groups): Collection
    {
        return OptionValue::query()->whereIn('option_group_id', $groups->modelKeys())
            ->where('is_active', true)->orderBy('sort_order')->orderBy('id')
            ->get()->groupBy('option_group_id');
    }

    public function pricing(Collection $products): Collection
    {
        return PricingConfiguration::query()->whereIn('product_id', $products->modelKeys())
            ->where('status', 'active')
            ->where('effective_date', '<=', now())
            ->orderByDesc('effective_date')->orderByDesc('version')->orderByDesc('id')
            ->get()->unique('product_id')->keyBy('product_id');
    }

    public function priceModel(Product $product): string
    {
        $model = $product->getAttributes()['price_model'] ?? null;
        $value = $model instanceof BackedEnum ? $model->value : $model;

        return is_string($value) && isset(self::PRICE_MODELS[$value]) ? $value : 'quotation';
    }

    public function displayPrice(Product $product, ?PricingConfiguration $pricing): ?int
    {
        if (! in_array($this->priceModel($product), ['fixed', 'starting_from'], true)) {
            return null;
        }

        return $this->pricingMatches($product, $pricing)
            ? $this->rupiah($pricing->base_price)
            : null;
    }

    public function pricingMatches(Product $product, ?PricingConfiguration $pricing): bool
    {
        $model = $pricing?->getAttributes()['price_model'] ?? null;

        return ($model instanceof BackedEnum ? $model->value : $model) === $this->priceModel($product);
    }

    public function json(mixed $value): ?array
    {
        $decoded = is_string($value) ? json_decode($value, true) : $value;

        return is_array($decoded) ? $decoded : null;
    }

    public function rupiah(mixed $value): ?int
    {
        if (! is_int($value) && ! is_string($value)) {
            return null;
        }

        $integer = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

        return $integer === false ? null : $integer;
    }
}