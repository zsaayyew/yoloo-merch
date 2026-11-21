<?php

namespace App\Services\Ordering;

use App\Enums\OrderChannel;
use App\Enums\RequestStatus;
use App\Models\Customer;
use App\Models\OrderRequest;
use App\Models\Product;
use App\Models\SubmissionToken;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * PRD-03 ORD — Penyimpanan custom order request (ORD-FR-010, ORD-FR-012, ORD-FR-013).
 *
 * Semua langkah dalam satu DB::transaction():
 *   1. kunci dan cek token sekali pakai
 *   2. cari/buat pelanggan dari nomor WhatsApp
 *   3. buat order_requests dengan status "new" dan kode REQ-YYMMDD-XXXX
 *   4. pindahkan lampiran sementara milik sesi ke request ini
 *   5. tandai token terpakai
 * Setelah tersimpan, ORD tidak mengubah status lagi (itu tugas FIN).
 */
class CustomRequestSubmissionService
{
    private const MAX_CODE_ATTEMPTS = 3;

    public function __construct(
        private readonly OrderCodeGenerator $codeGenerator,
        private readonly AttachmentService $attachmentService,
    ) {}

    /** Buat token baru untuk satu kali tampil formulir. */
    public function issueToken(string $sessionId): SubmissionToken
    {
        return SubmissionToken::create([
            'token' => Str::random(64),
            'form_type' => SubmissionToken::TYPE_CUSTOM_ORDER,
            'session_id' => $sessionId,
            'expires_at' => now()->addHours(SubmissionToken::LIFETIME_HOURS),
        ]);
    }

    /**
     * Simpan request. Jika token sudah pernah dipakai oleh sesi yang sama
     * (tombol ditekan dua kali), kembalikan request yang sudah ada.
     *
     * @param  array<string, mixed>  $data  hasil CustomOrderRequest::validated()
     *
     * @throws ValidationException jika token tidak valid/kedaluwarsa
     */
    public function submit(array $data, ?Product $product, string $sessionId, ?string $uploadToken = null): OrderRequest
    {
        return DB::transaction(function () use ($data, $product, $sessionId, $uploadToken) {
            $token = SubmissionToken::query()
                ->where('token', $data['submission_token'])
                ->where('form_type', SubmissionToken::TYPE_CUSTOM_ORDER)
                ->lockForUpdate()
                ->first();

            if (! $token || $token->session_id !== $sessionId) {
                $this->failToken();
            }

            if ($token->isUsed() && $token->order_request_id) {
                return $token->orderRequest;
            }

            if ($token->isExpired()) {
                $this->failToken();
            }

            $customer = Customer::resolveFromContact($data);
            $orderRequest = $this->createRequest($data, $customer, $product);

            if ($uploadToken) {
                $this->attachmentService->attachToRequest($orderRequest, $uploadToken);
            }

            $token->update([
                'used_at' => now(),
                'order_request_id' => $orderRequest->id,
            ]);

            return $orderRequest;
        });
    }

    private function createRequest(array $data, Customer $customer, ?Product $product): OrderRequest
    {
        $attributes = [
            'customer_id' => $customer->id,
            'channel' => OrderChannel::Website,
            'status' => RequestStatus::New,
            'product_id' => $product?->id,
            'product_name_snapshot' => $product?->name,
            'description' => $data['description'],
            'quantity' => (int) $data['quantity'],
            'specification' => $data['specification'] ?? null,
            'desired_deadline' => $data['desired_deadline'] ?? null,
            'budget' => isset($data['budget']) && $data['budget'] !== '' ? (int) $data['budget'] : null,
            'notes' => $data['notes'] ?? null,
            'submitted_at' => now(),
        ];

        // Index unique pada kolom code adalah penjaga terakhir; jika bentrok, buat kode baru.
        for ($attempt = 1; ; $attempt++) {
            try {
                return OrderRequest::create($attributes + ['code' => $this->codeGenerator->forRequest()]);
            } catch (UniqueConstraintViolationException $e) {
                if ($attempt >= self::MAX_CODE_ATTEMPTS) {
                    throw $e;
                }
            }
        }
    }

    private function failToken(): never
    {
        throw ValidationException::withMessages([
            'submission_token' => 'Formulir sudah kedaluwarsa. Periksa kembali isianmu, lalu kirim ulang.',
        ]);
    }
}
