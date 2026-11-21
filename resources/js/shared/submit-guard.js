/**
 * Shared — Pengunci tombol kirim (ORD-FR-013, ORD-BR-011).
 *
 * Mencegah formulir terkirim dua kali karena tombol ditekan berulang.
 * Ini hanya lapisan tampilan; penjaga utama tetap SubmissionToken di server.
 *
 * Cara pakai di Blade:
 *   <form method="POST" action="..." data-submit-guard>
 *       @csrf
 *       ...
 *       <button type="submit" data-loading-text="Mengirim...">Kirim pesanan</button>
 *   </form>
 *
 * Tombol juga bisa dikunci dari skrip lain (misalnya saat file masih diunggah):
 *   form.dataset.submitBlocked = 'true';   // tombol tidak bisa dipakai
 *   delete form.dataset.submitBlocked;     // tombol aktif lagi
 */

const SELECTOR = 'form[data-submit-guard]';

function getSubmitButtons(form) {
    return form.querySelectorAll('button[type="submit"], input[type="submit"]');
}

function lockForm(form) {
    form.dataset.submitting = 'true';

    getSubmitButtons(form).forEach((button) => {
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');

        const loadingText = button.dataset.loadingText;
        if (loadingText && button.tagName === 'BUTTON') {
            button.dataset.originalText = button.textContent;
            button.textContent = loadingText;
        }
    });
}

function unlockForm(form) {
    delete form.dataset.submitting;

    getSubmitButtons(form).forEach((button) => {
        button.disabled = false;
        button.removeAttribute('aria-busy');

        if (button.dataset.originalText !== undefined) {
            button.textContent = button.dataset.originalText;
            delete button.dataset.originalText;
        }
    });
}

function handleSubmit(event) {
    const form = event.target.closest(SELECTOR);
    if (!form) {
        return;
    }

    // Sudah sedang mengirim, atau dikunci skrip lain (misalnya upload belum selesai).
    if (form.dataset.submitting === 'true' || form.dataset.submitBlocked === 'true') {
        event.preventDefault();
        return;
    }

    lockForm(form);
}

export function initSubmitGuard() {
    // Event submit hanya terpicu jika validasi HTML5 (required, dll.) lolos,
    // jadi tombol tidak terkunci saat form masih salah isi.
    document.addEventListener('submit', handleSubmit);

    // Saat pengguna menekan Back, browser bisa menampilkan halaman dari cache
    // dengan tombol masih terkunci. Buka lagi kuncinya.
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            document.querySelectorAll(SELECTOR).forEach(unlockForm);
        }
    });
}
