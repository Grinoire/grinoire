<?php
declare(strict_types= 1);

namespace grinoire\src\model;

use PDO;
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
     *  Recupere et instancie un deck avec les valeur original ( cartes + hero ) selon son ID
     *  @param    int     $deckId  Id du deck selectionné
     *  @param    Card[]  $cardList objet carte genere selon le choix de carte de l'utilisateur
     *  @return   self
     */
    public function getDeckById( int $deckId, array $cardList ) :self
    {
        $data = $this->getDataForDeck($deckId);
        $hero = $this->getHeroForDeck((int)$deckId); //recupere le hero lié au deck
        shuffle($cardList); // on melange les carte

        $this->setDeck( new Deck( $data, $cardList, $hero));

        return $this;
    }

    /**
     *  recupere les cartes original associé au deck, et les retourne en objet
     *
     *  @param    int      $deckId
     *  @return   Card[]   Carte instancié
     */
    public function getAllCardByDeck( int $deckId ) :array
    {
        $statement = 'SELECT * FROM `card` AS c WHERE c.`card_deck_id_fk` = :deckId';
        $param = [ ':deckId' => $deckId ];
        $data = $this->pdo->makeSelect( $statement, $param);

        $cardList = [];
        foreach ($data as $id => $card ) {
            $cardList[] = new Card($card);
        }

        return $cardList;
    }




    /**
     *  Recupère le heros original associé au deck séléctionné
     *  @param  int    $deckId
     *  @return Hero   Hero instance
     */
    public function getHeroForDeck( int $deckId ) :Hero
    {
        $statement =
        'SELECT d.`hero_name`, d.`hero_bg`, d.`hero_mana`, d.`hero_life`, d.`hero_damage_received`
        FROM `deck` AS d
        WHERE d.`deck_id` = :deckId';
        $param = [ ':deckId' => $deckId ];

        $data = $this->getPdo()->makeSelect( $statement, $param, false );

        return new Hero($data);
    }


    /**
     * Recupere et retourne les données du deck
     * @param   int    $deckId   Selected deck ID
     * @return  array
     */
    public function getDataForDeck(int $deckId) :array
    {
        $statement =
        'SELECT d.`deck_id`, d.`deck_name`, d.`deck_color`
        FROM `deck` AS d
        WHERE d.`deck_id` = :deck_id';
        $param = [ ':deck_id' => $deckId ];

        return $this->pdo->makeSelect( $statement, $param, false );
    }




    /**
    * Recupere une carte original selon son ID et la retourne sous forme d'objet
    * @param   int   $cardId   Id carte
    * @return  Card
    */
    public function getCardById(int $cardId) :Card
    {
        $statement = 'SELECT * FROM card WHERE `card_id` = :cardId';
        $param = [':cardId' => [$cardId, PDO::PARAM_INT]];

        $data = $this->getPdo()->makeSelect($statement, $param, false);

        return new Card($data);
    }


    /**
     * Copy original value of card & Hero in database
     *
     * And overwrite original value in deck property for work on them
     *
     * @param  int  $userId  User ID
     */
    public function setTmpDeck(int $userId) :void
    {
        $this->setTmpHero($this->getDeck()->getHero(), $userId);
        $this->setTmpCards($this->getDeck()->getCardList(), $userId);

        $this->setDeck(
            new Deck($this->getDataForDeck($this->getDeck()->getId()), $this->getTmpCards($userId), $this->getTmpHero($userId))
        );
    }

    /**
     * Copy hero in DB, because we need to keep original value in card tabel so we copy her
     * @param   Hero  $hero    Hero instance
     * @param   int   $userId  User ID
     * @return  int
     */
    public function setTmpHero(Hero $hero, int $userId) :int
    {
        $statement =
        'INSERT INTO tmp_hero (tmphero_mana, tmphero_name, tmphero_life, tmphero_bg, tmphero_damage_received, tmphero_user_id_fk)
        VALUES(:mana, :name, :life, :bg, :damageReceived, :userId)';

        $params = [
            ':mana'           => [$hero->getMana(),             PDO::PARAM_INT],
            ':name'           => $hero->getName(),
            ':life'           => [$hero->getLife(),             PDO::PARAM_INT],
            ':bg'             => $hero->getBg(),
            ':damageReceived' => [$hero->getDamageReceived(),   PDO::PARAM_INT],
            ':userId'         => $userId
        ];

        return $this->getPdo()->makeUpdate($statement, $params);
    }


    /**
     * Copy hero in DB, because we need to keep original value in card tabel so we copy her
     * @param   Card[]  $cards   all card instance selected by user
     * @param   int     $userId  User ID
     * @return  array
     */
    public function setTmpCards(array $cards, int $userId) :array
    {
        $response = [];

        foreach ($cards as $card) {
            $statement =
            'INSERT INTO tmp_card ( tmpcard_name, tmpcard_description, tmpcard_bg, tmpcard_life, tmpcard_attack, tmpcard_mana,
                                    tmpcard_status, tmpcard_damage_received, tmpcard_type_id_fk, tmpcard_user_id_fk)
            VALUES(:name, :description, :bg, :life, :attack, :mana, :status, :damageReceived, :type_id_fk, :user_id_fk)';

            $params = [
                ':name'           => $card->getName(),
                ':description'    => $card->getDescription(),
                ':bg'             => $card->getBg(),
                ':life'           => [$card->getLife(),           PDO::PARAM_INT],
                ':attack'         => $card->getAttack(),
                ':mana'           => [$card->getMana(),           PDO::PARAM_INT],
                ':status'         => $card->getStatus(),
                ':damageReceived' => [$card->getDamageReceived(), PDO::PARAM_INT],
                ':type_id_fk'     => [$card->getTypeIdFk(),       PDO::PARAM_INT],
                ':user_id_fk'     => $userId
            ];

            $response[] = $this->getPdo()->makeUpdate($statement, $params);
        }

        return $response;
    }


    /**
     * Get user copy of hero in DB -> tmp_hero
     * @param   int   $userId  User ID
     * @return  Hero
     */
    public function getTmpHero(int $userId) :Hero
    {
        $statement = 'SELECT * FROM tmp_hero WHERE tmphero_user_id_fk = :userId';
        $params = [':userId' => [$userId, PDO::PARAM_INT]];
        $response = $this->getPdo()->makeSelect($statement, $params, false);

        return new Hero($response);
    }

    /**
     * Get all user copy for card in deck in DB -> tmp_card
     * @param   int     $userId  User ID
     * @return  Card[]           All card instance selected by user
     */
    public function getTmpCards(int $userId) :array
    {
        $statement = 'SELECT * FROM tmp_card WHERE tmpcard_user_id_fk = :userId';
        $params = [':userId' => [$userId, PDO::PARAM_INT]];
        $response = $this->getPdo()->makeSelect($statement, $params);
        $cards = [];

        foreach ($response as $card) {
            $cards[] = new Card($card);
        }
        return $cards;
    }


    /**
     * Update a hero in tmp_hero table
     * @param   Hero  $hero  Hero instance
     * @return  int
     */
    public function UpdateTmpHero(Hero $hero) :int
    {
        $statement =
        'UPDATE tmp_hero
        SET
        tmphero_mana = :mana,
        tmphero_name = :name,
        tmphero_life = :life,
        tmphero_bg = :bg,
        tmphero_damage_received = :damageReceived
        WHERE tmphero_id = :heroId';

        $params = [
            ':mana'           => [$hero->getMana(),           PDO::PARAM_INT],
            ':name'           => $hero->getName(),
            ':life'           => [$hero->getLife(),           PDO::PARAM_INT],
            ':bg'             => $hero->getBg(),
            ':damageReceived' => [$hero->getDamageReceived(), PDO::PARAM_INT],
            ':heroId'         => [$hero->getId(),             PDO::PARAM_INT]
        ];

        return $this->getPdo()->makeUpdate($statement, $params);
    }


    /**
     * Update a card in tmp_card table
     * @param   Card  $card  Card instance
     * @return  int
     */
    public function UpdateTmpCard(Card $card) :int
    {
        $statement =
        'UPDATE tmp_card
        SET
        tmpcard_name            = :name,
        tmpcard_description     = :description,
        tmpcard_bg              = :bg,
        tmpcard_life            = :life,
        tmpcard_attack          = :attack,
        tmpcard_mana            = :mana,
        tmpcard_status          = :status,
        tmpcard_damage_received = :damageReceived,
        tmpcard_type_id_fk      = :type_id_fk,
        tmpcard_user_id_fk      = :user_id_fk
        WHERE tmpcard_id        = :cardId';

        $params = [
            ':name'           => $card->getName(),
            ':description'    => $card->getDescription(),
            ':bg'             => $card->getBg(),
            ':life'           => [$card->getLife(),             PDO::PARAM_INT],
            ':attack'         => $card->getAttack(),
            ':mana'           => [$card->getMana(),             PDO::PARAM_INT],
            ':status'         => $card->getStatus(),
            ':damageReceived' => [$card->getDamageReceived(),   PDO::PARAM_INT],
            ':type_id_fk'     => [$card->getTypeIdFk(),         PDO::PARAM_INT],
            ':cardId'         => [$card->getId(),               PDO::PARAM_INT]
        ];

        return $this->getPdo()->makeUpdate($statement, $params);
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
