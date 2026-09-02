# La Ferme du Bois Frican — Site vitrine

Site web du GAEC du Bois Frican (élevage, ateliers, séjours équestres).
Stack : **PHP 8** + PHPMailer (SMTP) — pas de base de données, contenu statique servi par Apache.

## Table des matières
- [Prérequis](#prérequis)
- [Test en local (Windows / macOS / Linux)](#test-en-local)
- [Déploiement sur OVH mutualisé](#déploiement-sur-ovh)
- [Configuration du formulaire de contact](#configuration-du-formulaire-de-contact)
- [Fichiers à ne PAS uploader en production](#fichiers-à-ne-pas-uploader)

---

## Prérequis
- **PHP ≥ 8.0** avec extensions `mbstring`, `openssl`, `curl` (installées par défaut sur OVH).
- **Composer** (pour installer PHPMailer + Dotenv).
- Un serveur SMTP (OVH, Gmail avec mot de passe d'application, Sendinblue, etc.).

---

## Test en local

### Option 1 — Serveur PHP intégré (le plus simple, aucun XAMPP nécessaire)

Sous Windows, macOS ou Linux :

```bash
# 1. Cloner ou dézipper le projet dans un dossier (ex: C:\dev\bois-frican)
cd C:\dev\bois-frican          # Windows PowerShell / CMD
# cd ~/dev/bois-frican         # macOS / Linux

# 2. Installer les dépendances Composer
composer install

# 3. (Optionnel) Créer un .env pour tester l'envoi de mail
copy .env.example .env         # Windows
# cp .env.example .env         # macOS / Linux

# 4. Lancer le serveur PHP intégré sur le port 8000
php -S 127.0.0.1:8000
```

Puis ouvrir <http://127.0.0.1:8000> dans le navigateur.

> **Note Windows** : si `php` n'est pas reconnu, installer PHP depuis
> <https://windows.php.net/download/> et l'ajouter au PATH, ou utiliser
> l'exécutable de XAMPP : `C:\xampp\php\php.exe -S 127.0.0.1:8000`

### Option 2 — XAMPP / WAMP / MAMP

1. Copier le contenu du projet dans `C:\xampp\htdocs\bois-frican\`.
2. Ouvrir un terminal dans ce dossier et lancer `composer install`.
3. Démarrer Apache dans le panneau XAMPP.
4. Ouvrir <http://localhost/bois-frican/>.

---

## Déploiement sur OVH

### Étape 1 — Préparer les fichiers en local
```bash
composer install --no-dev --optimize-autoloader
```
Cette commande crée le dossier `vendor/` (nécessaire pour PHPMailer et Dotenv).

### Étape 2 — Uploader les fichiers via FTP (FileZilla, WinSCP…)
Uploader **tout le contenu du projet** dans le dossier `www/` (ou `htdocs/`) de votre hébergement OVH, **SAUF** les fichiers listés dans la section suivante.

### Étape 3 — Créer le fichier `.env` sur le serveur
Créer **directement sur le serveur** un fichier `.env` à la racine avec les identifiants SMTP OVH :

```
MAIL_HOST=ssl0.ovh.net
MAIL_USERNAME=contact@leboisfrican.fr
MAIL_PASSWORD=votre_mot_de_passe
MAIL_FROM=contact@leboisfrican.fr
MAIL_FROM_NAME="Site La Ferme du Bois Frican"
MAIL_PORT=587
MAIL_TO=gaec@leboisfrican.fr
```

> Le fichier `.env` est **automatiquement protégé** par `.htaccess`, il ne sera
> jamais accessible depuis un navigateur.

### Étape 4 — Vérifier les autorisations
Les fichiers doivent être en `644` et les dossiers en `755`. Sur OVH mutualisé
c'est le comportement par défaut.

### Étape 5 — Tester
Ouvrir <https://leboisfrican.fr/> puis <https://leboisfrican.fr/fbf_contact.php>
et envoyer un message test.

---

## Configuration du formulaire de contact

Le fichier `.env` définit les paramètres SMTP :

| Variable          | Description                                              | Exemple OVH                |
|-------------------|----------------------------------------------------------|----------------------------|
| `MAIL_HOST`       | Serveur SMTP                                             | `ssl0.ovh.net`             |
| `MAIL_USERNAME`   | Adresse email d'envoi                                    | `contact@leboisfrican.fr`  |
| `MAIL_PASSWORD`   | Mot de passe SMTP                                        | `********`                 |
| `MAIL_PORT`       | Port SMTP (587 pour STARTTLS, 465 pour SSL)              | `587`                      |
| `MAIL_FROM`       | Adresse « De: » affichée (souvent égale à MAIL_USERNAME) | `contact@leboisfrican.fr`  |
| `MAIL_FROM_NAME`  | Nom « De: » affiché                                      | `Site La Ferme du Bois Frican` |
| `MAIL_TO`         | Adresse qui reçoit les messages                          | `gaec@leboisfrican.fr`     |

Si le fichier `.env` est absent ou incomplet, le formulaire affiche un message
explicite invitant les visiteurs à téléphoner en attendant — jamais d'erreur PHP
visible.

---

## Fichiers à ne PAS uploader

Ces fichiers/dossiers sont utilisés uniquement pour le développement Emergent
ou pour la doc, ils **ne doivent pas** être uploadés sur OVH :

```
frontend/               # Shim yarn pour Emergent Preview
backend/                # Stub FastAPI pour Emergent Preview
memory/                 # Notes internes du projet (PRD)
test_reports/           # Rapports du testing agent
.emergent_router.php    # Router PHP local Emergent
test_result.md          # Notes internes
.env                    # À créer directement sur le serveur (JAMAIS commit)
```

Les fichiers `.htaccess`, `README.md`, `.env.example`, `composer.json/lock` sont
protégés par Apache mais peuvent être uploadés — ils ne sont juste pas
accessibles depuis le web.

---

## Structure du projet

```
/
├── .htaccess                   # Config Apache (sécurité, cache, compression)
├── index.php                   # Redirection vers accueil
├── Templatebase.php            # Layout commun (header + menu)
├── footerbase.php              # Pied de page commun
├── fbf_accueil.php             # (sous /accueil/) Page d'accueil
├── fbf_apropos.php             # Page « À propos »
├── fbf_contact.php             # Page contact
├── fbf_decouverte.php          # Page « Découverte »
├── fbf_infopratique.php        # Page « Infos Pratiques »
├── fbf_mentionslegales.php     # Mentions légales HTML
├── formulaire_contact.php      # Traitement du POST du formulaire
├── fbf_style_test.css          # Feuille de style principale
├── assets/
│   ├── css/                    # CSS spécifiques (accueil, carousel, etc.)
│   └── js/                     # menu.js, carousel.js
├── images/                     # Photos et vidéos
├── composer.json               # Dépendances (PHPMailer + Dotenv)
└── vendor/                     # Généré par « composer install »
```
