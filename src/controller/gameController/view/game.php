

<?php

// var_dump($user);
// var_dump($opponent);
?>
    <div id="global-container" class="gameContainer"><!-- Container "plateau de jeu" -->
        <div class="gameWrapper"><!-- Wrapper "plateau de jeu" -->

            <!-- Cote gauche du plateau: pioche joueur1 & joueur2 -->
            <div class="leftContainer">
                <div class="leftWrapper">
                    <div class="drawContainer firstDraw grey">
                        <?php
                        foreach ($opponent->getCardList()  as $card) { //build card in draw
                            if ($card->getStatus() === 0) {
                                echo '<a href=""><div class="card back"></div></a>';
                            }
                        }
                        ?>
                    </div>
                    <!-- pioche joueur 2 -->
                    <div class="drawContainer secondDraw blue">
                        <?php
                        foreach ($user->getCardList()  as $card) { //build card in draw
                            if ($card->getStatus() === 0) {
                                echo '<a href=""><div class="card back"></div></a>';
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
                        <?php
                        foreach ($opponent->getCardList() as $card) { //build card in main
                            if ($card->getStatus() === 1) {
                                echo '<a href=""><div class="card">' . $card->getBg() . '</div></a>';
                            } // TODO: jen ete la
                        }
                        ?>
                    </div>

                    <div class="hero-opponent">
                        <?=$opponent->getHero()->getName()?>
                    </div>

                    <div class="boardGame yellow">
                        boardGame
                    </div>

                    <div class="hero-player">
                        <?=$user->getHero()->getName()?>
                    </div>

                    <div id="deck-2" class="deckContainer secondPlayerDeck cyan">
                        <?php
                        foreach ($user->getCardList() as $card) { //build card in main
                            if ($card->getStatus() === 1) {
                                echo '<a href=""><div class="card">' . $card->getBg() . '</div></a>';
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
