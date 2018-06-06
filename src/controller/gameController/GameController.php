<?php
declare(strict_types=1);

namespace grinoire\src\controller\GameController;
use grinoire\src\controller\CoreController;
use grinoire\src\exception\UserException;
use grinoire\src\model\DeckManager;
use grinoire\src\model\UserManager;
use grinoire\src\model\GameManager;


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
     *  Display deck selection view, get selected deck(card & hero) in SQL
     *  Redirect to Matchmaking view (loading)
     */
    public function selectDeckAction() : void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {

            if (array_key_exists('selectedDeck', $this->getPost()) && ($this->getPost("selectedDeck") == 1 || $this->getPost("selectedDeck") == 2)) {

                //get and build selected deck
                $deckManager = new DeckManager();
                $deck = $deckManager->getDeckById( (int) $this->getPost("selectedDeck"))->getDeck();
                if (is_object($deck)) {
                    $this->setSession("deck", $deck);
                    $userManager = new UserManager();
                    $userManager->setReady((int)$this->getSession('userConnected'), 1);
                } else {
                    throw new UserException("Une erreur s'est produite lors de la séléction, merci de rééssayer.<br>Si le problème persiste, merci de contacter un administrateur");
                }

            } else {
                $this->render(true);
                // throw new \Exception('Merci de séléctionner un deck !');
            }

        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true); //show view deck selection
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }


    /**
     * Find a opponent, auto-retry if opponent not founded
     */
    public function matchMakingAction() :void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {

            $userManager = new UserManager();

            do { //get a opponent except own ID
                $opponant = $userManager->getOpponent((int)$this->getSession('userConnected')); // TODO: build function getOpponent()
            } while (is_null($opponant));

            if ($opponant) { //set new game in session & display view default
                $gameManager = new GameManager();
                $gameId = $gameManager->newGame( (int)$this->getSession('userConnected'), $opponant->getId()); // TODO: build function newGame()
                $this->setSession('game', $gameManager->getGame($gameId));

                $this->render(true);
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

}
