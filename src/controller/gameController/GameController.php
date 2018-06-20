<?php
declare(strict_types=1);

namespace grinoire\src\controller\GameController;
use grinoire\src\controller\CoreController;
use grinoire\src\exception\UserException;
use grinoire\src\model\DeckManager;
use grinoire\src\model\UserManager;
use grinoire\src\model\GameManager;

// TODO: remove status ready when game is launched
// TODO:



/**
 * Handle view for select deck
 */
class GameController extends CoreController
{

    /**
     * SelectionController Construct
     * @param  array  $get   Global $_GET
     * @param  array  $post  Global $_POST
     */
    public function __construct(array $get, array $post)
    {
        parent::__construct($get, $post);
    }


    /**
     *  Display deck selection view by default
     *
     *  Get selected deck(card & hero) if $_POST['value'] have been send
     *
     *  Build a copy of original card & deck in temporary table
     *
     *  Redirect to Matchmaking view
     *
     *  Show userException in selectionDeck view
     */
    public function selectDeckAction() : void
    {
        $this->init(__FILE__, __FUNCTION__);

        try { //if player select a deck
            if (array_key_exists('selectedDeck', $this->getPost()) && ($this->getPost("selectedDeck") == 1 || $this->getPost("selectedDeck") == 2)) {

                $deckManager = new DeckManager();

                // if player have selected all 20 card
                if (array_key_exists('selectedCard', $this->getPost()) && count($this->getPost('selectedCard')) === 20) {

                    //on recupere chaque carte selectionne en objet
                    $selectedCardList = [];
                    foreach ($this->getPost('selectedCard') as $key => $cardId) {
                        $selectedCardList[] = $deckManager->getCardById((int)$cardId);
                    }

                    //build and get the selected deck whith original model
                    $deck = $deckManager->getDeckById((int)$this->getPost("selectedDeck"), $selectedCardList)->getDeck();

                    if (is_object($deck)) {
                        $userManager = new UserManager();
                        $userManager->setReady((int)$this->getSession('userConnected'), 1);
                        $userManager->setSelectedDeck((int)$this->getSession('userConnected'), $deck->getId());
                        $deckManager->setTmpDeck($this->getSession('userConnected')); //ici on remplace les modele original par des copie généré dans tmp card et tmp hero
                        $this->setSession("deck", $deckManager->getDeck()); //define session for deck
                    } else {
                        throw new UserException("Une erreur s'est produite lors de la séléction, merci de rééssayer.<br>Si le problème persiste, merci de contacter un administrateur");
                    }

                    //redirect to viex loading, time to find opponent
                    redirection('?c=game&a=matchMaking');

                } else {
                    //get all card and display them, player can choose 20 of them
                    $data['cardList'] = $deckManager->getAllCardByDeck((int) $this->getPost("selectedDeck"));
                    $this->render(true, 'selectDeck', $data);
                }


            } else { //default view
                $this->render(true);
            }

        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true); //show view deck selection
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

            do { //get a opponent except own ID
                $opponant = $userManager->getOpponent((int)$this->getSession('userConnected'));
            } while (is_null($opponant));

            if ($opponant) { //set new game in session & display view default
                $gameManager = new GameManager();
                $gameId = $gameManager->newGame( (int)$this->getSession('userConnected'), $opponant->getId());
                $this->setSession('game', $gameManager->getGame($gameId));

                redirection('?c=game&a=game');
            } else {
                $this->render(true);
            }

        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true); //show view deck selection
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
            //define how much card give in main for each player at start
            $nbCardInMain = 3; //// TODO: constant for this ?!?

            if (isset($player)) {
                // code...
            } else { //default view

                //define card status for setting position on board
                //3 on main, other in draw
                for ( $i=0 ; $i < count($this->getSession('deck')->getCardList()) ; $i++ ) {
                    if ($i < $nbCardInMain) {
                        // $this->getSession('deck')->getCardList()[$i]->setStatus(1);
                    } else {
                        $this->getSession('deck')->getCardList()[$i]->setStatus(0);
                    }
                }

                //get hero
                $data['hero'] = $this->getSession('deck')->getHero();
                //get all the card in selected deck shuffled with status defined for init game
                $data['cardList'] = $this->getSession('deck')->getCardList();

                $this->render(true, 'game', $data);
            }



        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true); //show view deck selection
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }

    }

}
