<?php
declare(strict_types= 1);

namespace grinoire\src\model\entities;

/**
 *  Represente le Hero
 */
class Hero
{
    /**
     *  nom du heros
     *  @var  string
     */
    private $name;

    /**
     *  image du heros
     *  @var  string
     */
    private $bg;

    /**
     *  mana disponible
     *  @var  int
     */
    private $mana;

    /**
     *  vie total du heros
     *  @var  int
     */
    private $life;

    /**
     *  dommage recu par le heros
     *  @var  int
     */
    private $damageReceived;




    // ----------------------------- //
    // ------ METHOD MAGIQUE ------- //
    // ----------------------------- //
    public function __construct( $data )
    {

        $this->hydratation($data);
    }


    // ------------------------- //
    // ------ METHOD SQL ------- //
    // ------------------------- //
    
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
    // ------ METHOD ------- //
    // --------------------- //



    // --------------------- //
    // ------ SETTERS ------ //
    // --------------------- //

    /**
    * Defini le nom du héros
    * @param  string $name
    * @return self   ->FLUENT->
    */
    public function setName( string $name ) :self {
        $this->name = $name;
        return $this;
    }

    /**
    * Defini l'image representant le héros
    * @param  string  $bg
    * @return self   ->FLUENT->
    */
    public function setBg( string $bg ) :self {
        $this->bg = $bg;
        return $this;
    }

    /**
    * Defini la valeur de mana disponible
    * @param  int  $mana
    * @return self ->FLUENT->
    */
    public function setMana( int $mana ) :self {
        $this->mana = $mana;
        return $this;
    }

    /**
    * Defini la valeur de la vie du héros
    * @param  int   $life
    * @return self  ->FLUENT->
    */
    public function setLife( int $life ) :self {
        $this->life = $life;
        return $this;
    }

    /**
    * defini le nombre de degat reçu par le héros
    * @param  int   $damageReceived
    * @return self  ->FLUENT->
    */
    public function setDamageReceived( int $damageReceived ) :self {
        $this->damageReceived = $damageReceived;
        return $this;
    }

    // --------------------- //
    // ------ GETTERS ------ //
    // --------------------- //

	/**
	 * Retourne le nom du héros
	 * @return string
	 */
	public function getName() :string {
		return $this->name;
	}

	/**
	 * Retourne l'image représentant le héros
	 * @return string
	 */
	public function getBg() :string {
		return $this->bg;
	}

	/**
	 * Retourne le mana disponible
	 * @return int
	 */
	public function getMana() :int {
		return $this->mana;
	}

	/**
	 * Retourne la vie total du héros
	 * @return int
	 */
	public function getLife() :int {
		return $this->life;
	}

	/**
	 * Retourne la valeur des dommages reçu par le héros
	 * @return int
	 */
	public function getDamageReceived() :int {
		return $this->damageReceived;
	}
}
