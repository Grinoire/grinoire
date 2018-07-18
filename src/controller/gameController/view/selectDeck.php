<?php
if (!isset($cardList)) { //si on n'a pas selectionne de hero
?>
<section id="selectDeck-section">

    <div id="selectDeck-section-wrapper">

        <h1><span>C</span>HOIX DU HÉRO</h1>

        <div id="selectDeck-img-hero">

            <div id="selectDeck-container-chapelier">

                <img src="img/grinoire/selectDeck/chapelier.png"/>

                <form class="select-deck" action="" method="post">
<!--                    <img src="img/grinoire/selectDeck/chapelier.png" alt="Tim Burton">-->
                    <input type="hidden" name="selectedDeck" value="1">
                    <input id="selectDeck-chapelier" type="submit" value="">
                </form>

            </div>

            <div id="selectDeck-div-bateau">

            </div>
<!---->
            <div>
                <img id="gandalf" src="img/grinoire/selectDeck/gandalf.png"/>

                <form class="select-deck" action="" method="post">
<!--                    <img src="img/grinoire/selectDeck/gandalf.png" alt="Heroic-Fantasy">-->
                    <input type="hidden" name="selectedDeck" value="2">
                    <input id="selectDeck-gandalf" type="submit" value="">
                </form>

            </div>

        </div>

        <?php
        }
        elseif (isset($cardList)) { //une fois le hero selectionne on affiche les carte pour que l'utilisateur fasse une selection de 20 carte
            ?>
            <form class="deck-selection-card" action="" method="post">
                <input type="hidden" name="selectedDeck" value="<?= $_POST['selectedDeck'] ?>">
                <?php
                $counter = 0;
                foreach ($cardList as $key => $card):
                    $counter++;
                    ?>
                    <div class="card">
                        <input id="checkboxSelectCard<?= $counter ?>" class="selectCard" type="checkbox"
                               name="selectedCard[]" value="<?= $card->getId() ?>">
                        <label for="checkboxSelectCard<?= $counter ?>"></label>
                    </div>
                <?php endforeach; ?>
                <input class='deck-select-card-submit' type="submit" name="submit" value="Commencer a jouer !">
            </form>
            <?php
        }

        // isset($_POST['selectedCard']) ? var_dump($_POST['selectedCard']) : null;
        // isset($_POST['selectedDeck']) ? var_dump($_POST['selectedDeck']) : null;

        ?>

        <a id="selectDeck-lien-vers-accueil" href="?c=Home&a=grinoire">Retour à l'accueil</a>

    </div>


</section>
