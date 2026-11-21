<?php

namespace App\Http\Controllers\PublicSite\CompanyProfile;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        // ADM has not supplied the published About Content schema/data yet.
        return view('public.about.index', ['aboutContent' => []]);
    }
}
