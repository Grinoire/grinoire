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

                    $userManager = new UserManager();
                    $userManager->setReady((int)$this->getSession('userConnected'), 1); //set user ready in DB
                    $userManager->setSelectedDeck((int)$this->getSession('userConnected'), (int) $this->getPost('selectedDeck')); //set user deck ID selected
                    $deckManager->setTmpDeck($this->getSession('userConnected'), $selectedCardList, (int) $this->getPost('selectedDeck')); //insert in tmp_table selectedDeckwhith card and hero
                    $this->setSession("deck", $deckManager->getDeck()); //define session for deck

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

            $deckManager = new DeckManager();
            $gameManager = new GameManager();

            if (array_key_exists('nextTurn', $this->getGet())) {
                $gameManager->nextTurn((int) $this->getSession('game')->getId(), (int) $this->getSession('game')->getTurn());
                $this->setSession('game', $gameManager->getGame((int) $this->getSession('game')->getId()));
                $this->setSession('nextTurn');
                redirection('?c=game&a=game');

            } elseif ((int) $this->getSession('game')->getTurn() === 0) {


                $cardList = $deckManager->getTmpDeck((int) $this->getSession('userConnected'))->getCardList(); //get temporary deck
                $deckManager->initCardStatus($cardList);//init card status, 0 = draw, 1 = hand

                $data['user'] = $deckManager->getTmpDeck((int) $this->getSession('userConnected')); //get updated deck
                $data['opponent'] = $deckManager->getTmpDeck((int) $this->getSession('game')->getPlayer2Id());// TODO: maybe we need to control if is not her own id ...

                $this->render(true, 'game', $data);



            } else { //normal turn

                $data['user'] = $deckManager->getTmpDeck((int) $this->getSession('userConnected'));
                $data['opponent'] = $deckManager->getTmpDeck((int) $this->getSession('game')->getPlayer2Id());// TODO: maybe we need to control if is not her own id ...

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
