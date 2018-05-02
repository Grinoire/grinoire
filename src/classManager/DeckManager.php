<?php

declare(strict_types= 1);


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
    public function __construct()
    {
        $this->pdo = PdoManager::getInstance();
    }



    // ------------------------- //
    // ------ METHOD SQL ------- //
    // ------------------------- //

    /**
     *  Recupere et instancie un deck ( cartes + hero ) [COMPOSITION]

     *  @param    int   $deck_id  [description]
     *  @return   self            [description]
     */
    public function getDeckById( int $deck_id ) :self
    {
        $statement =
        'SELECT d.`deck_id`, d.`deck_name`, d.`deck_color`
        FROM `deck` AS d
        WHERE d.`deck_id` = :deck_id';
        $param = [ ':deck_id' => $deck_id ];

        $data = $this->pdo->makeSelect( $statement, $param );
        $cardList = $this->getAllCardByDeck($deck_id);
        $hero = $this->getHeroForDeck($deck_id);

        $this->setDeck( new Deck( $data[0], $cardList, $hero[0]) );
        return $this;
    }

    /**
     *  recupere les cartes associé au deck
     *
     *  @param    int    $deck_id
     *  @return   array  données des cartes a instancié
     */
    public function getAllCardByDeck( int $deck_id ) :array
    {
        $statement =
        'SELECT * FROM `card` AS c
        WHERE c.`card_deck_id_fk` = :deck_id';
        $param = [ ':deck_id' => $deck_id ];

        $data = $this->pdo->makeSelect( $statement, $param );

        return $data;
    }



    /**
     *  Recupère le heros associé au deck séléctionné
     *
     *  @param  int    $deck_id
     *  @return array  données du Hero a instancié
     */
    public function getHeroForDeck ( int $deck_id ) :array
    {
        $statement =
        'SELECT d.`hero_name`, d.`hero_bg`, d.`hero_mana`, d.`hero_life`, d.`hero_damage_received`
        FROM `deck` AS d
        WHERE d.`deck_id` = :deck_id';
        $param = [ ':deck_id' => $deck_id ];

        $data = $this->pdo->makeSelect( $statement, $param );

        return $data;
    }



    // --------------------- //
    // ------ SETTERS ------ //
    // --------------------- //

    /**
     *  Insere le tableau d'objet Card dans l'attribut deck
     *  @param    Deck  Instances de {card} représentant les cartes du deck
     *  @return   self
     */
    public function setDeck( Deck $cardObj ) :self
    {
        $this->deck = $cardObj;
        return $this;
    }

    /**
    * Instance du hero lié au deck
    * @param  Hero instance de la classe {Hero}
    * @return self ->FLUENT->
    */
    public function setHero( Hero $hero ) :self {
        $this->hero = $hero;
        return $this;
    }


    // --------------------- //
    // ------ GETTERS ------ //
    // --------------------- //

    /**
     *  retourne l'attribut deck
     *  @return  Deck
     */
    public function getDeck() :Deck {
        return $this->deck;
    }

    /**
    * Get value of Instance du hero lié au deck
    * @return Hero
    */
    public function getHero() :Hero {
        return $this->hero;
    }

}
