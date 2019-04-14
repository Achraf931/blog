<?php

//fichier de fonctionnalités communes à plusieurs scripts

//paramétrage de la langue de traduction pour PHP
setlocale(LC_ALL, "fr_FR");

//connexion à la base de données
try{
	$db = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $exception)
{
	die( 'Erreur : ' . $exception->getMessage() );
}

//ouverture de session pour connexions utilisateurs et admins
session_start();

?>
