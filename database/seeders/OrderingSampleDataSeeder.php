<?php

namespace Database\Seeders;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Enums\RequestStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRequest;
use App\Models\Product;
use App\Services\Catalog\ConfigurationPayloadBuilder;
use App\Services\Ordering\OrderCodeGenerator;
use Carbon\CarbonInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * PRD-03 ORD — Data DUMMY pesanan untuk demo dan asistensi.
 *
 * Mengisi data seolah-olah pelanggan sudah memesan lewat website:
 *   - 5 pelanggan contoh (nama & nomor fiktif, email @contoh.test)
 *   - 6 pesanan direct purchase (kode WEB-...), status awal ORD: Menunggu Konfirmasi / Belum Dibayar
 *   - 4 custom order request (kode REQ-...), status awal ORD: Baru Diterima
 *
 * Harga item dihitung lewat ConfigurationPayloadBuilder milik CAT (bukan angka karangan),
 * jadi sama dengan estimasi yang tampil di website. Status lanjutan (diproses, lunas, dll.)
 * bukan wewenang ORD; ubah lewat halaman admin FIN.
 *
 * Membutuhkan produk demo (OrderingDemoSeeder); dipanggil otomatis jika belum ada.
 * Jalankan: php artisan db:seed --class=OrderingSampleDataSeeder
 */
class OrderingSampleDataSeeder extends Seeder
{
    private const MARKER_WHATSAPP = '6281200000001';

