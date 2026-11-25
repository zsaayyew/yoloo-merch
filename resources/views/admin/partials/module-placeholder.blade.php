{{--
    ADM - Kerangka halaman modul yang belum diimplementasikan
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Dipakai sementara oleh halaman ADM yang sudah punya route dan menu agar
              navigasi dashboard tidak berakhir di halaman kosong.
              Variabel: $title (wajib), $requirement (wajib), $note (opsional).
--}}
<div class="p-6">
    <h1 class="text-xl font-semibold text-gray-900">{{ $title }}</h1>

    <p class="mt-2 max-w-2xl text-sm text-gray-600">
        Halaman ini sudah terhubung ke navigasi dashboard. Isi dan aksinya dikerjakan pada tahap
        <span class="font-medium text-gray-900">{{ $requirement }}</span>.
    </p>

    @isset($note)
        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $note }}</p>
    @endisset
</div>
