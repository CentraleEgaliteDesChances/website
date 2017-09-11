Site interne et externe de Centrale Égalité des Chances ![Travis badge](https://travis-ci.org/CentraleEgaliteDesChances/website.svg?branch=Update-versions-bundles)
=======================================================



Site web de Centrale Égalité des Chances, association étudiante de l'école Centrale Paris
proposant aux étudiants d'animer des séances de tutorat culturelles et scientifiques de
manière volontaire, de préparer des sorties culturelles en région parisienne et des événements
sur le campus durant l'année, ainsi que d'organiser des voyages et des stages à thème et à
l'étranger. Toutes ces actions visent des lycéens de lycées moins favorisés situés en région
parisienne et ont pour but de les pousser à réaliser des études supérieures.

Ce projet permet le développement du site interne et externe de l'association. Le site interne
permet l'organisation pratique des séances de tutorat, propose de sélectionner et de rechercher
facilement parmi les différentes activités clé-en-main, et rassemble les comptes-rendus réalisés.
Le site interne n'est accessible qu'aux membres de l'association grâce à un nom d'utilisateur et
un mot de passe communiqués en début d'année.

Le site externe présente les actions de l'association aux différents partenaires. Il est accessible
par tous.


Contribution
------------

Pour contribuer au développement du site CEC, voici les étapes à suivre :

* préparation de votre ordinateur
* connexion à GitHub et clonage du dépôt
* installation des vendors et préparation du projet
* bonnes pratiques et utilisation du dépôt

Ce document présente un court résumé. Pour plus d'information, merci de vous référer à la page
__documentation__ du WikiDesChances (<http://cec-ecp.com/wiki/index.php?title=Documentation>).


### Préparation de votre ordinateur ###

Pour contribuer au développement du site, n'hésitez pas à installer un serveur web local avec
une version PHP très récente (> 5.4) et MySQL. Les logiciels WAMP, MAMP et XAMP sont des
très bons exemples, et sont gratuits. Ces logiciels intègrent en général un serveur MySQL (c'est le cas pour MAMP).

Il faut aussi vous procurer GIT. Les puristes de la ligne de commande installeront directement
la distribution de GIT (<http://git-scm.com>), les autres pourront opter pour un logiciel avec
interface graphique, plus pratique pour une utilisation quotidienne. Je ne peux que vous conseiller
le logiciel GitHub (Windows : <http://windows.github.com>, Mac OS : <http://mac.github.com>).


### Connexion à GitHub et clonage du dépôt ###

Avant de cloner le dépôt, vous devez créer un compte GitHub (gratuit) et **communiquer votre
nom d'utilisateur à un administrateur du groupe CentraleEgaliteDesChances** (liste disponible [ici](https://github.com/centraleegalitedeschances?tab=members)) pour qu'il vous 
donne les droit de contribuer au dépôt.

Vous pouvez ensuite cloner ce dépôt sur votre ordinateur en utilisant le logiciel GitHub ou en ligne
de commande :

```
git clone https://github.com/CentraleEgaliteDesChances/website.git chemin/pour/votre/depot/local
```


### Installation des vendors et préparation du projet ###

Le projet du site utilise le framework PHP Symfony2 (http://symfony.com). Pour des raisons d'optimisation,
seuls les fichiers de notre projet ont été copiés et non pas toutes les bibliothèques requises du
framework. Pour télécharger ces "vendors", vous aurez besoin de [Composer](https://getcomposer.org/download/), le package manager pour PHP. Utilisez alors la ligne de commande :

```
cd chemin/vers/votre/depot/local
php composer.phar install
```

Une fois les vendors installés, vous avez l'opportunité de donner les paramètres locaux de votre projet.
Il s'agit principalement des paramètres de connexion à la base de donnée locale et au serveur d'envoi de mail SMTP.
Acceptez toutes les valeurs par défaut (entre crochets, tapez entrer pour accepter) sauf pour :

* database_user -> indiquez le database_user propre à votre serveur (`root` par défaut sous MAMP)
* database_password -> indiquez le database_password propre à votre serveur (`root` par défaut sous MAMP)

> Note : vous pourrez retrouver ces paramètres dans le fichier `app/config/parameters.yml` en cas de besoin.

> Assurez-vous que les paramètres de la base de donnée (notamment `database_user`, `database_password`, `database_port`) correspondent à ceux de votre serveur PHP. Sous MAMP, ces paramètres sont accessibles dans Préférences/Ports. Une mauvaise configuration peut faire apparatre une erreur de type "Database access denied" à l'étape qui suit.

Il faut ensuite créer la base de donnée, et tout cela est fait automatiquement en tapant :

```
php app/console doctrine:database:create
php app/console doctrine:schema:create
```

Vous pouvez ensuite charger les données de test (appelées "data fixtures") :

```
php app/console doctrine:fixtures:load
```

Et il ne vous reste plus qu'à installer les "assets" (les CSS, les JS, les images) et à vider le cache :

```
php app/console assets:install --symlink
php app/console cache:clear
```

Et voilà, vous devriez pouvoir accéder au site localement en utilisant une URL qui ressemble à
`http://localhost/web/app_dev.php` ou à `http://localhost/app_dev.php` si votre serveur pointe directement
sur le dossier web du dépôt (conseillé, c'est moins fatiguant à écrire).

Attention : assurez-vous que votre serveur PHP soit positionné sur le dossier du site. Sous MAMP, allez dans les préférences, onglet WebServer et renseignez le dossier du site dans "Document Root".


Bonnes pratiques et utilisation du dépôt
----------------------------------------

Quelques règles simples sont à respecter pour garder un dépôt organisé et un code propre et agréable.

### Bonne pratique dans le code ###

De manière générale, le code doit être aéré et correctement indenté (à l'aide de 4 espaces). Les classes, fonctions
et méthodes doivent être commentées en utilisant le modèle donné par les classes et méthodes existantes, et
en utilisant les mots-clés `@author`, `@version`, `@param`, `@return` et `@throw`. Les bonnes pratiques de Symfony2
et de la programmation orienté objet devront être observées.

Pour homogénéiser le code, tout doit être écrit en anglais (classes, méthodes, variables, commentaires, table
de base de donnée), sauf ce qui concerne l'affichage pour l'utilisateur final (qui doit être en français).

### Utilisation du dépôt GIT ###

La branche `master` correspond toujours à une version du site en production, ou pouvant à tout moment être mise
en production. Le développement se fera toujours sur une branche séparée, portant le numéro de la version en
développement (par ex: `v1.0`).

Pour contribuer, il faut créer une branche dérivant de la branche de développement portant le nom de la
fonctionnalité sur laquelle vous travaillez (par ex: `page-tutorat` ou `calendrier-seances`). Sur cette branche,
vous êtes libres (et encouragés) de tester de nouvelles choses, de faire du chibre, etc. N'oubliez pas de faire
des commits de manière régulière.

Lorsque vous avez terminé de travailler sur votre branche, NE FUSIONNEZ PAS immédiatement avec la branche de
développement mais ouvrez un pull request sur GitHub, en indiquant ce que vous avez fait. Cela permet aux autres
contributeurs de tester votre travail, d'émettre des commentaires ou des propositions. Vous pourrez ensuite
retravailler le code si nécessaire, et ajouter des commits à la branche.

Lorsque tout le monde accepte les commits de la branche, un propriétaire du compte CentraleEgaliteDesChens
pourra effectuer la fusion de la branche, fermer le pull request, et supprimer la branche.


Référence et documentation
--------------------------

Le [WikiDesChances](http://cec-ecp.com/wiki) propose de nombreuses pages permettant à n'importe qui d'apprendre
en quelques semaines à développer pour Symfony2, et lui permette de contribuer rapidement à ce projet. Le point
d'entrée principal pour la documentation se trouve à cette adresse : <http://cec-ecp.com/wiki/index.php?title=Documentation>.

Vous pourrez y trouver :

* Un descriptif des sites internet de CEC : <http://cec-ecp.com/wiki/index.php?title=Site_internet>
* Une formation complète pour devenir geek : <http://cec-ecp.com/wiki/index.php?title=Formation_Geek>
* Un aperçu du développement du site : <http://cec-ecp.com/wiki/index.php?title=Développement_du_site_internet>
* Une référence des bundles et des classes : <http://cec-ecp.com/wiki/index.php?title=Références>

Vous pouvez trouver de nombreuses informations nécessaires aux développeurs sur le site internet :

* Le schéma de la base de donnée : <http://cec.via.ecp.fr/doc/database-schema/schema-bdd.pdf>
* La documentation automatique : <http://cec.via.ecp.fr/doc/index.html>

Pour en savoir plus :

* GIT : <http://git-scm.com/documentation>
* Github : <https://help.github.com> ou [le site du zéro](http://fr.openclassrooms.com/informatique/cours/gerez-vos-codes-source-avec-git)
* Symfony : http://symfony.com/doc/current/book/index.html ([en français ici](http://symfony.com/fr/doc/master/book/index.html)) ou [le site du zéro](http://fr.openclassrooms.com/informatique/cours/developpez-vos-applications-web-avec-symfony2)
