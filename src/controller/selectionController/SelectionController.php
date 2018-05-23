<?php
declare(strict_types=1);

namespace grinoire\src\controller\SelectionController;

use grinoire\src\controller\CoreController;
use grinoire\src\model\DeckManager;


/**
 * Handle view for select deck
 * @package grinoire\src\controller
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
     *  Seclect a deck
     */
    public function selectDeckAction() : void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {

            if (array_key_exists('selectedDeck', $this->getPost()) && ($this->getPost("selectedDeck") == 1 || $this->getPost("selectedDeck") == 2)) {
                //generation du deck selectionné
                $deckManager = new DeckManager();
                $deck = $deckManager->getDeckById( (int) $this->getPost("selectedDeck"))->getDeck();
                $this->setSession("deck", $deck);

                //on envoie sur la page de recherche d'utilisateur : MATCHMAKING
                $this->render(true, 'matchMaking');
                // redirection('matchMaking.php');

            } else {
                $this->render(true);
                // throw new \Exception('Merci de séléctionner un deck !');
            }

        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }
}
