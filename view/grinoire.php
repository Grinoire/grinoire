<?php
session_start();

if (isset($_GET['deconnexion'])) {
    $_SESSION['userConnected'] = array();
    session_destroy();
    header('Location: ../web/index.php');
    die();
}

var_dump($_SESSION);
?>
<section style="display: grid;">
    <h1 style="color:red;text-align: center;">Bienvenue dans le jeux de carte GRINOIRE</h1>
    <a href="#" style="text-align:right;">PROFIL</a>
    <a href="#" style="text-align:center;">JOUER</a>
    <a href="?deconnexion">Deconnexion</a>
</section>
