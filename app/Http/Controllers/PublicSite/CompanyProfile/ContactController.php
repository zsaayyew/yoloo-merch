<?php

namespace App\Http\Controllers\PublicSite\CompanyProfile;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('public.contact.index');
    }
}
