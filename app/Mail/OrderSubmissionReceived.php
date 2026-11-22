<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\OrderRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * PRD-03 ORD — Email tanda terima pesanan / custom request (ORD-FR-015, ORD-AT-017).
 * Berisi kode dan ringkasan pengajuan. TIDAK berisi QRIS atau instruksi pembayaran (ORD-BR-012).
 */
class OrderSubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{location: string|null, hours: string|null}  $pickup
     */
    public function __construct(
        public Order|OrderRequest $submission,
        public array $pickup,
    ) {}

    public function envelope(): Envelope
    {
        $label = $this->isCustomRequest() ? 'Permintaan custom order' : 'Pesanan';

        return new Envelope(
            subject: "{$label} {$this->submission->code} diterima - YOLOO Merch",
        );
    }

    public function content(): Content
    {
        $this->submission->loadMissing($this->isCustomRequest() ? ['customer'] : ['customer', 'items']);

        return new Content(
            view: 'emails.ordering.submission-received',
            with: [
                'submission' => $this->submission,
                'isCustomRequest' => $this->isCustomRequest(),
                'pickup' => $this->pickup,
            ],
        );
    }

    public function isCustomRequest(): bool
    {
        return $this->submission instanceof OrderRequest;
    }
}
