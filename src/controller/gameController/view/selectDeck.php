<?php
use grinoire\src\model\UserManager;
 ?>

<form class="select-deck" action="?c=Game&a=matchMaking" method="post">
    <img src="" alt="Tim Burton">
    <input type="hidden" name="selectedDeck" value="1">
    <input type="submit" value="Jouer !">
</form>

<form class="select-deck" action="?c=Game&a=matchMaking" method="post">
    <img src="" alt="Heroic-Fantasy">
    <input type="hidden" name="selectedDeck" value="2">
    <input type="submit" value="Jouer !">
</form>

<?php
var_dump($_SESSION);

?>
