# La Ferme du Bois Frican

Site vitrine de la ferme (PHP + PHPMailer).

## Installation

```bash
composer install
```

## Configuration du formulaire de contact

1. Copier `.env.example` vers `.env`
   ```bash
   cp .env.example .env
   ```
2. Remplir les variables SMTP (fournies par votre hébergeur mail : OVH, Gmail, Orange, etc.)
3. `MAIL_TO` doit contenir l'adresse qui reçoit les messages (par défaut `gaec@leboisfrican.fr`)
4. Le fichier `.env` **ne doit pas être committé** (déjà dans `.gitignore`)

### Exemple de configuration OVH
```
MAIL_HOST=ssl0.ovh.net
MAIL_USERNAME=contact@leboisfrican.fr
MAIL_PASSWORD=motdepasse
MAIL_FROM=contact@leboisfrican.fr
MAIL_PORT=587
```

### Exemple de configuration Gmail (avec mot de passe d'application)
```
MAIL_HOST=smtp.gmail.com
MAIL_USERNAME=votre.compte@gmail.com
MAIL_PASSWORD=mot_de_passe_application_google
MAIL_FROM=votre.compte@gmail.com
MAIL_PORT=587
```

## Améliorations apportées

- **Formulaire de contact fonctionnel** : action relative, validation renforcée,
  gestion des erreurs, anti-spam basique (30 s entre deux envois).
- **Portabilité** : suppression de la référence codée en dur `/Ferme2/`.
  Le site fonctionne aussi bien en local (`http://localhost/Ferme2/`) qu'à la
  racine d'un domaine (`https://leboisfrican.fr/`) grâce à la variable
  `$basePath`.
- **Responsive** : formulaire de contact, breakpoints 1000px / 600px.
- **Menu hamburger** : bordure noire fine sur tous les boutons du menu
  (accessibilité + design cohérent).
- **Menu déroulant** : deux sous-menus (Découverte + Infos Pratiques)
  fonctionnels sur mobile.

## Démarrer en local

```bash
php -S 127.0.0.1:9999
# puis ouvrir http://127.0.0.1:9999/
```
