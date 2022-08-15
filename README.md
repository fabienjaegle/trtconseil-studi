# TRT Conseil

## Projet pour l'Evaluation Continue de Formation Studi : développement de la partie BackEnd d'une application web

### Prérequis
- Avoir PHP >= 8.1.9 installé sur la machine
	- l'extension `pdo_pgsql` doit être présente et activée
- Avoir `docker` installé sur la machine (accès BDD local)
- Avoir la cmdlet `symfony` présente sur la machine
	- peut être installé via l'outil `scoop` : lancer un terminal Powershell et taper `iex (new-object net.webclient).downloadstring('https://get.scoop.sh')`
	- Puis taper `scoop install symfony-cli` pour installer le CLI `symfony`

### Installation
Après avoir cloné le projet, ouvrir le projet via Visual Studio Code par exemple et lancer un terminal, puis taper la commande `composer install` pour installer toutes les dépendances du projet.

Créer ensuite la base de données via la commande `php bin/console doctrine:database:create`.

Effectuer les migrations via les commandes `php bin/console make:migration` et `php bin/console doctrine:migrations:migrate`.

### Administration

Aller à l'adresse `https://127.0.0.1:8000/register/admin` pour créer un administrateur.
P.S : cette route existe pour les besoins du projet. En production, la route devra être supprimée pour des questions de sécurité.