# Sécurisation de l’application *« Bibliothèque »*
## Authentification 48h
La durée maximale de session utilisateur a été limitée à 48 heures.
Pour cela, deux listeners ont été mis en place :

LoginSuccessListener initialise un horodatage login_at dans la session à chaque connexion réussie.

SessionLifetimeListener vérifie, à chaque requête, si le délai de 48h est dépassé. Si oui, la session est invalidée et l’utilisateur est redirigé vers la page de connexion.

## *Cookie* du mode affichage
Un contrôleur UiController a été ajouté pour simuler la sauvegarde du choix d’un mode d’affichage.
Deux routes /toggle-theme/dark et /toggle-theme/light permettent de définir un cookie myapp_dark_mode avec la valeur true ou false.

Le cookie est stocké pour une durée d’un an et visible dans l’onglet Application > Cookies des DevTools.
Et il est utilisé dans le style avec une classe qui est definit selon le theme dans base.html.twig

## Protection CSRF
La protection CSRF est activée sur l’ensemble des formulaires Symfony (connexion, enregistrement, ajout de livre, etc.) via le composant Form et l’option csrf_protection.

## Vulnérabilités des dépendances
Les dépendances du projet sont vérifiées à l’aide de la commande native :

composer audit


Cette commande analyse les bibliothèques installées et signale les éventuelles vulnérabilités de sécurité connues.

## Difficultés rencontrées et solutions
Problèmes de redirections infinies : le pare-feu main et les règles access_control entraient en conflit. La solution a été d’autoriser explicitement les routes /login et /register pour les utilisateurs anonymes.

Authentification non fonctionnelle au début : il a fallu corriger la configuration du form_login (chemins login_path et check_path) et remplacer l’import incorrect du Request Symfony.

Mise en place de la durée de session : la difficulté principale était de ne pas casser la session existante. Le système d’écoute sur security.interactive_login a permis d’initialiser proprement le timestamp.

CSP trop restrictive au départ : certains fichiers CSS/JS étaient bloqués. La politique a été ajustée pour autoriser self et https://trusted.cdn.com uniquement.


## Bilan des acquis
Ce projet m’a permis de :

Comprendre la configuration fine du pare-feu et du système d’authentification Symfony.

Mettre en place une gestion de session sécurisée et limitée dans le temps.

Appliquer une politique CSP stricte pour renforcer la sécurité des ressources.

Manipuler les cookies côté serveur et vérifier les protections CSRF intégrées.

Apprendre à auditer les dépendances et à prévenir les failles liées aux librairies externes.

Globalement, cela m’a aidé à consolider mes connaissances en sécurité web, en particulier sur les points liés à l’authentification, la validation et la protection contre les attaques courantes (XSS, CSRF, dépendances vulnérables).

## Remarques complémentaires

Le changement de projet a nécessité une réadaptation rapide, mais la structure de sécurité mise en place reste conforme aux bonnes pratiques Symfony.

