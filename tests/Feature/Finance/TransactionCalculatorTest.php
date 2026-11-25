<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\TransactionCalculator;
use PHPUnit\Framework\TestCase;

/**
 * FIN-FR-007 | FIN-BR-003, FIN-BR-020 | FIN-AT-004, FIN-AT-020
 * Tes murni (tanpa database), jadi cepat dijalankan.
 */
class TransactionCalculatorTest extends TestCase
{
    private TransactionCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new TransactionCalculator();
    }

    public function test_total_sesuai_contoh_acceptance_criteria_fin_fr_007(): void
    {
        // Item A: 2 x Rp25.000, Item B: 1 x Rp40.000, diskon Rp10.000, biaya tambahan Rp5.000 => Rp85.000
        $result = $this->calculator->calculate(
            [
                ['quantity' => 2, 'unit_price' => 25000],
                ['quantity' => 1, 'unit_price' => 40000],
            ],
            discount: 10000,
            additionalFee: 5000
        );

        $this->assertSame([50000, 40000], $result['items']);
        $this->assertSame(90000, $result['subtotal']);
        $this->assertSame(10000, $result['discount']);
        $this->assertSame(5000, $result['additional_fee']);
        $this->assertSame(85000, $result['total']);
    }

    public function test_tanpa_diskon_dan_biaya_tambahan_total_sama_dengan_subtotal(): void
    {
        $result = $this->calculator->calculate([['quantity' => 3, 'unit_price' => 12000]]);

        $this->assertSame(36000, $result['subtotal']);
        $this->assertSame(36000, $result['total']);
    }

    public function test_angka_string_bulat_dari_form_diterima(): void
    {
        $result = $this->calculator->calculate([['quantity' => '3', 'unit_price' => '12000']]);

        $this->assertSame(36000, $result['total']);
    }

    public function test_harga_satuan_nol_diperbolehkan(): void
    {
        $result = $this->calculator->calculate([['quantity' => 5, 'unit_price' => 0]]);

        $this->assertSame(0, $result['total']);
    }

    public function test_diskon_sama_dengan_subtotal_diperbolehkan(): void
    {
        $result = $this->calculator->calculate(
            [['quantity' => 1, 'unit_price' => 50000]],
            discount: 50000,
            additionalFee: 2000
        );

        $this->assertSame(2000, $result['total']);
    }

    public function test_transaksi_tanpa_item_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('minimal satu item');

        $this->calculator->calculate([]);
    }

    public function test_kuantitas_nol_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('Kuantitas item ke-1');

        $this->calculator->calculate([['quantity' => 0, 'unit_price' => 1000]]);
    }

    public function test_kuantitas_negatif_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->calculator->calculate([['quantity' => -2, 'unit_price' => 1000]]);
    }

    public function test_kuantitas_bukan_angka_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->calculator->calculate([['quantity' => 'abc', 'unit_price' => 1000]]);
    }

    public function test_kuantitas_desimal_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->calculator->calculate([['quantity' => '2.5', 'unit_price' => 1000]]);
    }

    public function test_harga_negatif_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('Harga satuan item ke-2');

        $this->calculator->calculate([
            ['quantity' => 1, 'unit_price' => 1000],
            ['quantity' => 1, 'unit_price' => -1],
        ]);
    }

    public function test_harga_kosong_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->calculator->calculate([['quantity' => 1]]);
    }

    public function test_diskon_melebihi_subtotal_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('tidak boleh melebihi subtotal');

        $this->calculator->calculate([['quantity' => 1, 'unit_price' => 50000]], discount: 50001);
    }

    public function test_diskon_negatif_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->calculator->calculate([['quantity' => 1, 'unit_price' => 50000]], discount: -1);
    }

    public function test_biaya_tambahan_negatif_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->calculator->calculate([['quantity' => 1, 'unit_price' => 50000]], additionalFee: -1);
    }

    public function test_total_baru_lebih_kecil_dari_pembayaran_bersih_ditolak_fin_at_020(): void
    {
        // Pembayaran Bersih Rp200.000, total baru Rp150.000 => ditolak.
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('Pembayaran Bersih');

        $this->calculator->assertTotalNotBelowNetPayment(newTotal: 150000, netPayment: 200000);
    }

    public function test_total_baru_sama_dengan_pembayaran_bersih_diperbolehkan(): void
    {
        $this->calculator->assertTotalNotBelowNetPayment(newTotal: 200000, netPayment: 200000);

        $this->addToAssertionCount(1);
    }

    public function test_total_baru_lebih_besar_dari_pembayaran_bersih_diperbolehkan(): void
    {
        $this->calculator->assertTotalNotBelowNetPayment(newTotal: 300000, netPayment: 200000);

        $this->addToAssertionCount(1);
    }
}
