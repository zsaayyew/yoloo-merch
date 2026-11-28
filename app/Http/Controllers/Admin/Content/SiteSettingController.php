<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    /**
     * Show the business profile form (PAGE-ADM-003, ADM-FR-007).
     */
    public function edit(): View
    {
        return view('admin.content.settings.edit', [
            'fields' => SiteSetting::FIELDS,
            'values' => SiteSetting::profile(),
        ]);
    }

    /**
     * Update the business profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'settings' => ['required', 'array'],
            'settings.business_name' => ['required', 'string', 'max:120'],
            'settings.tagline' => ['nullable', 'string', 'max:160'],
            'settings.description' => ['nullable', 'string', 'max:2000'],
            'settings.address' => ['nullable', 'string', 'max:500'],
            'settings.phone' => ['nullable', 'string', 'max:40'],
            'settings.whatsapp' => ['nullable', 'string', 'max:40'],
            'settings.email' => ['nullable', 'email', 'max:120'],
            'settings.instagram' => ['nullable', 'string', 'max:120'],
            'settings.operating_hours' => ['nullable', 'string', 'max:160'],
            'settings.maps_url' => ['nullable', 'url', 'max:255'],
        ]);

        foreach (array_keys(SiteSetting::FIELDS) as $key) {
            SiteSetting::put($key, $request->input("settings.{$key}"));
        }

        return redirect()
            ->route('admin.content.settings.edit')
            ->with('status', 'Profil usaha berhasil disimpan.');
    }
}
