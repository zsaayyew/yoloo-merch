<?php

namespace Tests\Feature\CompanyProfile;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_company_profile_routes_return_their_views_and_relevant_titles(): void
    {
        $this->withoutVite();

        $siteTitle = config('app.name') ?: 'YOLOO Merch';
        $pages = [
            'home' => ['/', 'public.home.index', 'Beranda'],
            'about' => ['/tentang-kami', 'public.about.index', 'Tentang Kami'],
            'reviews' => ['/ulasan-kolaborasi', 'public.reviews.index', 'Ulasan & Kolaborasi'],
            'gallery' => ['/galeri', 'public.gallery.index', 'Galeri'],
            'contact' => ['/kontak', 'public.contact.index', 'Kontak'],
        ];

        $this->assertSame('/produk', route('catalog.index', [], false));

        foreach ($pages as $routeName => [$url, $view, $pageTitle]) {
            $this->assertSame($url, route($routeName, [], false));

            $this->get($url)
                ->assertOk()
                ->assertViewIs($view)
                ->assertSee('<title>'.e($pageTitle.' | '.$siteTitle).'</title>', false)
                ->assertDontSee('<meta name="description" content="">', false);
        }
    }
}
