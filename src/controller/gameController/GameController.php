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
     * Sinon si un deck et 20 cartes sont deja selectionnés
     *
     * On genere une copie des carte et du heros selectionné en bdd et redirige l'utilisateur vers une partie
     */
    public function selectDeckAction() : void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            $deckManager = new DeckManager();

            //Si l'utilisateur a selectionné un deck, et que la valeur est valide
            if (array_key_exists('selectedDeck', $this->getPost()) && $deckManager->isValidDeck((int)$this->getPost('selectedDeck'))) {
                $userId = (int)$this->getSession('userConnected');
                $deckId = (int) $this->getPost('selectedDeck');

                //Si l'utilisateur a selectionné 20 cartes dans le deck
                if (array_key_exists('selectedCard', $this->getPost()) && count($this->getPost('selectedCard')) === 20) {
                    $selectedCardList = [];                                             //On declare un tableau vide
                    foreach ($this->getPost('selectedCard') as $key => $cardId) {       //Pr chaque carte original selectionné
                        $selectedCardList[] = $deckManager->getCardById((int)$cardId);  //On les sauvegarde en attendant de les copier
                    }

                    $userManager = new UserManager();
                    $userManager->setSelectedDeck($userId, $deckId);                    //On insere en bdd l'id du deck choisi
                    $deckManager->setTmpDeck($userId, $selectedCardList, $deckId);      //on genere des copie pour les carte et le hero appartenant a l'utilisateur
                    $this->setSession("deck", $deckManager->getDeck());                 //On genere une session pr le deck entier
                    redirection('?c=game&a=matchMaking');                               //On redirige l'utilisateur vers matchMakingAction() pr y trouver une partie
                } else {                                                                //Sinon aucune carte n'a encore ete selectioner
                    $data['cardList'] = $deckManager->getAllCardByDeck($deckId);        //On recupere toutes les cartes originales
                    require '../view/template-home/header.php';                         //on affiche la vue de selection des cartes
                    $this->render(false, 'selectDeck', $data);
                    require '../view/template-home/footer.php';
                }
            } else {                                                                    //Sinon l'utilisateur n'a pas de deck selectionné
                $data['decks'] = $deckManager->getAllDataForDeck();                     //On recupere les deck jouable
                require '../view/template-home/header.php';                             //On affiche la vue de selection du deck
                $this->render(false, 'selectDeck', $data);
                require '../view/template-home/footer.php';
            }
        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());

            require '../view/template-home/header.php';
            $this->render(false, 'selectDeck');
            require '../view/template-home/footer.php';
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }


    /**
     *  Genere une nouvelle partie pour le joueur si aucune partie n'est disponible
     *
     *  Sinon insere le joueur dans une partie en attente de second joueur
     *
     *  Redirige l'utilisateur vers la gestion du plateau de jeu
     */
    public function matchMakingAction() :void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            $userManager = new UserManager();
            $gameManager = new GameManager();
            $userId = (int)$this->getSession('userConnected');

            // if ($userManager->getUserById($userId)->getGameIdFk() === null) {                    //si l'utilisateurs n'est pas deja dans une partie
                $gameOpen = $gameManager->getActiveGame();                                          //on recupere les partie en attente
                if (count($gameOpen) == 0) {                                                        //si il n'y en a pas
                    $gameManager = new GameManager();
                    $gameId = $gameManager->newGame($userId);                                       //on la créé en bdd
                    $this->setSession('game', $gameManager->getGame($gameId));                      //on update la session
                } else {
                    foreach ($gameOpen as $game) {                                                  //pr chaque game en attente
                        if ($game->getPlayer1Id() !== $userId) {                                    //si l'id joueur n'est pas la notre
                            $gameManager->attributeGame($userId, $game->getId());                   //on attribue la game a l'utilisateur
                            $this->setSession('game', $gameManager->getGame($game->getId()));       //on update la session game
                            break;
                        }
                    }
                }
            // }
            redirection('?c=game&a=game');
        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true);
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
        $this->init(__FILE__, __FUNCTION__);
        try {
            $deckManager = new DeckManager();
            $gameManager = new GameManager();
            $userManager = new UserManager();
            $userId = (int) $this->getSession('userConnected');

            if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {    //On controle si le joueur n'est pas seul dans la partie
                $gameSession = $this->getSession('game');
                if ((int) $gameSession->getTurn() === 0) {                                              //Si c'est le premier tour de jeu, on initialise le plateau
                    $cardList = $deckManager->getTmpDeck($userId)->getCardList();                       //On recupere les cartes du joueur
                    $deckManager->initCardStatus($cardList);                                            //On initialise le status des carte(pioche ou en main)
                    $gameManager->nextTurn((int) $gameSession->getId(), (int) $gameSession->getTurn()); //On incremente le tour de 1 en BDD
                    $this->setSession('game', $gameManager->getGame((int) $gameSession->getId()));      //On met a jour la session de jeu
                }

                //on recupere le deck des joueur et affiche le plateau de jeu
                $data['user'] = $deckManager->getTmpDeck($userId);

                if ((int) $this->getSession('game')->getPlayer1Id() == $userId) {                       //Si on est le joueur 1 dans la partie
                    var_dump($gameSession);
                    $data['opponent'] = $deckManager->getTmpDeck((int) $gameSession->getPlayer2Id());   //On recupere le deck du joueur 2 en ennemi
                } else {                                                                                //Sinon si on est pas le joueur 1
                    var_dump($gameSession);
                    $data['opponent'] = $deckManager->getTmpDeck((int) $gameSession->getPlayer1Id());   //On recupere le deck du joueur 1 en ennemi
                }
                $this->render(true, 'game', $data);

            } else { //Si un seul joueur est dans la partie, on l'affiche seul sur le plateau de jeu :
                $cardList = $deckManager->getTmpDeck($userId)->getCardList();   //On recupere son deck
                $deckManager->initCardStatus($cardList);                        //On initialise le status des cartes
                $data['user'] = $deckManager->getTmpDeck($userId);              //Recupere le deck du joueur a jour
                $this->render(true, 'game', $data);                             //Affiche la vue par default
            }
        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true); //show view deck selection with error message
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }


    /**
     *   Defini la cible a attaque(hero ou carte), controle si la cible est valide
     *
     *   Attaque la cible, change le status si la cible meurt, ou finit la partie si le hero meurt  // TODO: a finir
     *
     *   @return   void
     */
    public function attackAction()
    {
        $this->init(__FILE__, __FUNCTION__);
        $gameManager = new GameManager();
        $userManager = new UserManager();
        $deckManager= new DeckManager();
        $userId = (int) $this->getSession('userConnected');
        $gameSession = $this->getSession('game');

        if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {                //Si le joueur n'est pas seul dans la partie
            if (array_key_exists('id', $this->getGet()) && array_key_exists('target', $this->getGet())) {       //Si une carte et sa cible sont defini

                $cardPlayer = $deckManager->getTmpCardByID((int) $this->getGet('id'));                          //On recupere la carte qui attaque

                if ($cardPlayer->getStatus() === 4) {

                    if (array_key_exists('hero', $this->getGet())) {                                                //Si on attaque un hero
                        if ((int) $gameSession->getPlayer2Id() === $userId) {                                       //On defini l'id de l'adversaire
                            $target = $deckManager->getTmpHero((int) $gameSession->getPlayer1Id());                 //On recupere le hero du joueur 1
                        } else {                                                                                    //Sinon
                            $target = $deckManager->getTmpHero((int) $gameSession->getPlayer2Id());                 //On recupere le hero du joueur 2
                        }
                    } else {                                                                                        //Sinon si on attaque une carte
                        $target = $deckManager->getTmpCardByID((int) $this->getGet('target'));                      //On recupere la carte
                    }

                    if ($cardPlayer->isValidTarget($target)) {                              //si la cible est valide (Hero ou target instance)
                        $dead = $cardPlayer->giveDamage($target);                           //on attaque la cible et retourne une valeur mort ou pas
                        if (get_class($target) == 'grinoire\src\model\entities\Hero') {     //Si la cible est un hero
                            $deckManager->UpdateTmpHero($target);                           //On met a jour le hero en BDD

                            // TODO: end of game if king die
                        } else {                                                            //Sinon si la cible est une carte
                            if ($dead === 2) {                                              //Si la carte est morte
                                $target->setStatus(2);                                      //On la place dans la defausse (statut=2)
                            }
                            $deckManager->UpdateTmpCard($target);                           //On met a jour la carte en BDD
                        }
                    } else {
                                                                                            // TODO: message cible invalide
                    }
                } else {
                                                                                            // TODO: message carte doit etre pose depuis au moins un tour pour attaquer
                }
                redirection('?c=game&a=game');                                              //On redirige vers gameAction()
            }
        }
    }



    /**
     *   Augmente le tour de 1 et le mana, update les cartes sur le plateau depuis moins d'un tour
     *
     *   Met a jour la session de jeu et redirige vers gameAction()
     *
     *   @return   void
     */
    public function nextTurnAction()
    {
        $this->init(__FILE__, __FUNCTION__);
        $gameManager = new GameManager();
        $userManager = new UserManager();
        $deckManager= new DeckManager();
        $userId = (int) $this->getSession('userConnected');
        $gameSession = $this->getSession('game');
        $heroPlayer = $deckManager->getTmpHero($userId);

        if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {    //Si le joueur n'est pas seul dans la partie
            $deckManager->UpdateStatusOnBoard($userId);                                             //On met a jour les carte sur le plateau depuis moins d'un tour
            $gameManager->incrementMana($gameSession->getId());                                     // TODO: jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj
            // $heroPlayer->setMana(
            //     1 + (int) ceil((int) $gameManager->getGame($gameSession->getId())->getTurn() / 2)   //// TODO: a revoir et completer selon tour de jeu
            // );
            // $deckManager->UpdateTmpHero($heroPlayer);
            $gameManager->nextTurn((int) $gameSession->getId(), (int) $gameSession->getTurn());     //on defini en BDD la valeur du tour actuel
            $this->setSession('game', $gameManager->getGame((int) $gameSession->getId()));          //On met a jour la valeur du tour en session
            redirection('?c=game&a=game');                                                          //On redirige vers gameAction()
        }
    }


    /**
     *   Si la pioche est cliqué, retire la carte de la pioche et l'ajoute en main
     *   @return   void
     */
    public function drawAction()
    {
        $this->init(__FILE__, __FUNCTION__);
        $gameManager = new GameManager();
        $userManager = new UserManager();
        $deckManager = new DeckManager();
        $userId = (int) $this->getSession('userConnected');

        if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {    //Si le joueur n'est pas seul dans la partie
            if (array_key_exists('id', $this->getGet())) {                                          //Si l'id de la carte a pioché est defini
                $card = $deckManager->getTmpCardByID((int) $this->getGet('id'));                    //On recupere la carte
                $card->setStatus(1);                                                                //On defini le status a 1 (1 = carte en main)
                $deckManager->UpdateTmpCard($card);                                                 //On met a jour la carte en BDD
                redirection('?c=game&a=game');                                                      //On redirige vers gameAction()
            }
        }
    }


    /**
     *   Deplace une carte sur le plateau si il la limite max n'est pas atteinte
     *   @return   void
     */
    public function moveCardAction()
    {
        $this->init(__FILE__, __FUNCTION__);
        $gameManager = new GameManager();
        $deckManager = new DeckManager();
        $userManager = new UserManager();
        $userId = (int) $this->getSession('userConnected');
        $gameSession = $this->getSession('game');

        if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {    //Si le joueur n'est pas seul dans la partie
            if (array_key_exists('id', $this->getGet())) {                                          //Si l'id de la carte a déplacé est defini

                $cardPlayer = $deckManager->getTmpCardByID((int) $this->getGet('id'));              //On recupere la carte
                $cardList = $deckManager->getTmpDeck($userId)->getCardList();                       //On recupere toutes les cartes du deck
                $cardOnBoard = 0;                                                                   //On initialise un compteur pour connaitre le nbr de carte sur le plateau
                foreach ($cardList as $card) {                                                      //Pour chacune des cartes
                    if ($card->getStatus() === 3 || $card->getStatus() === 4) {                     //si leur status est egale a 3 ou 4 (posé sur le plateau)
                        $cardOnBoard++;                                                             //on incremente le compteur de 1 pour connaitre le nbr de carte deja posées
                    }
                }

                $heroPlayer = $deckManager->getTmpHero($userId);                                    //On recupere le hero du joueur
                if ($cardOnBoard < 7) {                                                             //Si la limite de carte n'est pas atteinte et qu'on a suffisament de mana
                    if ($gameManager->getGame($gameSession->getId())->getMana() >= $cardPlayer->getMana())  // TODO: jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj
                        $cardPlayer->setStatus(3);                                                  //On deplace la carte demandé sur le plateau
                        $heroPlayer->setMana($heroPlayer->getMana() - $cardPlayer->getMana());      //on retire le coup en mana de la carte au mana restant
                        $deckManager->UpdateTmpCard($cardPlayer);                                   //On met a jour la carte en BDD
                        $deckManager->UpdateTmpHero($heroPlayer);                                   //On met a jour le hero en BDD
                    } else {
                                                                                                    // TODO: message erreur mana insufisant
                    }
                } else {
                                                                                                    // TODO: message erreur carte max sur le plateau
                }
                redirection('?c=game&a=game');                                                      //On redirige vers gameAction()
            }
        }
    }


    /**
     *   Remet a zero toute les info lie a la partie en cours
     *   Redirige l'utilisateur vers l'acceuil
     *   @return void
     */
    public function abandonAction()
    {
        $this->init(__FILE__, __FUNCTION__);

        $userManager = new UserManager();
        $userId = (int) $this->getSession('userConnected');                     //On stock l'id du joueur connecte
        $gameId = $userManager->getUserById($userId)->getGameIdFk();            //On recupere l'id de la partie en BDD

        if ($gameId !== null) {                                                 //Verifie l'id de la partie est valide
            $userManager->resetData($userId);                                   //Reinitialise les valeurs de l'utilisateur en BDD (gameFk, deckFk)
            $gameManager = new GameManager();
            $gameManager->resetData($gameId);                                   //Actualise les données liés a la partie en BDD (status)
            $deckManager = new DeckManager();
            $deckManager->resetData($userId);                                   //Efface la copie du deck et ses cartes genere temporairement (carte, hero)
        }
        redirection('?c=Home&a=grinoire');
    }
}
