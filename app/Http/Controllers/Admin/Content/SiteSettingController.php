<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    /**
     * Show the business profile and pickup settings form (PAGE-ADM-003, ADM-FR-003).
     */
    public function edit(): View
    {
        return view('admin.content.settings.edit');
    }
}
