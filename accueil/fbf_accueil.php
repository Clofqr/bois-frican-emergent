<?php
$title = "Accueil";
$basePath = '../';

$headerImage = "accueil/bienvenue_cover.jpg";

$headerContent = '
    <h1>A ch\'val au Bois Frican !</h1>
';

$mainContent = '
<div class="accueil-wrapper">
  <div class="accueil-title">
    <h2>Bienvenue à la ferme</h2>
    <p>
      Venez vivre un moment simple et authentique au cœur de la nature.<br>
      Ici, on découvre les animaux, on met les mains dans la terre, on cuisine, on bricole…<br><br>
      Portée par <strong>Jules et Apolline</strong>, notre ferme est un lieu de partage, de découvertes et de joie pour petits et grands.
    </p>
  </div>

  <div class="accueil-texte">
    <p style="color: #333; font-size: 16px; line-height: 1.6;">
      <strong>Une ferme vivante et engagée</strong><br>
      Nous produisons du lait avec des vaches élevées au pâturage, dans le respect de l’animal et de la nature.
    </p>

    <p style="color: #333; font-size: 16px; line-height: 1.6;">
      <strong>Une joyeuse compagnie</strong><br>
      Un troupeau de vaches, des poneys et nos fidèles compagnons chien et chat vivent actuellement notre ferme… et ce n’est qu’un début !
    </p>

    <p style="color: #333; font-size: 16px; line-height: 1.6;">
      <strong>Des activités autour du cheval</strong><br>
      Grâce à notre troupeau de poneys, nous proposons des activités équestres en séjour à la ferme, en atelier parent-enfant, ou en séance d’équithérapie.
    </p>

    <p style="color: #333; font-size: 16px; line-height: 1.6;">
      <strong>Grandir au contact de la nature</strong><br>
      Nos séjours visent à éveiller les sens, développer la curiosité, et offrir aux enfants une expérience qui nourrira leur futur.
    </p>

    <p style="color: #333; font-size: 16px; line-height: 1.6;">
      <strong>Des ateliers pour tous</strong><br>
      Les ateliers parent/enfant et les séances d’équithérapie sont ouverts à tous, indépendamment des séjours.
    </p>

    <p style="color: #333; font-size: 16px; line-height: 1.6;">
      <strong>Un accueil adapté aux enfants en situation de handicap</strong><br>
      Pour les établissements médico-sociaux, les séances sont personnalisées et pensées en lien avec le projet du groupe.
    </p>
  </div>
</div>
<h2> Galerie photos </h2>
<section id="carousel-menu">
    <div class="carousel-container carousel-accueil">
        <div class="carousel-slide">
            <video class="carousel-video" autoplay loop muted>
                <source src="images/video1.mp4" type="video/mp4">
            </video>
            <img src="images/gallery_accueil2.jpg" alt="Pâturage">
            <img src="images/poneys_gallery1.jpg" alt="Les poneys">
            <img src="images/vaches_gallery6.jpg" alt="Les vaches">
            <img src="images/atelier_gallery.jpg" alt="Ateliers et séjours">
            <img src="images/gallery_accueil_michka.jpg" alt="Michka">
            <img src="images/vaches_traite.jpg" alt="Pâturage">
            <img src="images/logement_sejour.jpg" alt="Vacances à la ferme">
            <img src="images/gallery_accueil3.jpg" alt="Logement séjour équestre">
        </div>
        <button class="carousel-prev">Précédent</button>
        <button class="carousel-next">Suivant</button>
    </div>
</section>
';

include '../Templatebase.php';
include '../footerbase.php';
?>


