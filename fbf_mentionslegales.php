<?php
$title = "Mentions Légales";
$basePath = './';
$headerImage = '';
$headerContent = '<h1>Mentions Légales</h1>';

ob_start();
?>

<div class="mentions-wrapper">
    <p class="mentions-intro">
        Conformément aux dispositions des articles 6-III et 19 de la Loi n° 2004-575 du 21 juin 2004
        pour la Confiance dans l'économie numérique, dite L.C.E.N., nous portons à la connaissance des
        utilisateurs et visiteurs, ci-après l'"Utilisateur", du site
        <strong>leboisfrican.fr</strong> les présentes mentions légales.
    </p>

    <section class="mentions-section" data-testid="mentions-editeur">
        <h2>1. Éditeur du site</h2>
        <p>
            <strong>Raison sociale :</strong> GAEC du Bois Frican<br>
            <strong>Adresse :</strong> Impasse du Bois Frican, 61330 Céaucé, France<br>
            <strong>Téléphone :</strong> 06 35 17 86 90<br>
            <strong>Email :</strong> <a href="mailto:gaec@leboisfrican.fr">gaec@leboisfrican.fr</a><br>
            <strong>SIRET :</strong> <span class="mentions-placeholder" data-testid="mentions-siret">[À COMPLÉTER — N° SIRET à 14 chiffres]</span><br>
            <strong>RCS / Immatriculation :</strong> <span class="mentions-placeholder" data-testid="mentions-rcs">[À COMPLÉTER — RCS Alençon n°...]</span><br>
            <strong>Responsables de la publication :</strong> Jules et Apolline
        </p>
    </section>

    <section class="mentions-section" data-testid="mentions-hebergeur">
        <h2>2. Hébergeur</h2>
        <p>
            <strong>Hébergeur :</strong> <span class="mentions-placeholder" data-testid="mentions-hebergeur-nom">[À COMPLÉTER — ex : OVH SAS]</span><br>
            <strong>Adresse :</strong> <span class="mentions-placeholder">[À COMPLÉTER — adresse de l'hébergeur]</span><br>
            <strong>Site :</strong> <span class="mentions-placeholder">[À COMPLÉTER — URL de l'hébergeur]</span>
        </p>
    </section>

    <section class="mentions-section">
        <h2>3. Propriété intellectuelle</h2>
        <p>
            L'ensemble du contenu du site leboisfrican.fr (textes, images, photographies, logos, vidéos,
            graphismes, éléments sonores, mise en page…) est la propriété exclusive du GAEC du Bois Frican
            ou de ses partenaires, et est protégé par les lois françaises et internationales relatives à
            la propriété intellectuelle.
        </p>
        <p>
            Toute reproduction, représentation, modification, publication, adaptation, totale ou partielle
            des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite sans
            l'autorisation écrite préalable du GAEC du Bois Frican.
        </p>
    </section>

    <section class="mentions-section">
        <h2>4. Données personnelles</h2>
        <p>
            Le site leboisfrican.fr collecte des données personnelles uniquement via le formulaire de
            contact (nom, prénom, ville, code postal, adresse email, numéro de téléphone, message).
            Ces informations sont utilisées exclusivement pour répondre aux demandes des utilisateurs
            et ne sont ni cédées ni vendues à des tiers.
        </p>
        <p>
            Conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi
            "Informatique et Libertés", vous disposez d'un droit d'accès, de rectification et de
            suppression des données vous concernant. Pour exercer ce droit, envoyez un email à
            <a href="mailto:gaec@leboisfrican.fr">gaec@leboisfrican.fr</a>.
        </p>
    </section>

    <section class="mentions-section">
        <h2>5. Cookies</h2>
        <p>
            Le site leboisfrican.fr peut être amené à utiliser des cookies techniques nécessaires à
            son bon fonctionnement (session, préférences). Aucun cookie de suivi publicitaire n'est
            déposé sans votre consentement.
        </p>
    </section>

    <section class="mentions-section">
        <h2>6. Responsabilité</h2>
        <p>
            Le GAEC du Bois Frican s'efforce de fournir des informations exactes et à jour sur son
            site. Toutefois, il ne peut garantir l'exactitude, la complétude ou l'actualité des
            informations diffusées. En conséquence, l'utilisateur reconnaît utiliser ces informations
            sous sa responsabilité exclusive.
        </p>
    </section>

    <section class="mentions-section">
        <h2>7. Droit applicable</h2>
        <p>
            Les présentes mentions légales sont soumises au droit français. En cas de litige, et à
            défaut de résolution amiable, les tribunaux français seront seuls compétents.
        </p>
    </section>

    <p class="mentions-updated">
        Dernière mise à jour : <?= date('d/m/Y') ?>
    </p>
</div>

<?php
$mainContent = ob_get_clean();

include 'Templatebase.php';
include 'footerbase.php';
?>
