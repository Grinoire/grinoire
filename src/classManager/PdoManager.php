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
    //$params is an associative array with the form '[placeholderName'=>value] OR ['placeholder'=>[ value, PDO::PARAM_STR, 'PDO::PARAM_STR']]
    //EXEMPLE PARAMS : [':id' => [ $id, PDO::PARAM_INT, 'PDO::PARAM_INT' ] , ':name' => $name ]
    //return PDOStatement
    public function makeStatement(string $sql, array $params = array()) : PDOStatement
    {
        //SI il n'y as pas de params on execute QUERY
        if(!$params)
        {
            $statement = $this->pdo->query($sql);

            if($statement === false)
            {
                $this->setErrorStatement( 'Query', $sql, $params ); //erreur
            }
        }
        //SINON SI il ya des parametre on execute PREPARE
        elseif(($statement = $this->pdo->prepare($sql)) !== false)
        {
            //on affecte les parametre aux placeholder
            foreach ($params as $placeholder => $value)
            {

                if ( is_array($value) )
                {
                    if($statement->bindValue($placeholder, $value[0], $value[1]) === false)
                    {
                        $this->setErrorStatement( 'BindValue[options]', $sql, $params ); //erreur
                    }
                }
                elseif($statement->bindValue($placeholder, $value=='' ? null : $value) === false)
                {
                    $this->setErrorStatement( 'BindValue', $sql, $params ); //erreur
                }
            }
            //SI execute retourne false
            if(!$statement->execute())
            {
                $this->setErrorStatement( 'Execute', $sql, $params ); //erreur
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
    //EX : $this->makeSelect( ' SELECT * FROM nain WHERE n_id = :id' , [ :id => [ $id , PDO::PARAM_INT, 'PDO::PARAM_INT' ] , :name => $name] , PDO::FETCH_NUM )
    public function makeSelect( string $sql, array $params = array(), $fetchStyle = PDO::FETCH_ASSOC, $fetchArg = NULL) :array
    {
        $statement = $this->makeStatement($sql, $params);

        $data = isset($fetchArg) ? $statement->fetchAll($fetchStyle, $fetchArg) : $statement->fetchAll($fetchStyle);
        $statement->closeCursor();

        return $data;
    }



    public function makeUpdate( string $sql, array $params = array()) :void
    {
        $statement = $this->makeStatement($sql, $params);
        $statement->closeCursor();
    }


    /**
     *  [ Extension de PdoManager->MakeStatement() pour la gestion d'erreur ]
     *  MakeStatement gere la preparation des requete
     *  @param  string  $action  [ action de la requete ]
     *  @param  string  $sql     [ requete ]
     *  @param  array   $params  [ parametres de la requete ]
     *  @return THROW-New-Exception( :messageErreur)
     */
    protected function setErrorStatement( string $action, string $sql, array $params = [] ) :void
    {
        //message generique
        $errorMessage = '<strong>' . $action . ' n\'a pas marché !<br>Merci de contacter un administrateur.<br></strong>';

        if(DEBUG == 'DEV')
        { //message detaillé
            $errorMessage = '<strong>' . $action . ' n\'a pas marché !<br><br> QUERY</strong> = <br>' . $sql . '<br>';

            //affichage parametre => param = bindValue( $placeholder, $value, $type);
            foreach ($params as $placeholder => $value)
            {
                if ( is_array($value) )
                {
                    $errorMessage .= '<br><strong>Params</strong> = bindValue( ' . $placeholder . ', ' . $value[0] . ', ' . $value[2] . ' );';
                }
                else
                {
                    $errorMessage .= '<br><strong>Params</strong> = bindValue( ' . $placeholder . ', ' . $value .' );';
                }
            }
        }

        throw new PdoException($errorMessage);
    }

}