# La Ferme du Bois Frican - PRD

## Problème initial
Cloner le repo https://github.com/Clofqr/bois-frican.git et corriger :
1. Formulaire de contact non fonctionnel (destinataire : gaec@leboisfrican.fr)
2. Responsive (desktop, tablette, mobile)
3. Boutons du menu hamburger avec bordure fine noire

## Stack
- PHP 8.2 (site vitrine multipages)
- PHPMailer (envoi SMTP) + vlucas/phpdotenv
- CSS custom (fbf_style_test.css + assets/css/*)
- JS vanilla (menu hamburger + carrousel)

## Corrections apportées (2026-01)

### Formulaire de contact
- `fbf_contact.php` : action URL relative (`formulaire_contact.php`) au lieu de
  `http://localhost/Ferme2/...` ; suppression du `</main>';` parasite ; suppression
  du `\'` littéral ; ajout de data-testid pour tests.
- `formulaire_contact.php` : `session_start()` idempotent (fix double call) ;
  chargement `.env` en `safeLoad()` avec fallback ; redirection relative ;
  validation renforcée ; anti-spam 30s ; erreurs loguées côté serveur uniquement ;
  fallback destinataire = `gaec@leboisfrican.fr`.
- `.env.example` : nouveau template SMTP documenté (OVH, Gmail...).

### Portabilité
- Suppression de `<base href="/Ferme2/">` (cassait le rendu hors XAMPP).
- Introduction d'une variable `$basePath` définie par chaque page (racine = `'./'`,
  sous-dossier `accueil/` = `'../'`), utilisée pour tous les liens CSS/JS/nav/images.

### Responsive & design
- Nouveau bloc CSS `.hamburger-btn` : bordure noire fine (1px solid #000) sur le
  bouton et sur chaque item du menu déroulant mobile.
- Nouveau layout du formulaire de contact : flex desktop, empilé en tablette
  (<1000px), padding réduit en mobile (<600px).
- `label`/`input`/`textarea` : styles cohérents, `input:focus` accessible.
- `.feedback-success` / `.feedback-error` : nouveaux styles pour les retours.

### Divers
- `menu.js` : support des multiples dropdowns (avant seul le premier fonctionnait),
  attributs ARIA sur le bouton hamburger, fermeture au clic extérieur.
- `footerbase.php` : liens dynamiques via `$basePath`, ajout de l'email GAEC.
- `README.md` : instructions installation + configuration SMTP.

## Backlog / Prochaines idées
- P1 : Page dédiée mentions légales (le lien pointe actuellement vers contact).
- P1 : Google reCAPTCHA v3 pour anti-spam plus robuste.
- P2 : SEO (meta description, Open Graph, sitemap.xml).
- P2 : Optimisation images (WebP, lazy loading).
- P2 : Formulaire de réservation (le lien "Réservez" est actuellement `display: none`).
