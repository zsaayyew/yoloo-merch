// CP-FR-003 - Menu navigasi mobile
// Domain : PRD-01 CP  - Company Profile (Bertrand)
// Status : modul tersedia; import entry point bersama masih menunggu koordinasi.

document.querySelectorAll('[data-mobile-nav-toggle]').forEach((toggle) => {
    const controlledId = toggle.getAttribute('aria-controls');
    const navigation = controlledId ? document.getElementById(controlledId) : null;

    if (!navigation) {
        return;
    }

    const label = toggle.querySelector('[data-mobile-nav-label]');

    const setExpanded = (expanded) => {
        toggle.setAttribute('aria-expanded', String(expanded));
        navigation.hidden = !expanded;

        if (label) {
            label.textContent = expanded ? 'Tutup menu' : 'Menu';
        }
    };

    toggle.addEventListener('click', () => {
        setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
    });

    navigation.addEventListener('click', (event) => {
        if (event.target.closest('a[href]')) {
            setExpanded(false);
        }
    });

    navigation.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            setExpanded(false);
            toggle.focus();
        }
    });
});
