<?php
// TODO: PB constant non reconnu ds lla connexion a pdo

declare(strict_types= 1);

namespace grinoire\src\model;

use PDO;
use PDOStatement;
use grinoire\src\exception\PdoException;

/**
 *  [Singleton] Handle Pdo connection, query and statement
 *
 *  -> makeSelect() : handle query request
 *
 *  -> makeUpdate() : handle update | delete | insert request
 *
 *  -> makeStatement() : define PDOStatement for each request
 *
 *  -> setErrorStatement() : define message error to display
 */
class PdoManager
{

    /**
     * @var  PDO
     */
    protected $pdo;

    /**
     * @var  PdoManager
     */
    private static $instance;

    /**
     *  Connect to database with {PDO}
     */
    private function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . HOST_DB . ';dbname=' . NAME_DB . ';charset=utf8', LOGIN_DB, MDP_DB, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION )
            );
        } catch (PdoException $e) {
            getErrorMessageDie($e);
        }

    }

    /**
     * PDO Instance
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     *  Singleton
     *  @return  PdoManager
     */
    public static function getInstance() :PdoManager
    {
        if (is_null(self::$instance)) {
            self::$instance = new PdoManager;
        }
        return self::$instance;
    }



    /**
     * Generates a PDOStatement using query or prepare, depending on $params composition
     * @param   string          $sql      Your query
     * @param   array           $params   Associative array like [':param'=>val] OR [':param'=>[ val, PDO::PARAM_STR]]
     * @return  PDOStatement
     */
    public function makeStatement(string $sql, array $params = array()) :PDOStatement
    {
        //SI il n'y as pas de params on execute QUERY
        if (!$params) {
            $statement = $this->pdo->query($sql);

            if ($statement === false) {
                $this->setErrorStatement('Query', $sql, $params); //erreur
            }
        }
        //SINON SI il ya des parametre on execute PREPARE
        elseif (($statement = $this->pdo->prepare($sql)) !== false) {
            //on affecte les parametre aux placeholder
            foreach ($params as $placeholder => $value) {

                if (is_array($value)) {
                    if ($statement->bindValue($placeholder, $value[0], $value[1]) === false) {
                        $this->setErrorStatement('BindValue[options]', $sql, $params); //erreur
                    }
                } elseif ($statement->bindValue($placeholder, $value=='' ? null : $value) === false) {
                    $this->setErrorStatement('BindValue', $sql, $params); //erreur
                }
            }
            //SI execute retourne false
            if ($statement->execute()) {

            } else {
                $this->setErrorStatement('Execute', $sql, $params); //erreur
            }
        }

        return $statement;
    }



    /**
     * Specialisation of makeStatement() for SELECT queries
     * @param   string          $sql            Your query
     * @param   array           $params         [opt] Associative array like [':param'=>val] OR [':param'=>[ val, PDO::PARAM_STR]]
     * @param   bool            $fetchAll       [opt] Set to false if request contains only one rows of result
     * @param   int             $fetchStyle     [opt] This is the PDO option passed to fetch or fetchAll. Defaults to PDO::FETCH_ASSOC
     * @param   int             $fetchArg       [opt] This is needed for some values of $fetchStyle only if fetchAll is active
     * @return  array
     */
    public function makeSelect(string $sql, array $params = array(),bool $fetchAll = true, $fetchStyle = \PDO::FETCH_ASSOC, $fetchArg = null) :array
    {
        $statement = $this->makeStatement($sql, $params);

        if ($fetchAll) {
            $data = isset($fetchArg) ? $statement->fetchAll($fetchStyle, $fetchArg) : $statement->fetchAll($fetchStyle);
        } else {
            $data = $statement->fetch($fetchStyle);
        }
        $statement->closeCursor();

        return $data;
    }


    /**
     * Specialisation of makeStatement() for INSERT || UPDATE || DELETE queries
     * @param   string          $sql      Your query
     * @param   array           $params   Associative array like [':param'=>val] OR [':param'=>[ val, PDO::PARAM_STR]]
     * @return  int                       Number of rows affected by the last SQL statement
     */
    public function makeUpdate(string $sql, array $params = array()) :int
    {
        $statement = $this->makeStatement($sql, $params);
        $affectedRow = $statement->rowCount();
        $statement->closeCursor();
        return $affectedRow;
    }


    /**
     *  Sppecialisation of makeStatement for handling Exception
     *  @param  string      $action   Request action
     *  @param  string      $sql      Query
     *  @param  array       $params   Query parameters
     *  @throws PdoException          PDO -> FATAL_ERROR
     *  @return void
     */
    protected function setErrorStatement(string $action, string $sql, array $params = []) :void
    {
        //generic message
        $errorMessage = '<strong>' . $action . ' n\'a pas marché !<br>Merci de contacter un administrateur.<br></strong>';

        if (DEBUG == 'DEV') { //dev message
            $errorMessage = '<strong>' . $action . ' n\'a pas marché !<br><br> QUERY</strong> = <br>' . $sql . '<br>';

            foreach ($params as $placeholder => $value) {
                if (is_array($value)) {

                    switch ($value[1]) { //convert PDO::CONSTANT_VALUE to string
                        case 0:
                            $paramType = 'PDO::PARAM_NULL';
                            break;
                        case 1:
                            $paramType = 'PDO::PARAM_INT';
                            break;
                        case 2:
                            $paramType = 'PDO::PARAM_STR';
                            break;
                        case '5':
                            $paramType = 'PDO::PARAM_BOOL';
                            break;
                        default:
                            $paramType = 'TYPE INCONNU';
                            break;
                    }

                    $errorMessage .= '<br><strong>Params</strong> = bindValue( ' . $placeholder . ', ' . $value[0] . ', ' . $paramType . ' );';
                } else {
                    $errorMessage .= '<br><strong>Params</strong> = bindValue( ' . $placeholder . ', ' . $value .' );';
                }
            }
        }

        throw new PdoException($errorMessage);
    }
}
