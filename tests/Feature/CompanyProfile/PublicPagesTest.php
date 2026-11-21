<?php

namespace Tests\Feature\CompanyProfile;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_company_profile_routes_return_their_views_and_relevant_titles(): void
    {
        $this->withoutVite();

        $siteTitle = 'YOLOO Merch';
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
                ->assertDontSee('<meta name="description" content="">', false)
                ->assertSee('aria-label="Navigasi utama"', false)
                ->assertSee('/images/brand/yoloo-logo-full.png', false)
                ->assertSee('aria-label="Navigasi footer"', false);
        }
    }

    public function test_public_navigation_and_home_sections_are_consistent(): void
    {
        $this->withoutVite();

        $response = $this->get('/')->assertOk()
            ->assertSee('/images/brand/yoloo-logo-full.png', false)
            ->assertSee('Your Personal Merchandise Solution')
            ->assertSee('Jelajahi Katalog')
            ->assertSee('Ulasan &amp; Kolaborasi', false)
            ->assertDontSee('Cek Pesanan')
            ->assertDontSee('Doni')
            ->assertDontSee('Rena');

        $html = $response->getContent();
        $orderedHeadings = [
            'home-hero-title', 'home-intro-title', 'home-featured-title',
            'home-services-title', 'home-advantages-title', 'home-cta-title',
        ];
        $lastPosition = -1;
        foreach ($orderedHeadings as $heading) {
            $position = strpos($html, $heading);
            $this->assertNotFalse($position, "Missing homepage section heading {$heading}.");
            $this->assertGreaterThan($lastPosition, $position);
            $lastPosition = $position;
        }
    }

    public function test_content_pages_render_safe_empty_states_and_official_contact_links(): void
    {
        $this->withoutVite();

        $this->get('/tentang-kami')->assertOk()
            ->assertSee('Tentang YOLOO Merch')
            ->assertDontSee('Visi &amp; Misi', false);

        $this->get('/ulasan-kolaborasi')->assertOk()
            ->assertSee('Belum ada ulasan yang dipublikasikan.')
            ->assertSee('Belum ada kolaborasi yang dipublikasikan.');

        $this->get('/galeri')->assertOk()
            ->assertSee('Galeri sedang disiapkan')
            ->assertDontSee('Acrylic/Keychain');

        $this->get('/kontak')->assertOk()
            ->assertSee('Informasi kontak akan ditampilkan setelah kanal resmi dikonfirmasi.')
            ->assertDontSee('https://wa.me/', false)
            ->assertDontSee('yoloomerch@gmail.com')
            ->assertDontSee('Google Maps');
    }
}
