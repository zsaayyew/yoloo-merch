// CP-FR-011 / CP-FR-012: filter published gallery cards and manage the page-owned dialog.
document.querySelectorAll('[data-gallery-grid]').forEach((grid) => {
    const section = grid.closest('section');
    const cards = [...grid.querySelectorAll('[data-gallery-item]')];
    const filters = section?.querySelectorAll('[data-gallery-filter]') ?? [];
    const emptyState = section?.querySelector('[data-gallery-empty]');
    const status = section?.querySelector('[data-gallery-filter-status]');
    const dialog = section?.querySelector('[data-gallery-lightbox]');
    const dialogImage = dialog?.querySelector('[data-gallery-dialog-image]');
    const dialogCaption = dialog?.querySelector('[data-gallery-dialog-caption]');
    let opener = null;

    if (!section || !cards.length) return;

    const applyFilter = (category) => {
        let visibleCount = 0;

        cards.forEach((card) => {
            const visible = category === 'all' || card.dataset.category === category;
            card.hidden = !visible;
            visibleCount += Number(visible);
        });

        filters.forEach((filter) => {
            const selected = filter.dataset.galleryFilter === category;
            filter.setAttribute('aria-pressed', String(selected));
        });

        if (emptyState) emptyState.hidden = visibleCount > 0;
        if (status) status.textContent = `${visibleCount} foto ditampilkan.`;
    };

    filters.forEach((filter) => filter.addEventListener('click', () => applyFilter(filter.dataset.galleryFilter ?? 'all')));
    section.querySelector('[data-gallery-reset]')?.addEventListener('click', () => {
        applyFilter('all');
        filters[0]?.focus();
    });

    if (!dialog || !dialogImage) return;

    section.addEventListener('click', (event) => {
        const button = event.target instanceof Element ? event.target.closest('[data-gallery-open]') : null;
        if (!button) return;

        const source = button.dataset.fullSrc;
        if (!source) return;

        opener = button;
        dialogImage.src = source;
        dialogImage.alt = button.dataset.alt ?? '';
        if (dialogCaption) dialogCaption.textContent = button.dataset.caption || button.dataset.alt || 'Foto galeri diperbesar';
        dialog.showModal();
        dialog.querySelector('[data-gallery-close]')?.focus();
    });

    dialog.querySelector('[data-gallery-close]')?.addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dialog.close();
    });
    dialog.addEventListener('close', () => {
        dialogImage.removeAttribute('src');
        if (opener instanceof HTMLElement) opener.focus();
        opener = null;
    });
});
