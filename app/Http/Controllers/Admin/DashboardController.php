<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collaboration;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\Media;
use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Models\Role;
use App\Models\SiteContent;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard (PAGE-ADM-002, ADM-FR-002).
     */
    public function __invoke(): View
    {
        $productTotal = Product::count();
        $productActive = Product::where('status', 'active')->count();
        $productDraft = Product::where('status', 'draft')->count();
        $productArchived = Product::where('status', 'archived')->count();

        $categoryTotal = Category::count();
        $categoryActive = Category::where('is_active', true)->count();

        $pricingTotal = PricingConfiguration::count();
        $pricingActive = PricingConfiguration::where('status', 'active')->count();

        $adminAccounts = User::whereHas('role', fn ($query) => $query->where('name', Role::ADMIN));

        $cards = [
            [
                'label' => 'Produk',
                'value' => $productTotal,
                'note' => sprintf('%d aktif · %d draft · %d arsip', $productActive, $productDraft, $productArchived),
                'route' => 'admin.products.index',
            ],
            [
                'label' => 'Kategori Produk',
                'value' => $categoryTotal,
                'note' => sprintf('%d aktif · %d nonaktif', $categoryActive, $categoryTotal - $categoryActive),
                'route' => 'admin.categories.index',
            ],
            [
                'label' => 'Konfigurasi Harga',
                'value' => $pricingTotal,
                'note' => sprintf('%d konfigurasi aktif', $pricingActive),
                'route' => 'admin.pricing.index',
            ],
            [
                'label' => 'Konten Halaman',
                'value' => SiteContent::count(),
                'note' => 'Beranda, tentang, dan profil usaha',
                'route' => 'admin.content.homepage.edit',
            ],
            [
                'label' => 'Testimoni',
                'value' => Testimonial::count(),
                'note' => 'Ulasan yang tampil di website',
                'route' => 'admin.content.testimonials.index',
            ],
            [
                'label' => 'Kolaborasi',
                'value' => Collaboration::count(),
                'note' => 'Dokumentasi kerja sama',
                'route' => 'admin.content.collaborations.index',
            ],
            [
                'label' => 'Galeri',
                'value' => GalleryItem::count(),
                'note' => sprintf('%d kategori galeri', GalleryCategory::count()),
                'route' => 'admin.content.gallery.index',
            ],
            [
                'label' => 'Media',
                'value' => Media::count(),
                'note' => 'Berkas yang dipakai konten website',
                'route' => 'admin.media.index',
            ],
        ];

        if (request()->user()?->isOwner()) {
            $cards[] = [
                'label' => 'Akun Admin',
                'value' => (clone $adminAccounts)->count(),
                'note' => sprintf('%d aktif', (clone $adminAccounts)->where('is_active', true)->count()),
                'route' => 'admin.users.index',
            ];
        }

        return view('admin.dashboard.index', [
            'cards' => $cards,
        ]);
    }
}
