    <div id="global-container" class="gameContainer"><!-- Container "plateau de jeu" -->
        <div class="gameWrapper"><!-- Wrapper "plateau de jeu" -->

            <!-- Cote gauche du plateau: pioche joueur1 & joueur2 -->
            <div class="leftContainer">
                <div class="leftWrapper">
                    <div class="drawContainer firstDraw grey">
                        <?php
                        if (isset($opponent)) {
                            foreach ($opponent->getCardList()  as $card) { //pr chaque carte
                                if ($card->getStatus() === 0) {
                                    echo '<a href="?c=game&a=game&id=' . $card->getId() . '"><div class="card back"></div></a>';
                                }
                            }
                        }
                        ?>
                    </div>
                    <!-- pioche joueur 2 -->
                    <div class="drawContainer secondDraw blue">
                        <?php
                        foreach ($user->getCardList()  as $card) { //pr chaque carte
                            if ($card->getStatus() === 0) {
                                echo '<a href="?c=game&a=game&id=' . $card->getId() . '&draw"><div class="card back"></div></a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>


            <!------------------------------->
            <!-- PARTIE ENNEMI DU PLATEAU --->
            <!------------------------------->
            <!-- Centre du plateau: Deck joueur1 & joueur2 + zone de combat -->
            <div class="middleContainer">
                <div class="middleWrapper">

                    <!-- generation des cartes de l'adversaire tenu en main-->
                    <div id="deck-1" class="deckContainer firstPlayerDeck red">
                        <?php
                        if (isset($opponent)) {
                            foreach ($opponent->getCardList() as $card) {//pr chaque carte
                                if ($card->getStatus() === 1) {
                                    echo '<div class="card">' . $card->getBg() . '</div>';
                                }
                            }
                        }
                        ?>
                    </div>

                    <div class="hero-opponent">
                        <?php if (isset($_GET["id"]) && isset($opponent)): ?>
                            <a href="?c=game&a=game&id=<?=$_GET["id"] . '&target=' . $opponent->getHero()->getId()?>&hero">
                                <?=$opponent->getHero()->getName()  . ' ' . $opponent->getHero()->getDamageReceived()?>
                            </a>
                        <?php elseif (isset($opponent)): ?>
                            <?=$opponent->getHero()->getName()?>
                        <?php endif; ?>
                    </div>

                    <!------------------------------->
                    <!-- PARTIE CENTRAL DU PLATEAU -->
                    <!------------------------------->
                    <div class="boardGame yellow board-container">

                            <div class="board-top">
                                <?php //on affiche les carte de l'ennemi avec lin de selection si une carte a deja ete selectionne
                                if (isset($_GET["id"]) && isset($opponent)) {
                                    $content = '';
                                    foreach ($opponent->getCardList() as $card) { //pr chaque carte
                                        if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                            $content .= '<a href="?c=game&a=game&id=' . $_GET["id"] . '&target=' . $card->getId() . '"><div class="card">'
                                            . $card->getBg()
                                            . '<br>' . $card->getStatus()
                                            . '<br>' . $card->getDamageReceived()
                                            . '</div></a>';
                                        }
                                    }
                                } elseif (isset($opponent)) {
                                    $content = '';
                                    foreach ($opponent->getCardList() as $card) { //pr chaque carte
                                        if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                            $content .= '<div class="card">' . $card->getBg()
                                            . '<br>' . $card->getStatus()
                                            . '<br>' . $card->getDamageReceived()
                                            . '</div></a>';
                                        }
                                    }
                                }

                                echo isset($content) ? $content :null;
                                ?>
                            </div>

                            <div class="board-bottom">
                                <?php //on affiche le plateau par default, si une carte est selectionné on genere un lien
                                if (isset($_GET["id"])) {
                                    $content = '<a class="boardGame-link" href="?c=game&a=game&id=' . $_GET['id'] . '&zone=board">Board';
                                    foreach ($user->getCardList() as $card) { //pr chaque carte
                                        if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                            $content .= '<a href="?c=game&a=game&id=' . $card->getId() . '"><div class="card">'
                                            . $card->getBg()
                                            . '<br>' . $card->getStatus()
                                            . '<br>' . $card->getDamageReceived()
                                            . '</div></a>';
                                        }
                                    }
                                    $content .= '</a>';
                                } else {
                                    $content = '';
                                    foreach ($user->getCardList() as $card) { //pr chaque carte
                                        if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                            $content .= '<a href="?c=game&a=game&id=' . $card->getId() . '"><div class="card">'
                                            . $card->getBg()
                                            . '<br>' . $card->getStatus()
                                            . '<br>' . $card->getDamageReceived()
                                            . '</div></a>';
                                        }
                                    }
                                }

                                echo isset($content) ? $content :null;
                                ?>
                        </div>

                    </div>


                    <!------------------------------->
                    <!-- PARTIE JOUEUR DU PLATEAU --->
                    <!------------------------------->
                    <div class="hero-player">
                        <?=$user->getHero()->getName()?>
                    </div>

                    <!-- generation des carte du joueur tenu en main-->
                    <div id="deck-2" class="deckContainer secondPlayerDeck cyan">
                        <?php
                        foreach ($user->getCardList() as $card) { //pr chaque carte
                            if ($card->getStatus() === 1) {
                                    echo '<a href="?c=game&a=game&id=' . $card->getId() . '"><div class="card">' . $card->getBg() . '</div></a>';
                            }
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
        <a href="?c=home&a=grinoire&deconnexion">deconnection</a>
    </div><!-- Fin Container "plateau de jeu" -->


    <?php

    // var_dump($_SESSION);
    // var_dump($opponent);
    ?>
