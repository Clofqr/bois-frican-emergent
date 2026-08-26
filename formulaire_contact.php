<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/vendor/autoload.php';

// Chargement facultatif du fichier .env (n'échoue pas s'il est absent)
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Destinataire (fallback sur l'adresse officielle du GAEC)
$destinataire = $_ENV['MAIL_TO'] ?? 'gaec@leboisfrican.fr';

// Redirection vers la page de contact (chemin relatif pour être portable)
$redirect_page = 'fbf_contact.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . $redirect_page);
    exit;
}

// Anti-spam basique : limite de 30 secondes entre 2 envois
if (isset($_SESSION['last_submit']) && (time() - $_SESSION['last_submit']) < 30) {
    $_SESSION['form_error'] = "Veuillez attendre 30 secondes avant d'envoyer un nouveau message.";
    header("Location: " . $redirect_page);
    exit;
}

$nom       = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$prenom    = htmlspecialchars(trim($_POST['surname'] ?? ''), ENT_QUOTES, 'UTF-8');
$ville     = htmlspecialchars(trim($_POST['ville'] ?? ''), ENT_QUOTES, 'UTF-8');
$cp        = htmlspecialchars(trim($_POST['cp'] ?? ''), ENT_QUOTES, 'UTF-8');
$email     = trim($_POST['email'] ?? '');
$telephone = htmlspecialchars(trim($_POST['tel'] ?? ''), ENT_QUOTES, 'UTF-8');
$message   = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

if (empty($nom) || empty($prenom) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['form_error'] = "Veuillez vérifier les champs obligatoires ou l'adresse email.";
    header("Location: " . $redirect_page);
    exit;
}

// Vérification de la configuration SMTP
$mailHost     = $_ENV['MAIL_HOST']     ?? '';
$mailUser     = $_ENV['MAIL_USERNAME'] ?? '';
$mailPass     = $_ENV['MAIL_PASSWORD'] ?? '';
$mailPort     = $_ENV['MAIL_PORT']     ?? 587;
$mailFrom     = $_ENV['MAIL_FROM']     ?? $mailUser;
$mailFromName = $_ENV['MAIL_FROM_NAME'] ?? 'Site La Ferme du Bois Frican';

if (empty($mailHost) || empty($mailUser) || empty($mailPass)) {
    $_SESSION['form_error'] = "Le service d'envoi de mail n'est pas configuré. Merci de nous joindre par téléphone au 06.35.17.86.90 en attendant.";
    header("Location: " . $redirect_page);
    exit;
}

$sujet = "Nouveau message de contact : " . $prenom . " " . $nom;
$corps_email  = "Nouveau message de contact reçu depuis le site.\n\n";
$corps_email .= "Nom       : " . $nom . "\n";
$corps_email .= "Prénom    : " . $prenom . "\n";
$corps_email .= "Ville     : " . $ville . "\n";
$corps_email .= "Code Postal : " . $cp . "\n";
$corps_email .= "Email     : " . $email . "\n";
$corps_email .= "Téléphone : " . $telephone . "\n\n";
$corps_email .= "--- MESSAGE ---\n" . $message . "\n";

$mail = new PHPMailer(true);

try {
    $mail->CharSet = 'UTF-8';
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true,
        ],
    ];

    $mail->isSMTP();
    $mail->Host       = $mailHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $mailUser;
    $mail->Password   = $mailPass;
    $mail->SMTPSecure = ((int)$mailPort === 465)
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int)$mailPort;

    $mail->setFrom($mailFrom, $mailFromName);
    $mail->addReplyTo($email, $prenom . ' ' . $nom);
    $mail->addAddress($destinataire, 'La Ferme du Bois Frican');

    $mail->Subject = $sujet;
    $mail->Body    = $corps_email;

    $mail->send();

    $_SESSION['last_submit'] = time();
    $_SESSION['form_success'] = true;
} catch (Exception $e) {
    error_log('[Contact] Erreur PHPMailer : ' . $mail->ErrorInfo);
    $_SESSION['form_error'] = "Une erreur est survenue lors de l'envoi. Merci de réessayer plus tard.";
}

header("Location: " . $redirect_page);
exit;
