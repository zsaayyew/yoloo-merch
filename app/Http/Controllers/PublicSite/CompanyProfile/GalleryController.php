<?php

namespace App\Http\Controllers\PublicSite\CompanyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        // ADM has not implemented GalleryItem/GalleryCategory fields or publication scopes.
        return view('public.gallery.index', [
            'galleryCategories' => new Collection,
            'galleryItems' => new Collection,
        ]);
    }
}
