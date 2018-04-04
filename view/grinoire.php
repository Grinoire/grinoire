<?php
session_start();

if(isset($_GET) && array_key_exists('deconnexion', $_GET)){
    unset($_SESSION['connexion']);
    header('Location: ../accueil_de_connexion.php');
    exit();
}
//var_dump($_SESSION['connexion']);
?>
<style>
    h1{
        color:red;
        text-align: center;
    }
</style>

<h1>Bienvenue dans le jeux de carte GRINOIRE</h1>
<h2>Bonjour <?= $_SESSION['connexion']['email'] ?></h2>
<a href="?deconnexion">Deconnexion</a>