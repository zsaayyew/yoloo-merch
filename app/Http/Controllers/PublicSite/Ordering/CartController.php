<?php

namespace App\Http\Controllers\PublicSite\Ordering;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSite\AddCartItemRequest;
use App\Http\Requests\PublicSite\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\Ordering\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PRD-03 ORD — Keranjang (PAGE-ORD-001).
 * Route: cart.index, cart.store, cart.update, cart.destroy.
 */
class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
    ) {}

    public function index(Request $request): View
    {
        $summary = $this->cartService->summary($this->cartService->current($request));

        return view('public.cart.index', $summary);
    }

    public function store(AddCartItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $product = Product::findOrFail($data['product_id']);

        $replace = $this->cartService->findOwnedItem($request, $data['replace_item_id'] ?? null);

        $item = $this->cartService->add(
            $request,
            $product,
            $data['option_value_ids'] ?? [],
            (int) $data['quantity'],
            $replace,
        );

        $message = $replace
            ? "Pilihan {$item->product_name_snapshot} diperbarui."
            : "{$item->product_name_snapshot} ditambahkan ke keranjang.";

        return redirect()->route('cart.index')->with('status', $message);
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($this->cartService->owns($request, $cartItem), 404);

        $this->cartService->updateQuantity($cartItem, (int) $request->validated('quantity'));

        return redirect()->route('cart.index')->with('status', 'Jumlah produk diperbarui.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($this->cartService->owns($request, $cartItem), 404);

        $name = $cartItem->product_name_snapshot;
        $this->cartService->remove($cartItem);

        return redirect()->route('cart.index')->with('status', "{$name} dihapus dari keranjang.");
    }
}
