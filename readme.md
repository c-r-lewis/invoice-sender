# README

Le fichier `docker-compose.yml` est utilisé pour définir et exécuter votre environnement de conteneurs. Vous pouvez personnaliser la configuration de la base de données, du serveur SMTP et d'autres paramètres en modifiant les variables d'environnement dans ce fichier.

## Configuration de la base de données

Pour configurer la base de données, vous devez modifier les variables d'environnement du service `db` :

- `MYSQL_ROOT_PASSWORD` : Définissez le mot de passe root de MySQL.
- `MYSQL_DATABASE` : Définissez le nom de la base de données.

Par exemple :
```yaml
db:
  environment:
    MYSQL_ROOT_PASSWORD: votre_mot_de_passe
    MYSQL_DATABASE: votre_base_de_donnees
```

Notez que le fichier `init.sql` contient un script pour initialiser la table avec les données. **Assurez vous que la table gt2i_facturedafi existe.** 

## Configuration du serveur SMTP

Pour configurer le serveur SMTP, vous devez modifier les variables d'environnement du service `cron` :

- `SMTP_HOST` : Définissez l'hôte du serveur SMTP.
- `SMTP_PORT` : Définissez le port du serveur SMTP.
- `SMTP_USERNAME` : Définissez le nom d'utilisateur du serveur SMTP.
- `SMTP_PASSWORD` : Définissez le mot de passe du serveur SMTP.
- `SMTP_AUTH` : Définissez si le serveur SMTP nécessite une authentification (true ou false).
- `SMTP_SENDER` : Définissez l'adresse email de l'expéditeur des emails.
- `HOST` : Définissez là où sont hébergés les factures.

Par exemple :
```yaml
cron:
  environment:
    SMTP_HOST: votre_hote_smtp
    SMTP_PORT: votre_port_smtp
    SMTP_USERNAME: votre_nom_utilisateur_smtp
    SMTP_PASSWORD: votre_mot_de_passe_smtp
    SMTP_SENDER: adresse_email_expediteur
    SMTP_AUTH: true
```
## Autres configurations

- `KEY` : La clé secrète utilisée pour générer les liens de téléchargement. Vous pouvez la modifier dans les services `cron` et `download`. **Assurez vous qu'il s'agisse de la même clé dans les deux configurations.**

Après avoir apporté les modifications nécessaires, enregistrez le fichier et exécutez la commande `docker-compose up -d` pour appliquer les modifications et démarrer les conteneurs.