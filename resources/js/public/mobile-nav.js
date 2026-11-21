// CP-FR-003: progressive enhancement for the native mobile navigation disclosure.
document.querySelectorAll('[data-mobile-nav]').forEach((disclosure) => {
    const toggle = disclosure.querySelector('[data-mobile-nav-toggle]');
    const navigation = disclosure.querySelector('#public-mobile-navigation');

    if (!(disclosure instanceof HTMLDetailsElement) || !toggle || !navigation) {
        return;
    }

    const label = toggle.querySelector('[data-mobile-nav-label]');
    const syncState = () => {
        toggle.setAttribute('aria-expanded', String(disclosure.open));
        if (label) label.textContent = disclosure.open ? 'Tutup menu' : 'Menu';
    };

    syncState();
    disclosure.addEventListener('toggle', syncState);

    navigation.addEventListener('click', (event) => {
        if (event.target instanceof Element && event.target.closest('a[href]')) {
            disclosure.open = false;
            syncState();
        }
    });

    disclosure.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && disclosure.open) {
            disclosure.open = false;
            syncState();
            toggle.focus();
        }
    });
});
