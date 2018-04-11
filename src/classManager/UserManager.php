<?php

declare(strict_types = 1);

/**
 * Description of UserManager
 *
 * @author webuser1801
 */
class UserManager {

    private $pdo;

    public function __construct() {
        $this->pdo = PdoManager::getInstance();
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function setConnectionUser($mail, $login, $password) {

        $requete = 'INSERT INTO user(user_mail, user_login, user_password) VALUES(:mail, :login, :password)';

        $param = [
            'mail' => $mail,
            'login' => $login,
            'password' => $password
        ];
        $this->getPdo()->makeUpdate($requete, $param);
    }
    
    public function getUserDataBase($mail, $password){
        
        $requete = 'SELECT user_id FROM user WHERE user_mail = :mail AND user_password = :password';

        $param = [
            'mail' => $mail,
            'password' => $password
        ];

        $data = $this->getPdo()->makeSelect($requete, $param);
        
        return $data;

        
    }
    
}
