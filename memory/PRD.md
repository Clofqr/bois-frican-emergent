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

## Itération 2 (2026-01)

### Corrections
- **Espace apropos** : `body.apropos .cadre` margin-top 120px → 20px
- **Espace contact** : `body.contact main` margin-top 100px → 20px
- **Header Infos Pratiques** : `$headerImage = 'images/panneau.jpg'` (comme Accueil / Découverte). Ajout de `body.infopratique main { margin-top: 200px }` pour laisser la place au header. Le vieux bloc `.cadre > .acces-photo` supprimé du mainContent.
- **Nouvelle page Mentions Légales HTML** : `fbf_mentionslegales.php` refaite en HTML pur (7 sections numérotées, style cohérent, placeholders `[À COMPLÉTER — ...]` mis en valeur en jaune pour SIRET / RCS / Hébergeur — à compléter par le GAEC). Plus de dépendance au PDF.
- **Feedback formulaire** : déplacé hors du form-container, directement dans `.contact-content` au-dessus des h3 → visible immédiatement après la redirection, jamais caché par le layout flex. JS simplifié : scroll seul, plus d'auto-suppression du success.

### Corrections issues du testing agent (code review)
- `formulaire_contact.php` : suppression du `htmlspecialchars()` sur les champs stockés (les emails arrivaient avec `&#039;` au lieu d'apostrophes). L'échappement HTML n'est nécessaire qu'à l'output HTML, pas dans un corps d'email texte.
- `formulaire_contact.php` : `last_submit` est maintenant aussi défini en cas d'échec SMTP (anti-spam robuste contre les rejeux).
- `Templatebase.php` : balise `</div>` orpheline (background-image) corrigée quand `$headerImage` est vide.

### Testing agent : 9/9 scénarios PASSED (100 %)
Rapport : `/app/test_reports/iteration_1.json`

## Itération 3 (2026-01)

### Nouvelles features
- **Bouton "remonter en haut"** : présent sur toutes les pages via Templatebase.php.
  - `<button class="scroll-to-top" data-testid="scroll-to-top-btn">` positionné fixed bottom-right, avec un chevron SVG.
  - JS dans `menu.js` : toggle la classe `.visible` selon `window.scrollY > 400`. Click → `window.scrollTo({top: 0, behavior: 'smooth'})`.
  - Styles CSS : rond jaune 48px (44px mobile), border 1px noire, ombre, transitions opacity/transform, focus-visible accessible.

### Correction bug
- **Header Infos Pratiques débordait sur grand écran** (image 1920×999 recouvrait Poney Éveil, Équithérapie, Séjours). Fix : rules dédiées `body.infopratique .background-image { aspect-ratio: 16/6; max-height: 500px; overflow: hidden }` + `object-fit: cover` sur l'image. Breakpoints responsive : 16/8 max 320px sous 900px, 16/10 max 260px sous 600px.
- N'affecte pas Accueil / Découverte (règles ciblées `body.infopratique`).

### Testing agent : 11/11 checks PASSED (100 %)
Rapport : `/app/test_reports/iteration_2.json`
Mesures : image bottom = 500px @1920/1440, 320px @900, 244px @390. Gap image→titre 95-105px partout.
