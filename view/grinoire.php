<?php
session_start();

if (isset($_GET['deconnexion'])) {
    $_SESSION['grinoire']['userConnected'] = array();
    session_destroy();
}

var_dump($_SESSION);
echo '<br>';
var_dump($_SESSION['grinoire']['userConnected']);
?>
<section style="display: grid;">
    <h1 style="color:red;text-align: center;">Bienvenue dans le jeux de carte GRINOIRE</h1>
    <a href="?section=profil" style="text-align:right;">PROFIL</a>
    <a href="#" style="text-align:center;">JOUER</a>
    <a href="?deconnexion">Deconnexion</a>
</section>
