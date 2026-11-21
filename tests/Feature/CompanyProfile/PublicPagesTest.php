<?php

namespace Tests\Feature\CompanyProfile;

use App\Services\Ordering\CartService;
use Illuminate\Support\Facades\Route;
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

    public function test_homepage_displays_the_cart_badge_and_mobile_cart_link_when_available(): void
    {
        $this->withoutVite();
        $this->mock(CartService::class)->shouldReceive('count')->once()->andReturn(2);

        $cartUrl = route('cart.index');
        $response = $this->get('/')->assertOk()
            ->assertSee('aria-label="Keranjang, 2 produk"', false)
            ->assertSee('bg-red-600', false)
            ->assertSee('aria-label="Navigasi utama mobile"', false)
            ->assertSee(route('catalog.index'), false);

        $this->assertSame(2, substr_count($response->getContent(), 'href="'.e($cartUrl).'"'));
    }

    public function test_homepage_shows_accessible_empty_cart_state(): void
    {
        $this->withoutVite();

        $this->get('/')->assertOk()
            ->assertSee('aria-label="Keranjang, 0 produk"', false)
            ->assertSee('href="'.e(route('cart.index')).'"', false)
            ->assertDontSee('bg-red-600', false)
            ->assertSee('Keranjang', false);
    }

    public function test_homepage_hero_links_to_catalog_and_custom_order(): void
    {
        $this->withoutVite();

        $html = $this->get('/')->assertOk()
            ->assertSee('Jelajahi Katalog')
            ->assertSee('Ajukan Pesanan Custom')
            ->assertSee('Kenali YOLOO Merch')
            ->getContent();

        $heroHeading = strpos($html, 'id="home-hero-title"');
        $introHeading = strpos($html, 'id="home-intro-title"');
        $customOrderLink = strpos($html, 'href="'.e(route('custom-orders.create')).'"');

        $this->assertNotFalse($heroHeading);
        $this->assertNotFalse($introHeading);
        $this->assertNotFalse($customOrderLink);
        $this->assertGreaterThan($heroHeading, $customOrderLink);
        $this->assertLessThan($introHeading, $customOrderLink);
        $this->assertStringContainsString('href="'.e(route('catalog.index')).'"', $html);
    }

    public function test_homepage_empty_catalog_state_is_compact_and_keeps_catalog_target(): void
    {
        $this->withoutVite();

        $html = view('public.home.index', [
            'featuredProducts' => collect(),
            'featuredImages' => collect(),
            'featuredPricing' => collect(),
            'collaborations' => collect(),
            'galleryPreview' => collect(),
            'testimonialPreview' => collect(),
        ])->render();

        $this->assertStringContainsString('Produk yang tersedia akan ditampilkan di sini.', $html);
        $this->assertLinkTarget($html, 'Lihat Katalog', route('catalog.index'));
        $this->assertStringContainsString('px-4 pb-16 pt-8 sm:pb-20 sm:pt-10 sm:px-6', $html);
        $this->assertStringNotContainsString('products.show', $html);
    }

    public function test_homepage_omits_cart_and_custom_order_links_when_optional_routes_are_unavailable(): void
    {
        $this->withoutVite();

        $cartRoute = Route::getRoutes()->getByName('cart.index');
        $customOrderRoute = Route::getRoutes()->getByName('custom-orders.create');
        $this->assertNotNull($cartRoute);
        $this->assertNotNull($customOrderRoute);

        $cartRoute->name('.test-disabled');
        $customOrderRoute->name('.test-disabled');
        Route::getRoutes()->refreshNameLookups();

        $this->assertFalse(Route::has('cart.index'));
        $this->assertFalse(Route::has('custom-orders.create'));

        $this->get('/')->assertOk()
            ->assertSee('Jelajahi Katalog')
            ->assertDontSee('aria-label="Keranjang,', false)
            ->assertDontSee('/keranjang', false)
            ->assertDontSee('/custom-order', false);
    }

    public function test_catalog_navigation_is_active_on_catalog_index_category_and_product_routes(): void
    {
        $this->withoutVite();

        foreach (['catalog.index', 'catalog.category', 'products.show'] as $routeName) {
            $path = '/__tests/'.$routeName;
            Route::get($path, fn () => view('partials.public.navbar'))->name($routeName);

            $response = $this->get($path)->assertOk()->assertSee('aria-current="page"', false);

            $this->assertSame(2, substr_count($response->getContent(), 'aria-current="page"'));
        }
    }

    public function test_public_ctas_and_review_anchors_have_their_expected_targets(): void
    {
        $this->withoutVite();

        $home = $this->get('/')->assertOk()->getContent();
        $this->assertLinkTarget($home, 'Jelajahi Katalog', route('catalog.index'));
        $this->assertLinkTarget($home, 'Ajukan Pesanan Custom', route('custom-orders.create'));
        $this->assertLinkTarget($home, 'Kenali YOLOO Merch', route('about'));
        $this->assertLinkTarget($home, 'Hubungi Kami', route('contact'));

        $galleryPreview = view('public.home.sections.gallery-preview', [
            'galleryPreview' => collect(['approved content']),
        ])->render();
        $this->assertLinkTarget($galleryPreview, 'Buka galeri', route('gallery'));

        $testimonialPreview = view('public.home.sections.testimonial-preview', [
            'testimonialPreview' => collect(['approved content']),
        ])->render();
        $this->assertLinkTarget($testimonialPreview, 'Baca ulasan', route('reviews').'#testimonials');

        $about = $this->get('/tentang-kami')->assertOk()->getContent();
        $this->assertLinkTarget($about, 'Jelajahi Katalog', route('catalog.index'));
        $this->assertLinkTarget($about, 'Ajukan Pesanan Custom', route('custom-orders.create'));
        $this->assertLinkTarget($about, 'Lihat kanal kontak', route('contact'));

        $reviews = $this->get('/ulasan-kolaborasi')->assertOk()->getContent();
        $this->assertLinkTarget($reviews, 'Ulasan pelanggan', '#testimonials');
        $this->assertLinkTarget($reviews, 'Kolaborasi', '#collaborations');
        $this->assertStringContainsString('id="testimonials"', $reviews);
        $this->assertStringContainsString('id="collaborations"', $reviews);
        $this->assertLinkTarget($reviews, 'Jelajahi katalog', route('catalog.index'));
        $this->assertLinkTarget($reviews, 'Kontak', route('contact'));

        $gallery = $this->get('/galeri')->assertOk()->getContent();
        $this->assertLinkTarget($gallery, 'Jelajahi katalog', route('catalog.index'));

        $this->assertStringContainsString('href="'.e(route('cart.index')).'"', $home);
        $this->assertStringContainsString('href="'.e(route('contact')).'"', $home);
    }

    public function test_catalog_ctas_are_omitted_when_the_catalog_route_is_unavailable(): void
    {
        $this->withoutVite();

        $catalogRoute = Route::getRoutes()->getByName('catalog.index');
        $this->assertNotNull($catalogRoute);
        $catalogUrl = route('catalog.index');
        $catalogRoute->name('.test-disabled');
        Route::getRoutes()->refreshNameLookups();
        $this->assertFalse(Route::has('catalog.index'));

        foreach (['/galeri', '/ulasan-kolaborasi', '/tentang-kami'] as $path) {
            $response = $this->get($path)->assertOk();
            $this->assertStringNotContainsString('href="'.e($catalogUrl).'"', $response->getContent());
        }
    }

    public function test_contact_metadata_is_neutral_and_unconfigured_channels_are_not_linked(): void
    {
        $this->withoutVite();

        $this->get('/kontak')->assertOk()
            ->assertSee('<title>Kontak | YOLOO Merch</title>', false)
            ->assertSee('<meta name="description" content="Informasi kontak resmi YOLOO Merch untuk pertanyaan tentang produk dan layanan.">', false)
            ->assertSee('Informasi kontak resmi belum tersedia. Silakan periksa kembali nanti.')
            ->assertDontSee('Hubungi YOLOO Merch melalui WhatsApp, Instagram, atau email.')
            ->assertDontSee('href="https://wa.me/', false)
            ->assertDontSee('href="mailto:', false);
    }

    public function test_contact_view_only_links_valid_official_channel_values(): void
    {
        $invalidHtml = view('public.contact.sections.channels', [
            'contactChannels' => [
                'whatsapp_number' => '123',
                'instagram_url' => 'https://example.invalid/profile',
                'instagram_handle' => '@yoloo',
                'email' => 'not an email',
            ],
        ])->render();

        $this->assertStringNotContainsString('href="https://wa.me/', $invalidHtml);
        $this->assertStringNotContainsString('href="https://example.invalid/profile"', $invalidHtml);
        $this->assertStringNotContainsString('href="mailto:', $invalidHtml);
        $this->assertStringContainsString('Informasi kontak resmi belum tersedia.', $invalidHtml);

        $validHtml = view('public.contact.sections.channels', [
            'contactChannels' => [
                'whatsapp_number' => '0812-3456-7890',
                'whatsapp_display' => '+62 812 3456 7890',
                'instagram_url' => 'https://www.instagram.com/yoloo_merch/',
                'instagram_handle' => '@yoloo_merch',
                'email' => 'halo@yoloomerch.com',
            ],
        ])->render();

        $this->assertStringContainsString('href="https://wa.me/6281234567890"', $validHtml);
        $this->assertStringContainsString('href="https://www.instagram.com/yoloo_merch/"', $validHtml);
        $this->assertStringContainsString('href="mailto:halo@yoloomerch.com"', $validHtml);
        $this->assertStringNotContainsString('Informasi kontak resmi belum tersedia.', $validHtml);
    }

    public function test_content_pages_render_safe_empty_states_and_official_contact_links(): void
    {
        $this->withoutVite();

        $this->get('/tentang-kami')->assertOk()
            ->assertSee('Tentang YOLOO Merch')
            ->assertSee(route('custom-orders.create'), false)
            ->assertSee('Ajukan Pesanan Custom')
            ->assertDontSee('Visi &amp; Misi', false);

        $this->get('/ulasan-kolaborasi')->assertOk()
            ->assertSee('Belum ada ulasan yang dipublikasikan.')
            ->assertSee('Belum ada kolaborasi yang dipublikasikan.');

        $this->get('/galeri')->assertOk()
            ->assertSee('Galeri sedang disiapkan')
            ->assertDontSee('Acrylic/Keychain');

        $this->get('/kontak')->assertOk()
            ->assertSee('Informasi kontak resmi belum tersedia. Silakan periksa kembali nanti.')
            ->assertDontSee('https://wa.me/', false)
            ->assertDontSee('yoloomerch@gmail.com')
            ->assertDontSee('Google Maps');
    }

    private function assertLinkTarget(string $html, string $label, string $expectedHref): void
    {
        preg_match_all('/<a\b([^>]*)>(.*?)<\/a>/si', $html, $anchors, PREG_SET_ORDER);

        foreach ($anchors as $anchor) {
            if (! preg_match('/\bhref=(?:"([^"]*)"|\'([^\']*)\')/i', $anchor[1], $hrefMatch)) {
                continue;
            }

            $actualHref = html_entity_decode(($hrefMatch[1] ?? '') !== '' ? $hrefMatch[1] : ($hrefMatch[2] ?? ''), ENT_QUOTES | ENT_HTML5);
            $actualLabel = html_entity_decode(strip_tags($anchor[2]), ENT_QUOTES | ENT_HTML5);

            if (str_contains($actualLabel, $label) && $actualHref === $expectedHref) {
                $this->assertTrue(true);

                return;
            }
        }

        $this->fail("Expected link '{$label}' to target '{$expectedHref}'.");
    }
}
