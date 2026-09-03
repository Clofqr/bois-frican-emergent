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

## Itération 4 (2026-01)

### Bugs corrigés
- **Carrousel Accueil - images invisibles** : la page est dans `/accueil/` mais les src pointaient vers `images/...` → 404. Corrigé en `../images/...` pour les 8 images. Suppression du <video src="../images/video1.mp4"> (45MB, bloquait le chargement et affichait un slide vide en position 0).
- **Boutons carrousel Apropos empiétaient sur les images** : `left: 60px` / `right: 70px` sur un container de 800px → boutons au milieu de l'image. Fix dans `/app/assets/css/carousel.css` : `left: 10px` / `right: 10px`, boutons ronds 42x42 avec chevrons SVG-like en pseudo-éléments, font-size 0 (masque le texte). Sur ≤600px : 36x36 et 6px.
- **`object-fit: contain` sur apropos** remplacé par `cover` → l'image remplit tout le container, les boutons sont donc bien collés aux BORDS des images (et pas dans des bandes vides).
- **Height container accueil** aligné de 500px à 400px (fix cosmétique testing agent : plus de bande vide de 100px en bas).

### Testing agent : 9/9 scenarios PASS (100 %)
Rapport : `/app/test_reports/iteration_3.json`
- Container 800px = image width 800px sur apropos (plus de bande latérale)
- Gap prev/next → bord = 10px exactement sur les deux carrousels
- 8 images accueil chargées, translateX(-1200px) après 2 clics next

## Itération 5 (2026-01)

