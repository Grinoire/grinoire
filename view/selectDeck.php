<?php
require_once("../config/ini.php");
require_once("../config/splAutoloadRegister.php");
require_once("../src/common/commonFunction.php");
session_start();
// session_destroy();

try {

    if ( isset($_POST["selectedDeck"]) && ($_POST["selectedDeck"] == 1 || $_POST["selectedDeck"] == 2) ) {
        //generation du deck selectionné
        $cardManager = new CardManager;
        $listCard = $cardManager->getAllCardByDeck($_POST["selectedDeck"]);
        $deck = $cardManager->setDeck($listCard)->getDeck();
        $_SESSION["grinoire"]["deck"] = $deck;

        //on envoie sur la page de recherche d'utilisateur : MATCHMAKING
        redirection( 'matchMaking.php' );
        var_dump($_SESSION["grinoire"]["deck"]);
    }
    else {
        echo 'pas de deck selectionne';
    }

} catch (Exception $e) { getErrorMessageDie($e); }

?>

<form class="select-deck" action="" method="post">
    <img src="" alt="Tim Burton">
    <input type="hidden" name="selectedDeck" value="1">
    <input type="submit" value="Jouer !">
</form>

<form class="select-deck" action="" method="post">
    <img src="" alt="Heroic-Fantasy">
    <input type="hidden" name="selectedDeck" value="2">
    <input type="submit" value="Jouer !">
</form>
