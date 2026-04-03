const menu = document.querySelector("#js-menu");
const navBarToggle = document.querySelector("#js-navbar-toggle")

navBarToggle.addEventListener("click", function(){
    menu.classList.toggle("active");
    navBarToggle.classList.toggle("change");
})

document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".hero-slide");
    const dots = document.querySelectorAll(".hero-dot");

    if (!slides.length || !dots.length) {
        return;
    }

    let indexActuel = 0;
    let intervalle = null;

    function afficherSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle("actif", i === index);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle("actif", i === index);
        });

        indexActuel = index;
    }

    function slideSuivante() {
        const prochainIndex = (indexActuel + 1) % slides.length;
        afficherSlide(prochainIndex);
    }

    dots.forEach((dot, i) => {
        dot.addEventListener("click", function () {
            afficherSlide(i);
            redemarrerAutoSlide();
        });
    });

    function demarrerAutoSlide() {
        intervalle = setInterval(slideSuivante, 5000);
    }

    function redemarrerAutoSlide() {
        clearInterval(intervalle);
        demarrerAutoSlide();
    }

    afficherSlide(0);
    demarrerAutoSlide();
});
