<?php

declare(strict_types=1);

/**
 * Description of UserManager
 *
 * @author webuser1801
 */
class UserManager
{

    private $pdo;

    public function __construct()
    {
        $this->pdo = PdoManager::getInstance();
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function setConnectionUser($mail, $login, $password)
    {

        $requete = 'INSERT INTO user(user_mail, user_login, user_password) VALUES(:mail, :login, :password)';

        $param = [
            'mail' => $mail,
            'login' => $login,
            'password' => $password
        ];
        $this->getPdo()->makeUpdate($requete, $param);
    }

    public function getUserDataBase($mail, $password)
    {

        $requete = 'SELECT user_id FROM user WHERE user_mail = :mail AND user_password = :password';

        $param = [
            'mail' => $mail,
            'password' => $password
        ];

        $data = $this->getPdo()->makeSelect($requete, $param);

        return $data;

    }

    public function getProfilById($id)
    {

        $requete = 'SELECT * FROM user WHERE user_id = :id';

        $param = [
            'id' => $id
        ];
        $data = $this->getPdo()->makeSelect($requete, $param);

        $user = new User($data[0]);

        return $user;

    }

    /**
     * @param $lastName
     * @param $firstName
     * @param $mail
     * @param $login
     * @param $password
     * @param $avatar
     * @param $id
     */
    public function updateProfilUserById($lastName, $firstName, $mail, $login, $password, $avatar, $id)
    {
        //si le champ avatar n'est pas sélectionner alors
        if (empty($avatar)) {
            //update sans le champ avatar
            $requete = 'UPDATE user SET user_last_name = :lastName, user_first_name = :firstName, user_mail = :mail, user_login = :login, user_password = :password WHERE user_id = :id';

            $param = [
                'lastName' => $lastName,
                'firstName' => $firstName,
                'mail' => $mail,
                'login' => $login,
                'password' => $password,
                'id' => $id
            ];
        //sinon, le champ avatar est sélectionné
        } else {
            //update de la requete avec le champ avatar
            $requete = 'UPDATE user SET user_last_name = :lastName, user_first_name = :firstName, user_mail = :mail, user_login = :login, user_password = :password, user_avatar = :avatar WHERE user_id = :id';
            $param = [
                'lastName' => $lastName,
                'firstName' => $firstName,
                'mail' => $mail,
                'login' => $login,
                'password' => $password,
                'avatar' => $avatar,
                'id' => $id
            ];
        }

        $data = $this->getPdo()->makeUpdate($requete, $param);

        return $data;
    }

    public function pictureProfilUser($avatar)
    {
        //on teste si la taille du fichier n'est pas trop grosse (7Mo)
            if ($avatar['size'] <= 7000000) {
                $infosfichier = pathinfo($avatar['name']);
                $extension_upload = $infosfichier['extension'];
                //on définis les exetentions autorisé
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                if (in_array($extension_upload, $extensions_autorisees)) {
                    // On peut valider le fichier et le stocker définitivement
                    move_uploaded_file($avatar['tmp_name'], 'img/avatar/' . basename($avatar['name']));
                    return $avatar['name'];
                }else{
                   throw new Exception('Extension de fichier non autorisé', 69);
                }
            }else{
                throw new Exception('La taille du fichier est trop volumineuse',69);
            }
    }

}