    public function run(ConfigurationPayloadBuilder $payloadBuilder, OrderCodeGenerator $codeGenerator): void
    {
        if (Customer::where('whatsapp_number', self::MARKER_WHATSAPP)->exists()) {
            $this->command?->warn('Data dummy ORD sudah ada. Jalankan migrate:fresh dulu jika ingin mengulang.');

            return;
        }

        if (! Product::where('slug', 'demo-stiker-vinyl')->exists()) {
            $this->call(OrderingDemoSeeder::class);
        }

        $sticker = Product::where('slug', 'demo-stiker-vinyl')->firstOrFail();
        $keychain = Product::where('slug', 'demo-gantungan-akrilik')->firstOrFail();
        $tshirt = Product::where('slug', 'demo-kaos-sablon')->firstOrFail();

        $opt = fn (string $label) => (int) DB::table('option_values')
            ->join('option_groups', 'option_groups.id', '=', 'option_values.option_group_id')
            ->where('option_groups.product_id', $sticker->id)
            ->where('option_values.label', $label)
            ->value('option_values.id');

        $s5 = $opt('5 x 5 cm');
        $s10 = $opt('10 x 10 cm');
        $glossy = $opt('Glossy');
        $doff = $opt('Doff');

        DB::transaction(function () use ($payloadBuilder, $codeGenerator, $sticker, $keychain, $tshirt, $s5, $s10, $glossy, $doff) {
            // ---------- Pelanggan contoh ----------
            $customers = collect([
                ['name' => 'Andi Pratama (Contoh)', 'whatsapp_number' => self::MARKER_WHATSAPP, 'email' => 'andi@contoh.test'],
                ['name' => 'Nur Aisyah (Contoh)', 'whatsapp_number' => '6281200000002', 'email' => 'aisyah@contoh.test'],
                ['name' => 'Kafe Senja (Contoh)', 'whatsapp_number' => '6281200000003', 'email' => null],
                ['name' => 'Himpunan Mahasiswa TEKOM (Contoh)', 'whatsapp_number' => '6281200000004', 'email' => 'hima@contoh.test'],
                ['name' => 'Budi Santoso (Contoh)', 'whatsapp_number' => '6281200000005', 'email' => null],
            ])->map(fn (array $contact) => Customer::resolveFromContact($contact))->values();

            // ---------- Pesanan direct purchase ----------
            // [pelanggan, hari lalu, catatan, [[produk, opsi, jumlah], ...]]
            $orders = [
                [0, 5, 'Mohon dibuat rapi, untuk acara kantor.', [[$sticker, [$s5, $glossy], 100]]],
                [1, 4, null, [[$keychain, [], 3]]],
                [2, 3, 'Untuk ditempel di cup minuman.', [[$sticker, [$s10, $doff], 200], [$keychain, [], 10]]],
                [3, 2, 'Kebutuhan kegiatan makrab himpunan.', [[$sticker, [$s5, $doff], 150]]],
                [4, 1, null, [[$keychain, [], 1]]],
                [0, 0, 'Pesanan tambahan.', [[$sticker, [$s10, $glossy], 50]]],
            ];

            foreach ($orders as [$customerIndex, $daysAgo, $notes, $lines]) {
                $orderedAt = now()->subDays($daysAgo)->setTime(9 + $daysAgo, 15);
                $payloads = array_map(
                    fn (array $line) => $payloadBuilder->build($line[0], $line[1], $line[2]),
                    $lines,
                );
                $total = (int) array_sum(array_column($payloads, 'estimated_total'));

                $order = Order::create([
                    'code' => $this->uniqueCode($codeGenerator, OrderCodeGenerator::PREFIX_ORDER, $orderedAt, Order::class),
                    'customer_id' => $customers[$customerIndex]->id,
                    'type' => OrderType::Direct,
                    'channel' => OrderChannel::Website,
                    'status' => OrderStatus::AwaitingConfirmation,
                    'payment_status' => PaymentStatus::Unpaid,
                    'subtotal' => $total,
                    'discount' => 0,
                    'additional_fee' => 0,
                    'total' => $total,
                    'estimated_total' => $total,
                    'fulfillment' => 'pickup',
                    'notes' => $notes,
                    'ordered_at' => $orderedAt,
                ]);

                foreach ($payloads as $payload) {
                    $order->items()->create([
                        'product_id' => $payload['product_id'],
                        'product_name_snapshot' => $payload['product_name_snapshot'],
                        'options_snapshot' => $payload['options'],
                        'quantity' => $payload['quantity'],
                        'price_model' => $payload['price_model'],
                        'unit_price' => (int) $payload['estimated_unit_price'],
                        'subtotal' => (int) $payload['estimated_total'],
                        'price_status' => OrderItem::PRICE_ESTIMATE,
                    ]);
                }
            }

            // ---------- Custom order request ----------
            // [pelanggan, hari lalu, produk referensi, opsi, jumlah, deskripsi, spesifikasi, tenggat (hari lagi), anggaran]
            $requests = [
                [3, 4, $tshirt, [], 60, 'Kaos sablon untuk panitia makrab, logo himpunan di dada kiri dan tulisan di punggung.', 'Kaos cotton combed 30s, warna hitam, ukuran S-XL.', 14, 4500000],
                [2, 3, $sticker, [$s10, $doff], 500, 'Stiker logo kafe untuk kemasan, ingin bentuk lingkaran dengan finishing doff.', 'Diameter 10 cm, potong bentuk lingkaran.', 10, 1500000],
                [1, 1, null, [], 25, 'Gantungan kunci akrilik bentuk karakter custom untuk souvenir ulang tahun.', 'Ukuran sekitar 5 cm, cetak dua sisi.', 7, null],
                [4, 0, null, [], 2, 'Ingin membuat banner untuk stan bazar kampus, belum punya desain.', null, null, 300000],
            ];

            foreach ($requests as [$customerIndex, $daysAgo, $product, $optionIds, $quantity, $description, $spec, $deadlineDays, $budget]) {
                $submittedAt = now()->subDays($daysAgo)->setTime(13 + $daysAgo, 30);
                $options = $product && $optionIds
                    ? $payloadBuilder->build($product, $optionIds, $quantity, forQuotation: true)['options']
                    : null;

                OrderRequest::create([
                    'code' => $this->uniqueCode($codeGenerator, OrderCodeGenerator::PREFIX_REQUEST, $submittedAt, OrderRequest::class),
                    'customer_id' => $customers[$customerIndex]->id,
                    'channel' => OrderChannel::Website,
                    'status' => RequestStatus::New,
                    'product_id' => $product?->id,
                    'product_name_snapshot' => $product?->name,
                    'options_snapshot' => $options,
                    'description' => $description,
                    'quantity' => $quantity,
                    'specification' => $spec,
                    'desired_deadline' => $deadlineDays ? now()->addDays($deadlineDays)->toDateString() : null,
                    'budget' => $budget,
                    'submitted_at' => $submittedAt,
                ]);
            }
        });

        $this->command?->info('Data dummy ORD dibuat: 5 pelanggan, 6 pesanan (WEB-...), 4 custom order (REQ-...).');
    }

    /** Kode dengan tanggal pesanan (bukan tanggal hari ini), dijamin unik. */
    private function uniqueCode(OrderCodeGenerator $generator, string $prefix, CarbonInterface $date, string $model): string
    {
        do {
            $code = $generator->make($prefix, $date);
        } while ($model::where('code', $code)->exists());

        return $code;
    }
}
