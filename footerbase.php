<?php $basePath = $basePath ?? './'; ?>
<footer>
    <div class="footer-content">
        <h4> La Ferme du Bois Frican </h4>
        <ul>
            <li>Impasse du bois Frican 61330 Céaucé</li>
            <li>Téléphone : 06 35 17 86 90</li>
            <li>Email : gaec@leboisfrican.fr</li>
        </ul>
        <a class="footer-link" href="mailto:gaec@leboisfrican.fr" data-testid="footer-mailto">@ Nous contacter</a>
    </div>

    <a class="footer-mentions" href="<?= $basePath ?>fbf_mentionslegales.php" data-testid="footer-mentions">Mentions Légales</a>

    <div class="footer-bottom">
        <div class="logo-footer">
            <img src="<?= $basePath ?>images/logo.png" alt="Logo La Ferme du Bois Frican">
        </div>
        <p>Site web de La Ferme du Bois Frican | Tous droits réservés.</p>
    </div>
</footer>
