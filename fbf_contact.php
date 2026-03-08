<?php

session_start();

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
    <main>
        
        
        <h3> Impasse du Bois Frican 61330 Céaucé </h3>
        <h3>Nous joindre par téléphone 06.35.17.86.90</h3>
        
        <div class="form-container">
            <div class="form">
                <form action="http://localhost/Ferme2/formulaire_contact.php" method="POST">
                    <?=  $feedback_message  ?>
                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" required>

                    <label for="surname">Prénom</label>
                    <input type="text" id="surname" name="surname" required>

                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" required>

                    <label for="code_postal">Code postal</label>
                    <input type="text" id="cp" name="cp" required>

                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="telephone">N° de téléphone</label>
                    <input type="text" id="tel" name="tel" required>

                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button class="bouton" type="submit">Envoyer</button>
                    
                </form>
            
            </div>  
            <img class="image-container" src="images/poneys.png" alt="contact">
        </div>  

        <h4> Mentions Légales et Conditions Générales d\'utilisation</h4>
        <a href="fbf_mentionslegales.pdf" target="_blank">Télécharger le fichier pdf</a>
      </main>';

<?php

$mainContent = ob_get_clean();

include 'Templatebase.php';
include 'footerbase.php';
?>