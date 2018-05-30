<?php
declare(strict_types=1);

namespace grinoire\src\controller\SelectionController;
use grinoire\src\exception\UserException;

use grinoire\src\controller\CoreController;
use grinoire\src\model\DeckManager;
use grinoire\src\model\UserManager;


/**
 * Handle view for select deck
 */
class SelectionController extends CoreController
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

                //redirect to loading screen for match-making
                $this->render(true, 'matchMaking');
                // redirection('matchMaking.php');

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
}
