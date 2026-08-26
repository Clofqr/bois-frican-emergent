document.addEventListener('DOMContentLoaded', function () {

    /* Menus déroulants (Découverte, Infos Pratiques...) */
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(function (dropdown) {
        const btn = dropdown.querySelector('.dropbtn');
        const menu = dropdown.querySelector('.dropdown-content');
        if (!btn || !menu) return;

        btn.addEventListener('click', function (e) {
            // Sur mobile (menu hamburger visible) : le clic ouvre/ferme le sous-menu
            // sans naviguer vers la page. Sur desktop : navigation classique.
            if (window.matchMedia('(max-width: 900px)').matches) {
                e.preventDefault();
                e.stopPropagation();
                // Ferme les autres dropdowns
                dropdowns.forEach(function (other) {
                    if (other !== dropdown) {
                        const otherMenu = other.querySelector('.dropdown-content');
                        if (otherMenu) otherMenu.classList.remove('show');
                    }
                });
                menu.classList.toggle('show');
            }
        });
    });

    // Ferme les dropdowns au clic en dehors
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown')) {
            dropdowns.forEach(function (dropdown) {
                const menu = dropdown.querySelector('.dropdown-content');
                if (menu) menu.classList.remove('show');
            });
        }
    });

    /* Menu hamburger */
    const hamburger = document.querySelector('.hamburger-btn');
    const nav = document.querySelector('.nav-links');

    if (hamburger && nav) {
        hamburger.setAttribute('aria-label', 'Ouvrir le menu');
        hamburger.setAttribute('aria-expanded', 'false');

        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = nav.classList.toggle('active');
            hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            hamburger.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
        });
    }

});
