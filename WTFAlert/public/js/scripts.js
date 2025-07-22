document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.nav-links');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            nav.classList.toggle('open');
        });
    }

    console.log('JS chargé avec succès');
});
