<?php
declare(strict_types=1);

namespace grinoire\src\controller\GameController;

use grinoire\src\controller\CoreController;
use grinoire\src\exception\UserException;
use grinoire\src\model\DeckManager;
use grinoire\src\model\UserManager;
use grinoire\src\model\GameManager;


class GameController extends CoreController
{

    /**
     * GameController Construct
     * @param  array  $get   Global $_GET
     * @param  array  $post  Global $_POST
     */
    public function __construct(array $get, array $post)
    {
        parent::__construct($get, $post);
    }


    /**
     * Affiche la selection du deck par default
     *
     * Sinon si un deck est selectionne, on affiche les cartes du deck pr que le joueur selectionne 20 cartes
     *
     * Sinon si un deck et 20 cartes sont deja selectionnés, on genere une copie
     * des carte et du heros selectionné en bdd et redirige l'utilisateurs afin de trouver une partie
     */
    public function selectDeckAction() : void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            //Si l'utilisateur a selectionné un deck, et que la valeur est valide // TODO: method pr verifier si deeck id est valide
            if (array_key_exists('selectedDeck', $this->getPost()) && ($this->getPost("selectedDeck") == 1 || $this->getPost("selectedDeck") == 2)) {
                $deckManager = new DeckManager();

                //Si l'utilisateur a selectionné 20 cartes dans le deck
                if (array_key_exists('selectedCard', $this->getPost()) && count($this->getPost('selectedCard')) === 20) {

                    $selectedCardList = []; //on recupere les originaux chaque carte selectionne en objet
                    foreach ($this->getPost('selectedCard') as $key => $cardId) {
                        $selectedCardList[] = $deckManager->getCardById((int)$cardId);
                    }

                    //On insere en bdd l'id du deck choisi, on genere des copie pour les carte et le hero
                    //On genere une session pr le deck entier et redirige l'utilisateur vers matchMakingAction() pr y trouver une partie
                    $userManager = new UserManager();
                    $userManager->setSelectedDeck((int)$this->getSession('userConnected'), (int) $this->getPost('selectedDeck'));
                    $deckManager->setTmpDeck($this->getSession('userConnected'), $selectedCardList, (int) $this->getPost('selectedDeck'));
                    $this->setSession("deck", $deckManager->getDeck());
                    redirection('?c=game&a=matchMaking');

                } else { //Sinon aucune carte n'a encore ete selectioner, on recupere toutes les cartes originales et on redirige sur la vue de selection des cartes
                    $data['cardList'] = $deckManager->getAllCardByDeck((int) $this->getPost("selectedDeck"));
//                    $this->render(true, 'selectDeck', $data);
                    require '../view/template-home/header.php';
                    $this->render(false, 'selectDeck', $data);
                    require '../view/template-home/footer.php';
                }

            //Sinon l'utilisateur n'a pas de deck selectionné, on affiche la vue de selection du deck
            } else {
//                $this->render(true);
                require '../view/template-home/header.php';
                $this->render(false, 'selectDeck');
                require '../view/template-home/footer.php';
            }

        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
//            $this->render(true); //show view deck selection
            require '../view/template-home/header.php';
            $this->render(false, 'selectDeck');
            require '../view/template-home/footer.php';
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }


    /**
     *  Find a opponent, selected in BDD by ready value = 1 (deck selected, attempt to play)
     *
     *  Auto-retry to find if opponent not founded
     *
     *  Redirect to game view when opponent have been found
     *
     *  Show UserException in matchMaking view
     */
    public function matchMakingAction() :void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {

            $userManager = new UserManager();
            $gameManager = new GameManager();
            $id = (int)$this->getSession('userConnected');

            if ($userManager->getUserById($id)->getGameIdFk() === NULL) {                           //si l'utilisateurs n'est pas deja dans une partie
                $gameOpen = $gameManager->getActiveGame();                                          //on recupere les partie en attente
                if (count($gameOpen) == 0) {                                                        //si il n'y en a pas
                    $gameManager = new GameManager();
                    $gameId = $gameManager->newGame($id);                                           //on la créé en bdd
                    $this->setSession('game', $gameManager->getGame($gameId));                      //on update la session

                } else{
                    foreach ($gameOpen as $game) {                                                  //pr chaque game en tattente
                        if ($game->getPlayer1Id() !== $id) {                                        //si l'id joueur n'est pas la notre
                            $gameManager->attributeGame($id, $game->getId());                       //on attribue la game a l'utilisateur
                            $this->setSession('game', $gameManager->getGame($game->getId()));       //on update la session game
                            break;
                        }
                    }
                }
            }
            // var_dump($this->getSession('game'));
            redirection('?c=game&a=game');

        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true); //affiche la vue par default de selection du deck
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }



    /**
     * init game, define default card status
     *
     *  Show UserException on game view
     */
    public function gameAction()
    {
        try {

            $deckManager = new DeckManager();
            $gameManager = new GameManager();
            $userManager = new UserManager();
            $id = (int) $this->getSession('userConnected');

            echo $id;
            // var_dump($this->getSession());

            //on controle si le joueur n'est pas seul dans la partie
            if ($gameManager->getGame($userManager->getUserById($id)->getGameIdFk())->getPlayer2Id() != null ) {

                //si le button prochain tour est cliqué, on ajoute 1 au tour et save en BDD et session
                if (array_key_exists('nextTurn', $this->getGet())) {
                    $deckManager->UpdateStatusOnBoard($id);
                    $gameManager->nextTurn((int) $this->getSession('game')->getId(), (int) $this->getSession('game')->getTurn());
                    $this->setSession('game', $gameManager->getGame((int) $this->getSession('game')->getId()));
                    redirection('?c=game&a=game');

                //Sinon si une carte de la pioche est cliqué
                } elseif (array_key_exists('id', $this->getGet()) && array_key_exists('draw', $this->getGet())) {
                    $card = $deckManager->getTmpCardByID((int) $this->getGet('id'));
                    $card->setStatus(1);
                    $deckManager->UpdateTmpCard($card);
                    redirection('?c=game&a=game');

                //Sinon si c'est le premier tour de jeu, on initialise le plateau
                } elseif ((int) $this->getSession('game')->getTurn() === 0) {

                    //recupere le deck du joueur et initialise en BDD le status des cartes [pioche, en main]
                    $cardList = $deckManager->getTmpDeck($id)->getCardList();
                    $deckManager->initCardStatus($cardList);

                    //on incremente le tour de 1
                    $gameManager->nextTurn((int) $this->getSession('game')->getId(), (int) $this->getSession('game')->getTurn());
                    $this->setSession('game', $gameManager->getGame((int) $this->getSession('game')->getId()));

                //si une carte et une cible on ete selectionné
                } elseif (array_key_exists('id', $this->getGet()) && array_key_exists('target', $this->getGet())) {

                    //on recupere la carte et sa cible
                    $cardPlayer = $deckManager->getTmpCardByID( (int) $this->getGet('id'));
                    if (array_key_exists('hero', $this->getGet())) {
                        if ((int) $this->getSession('game')->getPlayer2Id() === $id) { //defini l'id de l'adversaire et recupere le deck associé
                            $target = $deckManager->getTmpHero((int) $this->getSession('game')->getPlayer1Id());
                        } else {
                            $target = $deckManager->getTmpHero((int) $this->getSession('game')->getPlayer2Id());
                        }
                    } else {
                        $target = $deckManager->getTmpCardByID( (int) $this->getGet('target'));
                    }

                    //si la cible est valide (Hero ou target instance)
                    if ($cardPlayer->isValidTarget($target)) {
                        $dead = $cardPlayer->giveDamage($target); //on attaque la cible et retourne une valeur mort ou pas
                        if (get_class($target) == 'grinoire\src\model\entities\Hero') { //si hero
                            $deckManager->UpdateTmpHero($target); //on met a jour la table en BDD
                            // TODO: end of game if king die

                        } else { //sinon on met a jour la table card en BDD
                            if ($dead === 2) {
                                $target->setStatus(2); //carte defausser
                            }
                            $deckManager->UpdateTmpCard($target);
                        }

                    } else {
                        // TODO: message cible invalide
                    }

                    redirection('?c=game&a=game');

                    //sinon si une carte est une zone sont selectionne on essaye de deplace la carte
                } elseif (array_key_exists('id', $this->getGet()) && array_key_exists('zone', $this->getGet())) {

                    //on recupere la carte et sa cible
                    $cardPlayer = $deckManager->getTmpCardByID((int) $this->getGet('id'));

                    $cardList = $deckManager->getTmpDeck($id)->getCardList();
                    $cardOnBoard = 0;
                    foreach ($cardList as $card) {
                        if ($card->getStatus() === 3 || $card->getStatus() === 4) {
                            $cardOnBoard++;
                        }
                    }
                    if ($cardOnBoard < 7) {
                        $cardPlayer->setStatus(3);
                        $deckManager->UpdateTmpCard($cardPlayer);
                    }

                    redirection('?c=game&a=game');
                }


                //on recupere le deck des joueur et affiche le plateau de jeu
                $data['user'] = $deckManager->getTmpDeck($id);

                if ((int) $this->getSession('game')->getPlayer2Id() == $id) {                                       //si on est le joueur 2 dans la partie
                    $data['opponent'] = $deckManager->getTmpDeck((int) $this->getSession('game')->getPlayer1Id());  //on recupere le deck du joueur 1 en ennemi
                } else {                                                                                            //sinon si on est pas le joueur 2
                    $data['opponent'] = $deckManager->getTmpDeck((int) $this->getSession('game')->getPlayer2Id());  //on recupere le deck du joueur 2 en ennemi
                }
                $this->render(true, 'game', $data);

            } else { //si un seul joueur est dans la partie, on laffiche seul sur le plateau de jeu :
                $cardList = $deckManager->getTmpDeck($id)->getCardList();       //on recupere son deck
                $deckManager->initCardStatus($cardList);                        //on initialise le status des cartes
                $data['user'] = $deckManager->getTmpDeck($id);                  //recupere le deck du joueur a jour
                $this->render(true, 'game', $data);                             //affiche la vue par default
            }


        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true); //show view deck selection
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }

    }


    /**
     *   Remet a zero toute les info lie a la partie en cours
     *   Redirige l'utilisateur vers l'acceuil
     *   @return void
     */
    public function abandonAction() {
        $this->init(__FILE__, __FUNCTION__);

        $userManager = new UserManager();
        $userId = (int) $this->getSession('userConnected');                     //on stock l'id du joueur connecte
        $gameId = $userManager->getUserById($userId)->getGameIdFk();                //on recupere l'id de la partie en BDD

        if ($gameId !== NULL) {                                                 //verifie l'id de la partie est valide
            $userManager->resetData($userId);                                       //reinitialise les valeurs de l'utilisateur en BDD (gameFk, deckFk)
            $gameManager = new GameManager();
            $gameManager->resetData($gameId);                                   //actualise les données liés a la partie en BDD (status)
            $deckManager = new DeckManager();
            $deckManager->resetData($userId);                                       //Efface la copie du deck et ses cartes genere temporairement (carte, hero)
        }
        redirection('?c=Home&a=grinoire');
    }

}
