

    <div id="global-container" class="gameContainer"><!-- Container "plateau de jeu" -->
        <div class="gameWrapper"><!-- Wrapper "plateau de jeu" -->

            <!-- Cote gauche du plateau: pioche joueur1 & joueur2 -->
            <div class="leftContainer">
                <div class="leftWrapper">
                    <div class="drawContainer firstDraw grey">

                    </div>
                    <!-- pioche joueur 2 -->
                    <div class="drawContainer secondDraw blue">
                        <?php
                        foreach ($cardList as $card) { //build card in draw
                            if ($card->getStatus() === 0) {
                                echo $card->getBg() . '<br>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Centre du plateau: Deck joueur1 & joueur2 + zone de combat -->
            <div class="middleContainer">
                <div class="middleWrapper">

                    <div id="deck-1" class="deckContainer firstPlayerDeck red">
                    </div>

                    <div class="hero-opponent">Heros opponent</div>

                    <div class="boardGame yellow">
                        boardGame
                    </div>

                    <div class="hero-player">
                        <?=$hero->getBg()?>
                    </div>

                    <div id="deck-2" class="deckContainer secondPlayerDeck cyan">
                        <?php
                        foreach ($cardList as $card) { //build card in main
                            if ($card->getStatus() === 1) {
                                echo '<div class="card">' . $card->getBg() . '</div>';
                            } // TODO: jen ete la
                        }
                        ?>
                    </div>

                </div>
            </div>

            <!-- Cote droit du plateau: Defausse joueur1 & joueur2 -->
            <div class="rightContainer">
                <div class="rightWrapper">

                    <div class="discardedContainer firstHeros brown">HEROS player 1</div>

                    <a class="button-end-tour" href="?c=game&a=game&nextTurn">TOUR</a>

                    <div class="discardedContainer secondHeros purple">HEROS player 2</div>
                </div>
            </div>


        </div><!-- Fin Wrapper "plateau de jeu" -->
    </div><!-- Fin Container "plateau de jeu" -->

<?php

var_dump($_SESSION['grinoire']['deck']);
var_dump($hero);
 ?>
