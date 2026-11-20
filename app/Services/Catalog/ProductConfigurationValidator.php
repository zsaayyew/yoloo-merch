<?php

namespace App\Services\Catalog;

use App\Models\PricingConfiguration;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ProductConfigurationValidator
{
    public function __construct(private CatalogData $catalog) {}

    public function validate(Product $product, array $optionValueIds, int $quantity): void
    {
        $pricing = $this->catalog->pricing($product->newCollection([$product]))->get($product->getKey());
        $this->validatedOptions($product, $optionValueIds, $quantity, $pricing);
    }

    public function validatedOptions(
        Product $product,
        array $optionValueIds,
        int $quantity,
        ?PricingConfiguration $pricing,
    ): array {
        $groups = $this->catalog->groups($product);
        $values = $this->catalog->values($groups)->collapse()->keyBy('id');
        $minimum = $this->minimumQuantity($product, $pricing);
        $errors = $quantity < $minimum ? ['quantity' => "Jumlah minimal {$minimum} pcs."] : [];
        $selected = collect();

        foreach ($optionValueIds as $id) {
            $id = $this->catalog->rupiah($id);
            $value = $id === null ? null : $values->get($id);

            if ($value === null) {
                $errors['option_value_ids'] = 'Ada pilihan yang sudah tidak tersedia untuk produk ini.';
                continue;
            }

            $key = "option_group_{$value->option_group_id}";
            if ($selected->has($value->option_group_id)) {
                $errors[$key] = 'Pilih satu nilai untuk setiap grup opsi.';
                continue;
            }

            $selected->put($value->option_group_id, $value);
        }

        foreach ($groups as $group) {
            if ($group->is_required && ! $selected->has($group->id)) {
                $errors["option_group_{$group->id}"] = "Pilih {$group->name}.";
            }
        }

        $errors += $this->combinationErrors($groups, $selected);
        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        return $groups->filter(fn ($group) => $selected->has($group->id))
            ->map(fn ($group) => [
                'group' => $group->name,
                'value' => $selected->get($group->id)->label,
                'option_value_id' => (int) $selected->get($group->id)->id,
            ])->values()->all();
    }

    public function minimumQuantity(Product $product, ?PricingConfiguration $pricing): int
    {
        if (! $this->catalog->pricingMatches($product, $pricing)) {
            return 1;
        }

        $config = $this->catalog->json($pricing->config);

        return max(1, $this->catalog->rupiah($config['minimum_quantity'] ?? null) ?? 1);
    }

    private function combinationErrors(Collection $groups, Collection $selected): array
    {
        $errors = [];
        $ids = $selected->keyBy('id');

        foreach ($selected as $source) {
            foreach ($this->catalog->json($source->forbidden_combinations) ?? [] as $combination) {
                $conflicts = is_array($combination) ? $combination : [$combination];

                if ($conflicts === [] || ! collect($conflicts)->every(
                    fn ($id) => $this->catalog->rupiah($id) !== null && $ids->has((int) $id),
                )) {
                    continue;
                }

                $affectedGroups = collect($conflicts)
                    ->map(fn ($id) => $ids->get((int) $id)->option_group_id)
                    ->push($source->option_group_id)->unique();

                $names = $groups->whereIn('id', $affectedGroups)->pluck('name')->join(', ');
                foreach ($affectedGroups as $groupId) {
                    $errors["option_group_{$groupId}"] = "Kombinasi {$names} tidak tersedia.";
                }
            }
        }

        return $errors;
    }
}