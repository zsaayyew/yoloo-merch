<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\PaymentStatus;
use App\Services\Finance\FinanceRuleException;
use App\Services\Finance\PaymentLedgerService;
use PHPUnit\Framework\TestCase;

/**
 * FIN-FR-008, FIN-FR-019 | FIN-BR-005, 006, 008, 019 | FIN-AT-005, 006, 017, 018, 019
 * Tes murni (tanpa database), jadi cepat dijalankan.
 */
class PaymentLedgerServiceTest extends TestCase
{
    private PaymentLedgerService $ledger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ledger = new PaymentLedgerService();
    }

    // ---- Ringkasan: Pembayaran Bersih, Sisa Tagihan, status ----

    public function test_dp_qris_50rb_dari_total_200rb_fin_at_005(): void
    {
        $summary = $this->ledger->summarize(totalBill: 200000, validPaymentsTotal: 50000, completedRefundsTotal: 0);

        $this->assertSame(50000, $summary['net_payment']);
        $this->assertSame(150000, $summary['remaining']);
        $this->assertSame(PaymentStatus::PartiallyPaid, $summary['payment_status']);
        $this->assertSame(PaymentLedgerService::REFUND_NONE, $summary['refund_status']);
    }

    public function test_belum_ada_pembayaran_berstatus_belum_dibayar(): void
    {
        $summary = $this->ledger->summarize(totalBill: 200000, validPaymentsTotal: 0, completedRefundsTotal: 0);

        $this->assertSame(0, $summary['net_payment']);
        $this->assertSame(200000, $summary['remaining']);
        $this->assertSame(PaymentStatus::Unpaid, $summary['payment_status']);
    }

    public function test_pembayaran_penuh_berstatus_lunas(): void
    {
        $summary = $this->ledger->summarize(totalBill: 200000, validPaymentsTotal: 200000, completedRefundsTotal: 0);

        $this->assertSame(0, $summary['remaining']);
        $this->assertSame(PaymentStatus::Paid, $summary['payment_status']);
    }

    public function test_refund_sebagian_mengurangi_pembayaran_bersih_fin_at_017(): void
    {
        // Payment Valid Rp100.000, Refund Selesai Rp40.000 => Pembayaran Bersih Rp60.000
        $summary = $this->ledger->summarize(totalBill: 100000, validPaymentsTotal: 100000, completedRefundsTotal: 40000);

        $this->assertSame(60000, $summary['net_payment']);
        $this->assertSame(40000, $summary['remaining']);
        $this->assertSame(PaymentStatus::PartiallyPaid, $summary['payment_status']);
        $this->assertSame(PaymentLedgerService::REFUND_PARTIAL, $summary['refund_status']);
        $this->assertSame('Refund Sebagian', $summary['refund_status_label']);
    }

    public function test_refund_penuh_fin_at_018(): void
    {
        // Payment Valid Rp100.000, Refund penuh Rp100.000 => Pembayaran Bersih Rp0, Refund Penuh
        $summary = $this->ledger->summarize(totalBill: 100000, validPaymentsTotal: 100000, completedRefundsTotal: 100000);

        $this->assertSame(0, $summary['net_payment']);
        $this->assertSame(PaymentStatus::Unpaid, $summary['payment_status']);
        $this->assertSame(PaymentLedgerService::REFUND_FULL, $summary['refund_status']);
        $this->assertSame('Refund Penuh', $summary['refund_status_label']);
    }

    public function test_label_status_refund_tidak_ada(): void
    {
        $this->assertSame('Tidak Ada Refund', PaymentLedgerService::refundStatusLabel(PaymentLedgerService::REFUND_NONE));
    }

    public function test_refund_selesai_melebihi_pembayaran_valid_dianggap_data_tidak_konsisten(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->ledger->summarize(totalBill: 100000, validPaymentsTotal: 50000, completedRefundsTotal: 60000);
    }

    public function test_pembayaran_bersih_melebihi_total_dianggap_data_tidak_konsisten(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->ledger->summarize(totalBill: 100000, validPaymentsTotal: 120000, completedRefundsTotal: 0);
    }

    public function test_angka_negatif_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->ledger->summarize(totalBill: -1, validPaymentsTotal: 0, completedRefundsTotal: 0);
    }

    // ---- Validasi pembayaran baru ----

    public function test_pembayaran_melebihi_sisa_tagihan_ditolak_dan_menyebut_sisa_fin_at_006(): void
    {
        // Sisa tagihan Rp100.000 (total 200.000, bersih 100.000), mencoba Rp120.000
        try {
            $this->ledger->assertPaymentAllowed(amount: 120000, totalBill: 200000, netPayment: 100000);
            $this->fail('Seharusnya melempar FinanceRuleException.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Rp100.000', $e->getMessage());
            $this->assertStringContainsString('Rp120.000', $e->getMessage());
        }
    }

    public function test_pembayaran_tepat_sebesar_sisa_tagihan_diperbolehkan(): void
    {
        $this->ledger->assertPaymentAllowed(amount: 100000, totalBill: 200000, netPayment: 100000);

        $this->addToAssertionCount(1);
    }

    public function test_pembayaran_nol_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->ledger->assertPaymentAllowed(amount: 0, totalBill: 200000, netPayment: 0);
    }

    public function test_pembayaran_negatif_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->ledger->assertPaymentAllowed(amount: -5000, totalBill: 200000, netPayment: 0);
    }

    public function test_pembayaran_pada_transaksi_dibatalkan_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('dibatalkan');

        $this->ledger->assertPaymentAllowed(amount: 10000, totalBill: 200000, netPayment: 0, orderCancelled: true);
    }

    public function test_pembayaran_pada_transaksi_yang_sudah_lunas_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('lunas');

        $this->ledger->assertPaymentAllowed(amount: 1000, totalBill: 200000, netPayment: 200000);
    }

    public function test_pembayaran_pada_transaksi_total_nol_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->ledger->assertPaymentAllowed(amount: 1000, totalBill: 0, netPayment: 0);
    }

    public function test_setelah_refund_sisa_tagihan_bertambah_sehingga_bisa_bayar_lagi(): void
    {
        // Total 100.000, sudah bayar 100.000 lalu refund 40.000 => bersih 60.000, sisa 40.000.
        $this->ledger->assertPaymentAllowed(amount: 40000, totalBill: 100000, netPayment: 60000);

        $this->addToAssertionCount(1);
    }

    // ---- Validasi refund ----

    public function test_refund_melebihi_saldo_ditolak_dan_menyebut_maksimum_fin_at_019(): void
    {
        // Payment Rp100.000, refund sebelumnya Rp40.000 => saldo Rp60.000; mencoba Rp70.000.
        try {
            $this->ledger->assertRefundAllowed(
                amount: 70000,
                paymentIsValid: true,
                paymentAmount: 100000,
                completedRefundsForPayment: 40000
            );
            $this->fail('Seharusnya melempar FinanceRuleException.');
        } catch (FinanceRuleException $e) {
            $this->assertStringContainsString('Rp60.000', $e->getMessage());
        }
    }

    public function test_refund_tepat_sebesar_saldo_diperbolehkan(): void
    {
        $this->ledger->assertRefundAllowed(
            amount: 60000,
            paymentIsValid: true,
            paymentAmount: 100000,
            completedRefundsForPayment: 40000
        );

        $this->addToAssertionCount(1);
    }

    public function test_refund_kedua_yang_membuat_total_refund_melebihi_payment_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->ledger->assertRefundAllowed(
            amount: 1,
            paymentIsValid: true,
            paymentAmount: 100000,
            completedRefundsForPayment: 100000
        );
    }

    public function test_refund_pada_payment_void_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('Void');

        $this->ledger->assertRefundAllowed(
            amount: 10000,
            paymentIsValid: false,
            paymentAmount: 100000,
            completedRefundsForPayment: 0
        );
    }

    public function test_refund_nol_atau_negatif_ditolak(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->ledger->assertRefundAllowed(
            amount: 0,
            paymentIsValid: true,
            paymentAmount: 100000,
            completedRefundsForPayment: 0
        );
    }

    public function test_maksimum_refund_dihitung_dari_payment_dikurangi_refund_sebelumnya(): void
    {
        $this->assertSame(60000, $this->ledger->maxRefundable(100000, 40000));
        $this->assertSame(0, $this->ledger->maxRefundable(100000, 100000));
    }
}
