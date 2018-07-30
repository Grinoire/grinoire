<div class="board-container">
        <div id="boardWrapper" class="board-wrapper">

            <!--------------------->
            <!-- PARTIE OPPOSANT -->
            <!--------------------->
            <div id="topBand" class="black-band-top-bg">                        <!-- container haut plateau -->
                <div id="topBandWrapper" class="bottom-band-wrapper">           <!-- grid wrapper -->

                    <!-- hero blason -->
                    <div class="opponent-hero">
                        <?php
                        if (isset($opponent)) {
                            $hero = $opponent->getHero();
                            require("../view/pattern/heroBlason.php");
                        }
                        ?>
                    </div>

                    <!-- main de l'opposant -->
                    <div id="opponentHand" class="opponent-hand">
                        <?php
                        if (isset($opponent)) {
                            foreach ($opponent->getCardList() as $card) {//pr chaque carte
                                if ($card->getStatus() === 1) {
                                    require("../view/pattern/backCard.php");
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>




            <!------------------------>
            <!-- PARTIE UTILISATEUR -->
            <!------------------------>
            <div id="bottomBand" class="black-band-bottom-bg">                  <!-- container bas plateau -->
                <div id="bottomBandWrapper" class="bottom-band-wrapper">        <!-- grid wrapper -->

                    <!-- hero blason -->
                    <div class="player-hero">
                        <?php
                        if (isset($user)) {
                            $hero = $user->getHero();
                            require("../view/pattern/heroBlason.php");
                        }
                        ?>
                    </div>

                    <!-- compteur mana -->
                    <div id="playerMana" class="player-mana">
                        <?php
                        if (isset($user) && isset($game)) {
                            echo $user->getHero()->getMana() . ' / ' . $game->getMana();
                        } elseif (isset($user)) {
                            echo $user->getHero()->getMana() . ' / ' . $user->getHero()->getMana();
                        }
                        ?>
                    </div>

                    <!-- main du joueur -->
                    <div id="playerHand" class="player-hand">
                        <?php
                        if (isset($user)) {
                            foreach ($user->getCardList() as $card) {
                                //Si status en 'main'
                                if ($card->getStatus() === 1) {

                                    // echo '<a class="user-card" href="?c=game&a=game&idMain=' . $card->getId() . '">';

                                    //Selon le type de carte on charge le pattern correspondant
                                    if ($card->getTypeIdFk() == 1) {
                                        require("../view/pattern/legendaryCard.php");
                                    } elseif ($card->getTypeIdFk() == 3) {
                                        require("../view/pattern/sortCard.php");
                                    } elseif($card->getTypeIdFk() == 2 || $card->getTypeIdFk() == 4) {
                                        require("../view/pattern/creatureCard.php");
                                    }
                                    // echo "</a>";
                                }
                            }
                        }
                        ?>
                    </div>

                    <!-- deck du joueur -->
                    <div class="player-deck">

                    </div>
                </div>
            </div>


            <!----------------------->
            <!-- CENTRE DU PLATEAU -->
            <!----------------------->
            <div id="book" class="book-bg">
                <div id="bookWrapper" class="book-wrapper">

                    <!-- Ligne separatrice du livre -->
                    <div class="book-separator"></div>

                    <!-- Bouton fin de tour -->
                    <div class="stop-turn">
                        <?php
                        if (isset($game)) {
                            if (((int) $game->getTurn() % 2) == 0 && ((int) $game->getPlayer2Id() == $_SESSION["grinoire"]["userConnected"])
                                || ((int) $game->getTurn() % 2) != 0 && ((int) $game->getPlayer1Id() == $_SESSION["grinoire"]["userConnected"])) {
                                echo '<a class="stop-turn-link" href="?c=game&a=nextTurn">STOP</a>';
                            } else {
                                echo 'STOP';
                            }
                        }
                        ?>
                    </div>

                    <!-- Bordure du livre -->
                    <div class="left-border-gold"></div>
                    <div class="right-border-gold"></div>

                    <!-- Affichage des cartes de l'opposant' -->
                    <div class="opponent-center-board">
                        <?php
                        if (isset($_GET["id"]) && isset($opponent)) {
                            // code...
                        } elseif (isset($opponent)) {
                            foreach ($opponent->getCardList() as $card) { //on genere chaque carte
                                if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                    if ($card->getTypeIdFk() == 1) {
                                        require("../view/pattern/legendaryBlazon.php");
                                    } elseif($card->getTypeIdFk() == 2 || $card->getTypeIdFk() == 4) {
                                        require("../view/pattern/creatureBlazon.php");
                                    }
                                }
                            }
                        }
                        ?>
                    </div>

                    <!-- Affichage des cartes du joueur -->
                    <div id="playerCenterBoard" class="player-center-board">
                        <?php
                        if (isset($user)) {
                            foreach ($user->getCardList() as $card) {
                                if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                                    if ($card->getTypeIdFk() == 1) {
                                        require("../view/pattern/legendaryBlazon.php");
                                    } elseif($card->getTypeIdFk() == 2 || $card->getTypeIdFk() == 4) {
                                        require("../view/pattern/creatureBlazon.php");
                                    }
                                }
                            }
                        }
                        ?>
                    </div>

                    <?php
                    if (isset($_GET["idMain"])) {//zone de selection pr poser descarte sur le plateau
                        echo '<a class="boardGame-link" href="?c=game&a=moveCard&id=' . $_GET['idMain'] . '">';

                        echo '</a>';
                    }
                    ?>

                </div>
            </div>


        </div>
</div>
