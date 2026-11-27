<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MediaController extends Controller
{
    /**
     * Display a listing of the media (PAGE-ADM-005, ADM-FR-008).
     */
    public function index(): View
    {
        return view('admin.media.index');
    }
}
