<?php
// Point d'entrée du site : quand la page d'accueil est chargée via /, on est
// à la racine du site donc les liens/assets doivent être relatifs à "./".
// (Si /accueil/fbf_accueil.php est appelé directement, ce fichier n'est pas
// exécuté et fbf_accueil.php utilise son fallback '../' par défaut.)
$basePath = './';
require __DIR__ . '/accueil/fbf_accueil.php';
