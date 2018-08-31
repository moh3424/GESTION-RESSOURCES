<?php

/*
Ce fichier sera inclus dans TOUS les scripts (hors inc eux mêmes) pour les éléments suivants :
-connexion à la base de données BDD
-créer ou ouvrir une session
*-Définir le chemain absolu du site (comme dans wordpress)
*-inclure le fichier fonctions.inc.php à la fin de ce fichier pour l'embarquer dans tous les scripts.


*/

// -Connexion à la base de données BDD:
$pdo = new PDO ('mysql:host=localhost;dbname=gestion_rh', // driver mysql : serveur; nom de la PDD
                'root',// pseudo de la BDD
                '', // Mot de passe de la BDD
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,// option1 : pour affichez les erreurs SQL
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8') // option 2 : définit le jeu de caractères des échanges avec la BDD
                );

//-créer ou ouvrir une session:
session_start();

//-Définir le chemain absolu du site (comme dans wordpress):

define('RACINE_SITE', '/gestion_rh/');// cette constante servira  à créer les chemains absoluts utilisés dans haut.inc.php car ce fichier sera inclus dans des scripts qui se situent dans des dossiers différents du site. On ne peut donc pas faire de chemain relatif dans ce fichier.

// Variable d'affichage :

$contenu ='';
$contenu_gauche = "";
$contenu_droite ="";

// Inclusion du fichier fonctions fonctions.inc.php :

require_once('fonctions.inc.php');