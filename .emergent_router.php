<?php
// Router pour le serveur PHP intégré (php -S).
// Sert les fichiers statiques existants tels quels, et route toute autre requête
// vers index.php à la racine du site.

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && file_exists($file) && !is_dir($file)) {
    return false; // PHP built-in server sert le fichier statique tel quel
}

if (is_dir($file)) {
    $indexFile = rtrim($file, '/') . '/index.php';
    if (file_exists($indexFile)) {
        require $indexFile;
        return true;
    }
}

require __DIR__ . '/index.php';
