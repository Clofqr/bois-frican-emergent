# La Ferme du Bois Frican — Site vitrine

Site web du GAEC du Bois Frican (élevage, ateliers pédagogiques, séjours équestres).
Stack : **PHP 8** + PHPMailer (SMTP). Aucune base de données requise.

## Sommaire
- [Prérequis](#prérequis)
- [Test en local (Windows / macOS / Linux)](#test-en-local)
- [Déploiement sur OVH mutualisé](#déploiement-sur-ovh)
- [Configuration du formulaire de contact](#configuration-du-formulaire-de-contact)
- [Structure du projet](#structure-du-projet)

---

## Prérequis
- **PHP ≥ 8.0** avec les extensions `mbstring`, `openssl`, `curl` (installées par défaut dans XAMPP et sur OVH).
- **Composer** pour installer PHPMailer et Dotenv (<https://getcomposer.org/download/>).
- Un serveur SMTP (OVH, Gmail avec mot de passe d'application, Sendinblue, etc.) pour l'envoi des emails du formulaire.

---

## Test en local

### Windows avec XAMPP

1. Cloner le projet dans `C:\xampp\htdocs\bois-frican\` (ou n'importe quel sous-dossier de `htdocs`).
2. Ouvrir un terminal PowerShell/CMD dans ce dossier et installer les dépendances :
   ```powershell
   composer install
   ```
3. (Optionnel) Créer un fichier `.env` à partir de `.env.example` pour tester l'envoi de mails :
   ```powershell
   copy .env.example .env
   ```
4. Démarrer Apache dans le panneau XAMPP.
5. Ouvrir <http://localhost/bois-frican/> dans le navigateur.

> Le site fonctionne aussi bien à la racine d'un domaine (`http://localhost/`) que dans un sous-dossier (`http://localhost/bois-frican/`). Tous les chemins internes sont relatifs.

### Serveur PHP intégré (sans XAMPP)

Utile pour un test rapide sans installer Apache :

```powershell
composer install
php -S 127.0.0.1:8000
```
Puis ouvrir <http://127.0.0.1:8000>.

> Si `php` n'est pas reconnu dans PowerShell, ajouter `C:\xampp\php\` au PATH ou utiliser directement `C:\xampp\php\php.exe -S 127.0.0.1:8000`.

### macOS / Linux

```bash
composer install
php -S 127.0.0.1:8000
```

---

## Déploiement sur OVH

### 1. Préparer les dépendances en local
```bash
composer install --no-dev --optimize-autoloader
```
Cette commande crée le dossier `vendor/` nécessaire à PHPMailer.

### 2. Uploader les fichiers via FTP (FileZilla, WinSCP…)
Uploader **tout le contenu** du projet dans le dossier `www/` de votre hébergement OVH.

### 3. Créer le fichier `.env` directement sur le serveur
À la racine du site, créer un fichier `.env` avec les identifiants SMTP OVH :

```
MAIL_HOST=ssl0.ovh.net
MAIL_USERNAME=contact@leboisfrican.fr
MAIL_PASSWORD=votre_mot_de_passe_smtp
MAIL_FROM=contact@leboisfrican.fr
MAIL_FROM_NAME="Site La Ferme du Bois Frican"
MAIL_PORT=587
MAIL_TO=gaec@leboisfrican.fr
```

Le fichier `.env` est protégé par le `.htaccess` et ne sera jamais accessible depuis un navigateur.

### 4. Vérifier
Ouvrir <https://leboisfrican.fr/> puis <https://leboisfrican.fr/fbf_contact.php>, envoyer un message test.

---

## Configuration du formulaire de contact

Variables du fichier `.env` :

| Variable          | Description                                            | Exemple OVH                |
|-------------------|--------------------------------------------------------|----------------------------|
| `MAIL_HOST`       | Serveur SMTP                                           | `ssl0.ovh.net`             |
| `MAIL_USERNAME`   | Adresse email d'envoi                                  | `contact@leboisfrican.fr`  |
| `MAIL_PASSWORD`   | Mot de passe SMTP                                      | `********`                 |
| `MAIL_PORT`       | Port SMTP (587 pour STARTTLS, 465 pour SSL)            | `587`                      |
| `MAIL_FROM`       | Adresse « De: » (souvent identique à `MAIL_USERNAME`)  | `contact@leboisfrican.fr`  |
| `MAIL_FROM_NAME`  | Nom « De: » affiché                                    | `Site La Ferme du Bois Frican` |
| `MAIL_TO`         | Adresse qui reçoit les messages                        | `gaec@leboisfrican.fr`     |

Si le fichier `.env` est absent, le formulaire affiche un message explicite invitant à téléphoner — aucune erreur PHP visible côté visiteur.

---

## Structure du projet

```
/
├── .htaccess                   Configuration Apache (sécurité, cache, compression)
├── .env.example                Modèle des variables SMTP (à copier en .env)
├── index.php                   Redirige vers la page d'accueil
├── Templatebase.php            Layout commun (header + menu)
├── footerbase.php              Pied de page commun
├── fbf_apropos.php             Page « À propos »
├── fbf_contact.php             Page contact + formulaire
├── fbf_decouverte.php          Page « Découverte » (agrège ateliers/animaux/ferme)
├── fbf_infopratique.php        Page « Infos Pratiques »
├── fbf_mentionslegales.php     Page « Mentions légales »
├── fbf_ateliers.php            Fragment inclus par « Découverte »
├── fbf_animaux.php             Fragment inclus par « Découverte »
├── fbf_ferme.php               Fragment inclus par « Découverte »
├── formulaire_contact.php      Traitement du POST du formulaire (PHPMailer)
├── fbf_style_test.css          Feuille de style principale
├── accueil/
│   └── fbf_accueil.php         Page d'accueil (dans un sous-dossier)
├── assets/
│   ├── css/                    Feuilles de style annexes (accueil, carousel...)
│   └── js/                     menu.js + carousel.js
├── images/                     Photos et vidéos (avec .htaccess anti-upload)
│   └── .htaccess               Empêche l'exécution de scripts dans /images/
├── composer.json               Dépendances (PHPMailer + Dotenv)
├── composer.lock               Versions verrouillées
└── vendor/                     Généré par « composer install »
```

Le site est entièrement autonome : il n'y a **aucune dépendance à un serveur applicatif** (Node, Python, base de données) autre qu'Apache + PHP + Composer.
