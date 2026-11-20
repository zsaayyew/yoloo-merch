<?php

namespace App\Http\Controllers\PublicSite\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Catalog\CatalogData;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class CatalogController extends Controller
{
    public function __construct(private CatalogData $catalog) {}

    public function index(Request $request): Response
    {
        return $this->listing($request);
    }

    public function category(Request $request, string $category): Response
    {
        return $this->listing($request, $category);
    }

    private function listing(Request $request, ?string $slug = null): Response
    {
        try {
            $categories = $this->catalog->categories()->get();
            $category = $slug === null ? null : $categories->firstWhere('slug', $slug);

            if ($slug !== null && $category === null) {
                return response()->view('public.catalog.not-found', [
                    'categories' => $categories->take(6),
                    'heading' => 'Kategori tidak tersedia',
                ], 404);
            }

            return $this->results($request, $categories, $category);
        } catch (QueryException $exception) {
            report($exception);

            return response()->view('public.catalog.index', ['loadFailed' => true], 503);
        }
    }

    private function results(Request $request, Collection $categories, ?Category $category): Response
    {
        $keyword = $this->parameter($request, 'q', 100);
        $categorySlug = $category?->slug ?? $this->parameter($request, 'category');
        $filteredCategory = $categories->firstWhere('slug', $categorySlug);
        $model = $this->parameter($request, 'price_model');
        $priceModels = CatalogData::PRICE_MODELS;
        $model = isset($priceModels[$model]) ? $model : '';
        $query = $this->catalog->products()->orderBy('name')->orderBy('id');

        if ($filteredCategory !== null) {
            $query->where('category_id', $filteredCategory->id);
        }

        if ($model === 'quotation') {
            $query->where(fn ($builder) => $builder->where('price_model', 'quotation')
                ->orWhereNull('price_model')->orWhereNotIn('price_model', array_keys($priceModels)));
        } elseif ($model !== '') {
            $query->where('price_model', $model);
        }

        if ($keyword !== '') {
            $pattern = '%'.strtr(mb_strtolower($keyword), ['!' => '!!', '%' => '!%', '_' => '!_']).'%';
            $query->where(fn ($builder) => $builder
                ->whereRaw("LOWER(name) LIKE ? ESCAPE '!'", [$pattern])
                ->orWhereRaw("LOWER(description) LIKE ? ESCAPE '!'", [$pattern]));
        }

        $filters = array_filter([
            'q' => $keyword,
            'category' => $category === null ? $filteredCategory?->slug : null,
            'price_model' => $model,
        ], fn ($value) => $value !== null && $value !== '');
        $products = $query->paginate(12)->appends($filters);
        $items = $products->getCollection();

        return response()->view($category === null ? 'public.catalog.index' : 'public.catalog.category', [
            'categories' => $categories,
            'category' => $category,
            'categoryIgnored' => $categorySlug !== '' && $filteredCategory === null,
            'filters' => $filters,
            'products' => $products,
            'images' => $this->catalog->images($items),
            'pricing' => $this->catalog->pricing($items),
        ]);
    }

    private function parameter(Request $request, string $key, int $max = 120): string
    {
        $value = $request->query($key);
        if (! is_string($value)) {
            return '';
        }

        $clean = preg_replace('/[\x00-\x1F\x7F]/u', '', strip_tags($value));

        return mb_substr(trim($clean ?? ''), 0, $max);
    }
}