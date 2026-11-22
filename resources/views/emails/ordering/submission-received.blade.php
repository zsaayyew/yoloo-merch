{{--
    EMAIL - Tanda terima pesanan / custom request
    Domain  : PRD-03 ORD (Raisyah)
    Catatan : ORD-FR-015. CSS inline karena klien email tidak memuat Tailwind.
              TIDAK berisi QRIS, rekening, atau instruksi pembayaran (ORD-BR-012).
    Status  : diimplementasikan.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $isCustomRequest ? 'Permintaan custom order diterima' : 'Pesanan diterima' }}</title>
</head>
<body style="margin:0;padding:24px;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:12px;">
        <tr>
            <td style="padding:28px;">
                <p style="margin:0 0 4px;font-size:14px;color:#6b7280;">YOLOO Merch</p>
                <h1 style="margin:0 0 16px;font-size:22px;">
                    {{ $isCustomRequest ? 'Permintaan custom order diterima' : 'Pesanan diterima' }}
                </h1>

                <p style="margin:0 0 16px;font-size:15px;">Halo {{ $submission->customer->name }},</p>
                <p style="margin:0 0 16px;font-size:15px;line-height:1.5;">
                    @if ($isCustomRequest)
                        Terima kasih, permintaanmu sudah kami terima. Admin akan meninjau kebutuhanmu lalu
                        menghubungimu melalui WhatsApp untuk penawaran harga.
                    @else
                        Terima kasih, pesananmu sudah kami terima dengan status <strong>{{ $submission->status->label() }}</strong>.
                        Admin akan menghubungimu melalui WhatsApp untuk konfirmasi harga final.
                    @endif
                </p>

                <div style="margin:0 0 20px;padding:16px;background:#f9fafb;border-radius:8px;text-align:center;">
                    <p style="margin:0;font-size:13px;color:#6b7280;">{{ $isCustomRequest ? 'Kode permintaan' : 'Kode pesanan' }}</p>
                    <p style="margin:4px 0 0;font-size:22px;font-weight:bold;letter-spacing:2px;font-family:monospace;">{{ $submission->code }}</p>
                </div>

                @if ($isCustomRequest)
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                        @if ($submission->product_name_snapshot)
                            <tr><td style="padding:4px 0;color:#6b7280;">Referensi produk</td><td style="padding:4px 0;">{{ $submission->product_name_snapshot }}</td></tr>
                        @endif
                        <tr><td style="padding:4px 0;color:#6b7280;">Jumlah</td><td style="padding:4px 0;">{{ number_format($submission->quantity, 0, ',', '.') }}</td></tr>
                        <tr><td style="padding:4px 0;color:#6b7280;vertical-align:top;">Kebutuhan</td><td style="padding:4px 0;">{{ \Illuminate\Support\Str::limit($submission->description, 300) }}</td></tr>
                    </table>
                @else
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;border-collapse:collapse;">
                        @foreach ($submission->items as $item)
                            <tr>
                                <td style="padding:8px 0;border-bottom:1px solid #e5e7eb;">
                                    <strong>{{ $item->product_name_snapshot }}</strong><br>
                                    @foreach ($item->options_snapshot ?? [] as $option)
                                        <span style="color:#6b7280;">{{ $option['group'] }}: {{ $option['value'] }}</span><br>
                                    @endforeach
                                    <span style="color:#6b7280;">{{ number_format($item->quantity, 0, ',', '.') }} pcs</span>
                                </td>
                                <td style="padding:8px 0;border-bottom:1px solid #e5e7eb;text-align:right;white-space:nowrap;">
                                    {{ \App\Support\Money::format((int) $item->subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="padding:10px 0;font-weight:bold;">Total estimasi</td>
                            <td style="padding:10px 0;font-weight:bold;text-align:right;">{{ \App\Support\Money::format((int) $submission->estimated_total) }}</td>
                        </tr>
                    </table>
                    <p style="margin:4px 0 0;font-size:12px;color:#6b7280;">Harga di atas adalah estimasi. Harga final dikonfirmasi admin.</p>
                @endif

                <div style="margin:20px 0 0;padding:16px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;">
                    <p style="margin:0 0 6px;font-weight:bold;">Pengambilan pesanan</p>
                    <p style="margin:0;">Pesanan diambil langsung di lokasi YOLOO Merch.</p>
                    <p style="margin:6px 0 0;color:#6b7280;">Lokasi: {{ $pickup['location'] ?? 'akan dikonfirmasi admin melalui WhatsApp' }}</p>
                    <p style="margin:2px 0 0;color:#6b7280;">Jam: {{ $pickup['hours'] ?? 'akan dikonfirmasi admin melalui WhatsApp' }}</p>
                </div>

                <p style="margin:20px 0 0;font-size:13px;color:#6b7280;line-height:1.5;">
                    Simpan kode di atas untuk disebutkan saat menghubungi admin.
                    Email ini dikirim otomatis, mohon tidak membalas email ini.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
