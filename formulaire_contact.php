<?php

session_start();

require __DIR__ . '/vendor/autoload.php'; 
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$destinataire = $_ENV['MAIL_TO'];

$redirect_base = 'http://' . $_SERVER['HTTP_HOST'] . '/Ferme2/fbf_contact.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nom       = htmlspecialchars(trim($_POST['name'] ?? ''));
    $prenom    = htmlspecialchars(trim($_POST['surname'] ?? ''));
    $ville     = htmlspecialchars(trim($_POST['ville'] ?? ''));
    $cp        = htmlspecialchars(trim($_POST['cp'] ?? ''));
    $email     = htmlspecialchars(trim($_POST['email'] ?? ''));
    $telephone = htmlspecialchars(trim($_POST['tel'] ?? ''));
    $message   = htmlspecialchars(trim($_POST['message'] ?? ''));


    if (empty($nom) || empty($prenom) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['form_error'] = "Veuillez vérifier les champs obligatoires ou l'adresse email.";
    } else {
        $sujet = "Nouveau message de contact : " . $prenom . " " . $nom;
        $corps_email = "Nouveau message de contact\n\n";
        $corps_email .= "Nom : " . $nom . "\n";
        $corps_email .= "Prenom : " . $prenom . "\n";
        $corps_email .= "Ville : " . $ville . "\n";
        $corps_email .= "Code Postal : " . $cp . "\n";
        $corps_email .= "Email : " . $email . "\n";
        $corps_email .= "Telephone : " . $telephone . "\n\n";
        $corps_email .= "--- MESSAGE ---\n" . $message;

      
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';   

        try {

              $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
            $mail->isSMTP();
            
            $mail->Host =$_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];
            $mail->setFrom($_ENV['MAIL_FROM'], 'Demande de contact'); 
            $mail->addReplyTo($email, $prenom . ' ' . $nom); 
            $mail->addAddress($destinataire, 'La ferme du bois frican'); 

            
            $mail->Subject = $sujet;
            $mail->Body    = $corps_email;

    if (isset($_SESSION['last_submit'])) {

    $delay = time() - $_SESSION['last_submit'];

    if ($delay < 30) {
        $_SESSION['form_error'] = "Veuillez attendre 30 secondes avant de renvoyer un message.";
        header("Location: fbf_contact.php");
        exit;
    }
}

        $_SESSION['last_submit'] = time();

            $mail->send();
            $_SESSION['form_success'] = true;
        } catch (Exception $e) {
            $_SESSION['form_error'] = "Erreur lors de l'envoi : {$mail->ErrorInfo}";
        }
    }
    
    header("Location: fbf_contact.php"); 
    exit;
}