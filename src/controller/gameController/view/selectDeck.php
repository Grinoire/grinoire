<?php
if (!isset($cardList)) { //si on n'a pas selectionne de hero
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

<?php
}
elseif (isset($cardList)) { //une fois le hero selectionne on affiche les carte pour que l'utilisateur fasse une selection de 20 carte
    ?>
    <form class="deck-selection-card" action="" method="post">
        <input type="hidden" name="selectedDeck" value="<?=$_POST['selectedDeck']?>">
        <?php
        $counter = 0;
        foreach ($cardList as $key => $card):
            $counter ++;
            ?>
            <div class="card">
                    <input id="checkboxSelectCard<?= $counter ?>" class="selectCard" type="checkbox" name="selectedCard[]" value="<?=$card->getId()?>">
                    <label for="checkboxSelectCard<?= $counter ?>"></label>
            </div>
        <?php endforeach; ?>
        <input type="submit" name="submit" value="Commencer a jouer !">
    </form>
    <?php
}

isset($_POST['selectedCard']) ? var_dump($_POST['selectedCard']) : null;
isset($_POST['selectedDeck']) ? var_dump($_POST['selectedDeck']) : null;

?>
