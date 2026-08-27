<?php
$title = "Mentions Légales";
$basePath = './';
$headerImage = '';
$headerContent = '<h1>Mentions Légales</h1>';

$pdfFile = __DIR__ . '/fbf_mentionslegales.pdf';
$pdfExists = file_exists($pdfFile);

ob_start();
?>

<div class="mentions-wrapper">
    <p class="mentions-intro">
        Retrouvez ci-dessous les mentions légales et les conditions générales d'utilisation
        du site de La Ferme du Bois Frican.
    </p>

    <?php if ($pdfExists): ?>
        <div class="pdf-viewer" data-testid="mentions-pdf-viewer">
            <object
                data="fbf_mentionslegales.pdf#view=FitH&toolbar=1"
                type="application/pdf"
                aria-label="Mentions légales - PDF"
            >
                <iframe
                    src="fbf_mentionslegales.pdf#view=FitH&toolbar=1"
                    title="Mentions légales - PDF"
                    loading="lazy"
                >
                    <p>
                        Votre navigateur ne peut pas afficher le PDF directement.
                        <a href="fbf_mentionslegales.pdf" target="_blank" rel="noopener noreferrer">
                            Cliquez ici pour l'ouvrir dans un nouvel onglet
                        </a>.
                    </p>
                </iframe>
            </object>
        </div>

        <p class="mentions-fallback">
            Le PDF ne s'affiche pas correctement ?
            <a href="fbf_mentionslegales.pdf" target="_blank" rel="noopener noreferrer" data-testid="mentions-open-newtab">
                Ouvrir les mentions légales dans un nouvel onglet
            </a>.
        </p>
    <?php else: ?>
        <div class="mentions-missing" data-testid="mentions-missing">
            <h3>Document en cours de préparation</h3>
            <p>
                Les mentions légales seront prochainement mises à disposition ici.
                En attendant, pour toute demande, contactez-nous à
                <a href="mailto:gaec@leboisfrican.fr">gaec@leboisfrican.fr</a>.
            </p>
        </div>
    <?php endif; ?>
</div>

<?php
$mainContent = ob_get_clean();

include 'Templatebase.php';
include 'footerbase.php';
?>
