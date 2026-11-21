<?php

namespace App\Http\Controllers\PublicSite\CompanyProfile;

use App\Http\Controllers\Controller;
use App\Services\Catalog\CatalogData;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(CatalogData $catalog): View
    {
        $products = new Collection;
        $images = new Collection;
        $pricing = new Collection;

        try {
            // CAT's public query applies active product and active category rules.
            $products = $catalog->products()->orderBy('name')->orderBy('id')->limit(4)->get();
            $images = $catalog->images($products)->map(fn (Collection $productImages) => $productImages->first());
            $pricing = $catalog->pricing($products);
        } catch (QueryException $exception) {
            // Keep the homepage usable before local CAT migrations/data are available.
            report($exception);
        }

        return view('public.home.index', [
            'featuredProducts' => $products,
            'featuredImages' => $images,
            'featuredPricing' => $pricing,
            'collaborations' => new Collection,
            'galleryPreview' => new Collection,
            'testimonialPreview' => new Collection,
        ]);
    }
}
