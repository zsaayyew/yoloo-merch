<?php

namespace App\Http\Controllers\PublicSite\CompanyProfile;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        // Temporary public demo content while ADM's gallery schema is still a stub.
        $galleryCategories = collect([
            ['name' => 'Pin Bulat', 'slug' => 'pin-bulat'],
            ['name' => 'Keychain Akrilik', 'slug' => 'keychain-akrilik'],
            ['name' => 'Stiker Die-Cut', 'slug' => 'stiker-die-cut'],
            ['name' => 'Bottle Caps Pin', 'slug' => 'bottle-caps-pin'],
        ]);

        $galleryItems = collect([
            [
                'category_slug' => 'pin-bulat',
                'image_url' => 'https://monkeyduo.com/cdn/shop/files/custom-pins-buttons-badges.jpg?v=1699792144&width=1500',
                'alt_text' => 'Contoh pin bulat custom tampak depan dan belakang',
                'caption' => 'Pin bulat custom — contoh produk',
            ],
            [
                'category_slug' => 'keychain-akrilik',
                'image_url' => 'https://godomall-storage.cdn-nhncommerce.com/79df028c955294f0c57369f0afb4b4e0/goods/1000000019/image/detail/1000000019_detail_480.jpg',
                'alt_text' => 'Contoh keychain akrilik warna-warni dengan gantungan logam',
                'caption' => 'Keychain akrilik — contoh produk',
            ],
            [
                'category_slug' => 'stiker-die-cut',
                'image_url' => 'https://djuqbvg97u5zb.cloudfront.net/bestatpri/images/product/sticker-sheets-main_03534605202502.jpg',
                'alt_text' => 'Lembar stiker ilustrasi dengan potongan kiss-cut',
                'caption' => 'Stiker die-cut — contoh produk',
            ],
            [
                'category_slug' => 'bottle-caps-pin',
                'image_url' => 'https://content.instructables.com/FFM/FG9O/FYG86AAZ/FFMFG9OFYG86AAZ.jpg',
                'alt_text' => 'Contoh pin yang dibuat dari tutup botol',
                'caption' => 'Bottle caps pin — contoh produk',
            ],
        ]);

        return view('public.gallery.index', [
            'galleryCategories' => $galleryCategories,
            'galleryItems' => $galleryItems,
        ]);
    }
}
