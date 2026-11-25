<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PricingConfigurationController extends Controller
{
    /**
     * Display a listing of the pricing configurations (PAGE-ADM-004, ADM-FR-006).
     */
    public function index(): View
    {
        return view('admin.pricing.index');
    }
}
