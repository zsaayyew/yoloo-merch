<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\Content\Concerns\UpdatesSiteContent;
use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutContentController extends Controller
{
    use UpdatesSiteContent;

    /**
     * Show the about page content form (PAGE-ADM-003, ADM-FR-003).
     */
    public function edit(): View
    {
        return view('admin.content.about.edit', [
            'page' => SiteContent::PAGE_ABOUT,
            'pageLabel' => SiteContent::PAGES[SiteContent::PAGE_ABOUT],
            'sections' => SiteContent::SECTIONS[SiteContent::PAGE_ABOUT],
            'contents' => SiteContent::forPage(SiteContent::PAGE_ABOUT),
        ]);
    }

    /**
     * Update the about page content.
     */
    public function update(Request $request): RedirectResponse
    {
        $this->updateSiteContent($request, SiteContent::PAGE_ABOUT);

        return redirect()
            ->route('admin.content.about.edit')
            ->with('status', 'Konten halaman tentang berhasil disimpan.');
    }
}
