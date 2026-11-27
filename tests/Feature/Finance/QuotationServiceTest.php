<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\RequestStatus;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\OrderRequest;
use App\Services\Finance\FinanceRuleException;
use App\Services\Sales\QuotationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FIN-FR-003 | FLOW-FIN-001 langkah 4
 * Memakai RefreshDatabase (phpunit.xml memakai SQLite :memory:).
 */
class QuotationServiceTest extends TestCase
{
    use RefreshDatabase;

    private QuotationService $service;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(QuotationService::class);
        $this->customer = Customer::create([
            'name' => 'Budi Santoso',
            'whatsapp_number' => '6281234567890',
            'is_active' => true,
        ]);
    }

    private function makeRequest(RequestStatus $status, ?int $amount = null): OrderRequest
    {
        return OrderRequest::create([
            'code' => 'REQ-261126-'.$status->value,
            'customer_id' => $this->customer->id,
            'status' => $status,
            'description' => 'Kaos komunitas 50 pcs',
            'quantity' => 50,
            'quotation_amount' => $amount,
            'submitted_at' => now(),
        ]);
    }

    public function test_penawaran_saat_ditinjau_disimpan_dan_status_menjadi_penawaran_dikirim(): void
    {
        $request = $this->makeRequest(RequestStatus::UnderReview);

        $updated = $this->service->save($request, 2150000, 'DP 50% sebelum produksi');

        $this->assertSame(RequestStatus::QuotationSent, $updated->status);
        $this->assertSame(2150000, $updated->quotation_amount);
        $this->assertSame('DP 50% sebelum produksi', $updated->quotation_terms);

        $fresh = $request->fresh();
        $this->assertSame(RequestStatus::QuotationSent, $fresh->status);
        $this->assertSame(2150000, $fresh->quotation_amount);
        $this->assertSame(1, AuditLog::where('action', 'request.status_changed')->count());
    }

    public function test_revisi_saat_penawaran_dikirim_mengubah_isi_tanpa_mengubah_status_dan_tercatat(): void
    {
        $request = $this->makeRequest(RequestStatus::QuotationSent, 1000000);

        $updated = $this->service->save($request, 1200000, 'Syarat baru');

        $this->assertSame(RequestStatus::QuotationSent, $updated->status);
        $this->assertSame(1200000, $request->fresh()->quotation_amount);

        $audit = AuditLog::where('action', 'request.quotation_revised')->first();
        $this->assertNotNull($audit);
        $this->assertSame(1000000, $audit->changes['quotation_amount']['old']);
        $this->assertSame(1200000, $audit->changes['quotation_amount']['new']);
        $this->assertSame(0, AuditLog::where('action', 'request.status_changed')->count());
    }

    public function test_syarat_kosong_disimpan_sebagai_null(): void
    {
        $request = $this->makeRequest(RequestStatus::UnderReview);

        $this->service->save($request, 500000, '   ');

        $this->assertNull($request->fresh()->quotation_terms);
    }

    public function test_status_lain_tidak_boleh_diberi_penawaran_dan_data_tetap(): void
    {
        $others = [
            RequestStatus::New,
            RequestStatus::AwaitingInfo,
            RequestStatus::Approved,
            RequestStatus::Rejected,
            RequestStatus::Expired,
            RequestStatus::Converted,
        ];

        foreach ($others as $status) {
            $request = $this->makeRequest($status, 1000000);

            try {
                $this->service->save($request, 2000000, null);
                $this->fail('Seharusnya ditolak pada status '.$status->value);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('Penawaran hanya bisa dibuat', $e->getMessage());
            }

            $fresh = $request->fresh();
            $this->assertSame($status, $fresh->status);
            $this->assertSame(1000000, $fresh->quotation_amount);
        }
    }

    public function test_nominal_nol_atau_negatif_ditolak_dan_status_tetap_ditinjau(): void
    {
        $request = $this->makeRequest(RequestStatus::UnderReview);

        foreach ([0, -1000] as $amount) {
            try {
                $this->service->save($request, $amount, null);
                $this->fail('Nominal tidak valid seharusnya ditolak: '.$amount);
            } catch (FinanceRuleException $e) {
                $this->assertStringContainsString('lebih dari nol', $e->getMessage());
            }
        }

        $fresh = $request->fresh();
        $this->assertSame(RequestStatus::UnderReview, $fresh->status);
        $this->assertNull($fresh->quotation_amount);
    }

    public function test_hanya_status_ditinjau_dan_penawaran_dikirim_yang_bisa_diberi_penawaran(): void
    {
        $this->assertTrue(QuotationService::canQuote($this->makeRequest(RequestStatus::UnderReview)));
        $this->assertTrue(QuotationService::canQuote($this->makeRequest(RequestStatus::QuotationSent)));
        $this->assertFalse(QuotationService::canQuote($this->makeRequest(RequestStatus::New)));
        $this->assertFalse(QuotationService::canQuote($this->makeRequest(RequestStatus::Approved)));
    }
}
