<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\OrderStatus;
use App\Enums\RequestStatus;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\StatusTransitionService;
use PHPUnit\Framework\TestCase;

/**
 * FIN-FR-010, FIN-FR-003, FIN-FR-004 | FIN-BR-007, FIN-BR-009 | FIN-AT-002, FIN-AT-024
 * Tes murni (tanpa database), jadi cepat dijalankan.
 */
class StatusTransitionServiceTest extends TestCase
{
    private StatusTransitionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StatusTransitionService();
    }

    // ---------------- ORDER ----------------

    public function test_order_maju_satu_langkah_demi_satu_langkah(): void
    {
        $chain = [
            OrderStatus::AwaitingConfirmation,
            OrderStatus::AwaitingPayment,
            OrderStatus::Processing,
            OrderStatus::ReadyForPickup,
            OrderStatus::Completed,
        ];

        for ($i = 0; $i < count($chain) - 1; $i++) {
            $this->service->assertOrderTransition($chain[$i], $chain[$i + 1], actorIsOwner: false);
            $this->assertTrue($this->service->canChangeOrderStatus($chain[$i], $chain[$i + 1], false));
        }
    }

    public function test_siap_diambil_dari_diproses_fin_at_024(): void
    {
        $this->service->assertOrderTransition(OrderStatus::Processing, OrderStatus::ReadyForPickup, false, 'Barang siap');

        $this->addToAssertionCount(1);
    }

    public function test_order_tidak_boleh_melompati_langkah(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('tidak dapat diubah dari Menunggu Konfirmasi menjadi Diproses');

        $this->service->assertOrderTransition(OrderStatus::AwaitingConfirmation, OrderStatus::Processing, true);
    }

    public function test_order_tidak_boleh_mundur(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->service->assertOrderTransition(OrderStatus::Processing, OrderStatus::AwaitingPayment, true);
    }

    public function test_order_tidak_boleh_ke_status_yang_sama(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('sudah Diproses');

        $this->service->assertOrderTransition(OrderStatus::Processing, OrderStatus::Processing, true);
    }

    public function test_order_selesai_dan_dibatalkan_adalah_status_akhir(): void
    {
        foreach ([OrderStatus::Completed, OrderStatus::Cancelled] as $final) {
            $this->assertSame([], $this->service->allowedOrderTransitions($final, true));

            try {
                $this->service->assertOrderTransition($final, OrderStatus::Processing, true);
                $this->fail('Seharusnya melempar FinanceRuleException untuk status akhir.');
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('status akhir', $e->getMessage());
            }
        }
    }

    public function test_order_selesai_tidak_bisa_dibatalkan_bahkan_oleh_owner(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->service->assertOrderTransition(OrderStatus::Completed, OrderStatus::Cancelled, true, 'Alasan');
    }

    public function test_admin_tidak_boleh_membatalkan_order_dari_status_mana_pun(): void
    {
        $open = [
            OrderStatus::AwaitingConfirmation,
            OrderStatus::AwaitingPayment,
            OrderStatus::Processing,
            OrderStatus::ReadyForPickup,
        ];

        foreach ($open as $from) {
            $this->assertFalse($this->service->canChangeOrderStatus($from, OrderStatus::Cancelled, false));

            try {
                $this->service->assertOrderTransition($from, OrderStatus::Cancelled, false, 'Alasan');
                $this->fail('Admin seharusnya ditolak membatalkan dari '.$from->value);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('hanya dapat dilakukan oleh Owner', $e->getMessage());
            }
        }
    }

    public function test_owner_boleh_membatalkan_order_yang_belum_selesai_dengan_alasan(): void
    {
        $open = [
            OrderStatus::AwaitingConfirmation,
            OrderStatus::AwaitingPayment,
            OrderStatus::Processing,
            OrderStatus::ReadyForPickup,
        ];

        foreach ($open as $from) {
            $this->service->assertOrderTransition($from, OrderStatus::Cancelled, true, 'Pelanggan membatalkan');
        }

        $this->addToAssertionCount(1);
    }

    public function test_pembatalan_tanpa_alasan_ditolak(): void
    {
        foreach ([null, '', '   '] as $note) {
            try {
                $this->service->assertOrderTransition(OrderStatus::Processing, OrderStatus::Cancelled, true, $note);
                $this->fail('Pembatalan tanpa alasan seharusnya ditolak.');
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('Alasan pembatalan wajib diisi', $e->getMessage());
            }
        }
    }

    public function test_pilihan_status_order_untuk_admin_tidak_memuat_dibatalkan(): void
    {
        $this->assertSame(
            [OrderStatus::Processing],
            $this->service->allowedOrderTransitions(OrderStatus::AwaitingPayment, false)
        );
    }

    public function test_pilihan_status_order_untuk_owner_memuat_dibatalkan(): void
    {
        $this->assertSame(
            [OrderStatus::Processing, OrderStatus::Cancelled],
            $this->service->allowedOrderTransitions(OrderStatus::AwaitingPayment, true)
        );
    }

    public function test_alasan_order_wajib_hanya_untuk_dibatalkan(): void
    {
        $this->assertTrue($this->service->orderReasonRequired(OrderStatus::Cancelled));
        $this->assertFalse($this->service->orderReasonRequired(OrderStatus::Processing));
    }

    // ---------------- REQUEST ----------------

    public function test_alur_request_dari_baru_sampai_disetujui(): void
    {
        $this->service->assertRequestTransition(RequestStatus::New, RequestStatus::UnderReview);
        $this->service->assertRequestTransition(RequestStatus::UnderReview, RequestStatus::QuotationSent, null, 150000);
        $this->service->assertRequestTransition(RequestStatus::QuotationSent, RequestStatus::Approved);

        $this->addToAssertionCount(1);
    }

    public function test_request_boleh_meminta_informasi_lalu_kembali_ditinjau(): void
    {
        $this->service->assertRequestTransition(RequestStatus::UnderReview, RequestStatus::AwaitingInfo);
        $this->service->assertRequestTransition(RequestStatus::AwaitingInfo, RequestStatus::UnderReview);

        $this->addToAssertionCount(1);
    }

    public function test_request_tidak_boleh_langsung_dari_baru_ke_penawaran_atau_disetujui(): void
    {
        foreach ([RequestStatus::QuotationSent, RequestStatus::Approved] as $to) {
            try {
                $this->service->assertRequestTransition(RequestStatus::New, $to, null, 100000);
                $this->fail('Seharusnya ditolak: '.$to->value);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('tidak dapat diubah dari Baru Diterima', $e->getMessage());
            }
        }
    }

    public function test_penawaran_dikirim_wajib_punya_nominal_penawaran(): void
    {
        foreach ([null, 0, -5] as $amount) {
            try {
                $this->service->assertRequestTransition(RequestStatus::UnderReview, RequestStatus::QuotationSent, null, $amount);
                $this->fail('Seharusnya ditolak tanpa nominal penawaran.');
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('nominal penawaran', $e->getMessage());
            }
        }
    }

    public function test_ditolak_dan_kedaluwarsa_wajib_alasan(): void
    {
        foreach ([RequestStatus::Rejected, RequestStatus::Expired] as $to) {
            try {
                $this->service->assertRequestTransition(RequestStatus::QuotationSent, $to, '  ');
                $this->fail('Seharusnya ditolak tanpa alasan: '.$to->value);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('Alasan wajib diisi', $e->getMessage());
            }

            $this->service->assertRequestTransition(RequestStatus::QuotationSent, $to, 'Pelanggan tidak merespons');
        }

        $this->addToAssertionCount(1);
    }

    public function test_request_bisa_ditolak_dari_status_berjalan_dengan_alasan(): void
    {
        foreach ([RequestStatus::New, RequestStatus::UnderReview, RequestStatus::AwaitingInfo, RequestStatus::QuotationSent] as $from) {
            $this->service->assertRequestTransition($from, RequestStatus::Rejected, 'Di luar kapasitas produksi');
        }

        $this->addToAssertionCount(1);
    }

    public function test_status_akhir_request_tidak_bisa_diubah(): void
    {
        foreach ([RequestStatus::Rejected, RequestStatus::Expired, RequestStatus::Converted] as $final) {
            $this->assertSame([], $this->service->allowedRequestTransitions($final));

            try {
                $this->service->assertRequestTransition($final, RequestStatus::UnderReview);
                $this->fail('Status akhir seharusnya tidak bisa diubah: '.$final->value);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('status akhir', $e->getMessage());
            }
        }
    }

    public function test_dikonversi_tidak_bisa_dipilih_lewat_ubah_status_manual(): void
    {
        foreach ([RequestStatus::Approved, RequestStatus::QuotationSent] as $from) {
            $this->assertNotContains(RequestStatus::Converted, $this->service->allowedRequestTransitions($from));
        }

        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('Konversi ke Order');

        $this->service->assertRequestTransition(RequestStatus::Approved, RequestStatus::Converted);
    }

    public function test_request_disetujui_tidak_punya_pilihan_ubah_status_manual(): void
    {
        $this->assertSame([], $this->service->allowedRequestTransitions(RequestStatus::Approved));
    }

    public function test_request_tidak_boleh_ke_status_yang_sama(): void
    {
        $this->expectException(FinanceRuleException::class);

        $this->service->assertRequestTransition(RequestStatus::UnderReview, RequestStatus::UnderReview);
    }

    // ---------------- KONVERSI ----------------

    public function test_request_disetujui_dan_belum_dikonversi_boleh_dikonversi_fin_at_002(): void
    {
        $this->service->assertRequestConvertible(RequestStatus::Approved, alreadyConverted: false);

        $this->addToAssertionCount(1);
    }

    public function test_konversi_kedua_ditolak_fin_at_002(): void
    {
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('sudah dikonversi');

        $this->service->assertRequestConvertible(RequestStatus::Converted, alreadyConverted: true);
    }

    public function test_request_yang_punya_order_sumber_tetap_ditolak_walau_status_masih_disetujui(): void
    {
        // Data tidak konsisten: status masih Disetujui tetapi order hasil konversi sudah ada.
        $this->expectException(FinanceRuleException::class);
        $this->expectExceptionMessage('sudah dikonversi');

        $this->service->assertRequestConvertible(RequestStatus::Approved, alreadyConverted: true);
    }

    public function test_request_yang_belum_disetujui_tidak_boleh_dikonversi(): void
    {
        foreach ([RequestStatus::New, RequestStatus::UnderReview, RequestStatus::AwaitingInfo, RequestStatus::QuotationSent, RequestStatus::Rejected, RequestStatus::Expired] as $status) {
            try {
                $this->service->assertRequestConvertible($status, alreadyConverted: false);
                $this->fail('Seharusnya ditolak: '.$status->value);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('Disetujui Pelanggan', $e->getMessage());
            }
        }
    }
}
