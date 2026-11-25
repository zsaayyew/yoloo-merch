{{--
    ADM - Kerangka halaman modul yang belum diimplementasikan
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Dipakai sementara oleh halaman ADM yang sudah punya route dan menu agar
              navigasi dashboard tidak berakhir di halaman kosong.
              Variabel: $title (wajib), $requirement (wajib), $note (opsional).
              Warna mengikuti palet website publik (teal #14747b, #124f53, ink #181818).
--}}
<div class="min-h-[60vh] bg-[#f8f8f8] p-4 sm:p-6">
    <div class="mx-auto max-w-3xl rounded-3xl border border-[#181818]/10 bg-white p-6 shadow-sm sm:p-8">
        <p class="inline-flex rounded-full bg-[#DDF3F0] px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-[#124f53]">
            Segera hadir
        </p>

        <h1 class="mt-4 text-2xl font-black tracking-tight text-[#181818]">{{ $title }}</h1>

        <p class="mt-3 text-sm leading-7 text-[#181818]/70">
            Halaman ini sudah terhubung ke navigasi dashboard. Isi dan aksinya dikerjakan pada tahap
            <span class="font-bold text-[#14747b]">{{ $requirement }}</span>.
        </p>

        @isset($note)
            <p class="mt-2 text-sm leading-7 text-[#181818]/60">{{ $note }}</p>
        @endisset
    </div>
</div>
