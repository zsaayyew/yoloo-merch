<?php

namespace App\Http\Controllers\PublicSite\Catalog;

use App\Http\Controllers\Controller;
use App\Services\Catalog\CatalogData;
use App\Services\Catalog\ProductConfigurationValidator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function show(string $product, CatalogData $catalog, ProductConfigurationValidator $validator): Response
    {
        try {
            $item = $catalog->product($product);
            if ($item === null) {
                return response()->view('public.catalog.not-found', [
                    'categories' => $catalog->categories()->limit(6)->get(),
                ], 404);
            }

            $category = $catalog->categories()->find($item->category_id);
            $related = $catalog->products()->where('category_id', $item->category_id)
                ->whereKeyNot($item->getKey())->orderBy('name')->orderBy('id')->limit(4)->get();
            $all = $related->concat([$item]);
            $pricing = $catalog->pricing($all);
            $groups = $catalog->groups($item);

            return response()->view('public.catalog.show', [
                'product' => $item,
                'category' => $category,
                'related' => $related,
                'images' => $catalog->images($all),
                'pricing' => $pricing,
                'priceModel' => $catalog->priceModel($item),
                'groups' => $groups,
                'values' => $catalog->values($groups),
                'minimumQuantity' => $validator->minimumQuantity($item, $pricing->get($item->getKey())),
            ]);
        } catch (QueryException $exception) {
            report($exception);

            return response()->view('public.catalog.not-found', ['loadFailed' => true], 503);
        }
    }
}
