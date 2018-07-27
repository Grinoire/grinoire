<?php
declare(strict_types=1);

namespace grinoire\src\model;


use grinoire\src\model\entities\User;

class AdminManager
{

    /**
     * @var PdoManager
     */
    private $pdo;

    /**
     * AdminManager constructor.
     */
    public function __construct()
    {
        $this->pdo = PdoManager::getInstance();
    }

    /**
     * @return PdoManager
     */
    public function getPdo(): PdoManager
    {
        return $this->pdo;
    }
//SELECT * FROM role
//INNER JOIN role on user_role_id_fk = role_id
//INNER JOIN can on role_id = can_role_id_fk
//INNER JOIN `action` on can_action_id_fk = action_id
//WHERE user_id = :id',
    public function getRoleById(int $id)
    {
        $response = $this->getPdo()->makeSelect(
            'SELECT * FROM role
                  INNER JOIN user on user_role_id_fk = role_id
                  WHERE user_id = :id',
            [
                'id' => $id
            ],
            false
        );
//        return new User($response);
        $role = $response;
        return $role;
    }

    public function getActionByRole($role)
    {
        $response = $this->getPdo()->makeSelect(
            'SELECT action_name FROM `action` 
                      INNER JOIN can on action_id = can_action_id_fk
                      INNER JOIN role on can_role_id_fk = role_id
                      WHERE role_name = :role',
            [
                'role' => $role
            ],
            true
        );
        $action = $response;
        return $action;
    }

    public function getAllUser()
    {
        $response = $this->getPdo()->makeSelect(
            'SELECT * FROM user ORDER BY user_inscription DESC',
            [],
            true
        );
        foreach ($response as $key => $value) {
            $listes[$key] = new User($value);
        }
        return $listes;
    }


}