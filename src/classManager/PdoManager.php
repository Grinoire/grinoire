<?php

declare(strict_types= 1);


/** Gestionnaire PDO & SQL requete pattern
 *
 */
class PDOManager
{
    protected $pdo;
    private static $instance;

    //construct: genere l'instance PDO
    private function __construct()
    {
        try
        {
            $this->pdo = new PDO ('mysql:host=' . HOST_DB . ';dbname=' . NAME_DB . ';charset=utf8', USER_DB, PWD_DB, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
        }
        catch ( Exception $e )
        {
            die($e->getMessage());
        }
    }

    /** SINGLETON getInstance(): instancie PDOManager si ce n'est pas deja le cas
     *
     *  @return   PDOManager
     */
    public static function getInstance() :PDOManager
    {
        if ( is_null(self::$instance) )
        {
            self::$instance = new PDOManager;
        }
        return self::$instance;
    }

    //retourne l'instance de la connection PDO
    public function getPDO() :PDO { return $this->pdo; }



    //Generates a PDOStatement using query or prepare, depending on $params composition
    //$sql is your query
    //$params is an associative array with the form 'placeholderName'=>value. Defaults to empty
    //May return false or throw on error
    protected function makeStatement(string $sql, array $params = array()) : PDOStatement {
        if(!$params) {
            $statement = $this->pdo->query($sql);

            if($statement === false) {
                $message = "query n'a pas marché";

                if(DEBUG == 'DEV') {
                    $message .= ' query : '.$sql;
                }

                throw new Exception($message);
            }
        } elseif(($statement = $this->pdo->prepare($sql)) !== false) {
            foreach ($params as $placeholder => $value) {
                if($statement->bindValue($placeholder, $value=='' ? null : $value) === false) {
                    $message = "bindValue n'a pas marché";
                    if(DEBUG == 'DEV') {
                        $message .= ' query : '.$sql.' --- Param : '.implode('->', $params);
                    }

                    throw new Exception($message);
                }
            }

            if(!$statement->execute()) {
                $message = "execute n'a pas marché";

                if(DEBUG == 'DEV') {
                    $message .= ' query : '.$sql.' --- Param : '.implode('->', $params);
                }

                throw new Exception($message);
            }
        }

        return $statement;
    }

    //Specialisation of MakeStatement for SELECT queries
    //$sql is your query
    //$params is an associative array with the form 'placeholderName'=>value. Defaults to empty
    //$fetchStyle is the PDO option passed to fetchAll. Defaults to PDO::FETCH_ASSOC
    //$fetchArg is needed for some values of $fetchStyle
    //Returns an array of all the results. Format of the results depends on $fetchStyle
    //May return false or throw on error
    //EX : $this->makeSelect( ' SELECT * FROM nain WHERE n_id = :id' , [ :id => $id , :nom => $nom] , PDO::FETCH_NUM )
    protected function makeSelect($sql, $params = array(), $fetchStyle = PDO::FETCH_ASSOC, $fetchArg = NULL)
    {
        $statement = $this->makeStatement($sql, $params);

        $data = isset($fetchArg) ? $statement->fetchAll($fetchStyle, $fetchArg) : $statement->fetchAll($fetchStyle);
        $statement->closeCursor();

        return $data;
    }

}
