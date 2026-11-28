<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\OptionGroup;
use App\Models\OptionValue;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductOptionController extends Controller
{
    /**
     * Show the option groups and values of the given product (ADM-FR-005).
     */
    public function index(Product $product): View
    {
        $product->load(['optionGroups.values']);

        return view('admin.products.options.index', [
            'product' => $product,
            'values' => $product->optionGroups->flatMap->values->sortBy('label')->values(),
        ]);
    }

    /**
     * Store a new option group for the given product.
     */
    public function storeGroup(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $product->optionGroups()->create([
            'name' => $data['name'],
            'is_required' => $request->boolean('is_required'),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        return redirect()
            ->route('admin.products.options.index', $product)
            ->with('status', 'Grup opsi berhasil ditambahkan.');
    }

    /**
     * Remove the given option group.
     */
    public function destroyGroup(Product $product, OptionGroup $group): RedirectResponse
    {
        abort_unless($group->product_id === $product->id, 404);

        $group->delete();

        return redirect()
            ->route('admin.products.options.index', $product)
            ->with('status', 'Grup opsi berhasil dihapus.');
    }

    /**
     * Store a new value inside the given option group.
     */
    public function storeValue(Request $request, Product $product, OptionGroup $group): RedirectResponse
    {
        abort_unless($group->product_id === $product->id, 404);

        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'forbidden_combinations' => ['nullable', 'array'],
            'forbidden_combinations.*' => ['integer', 'exists:option_values,id'],
        ]);

        $group->values()->create([
            'label' => $data['label'],
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'forbidden_combinations' => array_values(array_map('intval', $data['forbidden_combinations'] ?? [])),
        ]);

        return redirect()
            ->route('admin.products.options.index', $product)
            ->with('status', 'Nilai opsi berhasil ditambahkan.');
    }

    /**
     * Toggle the availability of the given option value.
     */
    public function toggleValue(Product $product, OptionGroup $group, OptionValue $value): RedirectResponse
    {
        abort_unless($group->product_id === $product->id && $value->option_group_id === $group->id, 404);

        $value->update(['is_active' => ! $value->is_active]);

        return redirect()
            ->route('admin.products.options.index', $product)
            ->with('status', $value->is_active
                ? 'Nilai opsi "'.$value->label.'" diaktifkan.'
                : 'Nilai opsi "'.$value->label.'" dinonaktifkan.');
    }

    /**
     * Remove the given option value.
     */
    public function destroyValue(Product $product, OptionGroup $group, OptionValue $value): RedirectResponse
    {
        abort_unless($group->product_id === $product->id && $value->option_group_id === $group->id, 404);

        $value->delete();

        return redirect()
            ->route('admin.products.options.index', $product)
            ->with('status', 'Nilai opsi berhasil dihapus.');
    }
}
