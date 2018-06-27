<?php

declare(strict_types= 1);

namespace grinoire\src\model;
use PDO;

use grinoire\src\model\entities\Game;


/**
 *  Represents a GameManager
 */
class GameManager
{


    /**
     * Singleton DbManager
     * @var  PdoManager
     */
    private $pdo;



    /**
     * --------------------------------------------------
     *     MAGIC METHOD
     * ------------------------------------------------------
     */

    /**
     *  GameManager construct
     *  Singleton PdoManager
     */
    public function __construct()
    {
        $this->pdo = PdoManager::getInstance();
    }



    /**
     * --------------------------------------------------
     *     METHOD SQL
     * ------------------------------------------------------
     */



     /**
     * Insert a new game in database, auto generate game_FK in user
     * @param  int  $player1Id  Player ID
     * @param  int  $player2Id  Player ID
     * @return int  GameId
     */
     public function newGame(int $player1Id, int $player2Id) :int
     {
         $response = $this->getPdo()->makeUpdate( //generate a new game
             'INSERT INTO `game` (`game_player_1_id`, `game_player_2_id`, `game_turn`)
             VALUES (:player1, :player2, 0)',
             [
                 ':player1' => $player1Id,
                 ':player2' => $player2Id
             ]
         );

         $lastInsertId = (int) $this->getPdo()->getPdo()->lastInsertId(); //get last id inserted
         $this->session['grinoire']['activeGame'] = $lastInsertId;

         $response2 = $this->getPdo()->makeUpdate( //update both user to set active game id and remove ready state
             'UPDATE `user` SET `user_game_id_fk` = :gameId WHERE `user_id` = :player1 OR `user_id` = :player2',
             [
                 ':gameId'  => [$lastInsertId, PDO::PARAM_INT],
                 ':player1' => [$player1Id, PDO::PARAM_INT],
                 ':player2' => [$player2Id, PDO::PARAM_INT]
             ]
         );

         if ($response === 0 || $response2 === 0) { //error if one update return 0
             throw new \Exception("Merci de contacter un administrateur, la partie n'a pas pu étre généré !");
         } else {
             return $lastInsertId;
         }
     }


     /**
      * Get all defined properties for a game selected by ID
      * @param   int  $id  Game ID
      * @return  Game
      */
    public function getGame(int $id) :Game
    {
        $response = $this->getPdo()->makeSelect(
            'SELECT * FROM `game` WHERE `game_id` = :id',
            [':id' => [$id, PDO::PARAM_INT]],
            false
        );

        if ($response === 0) {
            throw new \Exception("Merci de contacter un administrateur, la partie n'a pas pu étre récupéré !");
        } else {
            return new Game($response);
        }
    }



    /**
     * Set turn property +=1 in Bdd
     * @param   int     $id          Game ID
     * @param   int     $actualTurn  Actual turn value
     * @return  void
     */
    public function nextTurn(int $id, int $actualTurn) {
        $actualTurn++;

        $response = $this->getPdo()->makeUpdate(
            'UPDATE `game` SET `game_turn` = :oldTurn WHERE `game_id` = :gameId',
            [
                ':oldTurn'   => [$actualTurn, PDO::PARAM_INT],
                ':gameId'    => [$id, PDO::PARAM_INT]
            ]
        );

        if ($response === 0) {
            throw new \Exception("Merci de contacter un administrateur, le passage au tour suivant n'a pas fonctionner !");
        }
    }





    /**
     * --------------------------------------------------
     *     GETTERS
     * ------------------------------------------------------
     */

    /**
     * Get property value
     * @return PdoManager  Handler DB connection
     */
    public function getPdo(): PdoManager
    {
        return $this->pdo;
    }


}
