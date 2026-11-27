{{--
    ADM - Pesan sukses dan galat untuk halaman dashboard
    Domain  : PRD-04 ADM - Admin Dashboard & CMS (Taufik)
    Catatan : Partial khusus domain ADM. Menampilkan session('status'), session('error'),
              dan daftar galat validasi agar setiap aksi memberi umpan balik (Master PRD bab 11).
--}}
@if (session('status'))
    <div role="status"
         class="rounded-2xl border border-[#14747b]/30 bg-[#DDF3F0] px-4 py-3 text-sm font-semibold text-[#124f53]">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div role="alert"
         class="rounded-2xl border border-[#b3261e]/30 bg-[#fdecea] px-4 py-3 text-sm font-semibold text-[#b3261e]">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div role="alert" class="rounded-2xl border border-[#b3261e]/30 bg-[#fdecea] px-4 py-3 text-sm text-[#b3261e]">
        <p class="font-bold">Periksa kembali isian berikut:</p>
        <ul class="mt-1 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
