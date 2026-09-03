<?php
$title = "Découverte";
$headerImage = "images/decouverte_cover.jpg";
$headerContent = '
    <h1>Découvrez la ferme et ses animaux</h1>
';

function capture_include(string $filePath): string {
    ob_start();
    include $filePath;
    return ob_get_clean();
}

$ateliersHtml = capture_include(__DIR__ . '/fbf_ateliers.php');
$animauxHtml  = capture_include(__DIR__ . '/fbf_animaux.php');
$fermeHtml    = capture_include(__DIR__ . '/fbf_ferme.php');

$mainContent = 
    '<section id="ateliers">'
    ."{$ateliersHtml}"
    .'</section>'
    .'<section id="animaux">'
    ."{$animauxHtml}"
    .'</section>'
    .'<section id="ferme">'
    ."{$fermeHtml}"
    .'</section>'
    .'<script>'
    .'window.onload = () => {'
    .'document.querySelectorAll(".toggle-trigger").forEach(el => {'
    .'el.addEventListener("click", () => {'
    .'const id = el.dataset.target;'
    .'const target = document.getElementById(id);'
    .'if (target) {'
    .'target.scrollIntoView({ behavior: "smooth", block: "start" });'
    .'}'
    .'});'
    .'});'
    .'if (location.hash) {'
    .'const anchor = document.querySelector(location.hash);'
    .'if (anchor) {'
    .'setTimeout(() => {'
    .'anchor.scrollIntoView({ behavior: "smooth", block: "start" });'
    .'}, 50);'
    .'}'
    .'}'
    .'const carouselSlide = document.querySelector(".carousel-slide");'
    .'const carouselImages = document.querySelectorAll(".carousel-slide img");'
    .'const prevBtn = document.querySelector(".carousel-prev");'
    .'const nextBtn = document.querySelector(".carousel-next");'
    .'if (!carouselSlide || carouselImages.length === 0 || !prevBtn || !nextBtn) {'
    .'return;'
    .'}'
    .'let counter = 0;'
    .'function updateCarouselPosition() {'
    .'const size = carouselImages[0].clientWidth;'
    .'carouselSlide.style.transform = `translateX(${-size * counter}px)`;'
    .'}'
    .'updateCarouselPosition();'
    .'nextBtn.addEventListener("click", () => {'
    .'if (counter >= carouselImages.length - 1) return;'
    .'counter++;'
    .'updateCarouselPosition();'
    .'});'
    .'prevBtn.addEventListener("click", () => {'
    .'if (counter <= 0) return;'
    .'counter--;'
    .'updateCarouselPosition();'
    .'});'
    .'window.addEventListener("resize", () => {'
    .'updateCarouselPosition();'
    .'});'
    .'};'
    .'</script>'
    .'<style>'
    .'html { scroll-behavior: smooth; }'
    .'section { scroll-margin-top: 80px; }'
    .'</style>';

include __DIR__ . '/Templatebase.php';
include __DIR__ . '/footerbase.php';
?>