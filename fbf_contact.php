<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = "Contact";
$headerImage = '';
$headerContent = "<h1> Contactez-nous </h1>";

$feedback_message = '';

if (isset($_SESSION['form_success']) && $_SESSION['form_success']) {
    $feedback_message = '<p class="feedback-success">Votre message a bien été envoyé ! Nous vous recontacterons bientôt.</p>';
    unset($_SESSION['form_success']);
}
if (isset($_SESSION['form_error']) && !empty($_SESSION['form_error'])) {
    $feedback_message = '<p class="feedback-error">' . $_SESSION['form_error'] . '</p>';
    unset($_SESSION['form_error']);
}

ob_start();
?>

    <img class="nous" src="images/contact_bulle.jpg" alt="contact">

    <div class="contact-content">
        <h3> Impasse du Bois Frican 61330 Céaucé </h3>
        <h3>Nous joindre par téléphone 06.35.17.86.90</h3>

        <div class="form-container">
            <div class="form">
                <form action="formulaire_contact.php" method="POST" novalidate data-testid="contact-form">
                    <?= $feedback_message ?>
                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" required data-testid="contact-input-name">

                    <label for="surname">Prénom</label>
                    <input type="text" id="surname" name="surname" required data-testid="contact-input-surname">

                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" required data-testid="contact-input-ville">

                    <label for="cp">Code postal</label>
                    <input type="text" id="cp" name="cp" required data-testid="contact-input-cp">

                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" required data-testid="contact-input-email">

                    <label for="tel">N° de téléphone</label>
                    <input type="tel" id="tel" name="tel" required data-testid="contact-input-tel">

                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required data-testid="contact-input-message"></textarea>

                    <button class="bouton" type="submit" data-testid="contact-submit-btn">Envoyer</button>
                </form>
            </div>
            <img class="image-container" src="images/poneys.png" alt="Poneys de la ferme">
        </div>

        <h4>Mentions Légales et Conditions Générales d'utilisation</h4>
        <a href="fbf_mentionslegales.pdf" target="_blank" rel="noopener">Télécharger le fichier pdf</a>
    </div>

<?php

$mainContent = ob_get_clean();

include 'Templatebase.php';
include 'footerbase.php';
?>
