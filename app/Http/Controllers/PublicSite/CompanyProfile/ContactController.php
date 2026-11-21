<?php

namespace App\Http\Controllers\PublicSite\CompanyProfile;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        // ADM's SiteSetting schema is not available; do not publish unverified initial contact values.
        return view('public.contact.index', [
            'contactChannels' => [],
            'businessLocation' => [],
        ]);
    }
}
