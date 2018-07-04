<?php

declare(strict_types= 1);

namespace grinoire\src\model\entities;

use Exception;
use grinoire\src\model\entitiesInterface\DealDamage;

/**
*   Represent a card
*/
class Card
{

    use DealDamage;

    /**
     * --------------------------------------------------
     *     PROPERTIES
     * ------------------------------------------------------
     */

    /**
    *  @var  int
    */
    private $id;
    /**
    *  @var  string
    */
    private $name;
    /**
    *  @var  string
    */
    private $description;
    /**
    *  @var  string
    */
    private $bg;
    /**
    *  @var  int
    */
    private $mana;
    /**
    *  @var  int
    */
    private $life;
    /**
    *  @var  int
    */
    private $attack;
    /**
    *  @var  int
    */
    private $damageReceived;
    /**
    *  @var  int
    */
    private $status;
    /**
    *  @var  int
    */
    private $typeIdFk;
    /**
    *  @var  int
    */
    private $deckIdFk;

    /**
     * Identifiant du joueur lié au deck si exisant
     * @var  int
     */
    private $userIdFk;


    /**
     * --------------------------------------------------
     *     MAGIC METHOD
     * ------------------------------------------------------
     */

    /**
     * Card construct
     * @param  array  $data  Data for hydratation
     */
    public function __construct(array $data)
    {
        $this->hydratation($data);
    }

    /**
     * Display all object property
     * @return  string
     */
    public function __toString() :string
    {
        return
            'ID -> ' . br($this->getId())
            . 'NAME -> ' . br($this->getName())
            . 'DESCRIPTION -> ' . br($this->getDescription())
            . 'BACKGROUND -> ' . br($this->getBg())
            . 'MANA -> ' . br($this->getMana())
            . 'LIFE -> ' . br($this->getLife())
            . 'ATTACK -> ' . br($this->getAttack())
            . 'DAMAGE_RECEIVED -> ' . br($this->getDamageReceived())
            . 'STATUS -> ' . br($this->getStatus())
            . 'TYPE_ID -> ' . br($this->getTypeIdFk())
            . 'DECK_ID -> ' . br($this->getDeckIdFk()) . '<br>';
    }



    /**
     * --------------------------------------------------
     *     METHOD
     * ------------------------------------------------------
     */

    /**
    *  Card hydratation
    *  @param array $data
    */
    private function hydratation(array $data): void
    {
        foreach ($data as $key => $val) {
            $arrayKey = explode('_', $key);
            unset($arrayKey[0]);
            $finalKey = '';
            foreach ($arrayKey as $key => $value) {
                $finalKey .= ucfirst($value);
            }
            $nomSetter = 'set' . $finalKey;


            if (method_exists($this, $nomSetter)) {
                if (is_numeric($val)) {
                    $val = (int) $val;
                }

                $this->$nomSetter($val);
            } else {
                if (DEBUG === 'DEV') {
                    throw new Exception("La Setter: ' . $nomSetter . ' :params = ' . $val . ' n\'existe pas", 404);
                } else {
                    throw new Exception("Un problème est survenu lors de la récupération de la page, merci de contacter un administrateur !", 404);
                }
            }
        }
    }




    /**
     * --------------------------------------------------
     *     METHOD
     * ------------------------------------------------------
     */





    /**
     * --------------------------------------------------
     *     GETTERS
     * ------------------------------------------------------
     */

    /**
    * Get the value of Id
    * @return int
    */
    public function getId() :int
    {
        return $this->id;
    }

    /**
    * Get the value of Name
    * @return string
    */
    public function getName() :string
    {
        return $this->name;
    }

    /**
    * Get the value of Description
    * @return string
    */
    public function getDescription() :string
    {
        return $this->description;
    }

    /**
    * Get the value of Background
    * @return string
    */
    public function getBg() :string
    {
        return $this->bg;
    }

    /**
    * Get the value of Mana
    * @return int
    */
    public function getMana() :int
    {
        return $this->mana;
    }

    /**
    * Get the value of Life
    * @return int|null
    */
    public function getLife() :?int
    {
        return $this->life;
    }

    /**
    * Get the value of Attack
    * @return int
    */
    public function getAttack() :int
    {
        return $this->attack;
    }

    /**
    * Get the value of Damage Received
    * @return int|null
    */
    public function getDamageReceived() :?int
    {
        return $this->damageReceived;
    }

    /**
    * Get the value of Status
    * @return int|null
    */
    public function getStatus() :?int
    {
        return $this->status;
    }

    /**
    * Get the value of Type Id
    * @return int
    */
    public function getTypeIdFk() :int
    {
        return $this->typeIdFk;
    }

    /**
    * Get the value of Deck Id
    * @return int
    */
    public function getDeckIdFk() :int
    {
        return $this->deckIdFk;
    }

    /**
     * @return int
     */
    public function getUserIdFk(): int
    {
        return $this->userIdFk;
    }


    /**
     * --------------------------------------------------
     *     SETTERS
     * ------------------------------------------------------
     */

    /**
    * Set the value of Id
    * @param  int $id
    * @return Card
    */
    public function setId(int $id) :Card
    {
        $this->id = $id;
        return $this;
    }

    /**
    * Set the value of Name
    * @param  string $name
    * @return Card
    */
    public function setName(string $name) :Card
    {
        $this->name = $name;
        return $this;
    }

    /**
    * Set the value of Description
    * @param  string $description
    * @return Card
    */
    public function setDescription(string $description) :Card
    {
        $this->description = $description;
        return $this;
    }

    /**
    * Set the value of Background
    * @param  string $bg bg card
    * @return Card
    */
    public function setBg(string $bg) :Card
    {
        $this->bg = $bg;
        return $this;
    }

    /**
    * Set the value of Mana
    * @param  int $mana
    * @return Card
    */
    public function setMana(int $mana) :Card
    {
        $this->mana = $mana;
        return $this;
    }

    /**
    * Set the value of Life
    * @param  int|null $life
    * @return Card
    */
    public function setLife(?int $life) :Card
    {
        $this->life = $life;
        return $this;
    }

    /**
    * Set the value of Attack
    * @param  int $attack
    * @return Card
    */
    public function setAttack(int $attack) :Card
    {
        $this->attack = $attack;
        return $this;
    }

    /**
    * Set the value of Damage Received
    * @param  int|null $damageReceived
    * @return Card
    */
    public function setDamageReceived(?int $damageReceived) :Card
    {
        $this->damageReceived = $damageReceived;
        return $this;
    }

    /**
    * Set the value of Status (0 = pioche, 1=main, 2= defausse, 3 = pose depuuis moin d'un tour , 4 = pose et peut jouer)
    * @param  int|null $status
    * @return Card
    */
    public function setStatus(?int $status) :Card
    {
        $this->status = $status;
        return $this;
    }

    /**
    * Set the value of Type Id
    * @param  int  $typeIdFk
    * @return Card
    */
    public function setTypeIdFk(int $typeIdFk) :Card
    {
        $this->typeIdFk = $typeIdFk;
        return $this;
    }

    /**
    * Set the value of Deck Id
    * @param  int  $deckIdFk
    * @return Card
    */
    public function setDeckIdFk(int $deckIdFk) :Card
    {
        $this->deckIdFk = $deckIdFk;
        return $this;
    }

    /**
     * @param int $userIdFk
     *
     * @return static
     */
    public function setUserIdFk(int $userIdFk)
    {
        $this->userIdFk = $userIdFk;
        return $this;
    }
}
