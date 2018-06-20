<?php
declare(strict_types= 1);

namespace grinoire\src\model;

use grinoire\src\model\entities\Card;
use grinoire\src\model\entities\Deck;
use grinoire\src\model\entities\Hero;


/**
 *  DeckManager gere les requetes necessaire a la construction d'un deck
 *  Il est compose d'une entite {Deck}
 *  Elle même compose d'une entite {Hero} et de 19 entite {Card}
 */
class DeckManager
{

    /**
     *  Instance representant le deck (AGREGATION)
     *  @var  array
     */
    protected $deck = array();

    /**
     *  Instance PdoManager
     *  @var  PdoManager
     */
    private $pdo;



    // ----------------------------- //
    // ------ METHOD MAGIQUE ------- //
    // ----------------------------- //
    /**
     *  DeckManager Construct
     */
    public function __construct()
    {
        $this->pdo = PdoManager::getInstance();
    }



    // ------------------------- //
    // ------ METHOD SQL ------- //
    // ------------------------- //

    /**
     *  Recupere et instancie un deck ( cartes + hero )
     *  @param    int     $deck_id  Id du deck selectionné
     *  @param    Card[]  $cardList objet carte genere selon le choix de carte de l'utilisateur
     *  @return   self
     */
    public function getDeckById( int $deck_id, array $cardList ) :self
    {
        $statement =
        'SELECT d.`deck_id`, d.`deck_name`, d.`deck_color`
        FROM `deck` AS d
        WHERE d.`deck_id` = :deck_id';
        $param = [ ':deck_id' => $deck_id ];

        $data = $this->pdo->makeSelect( $statement, $param, false ); //recupere les données du deck
        $hero = $this->getHeroForDeck((int)$deck_id); //recupere le hero lié au deck
        shuffle($cardList); // on melange les carte

        $this->setDeck( new Deck( $data, $cardList, $hero));

        return $this;
    }

    /**
     *  recupere les cartes associé au deck, et les retourne en objet
     *
     *  @param    int      $deck_id
     *  @return   Card[]   Carte instancié
     */
    public function getAllCardByDeck( int $deck_id ) :array
    {
        $statement = 'SELECT * FROM `card` AS c WHERE c.`card_deck_id_fk` = :deck_id';
        $param = [ ':deck_id' => $deck_id ];
        $data = $this->pdo->makeSelect( $statement, $param);

        $cardList = [];
        foreach ($data as $id => $card ) {
            $cardList[] = new Card($card);
        }

        return $cardList;
    }

    /**
     * Recupere une carte selon son ID et la retourne sous forme d'objet
     * @param   int   $cardId   Id carte
     * @return  Card
     */
    public function getCardById(int $cardId) :Card
    {
        $statement = 'SELECT * FROM card WHERE `card_id` = :cardId';
        $param = [':cardId' => [$cardId, \PDO::PARAM_INT]];

        $data = $this->getPdo()->makeSelect($statement, $param, false);

        return new Card($data);
    }



    /**
     *  Recupère le heros associé au deck séléctionné
     *  @param  int    $deck_id
     *  @return Hero   Hero instance
     */
    public function getHeroForDeck( int $deck_id ) :Hero
    {
        $statement =
        'SELECT d.`hero_name`, d.`hero_bg`, d.`hero_mana`, d.`hero_life`, d.`hero_damage_received`
        FROM `deck` AS d
        WHERE d.`deck_id` = :deck_id';
        $param = [ ':deck_id' => $deck_id ];

        $data = $this->getPdo()->makeSelect( $statement, $param, false );

        return new Hero($data);
    }



    // --------------------- //
    // ------ SETTERS ------ //
    // --------------------- //

    /**
     *  Insere le tableau d'objet Card dans l'attribut deck
     *  @param    Deck  $cardObj Instance representant le deck
     *  @return   self
     */
    public function setDeck( Deck $cardObj ) :self
    {
        $this->deck = $cardObj;
        return $this;
    }


    // --------------------- //
    // ------ GETTERS ------ //
    // --------------------- //

    /**
     *  retourne l'attribut deck
     *  @return  Deck
     */
    public function getDeck() :Deck
    {
        return $this->deck;
    }


    /**
     * Get instance of PdoManager
     * @return PdoManager
     */
    public function getPdo(): PdoManager
    {
        return $this->pdo;
    }

}