### Bugs corrigés
- **Apropos carrousel : `object-fit: contain` restauré** (user voulait garder les propriétés d'origine, pas de crop). Les boutons prev/next restent collés aux bords du container.
- **Accueil : vidéo remise en 1er slide** avec `poster="../images/gallery_accueil2.jpg"` fallback + `preload="metadata"` + `playsinline`. Si la vidéo (44 MB) n'arrive pas à se charger, le poster reste affiché → l'utilisateur voit toujours quelque chose.
- **Feedback formulaire contact plus caché derrière .nous** : ajout `position: relative; z-index: 20` sur `.feedback-success` / `.feedback-error` (.nous a z-index 10).

### Testing agent : 3/3 checks PASS (100 %)
Rapport : `/app/test_reports/iteration_4.json`
- Apropos : object-fit === 'contain' sur les 8 images
- Video : <video> en 1er enfant, poster attribute non-vide, hit-test au centre retourne VIDEO
- Contact feedback : z-index 20, position relative, hit-test retourne .feedback-error (pas .nous)

### Recommandation testing agent (non-bloquant)
- Compresser `images/video1.mp4` (44 MB → idéalement < 5 MB, MP4 faststart) pour que la vidéo joue réellement au lieu d'afficher seulement le poster. Le fichier actuel dépasse ce que Chromium peut charger en une passe → networkState=3.

## Itération 6 (2026-01)

### Position des h1 (Accueil, Découverte, Infos Pratiques)
- **Desktop** : `.cover` margin-top réduit de 30px à 15px → h1 remonte vers le haut de la page (y=36 sur 1440x900, largement au-dessus des boutons du menu à y=112).
- **Mobile** : `.header-title` padding-top passé de 70px à 8px et text-align:left → h1 aligné horizontalement avec le bouton hamburger (h1 à y=20, hamburger à y=32 sur 390x800), plus poussé sous.

### Portabilité OVH + Windows local
- **Nouveau `.htaccess` racine** (Apache OVH) :
  - Deny sur `.env`, `composer.*`, `start.sh`, `.emergent_router.php`, `.git*`, `README.md`
  - RedirectMatch 403 sur `vendor/`, `memory/`, `test_reports/`, `frontend/`, `backend/`, `.git`, `node_modules`
  - Blocage exécution PHP dans `images/` (uploads futurs)
  - Cache statique 1 mois pour images/vidéos, 1 semaine pour CSS/JS
  - Compression gzip
  - Headers de sécurité OWASP (X-Content-Type-Options, X-Frame-Options, Referrer-Policy)
  - Redirections HTTPS/www commentées, à décommenter au besoin
- **`vendor/.htaccess`** : deny all (double sécurité).
- **`README.md` refait** avec 3 sections : local Windows (via `php -S`, XAMPP), déploiement OVH pas-à-pas (composer, FTP, .env sur serveur), fichiers à ne pas uploader.

### Vérifications Windows-compat
- Aucun chemin dur (`C:\`, `/var/www`, `/home/`) dans le PHP
- Aucun `shell_exec` / `exec` / `system` / `passthru`
- Tous les `__DIR__`, `require`, `include` utilisent des séparateurs `/` (compatibles Windows)
- Tous les .php compilent sans warning

### Validation
- 7/7 pages retournent 200 sur Preview
- H1 mesuré : desktop y=36 (au-dessus nav y=112), mobile y=20 (face hamburger y=32)

## Itération 6 (2026-01) - Autonomie totale du projet (XAMPP + OVH)

### Bug initial rapporté
`.htaccess` contenait `<Directory "images">` qui est **invalide en contexte .htaccess** → Apache 500 sur XAMPP avec le message "Directory not allowed here".

### Fichiers modifiés
| Fichier | Raison |
|---|---|
| `/app/.htaccess` | Suppression du bloc `<Directory>` (source du 500). Remplacement des `RedirectMatch ^/` par des `RewriteRule (^|/)` pour fonctionner aussi en sous-dossier. Suppression des noms Emergent-only du FilesMatch. Ajout dotfile-block générique. |
| `/app/images/.htaccess` | **Nouveau** — remplace le `<Directory>` supprimé du fichier racine (FilesMatch bloquant .php/.phtml/.phar/.sh/.py/etc). |
| `/app/memory/.htaccess` `/app/test_reports/.htaccess` `/app/backend/.htaccess` `/app/frontend/.htaccess` `/app/admin/.htaccess` | **Nouveaux** — `Require all denied` pour protéger ces dossiers indépendamment du chemin d'installation (double protection, marche même sans mod_rewrite). |
| `/app/index.php` | Ajout de `$basePath = './'` avant l'include, chemin absolu `__DIR__ . '/...'`. Résout le bug critique en sous-dossier : sans ça, la home page cherchait ses assets un niveau au-dessus de la racine du projet. |
| `/app/accueil/fbf_accueil.php` | `$basePath = $basePath ?? '../'` (fallback null-safe pour l'accès direct à la page). Les 8 `<img>` + `<video>` du carrousel utilisent maintenant `$basePath` au lieu de `../` en dur. |
| `/app/fbf_apropos.php` `/app/fbf_contact.php` `/app/fbf_decouverte.php` `/app/fbf_infopratique.php` `/app/fbf_mentionslegales.php` | `include __DIR__ . '/Templatebase.php'` et `include __DIR__ . '/footerbase.php'` — chemins absolus pour marcher que le CWD soit la racine ou un sous-dossier. |
| `/app/fbf_decouverte.php` lignes 14-16 | `capture_include(__DIR__ . '/fbf_*.php')` — même raison. |
| `/app/fbf_ferme.php` | `src="images/Video.webm"` (V majuscule, case-sensitive Linux OVH). Ajout `playsinline preload="metadata"`. |
| `/app/.gitignore` | Refait — exclut `.env`, `vendor/`, `frontend/`, `backend/`, `memory/`, `test_reports/`, `.emergent/`, `.emergent_router.php`, `.ruff_cache`, `test_result.md`, `yarn.lock`, fichiers IDE/OS. |
| `/app/.gitattributes` | **Nouveau** — `export-ignore` sur les dossiers Emergent-only (pour `git archive`) + `eol=lf` sur tous les fichiers texte. |
| `/app/README.md` | Refait — aucune mention d'Emergent, sections XAMPP Windows, PHP intégré, OVH mutualisé. |

### Testing agent : 87/87 assertions bash + Playwright 100 %
Rapport : `/app/test_reports/iteration_6.json`. Testé sur DEUX vhosts :
- 127.0.0.1:8080 (DocumentRoot=/app) — cas OVH racine
- 127.0.0.1:8081/bois-frican (symlink) — cas XAMPP réel sous-dossier

Le projet est maintenant **totalement autonome**, sans dépendance à l'environnement Emergent, à /app/... ni à WSL. Il fonctionne à la racine d'un domaine ou dans un sous-dossier, sur XAMPP Windows comme sur OVH mutualisé.
