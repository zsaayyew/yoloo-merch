<?php

namespace App\Http\Controllers\PublicSite\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSite\PriceEstimateRequest;
use App\Services\Catalog\CatalogData;
use App\Services\Catalog\ConfigurationPayloadBuilder;
use App\Services\Catalog\PriceCalculator;
use App\Services\Catalog\ProductConfigurationValidator;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class PriceEstimateController extends Controller
{
    public function __invoke(
        PriceEstimateRequest $request,
        string $product,
        CatalogData $catalog,
        PriceCalculator $calculator,
        ProductConfigurationValidator $validator,
        ConfigurationPayloadBuilder $payloadBuilder,
    ): JsonResponse|RedirectResponse|Response {
        $data = $request->validated();
        $intent = $data['intent'] ?? 'estimate';
        $options = $data['option_value_ids'] ?? [];
        $quantity = (int) $data['quantity'];
        $detail = route('products.show', ['product' => $product]).'#konfigurasi';

        try {
            $item = $catalog->product($product);
            if ($item === null) {
                return $request->expectsJson()
                    ? response()->json(['message' => 'Produk ini sudah tidak tersedia.'], 404)
                    : response()->view('public.catalog.not-found', [], 404);
            }

            if ($intent === 'quotation') {
                $payload = $payloadBuilder->build($item, $options, $quantity, forQuotation: true);
                if (! Route::has('custom-orders.create')) {
                    throw ValidationException::withMessages([
                        'product' => 'Formulir penawaran sedang tidak tersedia.',
                    ]);
                }

                return redirect()->route('custom-orders.create', [
                    'produk' => $item->slug,
                    'opsi' => array_column($payload['options'], 'option_value_id'),
                    'jumlah' => $payload['quantity'],
                ])->with('catalog_configuration', $payload);
            }

            $estimate = null;
            if ($intent === 'estimate') {
                $estimate = $calculator->estimate($item, $options, $quantity);
            } else {
                $validator->validate($item, $options, $quantity);
            }

            return $request->expectsJson()
                ? response()->json($estimate ?? ['valid' => true])
                : redirect()->to($detail)->withInput()->with('catalog_estimate', $estimate);
        } catch (ValidationException $exception) {
            $exception->redirectTo($detail);
            throw $exception;
        } catch (QueryException $exception) {
            report($exception);
            $message = 'Data produk tidak dapat dimuat. Silakan coba lagi.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 503)
                : redirect()->to($detail)->withInput()->withErrors(['product' => $message]);
        }
    }
}
