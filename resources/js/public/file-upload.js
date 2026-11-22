/**
 * PRD-03 ORD — Unggah lampiran satu per satu (ORD-FR-011).
 *
 * Mencari elemen [data-file-upload] (komponen <x-form.file-upload>),
 * mengunggah setiap file yang dipilih ke route attachments.store, dan menampilkan daftarnya.
 * Selama ada file yang masih diunggah, tombol kirim formulir dikunci lewat
 * form.dataset.submitBlocked (dibaca shared/submit-guard.js).
 *
 * Pengecekan jenis dan ukuran di sini hanya untuk kenyamanan pengguna.
 * Penentu sebenarnya tetap validasi server (UploadAttachmentRequest).
 */

const ROOT_SELECTOR = '[data-file-upload]';

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

function firstErrorMessage(payload, fallback) {
    if (payload?.errors) {
        const first = Object.values(payload.errors)[0];
        if (Array.isArray(first) && first.length) {
            return first[0];
        }
    }
    return payload?.message || fallback;
}

function buildItem({ id, original_name: name, size, delete_url: deleteUrl }) {
    const li = document.createElement('li');
    li.className = 'flex items-center justify-between gap-3 rounded-lg border border-gray-200 px-3 py-2 text-sm';
    li.dataset.attachmentId = id;

    const info = document.createElement('span');
    info.className = 'min-w-0';

    const nameEl = document.createElement('span');
    nameEl.className = 'block truncate font-medium text-gray-900';
    nameEl.textContent = name; // textContent: aman dari HTML berbahaya di nama file

    const sizeEl = document.createElement('span');
    sizeEl.className = 'text-xs text-gray-500';
    sizeEl.textContent = size;

    info.append(nameEl, sizeEl);

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'shrink-0 text-sm font-medium text-red-600 hover:text-red-800';
    button.dataset.deleteUrl = deleteUrl;
    button.textContent = 'Hapus';

    li.append(info, button);
    return li;
}

function buildPendingItem(file) {
    const li = document.createElement('li');
    li.className = 'flex items-center justify-between gap-3 rounded-lg border border-dashed border-gray-300 px-3 py-2 text-sm text-gray-500';

    const nameEl = document.createElement('span');
    nameEl.className = 'min-w-0 truncate';
    nameEl.textContent = file.name;

    const status = document.createElement('span');
    status.className = 'shrink-0 text-xs';
    status.textContent = 'Mengunggah...';

    li.append(nameEl, status);
    return li;
}

function setup(root) {
    const input = root.querySelector('input[type="file"]');
    const list = root.querySelector('[data-file-list]');
    const errorBox = root.querySelector('[data-file-error]');
    const counter = root.querySelector('[data-file-count]');
    const form = root.closest('form');

    const uploadUrl = root.dataset.uploadUrl;
    const maxFiles = Number(root.dataset.maxFiles || 5);
    const maxBytes = Number(root.dataset.maxBytes || 10 * 1024 * 1024);
    const allowedTypes = (root.dataset.allowedTypes || '').split(',').filter(Boolean);

    let pending = 0;

    const currentCount = () => list.querySelectorAll('[data-attachment-id]').length;

    // Satu formulir bisa berisi beberapa kotak upload (misalnya satu per produk di checkout),
    // jadi jumlah unggahan yang berjalan dihitung bersama di form.dataset.uploadsPending.
    const changeFormPending = (delta) => {
        if (!form) {
            return;
        }
        const total = Math.max(0, Number(form.dataset.uploadsPending || 0) + delta);
        form.dataset.uploadsPending = String(total);
        if (total > 0) {
            form.dataset.submitBlocked = 'true';
        } else {
            delete form.dataset.submitBlocked;
        }
    };

    const refresh = () => {
        if (counter) {
            counter.textContent = String(currentCount());
        }
    };

    const showError = (message) => {
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    };

    const clearError = () => {
        errorBox.textContent = '';
        errorBox.classList.add('hidden');
    };

    async function upload(file) {
        if (allowedTypes.length && !allowedTypes.includes(file.type)) {
            showError(`"${file.name}" tidak didukung. Gunakan JPG, PNG, atau PDF.`);
            return;
        }
        if (file.size > maxBytes) {
            showError(`"${file.name}" lebih dari 10 MB.`);
            return;
        }
        if (currentCount() + pending >= maxFiles) {
            showError(`Maksimal ${maxFiles} file.`);
            return;
        }

        const placeholder = buildPendingItem(file);
        list.append(placeholder);
        pending += 1;
        changeFormPending(1);
        refresh();

        const body = new FormData();
        body.append('file', file);
        if (root.dataset.cartItemId) {
            body.append('cart_item_id', root.dataset.cartItemId); // lampiran desain per item checkout
        }

        try {
            const response = await fetch(uploadUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken(), Accept: 'application/json' },
                body,
            });
            const payload = await response.json().catch(() => null);

            if (!response.ok) {
                const fallback = response.status === 429
                    ? 'Terlalu banyak percobaan. Tunggu sebentar lalu coba lagi.'
                    : `"${file.name}" gagal diunggah.`;
                showError(firstErrorMessage(payload, fallback));
                placeholder.remove();
                return;
            }

            placeholder.replaceWith(buildItem(payload));
        } catch {
            showError(`"${file.name}" gagal diunggah. Periksa koneksi internet lalu coba lagi.`);
            placeholder.remove();
        } finally {
            pending -= 1;
            changeFormPending(-1);
            refresh();
        }
    }

    async function remove(button) {
        const item = button.closest('[data-attachment-id]');
        button.disabled = true;
        clearError();

        try {
            const response = await fetch(button.dataset.deleteUrl, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken(), Accept: 'application/json' },
            });

            // 404 berarti file sudah tidak ada (misalnya sudah dibersihkan), jadi tetap dihapus dari daftar.
            if (!response.ok && response.status !== 404) {
                throw new Error('delete failed');
            }
            item?.remove();
        } catch {
            showError('File gagal dihapus. Coba lagi.');
            button.disabled = false;
        } finally {
            refresh();
        }
    }

    input.addEventListener('change', async () => {
        const files = Array.from(input.files || []);
        input.value = ''; // supaya file yang sama bisa dipilih lagi
        clearError();

        for (const file of files) {
            // Diunggah berurutan supaya batas jumlah file terjaga.
            await upload(file);
        }
    });

    list.addEventListener('click', (event) => {
        const button = event.target.closest('[data-delete-url]');
        if (button) {
            event.preventDefault();
            remove(button);
        }
    });

    form?.addEventListener('submit', () => {
        if (pending > 0) {
            showError('Tunggu sampai semua file selesai diunggah.');
        }
    });

    refresh();
}

export function initFileUpload() {
    document.querySelectorAll(ROOT_SELECTOR).forEach(setup);
}
