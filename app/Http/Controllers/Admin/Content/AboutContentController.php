<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AboutContentController extends Controller
{
    /**
     * Show the about page content form (PAGE-ADM-003, ADM-FR-003).
     */
    public function edit(): View
    {
        return view('admin.content.about.edit');
    }
}
