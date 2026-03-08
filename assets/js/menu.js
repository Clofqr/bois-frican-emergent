document.addEventListener('DOMContentLoaded', function () {

    /* Menu déroulant */
    const btn = document.querySelector('.dropbtn');
    const menu = document.querySelector('.dropdown-content');

    if (btn && menu) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.toggle('show');
        });

        document.addEventListener('click', function () {
            menu.classList.remove('show');
        });
    }

    /* Menu hamburger */
    const hamburger = document.querySelector('.hamburger-btn');
    const nav = document.querySelector('.nav-links');

    if (hamburger && nav) {
        hamburger.addEventListener('click', function () {
            nav.classList.toggle('active');
        });
    }

});