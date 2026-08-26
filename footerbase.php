<?php $basePath = $basePath ?? './'; ?>
<footer>
    <div class="footer-content">
        <h4> La Ferme du Bois Frican </h4>
        <ul>
            <li>Impasse du bois Frican 61330 Céaucé</li>
            <li>Téléphone : 06 35 17 86 90</li>
            <li>Email : gaec@leboisfrican.fr</li>
        </ul>
        <a class="footer-link" href="<?= $basePath ?>fbf_contact.php">@ Nous contacter</a>
        <ul class="socials">
            <li><a class="footer-link" href="#" aria-label="Facebook">Facebook</a></li>
            <li><a class="footer-link" href="#" aria-label="Instagram">Instagram</a></li>
        </ul>
    </div>

    <a class="footer-mentions" href="<?= $basePath ?>fbf_contact.php">Mentions Légales</a>

    <div class="footer-bottom">
        <div class="logo-footer">
            <img src="<?= $basePath ?>images/logo.png" alt="Logo La Ferme du Bois Frican">
        </div>
        <p>Site web de La Ferme du Bois Frican | Tous droits réservés.</p>
    </div>
</footer>
