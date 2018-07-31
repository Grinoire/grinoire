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
            $deckManager = new DeckManager();
            $userId = (int)$this->getSession('userConnected');

            // if ($userManager->getUserById($userId)->getGameIdFk() === null) {                    //si l'utilisateurs n'est pas deja dans une partie
                $gameOpen = $gameManager->getActiveGame();                                          //on recupere les partie en attente
                if (count($gameOpen) == 0) {                                                        //si il n'y en a pas
                    $gameManager = new GameManager();
                    $gameId = $userManager->getUserById($userId)->getGameIdFk();        //on recupere l'id de la partie en BDD

                    if ($gameId !== null) {                                             //verifie que l'id de la partie est valide
                        $userManager->resetData($userId);                               //reinitialise les valeurs de l'utilisateur en BDD (gameFk, deckFk)
                        $gameManager->resetData($gameId);                               //actualise les données liés a la partie en BDD (status)
                        $deckManager->resetData($userId);                               //Efface la copie du deck et ses cartes genere temporairement (carte, hero)
                    }
                    $gameId = $gameManager->newGame($userId);                                       //on la créé en bdd
                    $this->setSession('game', $gameManager->getGame((int) $gameId));                      //on update la session
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
                    $this->setSession('drawed', 0);                                                     //On initialise le nombre de carte pioché par tour
                    $this->setSession('played', []);
                }

                $this->setSession('game', $gameManager->getGame((int) $gameSession->getId()));
                $data['game'] = $gameManager->getGame((int) $gameSession->getId());

                //on recupere le deck des joueur et affiche le plateau de jeu
                $data['user'] = $deckManager->getTmpDeck($userId);
                if ((int) $this->getSession('game')->getPlayer1Id() == $userId) {                       //Si on est le joueur 1 dans la partie
                    $data['opponent'] = $deckManager->getTmpDeck(                                       //On recupere le deck du joueur 2 en ennemi
                        (int) $gameManager->getGame($gameSession->getId())->getPlayer2Id()
                    );
                } else {                                                                                //Sinon si on est pas le joueur 1
                    $data['opponent'] = $deckManager->getTmpDeck(                                       //On recupere le deck du joueur 2 en ennemi
                        (int) $gameManager->getGame($gameSession->getId())->getPlayer1Id()
                    );
                }

                if (array_key_exists('ajax', $this->getGet())) {
                    return json_encode($data);
                } else {
                    $this->render(true, 'game', $data);
                }


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
                $data['attack'] = true;

                if ($cardPlayer->getStatus() === 4) {                                                           //Si la carte peut attaquer
                    if (array_key_exists('hero', $this->getGet())) {                                            //Si on attaque un hero
                        if ((int) $gameSession->getPlayer2Id() === $userId) {                                   //On defini l'id de l'adversaire
                            $target = $deckManager->getTmpHero((int) $gameSession->getPlayer1Id());             //On recupere le hero du joueur 1
                        } else {                                                                                //Sinon
                            $target = $deckManager->getTmpHero((int) $gameSession->getPlayer2Id());             //On recupere le hero du joueur 2
                        }

                        foreach ($deckManager->getTmpCards($target->getUserIdFk()) as $card) {                  //pour chaque carte du deck ennemi
                            if ($card->getStatus() == 4 && $card->getTypeIdFk() == 2) {                         //si une carte bouclier est posé
                                $data['error'] = 'Vous devez d\'abord attaquer le défenseur';
                                if (array_key_exists('ajax', $this->getGet())) {
                                    echo json_encode($data);
                                    return;
                                } else {
                                    redirection('?c=game&a=game');                                      //On redirige vers gameAction()
                                }// redirection('?c=game&a=game');                                                  //On redirige vers gameAction()
                            }
                        }
                    } else {                                                                                    //Sinon si on attaque une carte
                        $target = $deckManager->getTmpCardByID((int) $this->getGet('target'));                  //On recupere la carte

                        foreach ($deckManager->getTmpCards($target->getUserIdFk()) as $card) {                  //pour chaque carte du deck ennemi
                            if ($card->getStatus() == 4 && $card->getTypeIdFk() == 2 && $target->getTypeIdFk() != 2) {                         //si une carte bouclier est posé
                                $data['error'] = 'Vous devez d\'abord attaquer le défenseur';
                                if (array_key_exists('ajax', $this->getGet())) {
                                    echo json_encode($data);
                                    return;
                                } else {
                                    redirection('?c=game&a=game');                                      //On redirige vers gameAction()
                                }
                                // redirection('?c=game&a=game');                                                  //On redirige vers gameAction()
                            }
                        }
                    }

                    if (!in_array($cardPlayer->getId(), $this->getSession('played'))) {
                        if ($cardPlayer->isValidTarget($target)) {                              //si la cible est valide (Hero ou target instance)

                            $dead = $cardPlayer->giveDamage($target);                           //on attaque la cible et retourne une valeur mort ou pas

                            $cardPlayed = $this->getSession('played');
                            $cardPlayed[] = $cardPlayer->getId();
                            $this->setSession('played', $cardPlayed);

                            if (get_class($target) == 'grinoire\src\model\entities\Hero') {     //Si la cible est un hero
                                $deckManager->UpdateTmpHero($target);                           //On met a jour le hero en BDD
                                if ($dead === 2) {                                              //Si la carte est morte
                                    //LE ROI EST MORT
                                    $data['win'] = 'Victoire, l\'encre prend la fuite suite a la mort du roi ennemi';
                                    $data['hero'] = $target->getId();
                                    $data['heroLife'] = 0;
                                } else {
                                    $data['hero'] = $target->getId();
                                    $data['heroLife'] = $target->getLife() - $target->getDamageReceived();
                                }
                            } else {                                                            //Sinon si la cible est une carte
                                if ($dead === 2) {                                              //Si la carte est morte
                                    $target->setStatus(2);                                      //On la place dans la defausse (statut=2)
                                    $data['target'] = $target->getId();
                                    $data['targetLife'] = 0;
                                } else {
                                    $data['target'] = $target->getId();
                                    $data['targetLife'] = $target->getLife() - $target->getDamageReceived();
                                }
                                $deckManager->UpdateTmpCard($target);                           //On met a jour la carte en BDD

                                $dead = $target->giveDamage($cardPlayer);                       //la carte attaquer contre attaque
                                if ($dead === 2) {                                              //Si la carte est morte
                                    $cardPlayer->setStatus(2);                                  //On la place dans la defausse (statut=2)
                                    $data['cardPlayer'] = $cardPlayer->getId();
                                    $data['cardPlayerLife'] = 0;
                                } else {
                                    $data['cardPlayer'] = $cardPlayer->getId();
                                    $data['cardPlayerLife'] = $cardPlayer->getLife() - $cardPlayer->getDamageReceived();
                                }
                                $deckManager->UpdateTmpCard($cardPlayer);                       //On met a jour la carte en BDD
                            }
                        } else {
                            $data['error'] = 'Vous ne pouvez pas attaquer cette carte...';
                        }
                    } else {
                        $data['error'] = 'Cette carte a déja était jouer ce tour';
                    }
                } else {
                    $data['error'] = 'Vous ne pouvez pas attaquer avec une carte posé ce tour';
                }

                if (array_key_exists('ajax', $this->getGet())) {
                    echo json_encode($data);
                } else {
                    redirection('?c=game&a=game');                                      //On redirige vers gameAction()
                }
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
        $firstHero = $deckManager->getTmpHero($gameSession->getPlayer1Id());
        $secondHero = $deckManager->getTmpHero($gameSession->getPlayer2Id());

        if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {    //Si le joueur n'est pas seul dans la partie
            $oGame = $gameManager->getGame($gameSession->getId());                                      //On recupere la partie en cours en BDD

            if (((int) $oGame->getTurn() % 2) == 0 && ((int) $oGame->getPlayer2Id() == $userId)         //Si on joue un tour pair et qu'on est le joueur 2 on peut jouer
                || ((int) $oGame->getTurn() % 2) != 0 && ((int) $oGame->getPlayer1Id() == $userId)) {   //Si on joue un tour impair, et qu'on est le premier joueur on peut jouer
                $deckManager->UpdateStatusOnBoard($userId);                                             //On met a jour les carte sur le plateau depuis moins d'un tour
                $gameManager->nextTurn((int) $gameSession->getId(), (int) $gameSession->getTurn());     //on defini en BDD la valeur du tour actuel
                $gameManager->incrementMana($gameSession->getId());                                     //On incremente le mana en  BDD

                $firstHero->setMana($gameSession->getMana());                                          //on defini le mana pour chaque hero
                $deckManager->UpdateTmpHero($firstHero);                                               //On met le hero a jour en BDD
                $secondHero->setMana($gameSession->getMana());                                          //on defini le mana pour chaque hero
                $deckManager->UpdateTmpHero($secondHero);                                               //On met le hero a jour en BDD
                $this->setSession('game', $gameManager->getGame((int) $gameSession->getId()));          //On met a jour la valeur du tour en session
                $this->setSession('drawed', 0);                                                         //On reinitialise le nombre de carte pioché
                $this->setSession('played', []);
                $data['turn'] = true;
                $data['mana'] = $firstHero->getMana() . ' / ' . $oGame->getMana();
                $data['userId'] = $userId;
                $data['playId'] = $userId;
                // redirection('?c=game&a=game');
            } else {
                $data['error'] = 'Ce n\'est pas encore votre tour';
            }
        } else {
            $data['error'] = 'Veuillez attendre un second joueur avant de jouer';
        }
        if (array_key_exists('ajax', $this->getGet())) {
            echo json_encode($data);
        } else {
            redirection('?c=game&a=game');                                      //On redirige vers gameAction()
        }
    }


    /**
     *   Retourne les données php a ajax afin de determiner si l'ennemi a lance une action
     *
     *   @return void
     */
    public function renderOpponentAction()
    {
        $this->init(__FILE__, __FUNCTION__);

        $gameManager = new GameManager();
        $userManager = new UserManager();
        $deckManager= new DeckManager();
        $userId = (int) $this->getSession('userConnected');
        $gameSession = $this->getSession('game');

        if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {    //Si le joueur n'est pas seul dans la partie
            if ($gameSession->getPlayer1Id() == $userId) {
                $opponentId = $gameSession->getPlayer2Id();
            } else {
                $opponentId = $gameSession->getPlayer1Id();
            }

            $data['render'] = true;
            foreach ($deckManager->getTmpCards($opponentId) as $key => $card) {
                //(0 = pioche, 1=main, 2= defausse, 3 = pose depuis moin d'un tour , 4 = pose et peut jouer)
                // if ($card->getStatus() != 0 && $card->getStatus() != 4) {
                    $data['cards'][]  = [
                        'id'    => $card->getId(),
                        'life'  => $card->getLife() - $card->getDamageReceived(),
                        'mana'  => $card->getMana(),
                        'attack'=> $card->getAttack(),
                        'type'  => $card->getTypeIdFk(),
                        'status'=> $card->getStatus(),
                        'bg'    => $card->getBg()
                    ];
                // }
            }
            $data['opponentHero'] = [
                'id'    => $deckManager->getTmpHero($opponentId)->getId(),
                'life'  => $deckManager->getTmpHero($opponentId)->getLife() - $deckManager->getTmpHero($opponentId)->getDamageReceived(),
                'mana'  => $deckManager->getTmpHero($opponentId)->getMana()
            ];
            $data['opponent'] = [
                'id'    => $userManager->getUserById($opponentId)->getId(),
                'gameId'  => $userManager->getUserById($opponentId)->getGameIdFk()
            ];

            $data['user'] = [
                'id'    => $userManager->getUserById($userId)->getId(),
                'mana'  => $deckManager->getTmpHero($userId)->getMana()
            ];

            $oGame = $gameManager->getGame($gameSession->getId());
            if (((int) $oGame->getTurn() % 2) == 0 && ((int) $oGame->getPlayer2Id() == $userId)         //Si on joue un tour pair et qu'on est le joueur 2 on peut jouer
                || ((int) $oGame->getTurn() % 2) != 0 && ((int) $oGame->getPlayer1Id() == $userId)) {
                $playId = $userId;
            } else {
                $playId = null;
            }

            $data['game'] = [
                'id'     => $gameManager->getGame($gameSession->getId())->getId(),
                'turn'   => $gameManager->getGame($gameSession->getId())->getTurn(),
                'playId' => $playId,
                'userId' => $userId
            ];

            echo json_encode($data);
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
        $gameSession = $this->getSession('game');

        if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {        //Si le joueur n'est pas seul dans la partie
            $oGame = $gameManager->getGame($gameSession->getId());                                      //On recupere la partie en cours en BDD

            if (((int) $oGame->getTurn() % 2) == 0 && ((int) $oGame->getPlayer2Id() == $userId)         //Si on joue un tour pair et qu'on est le joueur 2 on peut jouer
                || ((int) $oGame->getTurn() % 2) != 0 && ((int) $oGame->getPlayer1Id() == $userId)) {   //Si on joue un tour impair, et qu'on est le premier joueur on peut jouer

                    if (array_key_exists('id', $this->getGet())) {                                      //Si l'id de la carte a pioché est defini
                        if (array_key_exists('drawed', $this->getSession())) {                          //Si la session existe
                            if ($this->getSession('drawed') == 0) {                                     //Si on a pas pioche de carte ce tour
                                $card = $deckManager->getTmpCardByID((int) $this->getGet('id'));        //On recupere la carte
                                $card->setStatus(1);                                                    //On defini le status a 1 (1 = carte en main)
                                $deckManager->UpdateTmpCard($card);                                     //On met a jour la carte en BDD
                                $this->setSession('drawed', 1);                                         //on sauvegarde en SESSION le nbr de carte pioche ce tour
                            } else {
                                // TODO: UNE CARTE PAR TOUR PEUT ETRE PIOCHE
                            }
                        }
                    }
            }
        }
        redirection('?c=game&a=game');                                                              //On redirige vers gameAction()
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

        if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {        //Si le joueur n'est pas seul dans la partie
            $oGame = $gameManager->getGame($gameSession->getId());                                      //On recupere la partie en cours en BDD

            if (((int) $oGame->getTurn() % 2) == 0 && ((int) $oGame->getPlayer2Id() == $userId)         //Si on joue un tour pair et qu'on est le joueur 2 on peut jouer
                || ((int) $oGame->getTurn() % 2) != 0 && ((int) $oGame->getPlayer1Id() == $userId)) {   //Si on joue un tour impair, et qu'on est le premier joueur on peut jouer

                    if (array_key_exists('id', $this->getGet())) {                                          //Si l'id de la carte a déplacé est defini

                        $cardPlayer = $deckManager->getTmpCardByID((int) $this->getGet('id'));              //On recupere la carte
                        $cardList = $deckManager->getTmpDeck($userId)->getCardList();                       //On recupere toutes les cartes du deck
                        $cardOnBoard = 0;                                                                   //On initialise un compteur pour connaitre le nbr de carte sur le plateau
                        foreach ($cardList as $card) {                                                      //Pour chacune des cartes
                            if ($card->getStatus() === 3 || $card->getStatus() === 4) {                     //si leur status est egale a 3 ou 4 (posé sur le plateau)
                                $cardOnBoard++;                                                             //on incremente le compteur de 1 pour connaitre le nbr de carte deja posées
                            }
                        }

                        $heroPlayer = $deckManager->getTmpHero($userId);                                                //On recupere le hero du joueur
                        if ($cardOnBoard < 7) {                                                                         //Si la limite de carte n'est pas atteinte et qu'on a suffisament de mana
                            if ($heroPlayer->getMana() >= $cardPlayer->getMana()) {    //Si on a asser de mana
                                $cardPlayer->setStatus(3);                                                              //On deplace la carte demandé sur le plateau
                                $heroPlayer->setMana($heroPlayer->getMana() - $cardPlayer->getMana());                  //on retire le coup en mana de la carte au mana restant
                                $deckManager->UpdateTmpCard($cardPlayer);                                               //On met a jour la carte en BDD
                                $deckManager->UpdateTmpHero($heroPlayer);                                               //On met a jour le hero en BDD

                                $data['move'] = $cardPlayer->getId();
                                $data['bg'] = $cardPlayer->getBg();
                                $data['heroMana'] = $heroPlayer->getMana() . ' / ' . $oGame->getMana();

                            } else {
                                $data['error'] = 'Vous n\'avez pas asser de mana';
                                // TODO: MANA INSUFFISANT
                            }
                        } else {
                            $data['error'] = 'Vous ne pouver pas ajouter plus de cartes';
                            // TODO: TROP DE CARTE SUR LE PLATEAU
                        }
                    }
            } else {
                $data['error'] = 'Ce n\'est pas encore votre tour, soyer patient';
            }
        }
        if (array_key_exists('ajax', $this->getGet())) {
            echo json_encode($data);
        } else {
            redirection('?c=game&a=game');                                      //On redirige vers gameAction()
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



// public function gameAction()
// {
//     $this->init(__FILE__, __FUNCTION__);
//     try {
//         $deckManager = new DeckManager();
//         $gameManager = new GameManager();
//         $userManager = new UserManager();
//         $userId = (int) $this->getSession('userConnected');
//         if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {    //On controle si le joueur n'est pas seul dans la partie
//             $gameSession = $this->getSession('game');
//
//             if ((int) $gameSession->getTurn() === 0) {                                              //Si c'est le premier tour de jeu, on initialise le plateau
//                 $cardList = $deckManager->getTmpDeck($userId)->getCardList();                       //On recupere les cartes du joueur
//                 $deckManager->initCardStatus($cardList);                                            //On initialise le status des carte(pioche ou en main)
//                 $gameManager->nextTurn((int) $gameSession->getId(), (int) $gameSession->getTurn()); //On incremente le tour de 1 en BDD
//                 $this->setSession('game', $gameManager->getGame((int) $gameSession->getId()));      //On met a jour la session de jeu
//                 $this->setSession('drawed', 0);                                                     //On initialise le nombre de carte pioché par tour
//                 $this->setSession('played', []);
//             }
//
//             $this->setSession('game', $gameManager->getGame((int) $gameSession->getId()));
//             $data['game'] = $gameManager->getGame((int) $gameSession->getId());
//
//             //on recupere le deck des joueur et affiche le plateau de jeu
//             $data['user'] = $deckManager->getTmpDeck($userId);
//             if ((int) $this->getSession('game')->getPlayer1Id() == $userId) {                       //Si on est le joueur 1 dans la partie
//                 $data['opponent'] = $deckManager->getTmpDeck(                                       //On recupere le deck du joueur 2 en ennemi
//                     (int) $gameManager->getGame($gameSession->getId())->getPlayer2Id()
//                 );
//             } else {                                                                                //Sinon si on est pas le joueur 1
//                 $data['opponent'] = $deckManager->getTmpDeck(                                       //On recupere le deck du joueur 2 en ennemi
//                     (int) $gameManager->getGame($gameSession->getId())->getPlayer1Id()
//                 );
//             }
//
//             $this->render(true, 'game', $data);
//
//         } else { //Si un seul joueur est dans la partie, on l'affiche seul sur le plateau de jeu :
//             $cardList = $deckManager->getTmpDeck($userId)->getCardList();   //On recupere son deck
//             $deckManager->initCardStatus($cardList);                        //On initialise le status des cartes
//             $data['user'] = $deckManager->getTmpDeck($userId);              //Recupere le deck du joueur a jour
//             $this->render(true, 'game', $data);                             //Affiche la vue par default
//         }
//     } catch (UserException $e) {
//         $this->setSession('error', $e->getMessage());
//         $this->render(true); //show view deck selection with error message
//     } catch (\Exception $e) {
//         getErrorMessageDie($e);
//     }
// }




/**
 *   Deplace une carte sur le plateau si il la limite max n'est pas atteinte
 *   @return   void
 */
// public function moveCardAction()
// {
//     $this->init(__FILE__, __FUNCTION__);
//     $gameManager = new GameManager();
//     $deckManager = new DeckManager();
//     $userManager = new UserManager();
//     $userId = (int) $this->getSession('userConnected');
//     $gameSession = $this->getSession('game');
//
//     if ($gameManager->isGameFull((int) $userManager->getUserById($userId)->getGameIdFk())) {        //Si le joueur n'est pas seul dans la partie
//         $oGame = $gameManager->getGame($gameSession->getId());                                      //On recupere la partie en cours en BDD
//
//         if (((int) $oGame->getTurn() % 2) == 0 && ((int) $oGame->getPlayer2Id() == $userId)         //Si on joue un tour pair et qu'on est le joueur 2 on peut jouer
//             || ((int) $oGame->getTurn() % 2) != 0 && ((int) $oGame->getPlayer1Id() == $userId)) {   //Si on joue un tour impair, et qu'on est le premier joueur on peut jouer
//
//                 if (array_key_exists('id', $this->getGet())) {                                          //Si l'id de la carte a déplacé est defini
//
//                     $cardPlayer = $deckManager->getTmpCardByID((int) $this->getGet('id'));              //On recupere la carte
//                     $cardList = $deckManager->getTmpDeck($userId)->getCardList();                       //On recupere toutes les cartes du deck
//                     $cardOnBoard = 0;                                                                   //On initialise un compteur pour connaitre le nbr de carte sur le plateau
//                     foreach ($cardList as $card) {                                                      //Pour chacune des cartes
//                         if ($card->getStatus() === 3 || $card->getStatus() === 4) {                     //si leur status est egale a 3 ou 4 (posé sur le plateau)
//                             $cardOnBoard++;                                                             //on incremente le compteur de 1 pour connaitre le nbr de carte deja posées
//                         }
//                     }
//
//                     $heroPlayer = $deckManager->getTmpHero($userId);                                                //On recupere le hero du joueur
//                     if ($cardOnBoard < 7) {                                                                         //Si la limite de carte n'est pas atteinte et qu'on a suffisament de mana
//                         if ($gameManager->getGame($gameSession->getId())->getMana() >= $cardPlayer->getMana()) {    //Si on a asser de mana
//                             $cardPlayer->setStatus(3);                                                              //On deplace la carte demandé sur le plateau
//                             $heroPlayer->setMana($heroPlayer->getMana() - $cardPlayer->getMana());                  //on retire le coup en mana de la carte au mana restant
//                             $deckManager->UpdateTmpCard($cardPlayer);                                               //On met a jour la carte en BDD
//                             $deckManager->UpdateTmpHero($heroPlayer);                                               //On met a jour le hero en BDD
//                         } else {
//                             // TODO: MANA INSUFFISANT
//                         }
//                     } else {
//                         // TODO: TROP DE CARTE SUR LE PLATEAU
//                     }
//                 }
//         }
//         redirection('?c=game&a=game');                                      //On redirige vers gameAction()
//     }
// }
