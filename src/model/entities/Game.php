<?php
declare(strict_types= 1);

namespace grinoire\src\model\entities;

/**
 *  Represent a game between 2 player
 *  Handle turn & contains ID for game or player
 */
class Game
{


    /**
     * --------------------------------------------------
     *     PROPERTIES
     * ------------------------------------------------------
     */

     /**
      * Game ID
      * @var  int
      */
    private $id;

    /**
     * First player ID
     * @var  int
     */
    private $player1Id;

    /**
     * Second player ID
     * @var  int
     */
    private $player2Id;

    /**
     * Game turn
     * @var  int
     */
    private $turn;



    /**
     * --------------------------------------------------
     *     MAGIC METHOD
     * ------------------------------------------------------
     */

    /**
     * Game construct
     * @param  array  $data  [description]
     */
    public function __construct(array $data)
    {
        $this->hydratation($data);
    }



    /**
     * --------------------------------------------------
     *     METHOD
     * ------------------------------------------------------
     */

    /**
    *  [ HYDRATATION ]
    *  @param array $data
    */
    private function hydratation(array $data): void
    {
        try {
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
                    throw new \Exception(" La Setter ' . $nomSetter . ':params= ' . $val . ' n\'existe pas !");
                }
            }
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }



    /**
     * --------------------------------------------------
     *     SETTERS
     * ------------------------------------------------------
     */

    /**
    * Define property value
    * @param  int    $id
    * @return self   ->FLUENT->
    */
    public function setId(int $id) :self
    {
        $this->id = $id;
        return $this;
    }


    /**
     * Define property value
     * @param  int      $player1Id
     * @return self     ->FLUENT->
     */
    public function setPlayer1Id(int $player1Id) :self
    {
        $this->player1Id = $player1Id;
        return $this;
    }

    /**
     * Define property value
     * @param  int      $player2Id
     * @return self     ->FLUENT->
     */
    public function setPlayer2Id(int $player2Id) :self
    {
        $this->player2Id = $player2Id;
        return $this;
    }

    /**
     * Define property value
     * @param  int      $turn
     * @return self     ->FLUENT->
     */
    public function setTurn(int $turn) :self
    {
        $this->turn = $turn;
        return $this;
    }


    /**
     * --------------------------------------------------
     *     GETTERS
     * ------------------------------------------------------
     */

    /**
     * Get property value
     * @return int
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * Get property value
     * @return int
     */
    public function getPlayer1Id() :int
    {
        return $this->player1Id;
    }

    /**
     * Get property value
     * @return int
     */
    public function getPlayer2Id() :int
    {
        return $this->player2Id;
    }

    /**
     * Get property value
     * @return int
     */
    public function getTurn() :int
    {
        return $this->turn;
    }

}
