<?php
declare(strict_types= 1);

namespace grinoire\src\model\entities;


/**
 *  Contient les carte et le hero associé au deck
 */
class Deck
{

    /**
     *  Identifiant du deck
     *  @var  int
     */
    private $id;

    /**
     *  Nom du deck
     *  @var  string
     */
    private $name;

    /**
     *  couleur associée
     *  @var string
     */
    private $color;

    /**
    *  Instance du hero lié au deck [COMPOSITION]
    *  @var  Hero
    */
    protected $hero;

    /**
     *  Instance des cartes du deck [COMPOSITION]
     *  @var  array
     */
    protected $cardList = array();




    // ----------------------------- //
    // ------ METHOD MAGIQUE ------- //
    // ----------------------------- //

    /**
     * [__construct description]
     * @param  array  $data      [description]
     * @param  array  $cardList  [description]
     * @param  Hero   $hero      [description]
     */
    public function __construct( array $data, array $cardList, Hero $hero )
    {
        $this->hydratation($data);
        $this->setCardList($cardList);
        $this->setHero($hero);
    }

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
                else { throw new \Exception(" La Setter ' . $nomSetter . ':params= ' . $val . ' n\'existe pas !"); }

            }
        }
        catch (\Exception $e) { getErrorMessageDie( $e ); }
    }


    // --------------------- //
    // ------ METHOD ------- //
    // --------------------- //

    // public function shuffleCard(array $cards) :array
    // {
    //     shuffle($cards);
    //     return $cards;
    // }

    // public function setDraw(array $cards) :void
    // {
    //
    // }



    // --------------------- //
    // ------ SETTERS ------ //
    // --------------------- //

    /**
    * Défini la valeur de l'dentifiant du deck
    * @param  int    $id
    * @return self   ->FLUENT->
    */
    public function setId( int $id ) :self {
        $this->id = $id;
        return $this;
    }

    /**
    * Défini la valeur du Nom du deck
    * @param  string  $name
    * @return self    ->FLUENT->
    */
    public function setName( string $name ) :self {
        $this->name = $name;
        return $this;
    }

    /**
    * Défini la valeur de la couleur associée
    * @param  string  $color
    * @return self    ->FLUENT->
    */
    public function setColor( string $color ) :self {
        $this->color = $color;
        return $this;
    }

    /**
    * Défini l'instance des cartes du deck
    * @param  Card[]   $cardList  instance de {card}
    * @return self   ->FLUENT->
    */
    public function setCardList( array $cardList ) :self {
        $this->cardList = $cardList;
        return $this;
    }

    /**
    * Défini l'instance du hero lié au deck
    * @param  Hero  $hero
    * @return self  ->FLUENT->
    */
    public function setHero( Hero $hero ) :self {
        $this->hero = $hero;
        return $this;
    }


    // --------------------- //
    // ------ GETTERS ------ //
    // --------------------- //

	/**
	 * Retourne la valeur de l'identifiant du deck
	 * @return int
	 */
	public function getId() :int {
		return $this->id;
	}

	/**
	 * Retourne la valeur du Nom du deck
	 * @return string
	 */
	public function getName() :string {
		return $this->name;
	}

	/**
	 * Retourne la valeur de la couleur associée
	 * @return string
	 */
	public function getColor() :string {
		return $this->color;
	}

	/**
	 * Retourne les instances {card} du deck
	 * @return array
	 */
	public function getCardList() :array {
		return $this->cardList;
	}

	/**
	 * Retourne l'instance {hero} lié au deck
	 * @return Hero
	 */
	public function getHero() :Hero {
		return $this->hero;
	}
}
