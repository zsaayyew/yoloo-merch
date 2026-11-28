<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Enums\RequestStatus;
use App\Http\Middleware\EnsureUserIsActive;
use App\Models\Customer;
use App\Models\OrderRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FIN-FR-003 | Alur form penawaran ke QuotationController, termasuk hak akses Admin dan Owner.
 * Tes ini menjaga perbaikan Gate: tanpa policy OrderRequest, pengecekan lama selalu menghasilkan 403.
 * Tidak merender halaman. Memakai RefreshDatabase.
 */
class QuotationAccessTest extends TestCase
{
    use RefreshDatabase;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(EnsureUserIsActive::class);

        $this->customer = Customer::create([
            'name' => 'Budi Santoso',
            'whatsapp_number' => '6281234567890',
            'is_active' => true,
        ]);
    }

    private function actor(bool $owner): User
    {
        $user = new User();
        $user->setRelation('role', (new Role())->forceFill(['name' => $owner ? Role::OWNER : 'admin']));

        return $user;
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

    public function test_admin_menyimpan_penawaran_dan_status_menjadi_penawaran_dikirim(): void
    {
        $request = $this->makeRequest(RequestStatus::UnderReview);
        $showUrl = route('admin.requests.show', $request);

        $this->actingAs($this->actor(false))
            ->from($showUrl)
            ->put(route('admin.requests.quotation.update', $request), [
                'quotation_amount' => 2150000,
                'quotation_terms' => 'DP 50% sebelum produksi',
            ])
            ->assertRedirect($showUrl)
            ->assertSessionHas('success');

        $fresh = $request->fresh();
        $this->assertSame(2150000, $fresh->quotation_amount);
        $this->assertSame('DP 50% sebelum produksi', $fresh->quotation_terms);
        $this->assertSame(RequestStatus::QuotationSent, $fresh->status);
    }

    public function test_owner_juga_boleh_menyimpan_penawaran(): void
    {
        $request = $this->makeRequest(RequestStatus::UnderReview);

        $this->actingAs($this->actor(true))
            ->from(route('admin.requests.show', $request))
            ->put(route('admin.requests.quotation.update', $request), ['quotation_amount' => 1000000])
            ->assertSessionHas('success');

        $this->assertSame(1000000, $request->fresh()->quotation_amount);
    }

    public function test_revisi_penawaran_saat_penawaran_dikirim_mengubah_nominal_tanpa_ganti_status(): void
    {
        $request = $this->makeRequest(RequestStatus::QuotationSent, 1000000);

        $this->actingAs($this->actor(false))
            ->from(route('admin.requests.show', $request))
            ->put(route('admin.requests.quotation.update', $request), ['quotation_amount' => 1200000])
            ->assertSessionHas('success');

        $fresh = $request->fresh();
        $this->assertSame(1200000, $fresh->quotation_amount);
        $this->assertSame(RequestStatus::QuotationSent, $fresh->status);
    }

    public function test_request_baru_belum_boleh_diberi_penawaran_dan_data_tetap(): void
    {
        $request = $this->makeRequest(RequestStatus::New);

        $this->actingAs($this->actor(false))
            ->from(route('admin.requests.show', $request))
            ->put(route('admin.requests.quotation.update', $request), ['quotation_amount' => 500000])
            ->assertSessionHas('error');

        $fresh = $request->fresh();
        $this->assertNull($fresh->quotation_amount);
        $this->assertSame(RequestStatus::New, $fresh->status);
    }

    public function test_nominal_nol_tidak_menyimpan_apa_pun(): void
    {
        $request = $this->makeRequest(RequestStatus::UnderReview);

        $this->actingAs($this->actor(false))
            ->from(route('admin.requests.show', $request))
            ->put(route('admin.requests.quotation.update', $request), ['quotation_amount' => 0]);

        $fresh = $request->fresh();
        $this->assertNull($fresh->quotation_amount);
        $this->assertSame(RequestStatus::UnderReview, $fresh->status);
    }
}
