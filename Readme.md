
Projet Serpent

# Ce projet vise à gérer une base de données de serpents, en permettant la création, la modification, la suppression, le trie, le filtrage et l'affichage des informations sur les serpents. 

# Bienvenue dans la Ferme à Serpents Snake-GIGI ! Voici quelques fonctionnalités principales :

# Création de Serpents : Vous pouvez créer en spécifiant leurs caractéristiques telles que le nom, la race, le genre, la durée de vie, le poids, la date et l'heure de naissance. Vous pouvez génerer jusqu'à 15 serpents aléatoirement

# Modification des Serpents : Vous avez la possibilité de modifier les caractéristiques d'un serpent, y compris sa durée de vie, pour prolonger sa vie.

# Affichage des Profils : Vous pouvez afficher les profils des serpents, y compris leur famille, en accédant à leurs informations.

# Love Room : Les serpents peuvent être envoyés dans la Love Room pour s'accoupler et potentiellement avoir des bébés !

# Alertes de Décès : Les serpents ont une durée de vie limitée, et des alertes en haut de leur profil vous avertiront de leur décès imminent.

# Suppression des Serpents : Vous avez la possibilité de tuer un serpent manuellement.

Technologies Utilisées

# Ce projet utilise les technologies suivantes :

# PHP : pour la logique côté serveur.
# MySQL : pour la base de données des serpents, avec les tables Serpent et Race. Vous trouverez le fichier de la base de données nommé ElevageSerpents.sql
# Bootstrap et MDBBootstrap : pour l'interface utilisateur.

Fonctionnalités

# Voici les fonctionnalités clés du projet :

# Création de serpents sur mésure avec leurs caractéristiques
# Affichage de la liste des serpents avec possibilité de trier par différentes colonnes.
# Filtrage des serpents par genre et race.
# Pagination pour afficher un nombre défini de serpents par page.
# Mise à jour des informations des serpents.
# Suppression des serpents manuellement et automatiquement (en cas de décès).
# Génération aléatoire de 15 serpents.
# Suivi de la durée de vie des serpents et marquage comme décédés lorsque leur durée de vie est écoulée.
# Gestion de la fonction "Love Room" pour l'accouplement des serpents.

Comment Utiliser

# Pour utiliser le projet, suivez ces étapes :

# Assurez-vous d'avoir PHP et MySQL installés sur votre serveur.
# Importez la base de données à partir du fichier ElevageSerpents.sql.
# Configurez les informations de connexion à la base de données dans bdd.class.php.
# Démarrez le serveur.
# Accédez à l'application via le navigateur en ouvrant index.php.