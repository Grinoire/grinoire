<div class="board-container">
        <div id="boardWrapper" class="board-wrapper">
            <!-- Partie opposant -->
            <div id="topBand" class="black-band-top-bg">                        <!-- container haut plateau -->
                <div id="topBandWrapper" class="bottom-band-wrapper">           <!-- grid wrapper -->
                    <div class="opponent-hero">                                 <!-- hero blason -->

                    </div>
                    <div id="opponentHand" class="opponent-hand">                <!-- container 'main' -->
                    </div>
                </div>
            </div>




            <!-- Partie utilisateur -->
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
                    <div class="player-mana">
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

                                    echo '<a class="user-card" href="?c=game&a=game&idMain=' . $card->getId() . '">';

                                    //Si la carte est un sort
                                    if ($card->getTypeIdFk() == 1) {
                                        require("../view/pattern/legendaryCard.php");
                                    } elseif ($card->getTypeIdFk() == 3) {
                                        require("../view/pattern/sortCard.php");
                                    } elseif($card->getTypeIdFk() == 2 || $card->getTypeIdFk() == 4) {
                                        require("../view/pattern/creatureCard.php");
                                    }
                                    echo "</a>";
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


            <!-- Centre du plateau de jeu -->
            <div id="book" class="book-bg">
                <div id="bookWrapper" class="book-wrapper">


                    <div class="book-separator"></div>                      <!-- Ligne separatrice du livre -->
                    <div class="stop-turn"></div>                           <!-- Bouton fin de tour -->
                    <div class="left-border-gold"></div>                    <!-- Bordure du livre -->
                    <div class="right-border-gold"></div>                   <!-- Bordure du livre -->

                </div>
            </div>


        </div>
</div>
