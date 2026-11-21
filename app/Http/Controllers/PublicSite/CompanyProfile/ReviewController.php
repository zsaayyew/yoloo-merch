<?php

namespace App\Http\Controllers\PublicSite\CompanyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        // No query is issued against the unimplemented ADM schema.
        return view('public.reviews.index', [
            'testimonials' => new Collection,
            'collaborations' => new Collection,
        ]);
    }
}
