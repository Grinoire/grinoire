<?php
require_once("../config/ini.php");
require_once("../config/splAutoloadRegister.php");
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="Jeu de cartes a collectionner">
        <meta name="author" content="Darragon Damien, Alexandre Le Forestier">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Import LOCAL master.css-->
        <link rel="stylesheet" href="css/master.css">
        <title>Grinoire</title>
    </head>
    <body>


        <?php
        if (isset($_GET['section'])) {
            switch ($_GET['section']) {
                case 'create_account':
                    require '../view/create_account.php';
                    break;
                case 'log_in':
                    require '../view/log_in.php';
                    break;
                case 'grinoire':
                    require '../view/grinoire.php';
                    break;
                default :
                    require '../view/home.php';
            }
        }else{
            require '../view/home.php';
        }
        ?>



        <!--Import CDN Jquery.min.js-->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    </body>
</html>
