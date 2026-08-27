document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       Menus déroulants (Découverte, Infos Pratiques...)
       - Desktop : 1er clic = ouvre le sous-menu (empêche navigation),
                   2e clic sur le même bouton = navigue vers la page.
                   Le survol ouvre aussi le sous-menu.
       - Mobile  : le clic sur le bouton principal ouvre / ferme le
                   sous-menu (jamais de navigation immédiate).
       - Ferme quand : clic à l'extérieur, scroll qui sort le bouton
                        du champ de vision, touche Echap, perte de focus.
       ============================================================ */
    const dropdowns = document.querySelectorAll('.dropdown');

    function closeAllDropdowns(except) {
        dropdowns.forEach(function (d) {
            if (d === except) return;
            const m = d.querySelector('.dropdown-content');
            if (m) m.classList.remove('show');
        });
    }

    dropdowns.forEach(function (dropdown) {
        const btn = dropdown.querySelector('.dropbtn');
        const menu = dropdown.querySelector('.dropdown-content');
        if (!btn || !menu) return;

        btn.addEventListener('click', function (e) {
            const isOpen = menu.classList.contains('show');
            const isMobile = window.matchMedia('(max-width: 900px)').matches;

            // Sur mobile (menu hamburger) : navigation directe vers la page,
            // pas d'ouverture de sous-menu. Les sous-items restent accessibles
            // sur la page cible.
            if (isMobile) {
                return; // laisse le lien naviguer normalement
            }

            if (!isOpen) {
                // Desktop, premier clic : ouvre le sous-menu, empêche la navigation
                e.preventDefault();
                e.stopPropagation();
                closeAllDropdowns(dropdown);
                menu.classList.add('show');
                return;
            }
            // Desktop, deuxième clic : laisse naviguer vers la page
        });

        // Survol : ouvre sur desktop uniquement
        dropdown.addEventListener('mouseenter', function () {
            if (window.matchMedia('(min-width: 901px)').matches) {
                closeAllDropdowns(dropdown);
                menu.classList.add('show');
            }
        });
        dropdown.addEventListener('mouseleave', function () {
            if (window.matchMedia('(min-width: 901px)').matches) {
                menu.classList.remove('show');
            }
        });

        // Ferme quand le bouton sort du champ de vision (scroll)
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        menu.classList.remove('show');
                    }
                });
            }, { threshold: 0.1 });
            io.observe(btn);
        }
    });

    // Clic en dehors : ferme tous les sous-menus
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown')) closeAllDropdowns(null);
    });

    // Touche Echap : ferme tous les sous-menus
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAllDropdowns(null);
    });

    /* ============================================================
       Menu hamburger
       ============================================================ */
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
            if (!isOpen) closeAllDropdowns(null);
        });
    }

    /* ============================================================
       Feedback formulaire de contact : scroll et disparition auto
       ============================================================ */
    const feedback = document.querySelector('.feedback-success, .feedback-error');
    if (feedback) {
        setTimeout(function () {
            feedback.scrollIntoView({ behavior: 'smooth', block: 'center' });
            feedback.focus && feedback.focus();
        }, 100);
        // Retire le message succès après 10s pour libérer l'espace visuel
        if (feedback.classList.contains('feedback-success')) {
            setTimeout(function () {
                feedback.style.transition = 'opacity 0.6s ease';
                feedback.style.opacity = '0';
                setTimeout(function () { feedback.remove(); }, 700);
            }, 10000);
        }
    }
});
