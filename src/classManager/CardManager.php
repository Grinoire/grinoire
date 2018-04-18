<?php

declare(strict_types= 1);


/**
*************************************************************************************
*  Generation du deck et des cartes                                                 *
*  ================================================================================ *
*  @uses   class->Card  (AGREGATION)                                                *
*  @uses                                                                            *
*  ================================================================================ *
*  @method -> getAllCardByDeck() -> recupere les cartes selon l'id du deck en param *
*  @method                                                                          *
*  @method                                                                          *
*  @method                                                                          *
************************************************************************************/

class CardManager
{

    /**
     *  contient les OBJ Card générés (AGREGATION)
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

    public function __destruct() {}
    public function __toString() {}



    // ------------------------- //
    // ------ METHOD SQL ------- //
    // ------------------------- //

    /** recupere les cartes selon le deck
     *  @param    int    $deck_id
     *  @return   array  tableau d'objet Card
     */
    public function getAllCardByDeck( int $deck_id ) :array
    {
        $statement =
        'SELECT * FROM `card` AS c
        WHERE c.`card_deck_id_fk` = :deck_id';
        $param = [ 'deck_id' => $deck_id ];

        $data = $this->pdo->makeSelect( $statement, $param );

        //on genere un OBJ card par carte
        foreach ( $data as $key => $value )
        {
            $listCard[] = new Card($value);
        }

        return $listCard;
    }



    // --------------------- //
    // ------ SETTERS ------ //
    // --------------------- //

    /**
     *  Insere le tableau d'objet Card dans l'attribut deck
     *  @param    array        $cardObj
     *  @return   self
     */
    public function setDeck( array $cardObj ) :self
    {
        $this->deck = $cardObj;
        return $this;
    }



    // --------------------- //
    // ------ GETTERS ------ //
    // --------------------- //

    /**
     *  retourne l'attribut deck
     *  @require  :
     *  @return   array  [description]
     */
    public function getDeck() :array {
        return $this->deck;
    }

}
