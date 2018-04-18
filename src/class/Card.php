<?php

declare(strict_types= 1);

/**
*/
class Card
{
    // ----------------------- //
    // ------ ATTRIBUT ------- //
    // ----------------------- //

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


    // ----------------------------- //
    // ------ METHOD MAGIQUE ------- //
    // ----------------------------- //
    public function __construct( array $data )
    {
        $this->hydratation($data);
    }

    public function __toString() :string
    {
        return
            'ID -> ' . br( $this->getId())
            . 'NAME -> ' . br( $this->getName())
            . 'DESCRIPTION -> ' . br( $this->getDescription())
            . 'BACKGROUND -> ' . br( $this->getBg())
            . 'MANA -> ' . br( $this->getMana())
            . 'LIFE -> ' . br( $this->getLife())
            . 'ATTACK -> ' . br( $this->getAttack())
            . 'DAMAGE_RECEIVED -> ' . br( $this->getDamageReceived())
            . 'STATUS -> ' . br( $this->getStatus())
            . 'TYPE_ID -> ' . br( $this->getTypeIdFk())
            . 'DECK_ID -> ' . br( $this->getDeckIdFk()) . '<br>';

    }


    // ------------------------- //
    // ------ METHOD SQL ------- //
    // ------------------------- //



    // --------------------- //
    // ------ METHOD ------- //
    // --------------------- //

    /**
    *  [ HYDRATATION ]
    *  @param array $data
    */
    private function hydratation( array $data ): void
    {
        try
        {
            foreach ($data as $key => $val)
            {
                $arrayKey = explode('_', $key);
                unset($arrayKey[0]);
                $finalKey = '';
                foreach ($arrayKey as $key => $value) {
                    $finalKey .= ucfirst($value);
                }
                $nomSetter = 'set' . $finalKey;


                if(method_exists($this, $nomSetter))
                {
                    if ( is_numeric($val)) {
                        $val = (int) $val;
                    }

                    $this->$nomSetter($val);
                }
                else { throw new Exception(" La Setter ' . $nomSetter . ':params= ' . $val . ' n\'existe pas !"); }

            }
        }
        catch (Exception $e) { getErrorMessageDie( $e ); }
    }



    // --------------------- //
    // ------ GETTERS ------ //
    // --------------------- //

    /**
    * Get the value of Id
    * @return int
    */
    public function getId() :int {
        return $this->id;
    }

    /**
    * Get the value of Name
    * @return string
    */
    public function getName() :string {
        return $this->name;
    }

    /**
    * Get the value of Description
    * @return string
    */
    public function getDescription() :string {
        return $this->description;
    }

    /**
    * Get the value of Background
    * @return string
    */
    public function getBg() :string {
        return $this->bg;
    }

    /**
    * Get the value of Mana
    * @return int
    */
    public function getMana() :int {
        return $this->mana;
    }

    /**
    * Get the value of Life
    * @return int|null
    */
    public function getLife() :?int {
        return $this->life;
    }

    /**
    * Get the value of Attack
    * @return int
    */
    public function getAttack() :int {
        return $this->attack;
    }

    /**
    * Get the value of Damage Received
    * @return int|null
    */
    public function getDamageReceived() :?int {
        return $this->damageReceived;
    }

    /**
    * Get the value of Status
    * @return int|null
    */
    public function getStatus() :?int {
        return $this->status;
    }

    /**
    * Get the value of Type Id
    * @return int
    */
    public function getTypeIdFk() :int {
        return $this->typeIdFk;
    }

    /**
    * Get the value of Deck Id
    * @return int
    */
    public function getDeckIdFk() :int {
        return $this->deckIdFk;
    }


    // --------------------- //
    // ------ SETTERS ------ //
    // --------------------- //

    /**
    * Set the value of Id
    * @param int $id
    * @return self
    */
    public function setId(int $id) :Card {
        $this->id = $id;
        return $this;
    }

    /**
    * Set the value of Name
    * @param string $name
    * @return self
    */
    public function setName(string $name) :Card {
        $this->name = $name;
        return $this;
    }

    /**
    * Set the value of Description
    * @param string $description
    * @return self
    */
    public function setDescription(string $description) :Card {
        $this->description = $description;
        return $this;
    }

    /**
    * Set the value of Background
    * @param string $background
    * @return self
    */
    public function setBg(string $bg) :Card {
        $this->bg = $bg;
        return $this;
    }

    /**
    * Set the value of Mana
    * @param int $mana
    * @return self
    */
    public function setMana(int $mana) :Card {
        $this->mana = $mana;
        return $this;
    }

    /**
    * Set the value of Life
    * @param int $life
    * @return self
    */
    public function setLife(?int $life) :Card {
        $this->life = $life;
        return $this;
    }

    /**
    * Set the value of Attack
    * @param int $attack
    * @return self
    */
    public function setAttack(int $attack) :Card {
        $this->attack = $attack;
        return $this;
    }

    /**
    * Set the value of Damage Received
    * @param int $damage_received
    * @return self
    */
    public function setDamageReceived(?int $damageReceived) :Card {
        $this->damageReceived = $damageReceived;
        return $this;
    }

    /**
    * Set the value of Status
    * @param int $status
    * @return self
    */
    public function setStatus(?int $status) :Card {
        $this->status = $status;
        return $this;
    }

    /**
    * Set the value of Type Id
    * @param int $type_id
    * @return self
    */
    public function setTypeIdFk(int $typeIdFk) :Card {
        $this->typeIdFk = $typeIdFk;
        return $this;
    }

    /**
    * Set the value of Deck Id
    * @param int $deck_id
    * @return self
    */
    public function setDeckIdFk(int $deckIdFk) :Card {
        $this->deckIdFk = $deckIdFk;
        return $this;
    }

}
