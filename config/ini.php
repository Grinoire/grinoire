<?php
//Connexion base de données
const HOST_DB = "localhost";
const NAME_DB = "grinoire";
const USER_DB = "root";
const PWD_DB = "";

//Affiche toutes les erreur lors du developpement, definir a null en prod
const DEBUG = "DEV";

//nom de l'application
const APP_NAME = "grinoire";

//dossier contenant les avatar
define('DIR_IMG', dirname(dirname(__FILE__)) . '\web\img\avatar\\');
//dossier contenant les vues a afficher
define('DIR_VIEW', dirname(dirname(__FILE__)) . '\view\\');


