<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $basePath : chemin relatif vers la racine du site depuis la page courante.
//   - Pages à la racine  : './'   (défaut)
//   - Pages dans un sous-dossier (ex: accueil/) : '../'
// Ainsi le projet reste portable : il fonctionne aussi bien dans un sous-dossier
// (ex: http://localhost/Ferme2/) qu'à la racine d'un domaine (https://leboisfrican.fr/).
$basePath = $basePath ?? './';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?? "La Ferme du Bois Frican"?></title>
    <link rel="stylesheet" href="<?= $basePath ?>fbf_style_test.css" />
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/accueil.css" />
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/carousel.css" />
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/atelier.css" />
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/animaux.css"/>
</head>

<body class="<?php 
    if ($title === 'Accueil') echo 'accueil';
    elseif ($title === 'A propos') echo 'apropos'; 
    elseif ($title === 'Contact') echo 'contact';
    elseif ($title === 'Découverte') echo 'decouverte'; 
    elseif ($title === 'Infos Pratiques') echo 'infopratique';
    elseif ($title === 'Mentions Légales') echo 'mentionslegales';
    elseif (in_array($title, ['Ferme', 'Animaux', 'Ateliers'])) echo 'scroll-top-visible';
    if (empty(trim($headerImage))) {
    echo ' no-header-image';
    }
?>">

    <?php
    $navContent = '
<nav class="container">
  <button class="hamburger-btn" type="button" aria-label="Ouvrir le menu" aria-expanded="false" data-testid="hamburger-btn">
    &#9776; </button>
  <ul class="nav-links" data-testid="nav-links">
    <li><a class="btn" href="' . $basePath . 'accueil/fbf_accueil.php"><strong>Accueil</strong></a></li>
    <li class="dropdown menu-btn">
      <a class="btn dropbtn active-btn" href="' . $basePath . 'fbf_decouverte.php"><strong>Découverte</strong></a>
      <div class="dropdown-content">
        <a href="' . $basePath . 'fbf_decouverte.php#ateliers">Les ateliers</a>
        <a href="' . $basePath . 'fbf_decouverte.php#animaux">Les animaux</a>
        <a href="' . $basePath . 'fbf_decouverte.php#ferme">La vie à la ferme</a>
      </div>
    </li>
    <li><a class="btn" href="' . $basePath . 'reservation_test.php" style="display: none;"><strong>Réservez</strong></a></li>
    <li><a class="btn" href="' . $basePath . 'fbf_apropos.php"><strong>À propos</strong></a></li>
    <li><a class="btn" href="' . $basePath . 'fbf_contact.php"><strong>Contact</strong></a></li>
    <li class="dropdown menu-btn">
      <a class="btn dropbtn" href="' . $basePath . 'fbf_infopratique.php"><strong>Infos Pratiques</strong></a>
      <div class="dropdown-content">
        <a href="' . $basePath . 'fbf_infopratique.php">- Séjours équestres</a>
        <a href="' . $basePath . 'fbf_infopratique.php">- Equithérapie</a>
        <a href="' . $basePath . 'fbf_infopratique.php">- Poney Eveil</a>
      </div>
    </li>
  </ul>
</nav>';
    ?>

<header class="header">
    <?php if (!empty($headerImage)) : ?>
        <div class="background-image">
            <img class="size-image" src="<?php echo htmlspecialchars($basePath . trim($headerImage)); ?>" alt="">
        </div>
    <?php endif; ?>

    <div class="cover">
        <div class="header-title">
            <?= $headerContent ?? '' ?>
        </div>
        <?= $navContent ?>
    </div>

    <div class="scroll-arrow-container">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
            <path fill="#e6cc3d" d="M297.4 566.6C309.9 579.1 330.2 579.1 342.7 566.6L502.7 406.6C515.2 394.1 515.2 373.8 502.7 361.3C490.2 348.8 469.9 348.8 457.4 361.3L352 466.7L352 96C352 78.3 337.7 64 320 64C302.3 64 288 78.3 288 96L288 466.7L182.6 361.3C170.1 348.8 149.8 348.8 137.3 361.3C124.8 373.8 124.8 394.1 137.3 406.6L297.3 566.6z"/>
        </svg>
    </div>
</header>
    
    

    <main>
   
        <?= $mainContent ?? '' ?>
    </main>

    <button
        type="button"
        class="scroll-to-top"
        aria-label="Remonter en haut de la page"
        data-testid="scroll-to-top-btn"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor" d="M12 4l-8 8h5v8h6v-8h5z"/>
        </svg>
    </button>
 
<script src="<?= $basePath ?>assets/js/menu.js" defer ></script>    
<script src="<?= $basePath ?>assets/js/carousel.js" defer ></script>
    
</body>
</html>