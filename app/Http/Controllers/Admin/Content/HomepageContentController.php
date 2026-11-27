<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomepageContentController extends Controller
{
    /**
     * Show the homepage content form (PAGE-ADM-003, ADM-FR-003).
     */
    public function edit(): View
    {
        return view('admin.content.homepage.edit');
    }
}
