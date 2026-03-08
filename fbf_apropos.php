<?php
$title = "A propos";

$headerImage = '';

$headerContent = "
    <h1> A propos de nous </h1>
    ";

$mainContent = '

<div class="cadre">
    <div class="photo">
        <img src="images/apropos_applepie_gallery.jpg" alt="Apolline et les poneys">  
        <img src="images/apropos_gallery2.jpg" alt="Jules">
        <img src="images/apropos_applepiesweety2.jpg" alt="Apolline et Jules"> 
        <img src="images/apropos_jules_gallery.jpg" alt="Mishka et Jules">
    </div>
</div>

    
<h2> Jules et Apolline </h2>
<img class="medium1" src="images/apropos_gallery.jpg" alt="Jules">
<div class="apropos-texte">

  <p>
    <strong>Jules, entre nature et transmission</strong><br>
    Jules, 33 ans, est ingénieur agronome passionné par l’agriculture durable et la vie à la ferme. Après avoir été conseiller en agriculture biologique, il a travaillé comme salarié agricole, avec un intérêt tout particulier pour l’élevage laitier respectueux des animaux et de l’environnement.
  </p>

  <p>
    <strong>Un pédagogue dans l’âme</strong><br>
    Jules aime transmettre son savoir-faire aux plus jeunes à travers des activités à la fois ludiques et éducatives. Il propose des ateliers autour du bricolage, de la fabrication du pain au levain, de l’élevage ou encore de la cuisine, pour apprendre en s’amusant et se reconnecter au vivant.
  </p>
</div>

<div class="apropos-row">
  <div class="apropos-image">
    <img src="images/apropos_apo_gallery.jpg" alt="Apolline">
  </div>
  <div class="apropos-texte">
    <p>
      <strong>Une ferme familiale et accueillante</strong><br>
      La ferme pédagogique est portée par Jules et Apolline, un couple passionné par la nature et l’accompagnement humain. Ensemble depuis 14 ans et mariés depuis 4 ans, ils travaillent à créer un lieu chaleureux, sécurisant et enrichissant pour petits et grands.
    </p>

    <p>
      <strong>Le parcours d’Apolline</strong><br>
      Apolline, 32 ans, a grandi à la campagne, entourée d’animaux. Infirmière pendant 6 ans, elle s’est ensuite tournée vers l’équithérapie, avant d’obtenir son diplôme de monitrice d’équitation. Elle encadre aujourd’hui des ateliers variés autour du poney, notamment pour les jeunes enfants et les familles.
    </p>

    <p>
      <strong>Une expertise au service des enfants et des publics fragiles</strong><br>
      Référente de l’activité poney, Apolline propose des séances quotidiennes adaptées à chaque âge.<br>
      Forte de ses expériences en milieu médical et médico-social, Apolline a accompagné des enfants et adultes en situation de handicap, en utilisant le cheval comme médiateur relationnel. Elle sait instaurer un climat de confiance et de bienveillance pour assurer la sécurité physique et affective des enfants et personnes accueillis à la ferme.
    </p>
  </div>
</div>

<section id="carousel-apropos">
    <div class="carousel-container-apropos">
        <div class="carousel-slide-apropos">
            <div class="carousel-item-apropos">
                <img src="images/locky_gallery.jpg" alt="">
            </div>
            <div class="carousel-item-apropos">
                <img src="images/presentation.jpg" alt="">
            </div>
            <div class="carousel-item-apropos">
                <img src="images/animaux_gallery.jpg" alt="">
            </div>
            <div class="carousel-item-apropos">
                <img src="images/apropos_applepiesweety.jpg" alt="">
            </div>
            <div class="carousel-item-apropos">
                <img src="images/Jules.jpg" alt="">
            </div>
            <div class="carousel-item-apropos">
                <img src="images/poneys_gallery3.jpg" alt="">
            </div>
            <div class="carousel-item-apropos">
                <img src="images/vaches_gallery_3.jpg" alt="">
            </div>
            <div class="carousel-item-apropos">
                <img src="images/vaches_gallery_2.jpg" alt="">
            </div>
        </div>
        <button class="carousel-prev">Précédent</button>
        <button class="carousel-next">Suivant</button>
    </div>
</section>
      
';


include "Templatebase.php";
include 'footerbase.php';

?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const carouselSection = document.getElementById('carousel-apropos');
    if (!carouselSection) return;

    const carouselSlide = carouselSection.querySelector('.carousel-slide-apropos');
    const carouselItems = carouselSlide.querySelectorAll('.carousel-item-apropos');
    const prevBtn = carouselSection.querySelector('.carousel-prev');
    const nextBtn = carouselSection.querySelector('.carousel-next');

    if (!carouselSlide || carouselItems.length === 0 || !prevBtn || !nextBtn) {
        return;
    }

    let currentIndex = 0;

    const updateCarousel = () => {
        // La largeur de défilement est la largeur du carrousel lui-même
        const containerWidth = carouselSection.querySelector('.carousel-container-apropos').clientWidth;
        carouselSlide.style.transform = `translateX(${-currentIndex * containerWidth}px)`;
    };
    
    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex === 0) ? carouselItems.length - 1 : currentIndex - 1;
        updateCarousel();
    });

    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex === carouselItems.length - 1) ? 0 : currentIndex + 1;
        updateCarousel();
    });

    updateCarousel();

    window.addEventListener('resize', updateCarousel);
});

</script>