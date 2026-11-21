<?php

namespace App\Http\Controllers\PublicSite\CompanyProfile;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('public.home.index');
    }
}
