<div id="global-container" class="gameContainer"><!-- Container "plateau de jeu" -->
    <div class="gameWrapper"><!-- Wrapper "plateau de jeu" -->

        <!------------------------------->
        <!--  COTE GAUCHE DU PLATEAU  --->
        <!------------------------------->

        <!-- Cote gauche du plateau: pioche joueur1 & joueur2 -->
        <div class="leftContainer">
            <div class="leftWrapper">

                <!-- pioche joueur 2 -->
                <div class="drawContainer secondDraw blue">
                    <h3>PIOCHE 2</h3>
                    <?php
                    if (isset($opponent)) {
                        foreach ($opponent->getCardList()  as $card) { //pr chaque carte
                            if ($card->getStatus() === 0) {
                                echo '<div class="card back"></div>';
                            }
                        }
                    }
                    ?>
                </div>
                <!-- pioche joueur 1 -->
                <div class="drawContainer firstDraw grey">
                    <h3>PIOCHE</h3>
                    <?php
                    foreach ($user->getCardList()  as $card) { //pr chaque carte
                        if ($card->getStatus() === 0) {
                            echo '<a href="?c=Game&a=draw&id=' . $card->getId() . '"><div class="card back"></div></a>';
                        }
                    }
                    ?>
                </div>

            </div>
        </div>


        <!------------------------------->
        <!--  PLATEAU DE JEU CENTRAL  --->
        <!------------------------------->
        <!-- Centre du plateau: Deck joueur1 & joueur2 + zone de combat -->
        <div class="middleContainer">
            <div class="middleWrapper">

                <!------------------------------->
                <!-- PARTIE ENNEMI DU PLATEAU --->
                <!------------------------------->

                <!-- generation des cartes de l'adversaire tenu en main-->
                <div id="deck-1" class="deckContainer secondPlayerDeck red">
                    <?php
                    // if (isset($opponent)) {
                    //     foreach ($opponent->getCardList() as $card) {//pr chaque carte
                    //         if ($card->getStatus() === 1) {
                    //             echo '<div class="card" style="font-size: 11px">' . $card->getName()
                    //                 . '<br>status: ' . $card->getStatus()
                    //                 . '<br>degat: ' . $card->getDamageReceived()
                    //                 . '<br>type: ' . $card->getTypeIdFk()   //1legendaire, 2bouclier, 3 sort, 4 normal
                    //                 . '<br>mana: ' . $card->getMana()
                    //                 . '</div>';
                    //         }
                    //     }
                    // }
                    ?>
                </div>

                <!-- affichage du hero de l'adversaire -->
                <div class="hero-opponent">
                    <?php if (isset($_GET["id"]) && isset($opponent)): ?>
                        <a href="?c=Game&a=attack&id=<?=$_GET["id"] . '&target=' . $opponent->getHero()->getId()?>&hero">
                            <?=
                            $opponent->getHero()->getName()
                            . '<br>degat: ' . $opponent->getHero()->getDamageReceived()
                            . '<br>mana: ' . $opponent->getHero()->getMana()
                            ?>
                        </a>
                    <?php elseif (isset($opponent)): ?>
                        <?=
                        $opponent->getHero()->getName()
                        . '<br>degat: ' . $opponent->getHero()->getDamageReceived()
                        . '<br>mana: ' . $opponent->getHero()->getMana()
                        ?>
                    <?php endif; ?>
                </div>


                <!------------------------------->
                <!-- PARTIE CENTRAL DU PLATEAU -->
                <!------------------------------->
                <div class="boardGame yellow board-container">

                        <!-- on affiche les carte de l'ennemi avec lien de selection si une carte a deja ete selectionne -->
                        <div class="board-top">
                            <?php //si le joueur a selectionner une carte on permet la selection des carte ennemi sur le plateau
                            if (isset($_GET["id"]) && isset($opponent)) {
                                $content = '';

                                foreach ($opponent->getCardList() as $card) { //on genere chaque carte
                                    if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                        $content .= '<a href="?c=Game&a=attack&id=' . $_GET["id"] . '&target=' . $card->getId() . '"><div class="card" style="font-size: 11px">'
                                        . $card->getName()
                                        . '<br>status: ' . $card->getStatus()
                                        . '<br>degat: ' . $card->getDamageReceived()
                                        . '<br>type: ' . $card->getTypeIdFk()   //1legendaire, 2bouclier, 3 sort, 4 normal
                                        . '<br>mana: ' . $card->getMana()
                                        . '</div></a>';
                                    }
                                }
                            } elseif (isset($opponent)) { //sinon si le joueur n'a rien selectionne on les affiche sans lien de selection
                                // $content = '';
                                // foreach ($opponent->getCardList() as $card) { //on genere chaque carte
                                //     if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                //         $content .= '<div class="card" style="font-size: 11px">'
                                //         . $card->getName()
                                //         . '<br>status: ' . $card->getStatus()
                                //         . '<br>degat: ' . $card->getDamageReceived()
                                //         . '<br>type: ' . $card->getTypeIdFk()   //1legendaire, 2bouclier, 3 sort, 4 normal
                                //         . '<br>mana: ' . $card->getMana()
                                //         . '</div></a>';
                                //     }
                                // }
                            }

                            echo isset($content) ? $content :null;
                            ?>
                        </div>

                        <!-- on affiche les carte du joueur, affichage a revoir... -->

                        <div class="board-bottom">
                            <?php
                            if (isset($_GET["idMain"])) {//si une carte en main est selectionne
                                $content = '<a class="boardGame-link" href="?c=game&a=moveCard&id=' . $_GET['idMain'] . '">Board';     // TODO: BBBBBBBBBBBBBBBBBUUUUUUUUUUUUUUUUUUGGGGGGGGGGGGGGG
                                foreach ($user->getCardList() as $card) { //pr chaque carte
                                    if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                        $content .= '<a href="?c=Game&a=game&id=' . $card->getId() . '"><div class="card" style="font-size: 11px">'
                                        . $card->getName()
                                        . '<br>status: ' . $card->getStatus()
                                        . '<br>degat: ' . $card->getDamageReceived()
                                        . '<br>type: ' . $card->getTypeIdFk()   //1legendaire, 2bouclier, 3 sort, 4 normal
                                        . '<br>mana: ' . $card->getMana()
                                        . '</div></a>';
                                    }
                                }
                                $content .= '</a>';
                            } else {
                                $content = '';
                                // foreach ($user->getCardList() as $card) { //pr chaque carte
                                //     if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                //         $content .= '<a href="?c=game&a=game&id=' . $card->getId() . '"><div class="card" style="font-size: 11px">'
                                //         . $card->getName()
                                //         . '<br>status: ' . $card->getStatus()
                                //         . '<br>degat: ' . $card->getDamageReceived()
                                //         . '<br>type: ' . $card->getTypeIdFk()   //1legendaire, 2bouclier, 3 sort, 4 normal
                                //         . '<br>mana: ' . $card->getMana()
                                //         . '</div></a>';
                                //     }
                                // }
                            }

                            echo isset($content) ? $content :null;
                            ?>
                    </div>

                </div>


                <!------------------------------->
                <!-- PARTIE JOUEUR DU PLATEAU --->
                <!------------------------------->

                <!-- Affichage du heros du joueur -->
                <!-- <div class="hero-player">
                    <?=
                    // $user->getHero()->getName()
                    // . '<br>degat: ' . $user->getHero()->getDamageReceived()
                    // . '<br>mana: ' . $user->getHero()->getMana()
                    ?>
                </div> -->

                <!-- generation des carte du joueur tenu en main-->
                <div id="deck-2" class="deckContainer firstPlayerDeck cyan">
                    <?php
                    // foreach ($user->getCardList() as $card) { //pr chaque carte
                    //     if ($card->getStatus() === 1) {
                    //             echo '<a href="?c=game&a=game&idMain=' . $card->getId() . '"><div class="card" style="font-size: 11px">'
                    //             . $card->getName()
                    //             . '<br>status: ' . $card->getStatus()
                    //             . '<br>degat: ' . $card->getDamageReceived()
                    //             . '<br>type: ' . $card->getTypeIdFk()   //1legendaire, 2bouclier, 3 sort, 4 normal
                    //             . '<br>mana: ' . $card->getMana()
                    //             . '</div></a>';
                    //     }
                    // }
                    ?>
                </div>

            </div>
        </div>


        <!------------------------------->
        <!---  COTE DROIT DU PLATEAU  --->
        <!------------------------------->
        <!-- Cote droit du plateau: Defausse joueur1 & joueur2 -->
        <div class="rightContainer">
            <div class="rightWrapper">

                <div class="discardedContainer secondHeros purple">
                    <h3>DEFAUSSE 2</h3>
                    <?php
                    if (isset($opponent)) {
                        foreach ($opponent->getCardList() as $card) { //pr chaque carte
                            if ($card->getStatus() === 2) {
                                echo '<div class="card back"></div>';
                            }
                        }
                    }
                    ?>
                </div>

                <a class="button-end-tour" href="?c=game&a=nextTurn">TOUR N° <?= isset($game) ? $game->getTurn() : null;?></a>

                <div class="discardedContainer firstHeros brown">
                    <h3>DEFAUSSE</h3>
                    <?php
                    foreach ($user->getCardList() as $card) { //pr chaque carte
                        if ($card->getStatus() === 2) {
                            echo '<div class="card back"></div>';
                        }
                    }
                    ?>
                </div>

            </div>
        </div>


    </div><!-- Fin Wrapper "plateau de jeu" -->
    <a href="?c=Game&a=abandon">Abandonner</a>
</div><!-- Fin Container "plateau de jeu" -->


<?php

// var_dump($_SESSION);
// var_dump($opponent);
?>
