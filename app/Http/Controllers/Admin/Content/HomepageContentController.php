<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\Content\Concerns\UpdatesSiteContent;
use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageContentController extends Controller
{
    use UpdatesSiteContent;

    /**
     * Show the homepage content form (PAGE-ADM-003, ADM-FR-003).
     */
    public function edit(): View
    {
        return view('admin.content.homepage.edit', [
            'page' => SiteContent::PAGE_HOME,
            'pageLabel' => SiteContent::PAGES[SiteContent::PAGE_HOME],
            'sections' => SiteContent::SECTIONS[SiteContent::PAGE_HOME],
            'contents' => SiteContent::forPage(SiteContent::PAGE_HOME),
        ]);
    }

    /**
     * Update the homepage content.
     */
    public function update(Request $request): RedirectResponse
    {
        $this->updateSiteContent($request, SiteContent::PAGE_HOME);

        return redirect()
            ->route('admin.content.homepage.edit')
            ->with('status', 'Konten beranda berhasil disimpan.');
    }
}
