<?php
declare(strict_types=1);

namespace grinoire\src\model;
use PDO;
use grinoire\src\exception\UserException;
use grinoire\src\model\entities\User;


/**
 * Description of UserManager
 *
 * @author webuser1801
 */
class UserManager
{

    /**
     * @var  PdoManager
     */
    private $pdo;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->pdo = PdoManager::getInstance();
    }


    /**
     * @return  PdoManager
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * @param  [type]  $mail  [description]
     * @param  [type]  $login  [description]
     * @param  [type]  $password  [description]
     */
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


    /**
     *  --------------------------------------------------------------
     *  METHODE QUI TESTE EN BASE DE DONNEES L UTILISATEUR
     *  RENVOIS UN BOLLEEN
     *  Contourne la methode "makeselect" déjà existente
     *  --------------------------------------------------------------
     */

    /**
     *  Vérifit si le login est déjà en base de données (renvoit un booleen)
     * @param $login
     * @param $mail
     * @return bool
     */
    public function checkLoginInDataBase($login){

        $req = $this->getPdo()->getPdo()->prepare('SELECT user_id FROM user WHERE user_login = :login');
        $req->execute(array(
            'login' => $login
        ));
        return (bool)$req->fetch(PDO::FETCH_ASSOC);

    }

    /**
     * Vérifit si le mail est déjà en base de données (renvoit un booleen)
     * @param $mail
     * @return bool
     */
    public function checkMailInDataBase($mail){

        $req = $this->getPdo()->getPdo()->prepare('SELECT user_id FROM user WHERE user_mail = :mail');
        $req->execute(array(
            'mail'  => $mail
        ));
        return (bool)$req->fetch(PDO::FETCH_ASSOC);

    }

    /**
     *  Vérifit si le password est déjà en base de données (renvoit un booleen)
     * @param $password
     * @return bool
     */
    public function checkPasswordInDataBase($password){

        $req = $this->getPdo()->getPdo()->prepare('SELECT user_id FROM user WHERE user_password = :password');
        $req->execute(array(
            'password'  => $password
        ));
        return (bool)$req->fetch(PDO::FETCH_ASSOC);

    }

    public function isValidLoginProfil($login, $mylogin){
        $req = $this->getPdo()->getPdo()->prepare('SELECT user_id FROM user WHERE user_login = :login AND user_login != :mylogin');
        $req->execute(array(
            'login' => $login,
            'mylogin' => $mylogin
        ));
        return (bool)$req->fetch(PDO::FETCH_ASSOC);
    }

    public function isValidMailProfil($mail, $myMail){
        $req = $this->getPdo()->getPdo()->prepare('SELECT user_id FROM user WHERE user_mail = :mail AND user_mail != :myMail');
        $req->execute(array(
            'mail' => $mail,
            'myMail' => $myMail
        ));
        return (bool)$req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     *  --------------------------------------------------------------
     *      FIN DE METHODES TEST UTILISATEUR
     *  --------------------------------------------------------------
     */


    /**
     * [getUserDataBase description]
     * @param   string  $mail
     * @param   string  $password
     * @return
     */
    public function getUserDataBase($mail, $password)
    {

        $data = $this->getPdo()->makeSelect(
            'SELECT user_id FROM user WHERE user_mail = :mail AND user_password = :password',
            [
                'mail' => $mail,
                'password' => $password
            ],
            false
        );

        return new User($data);

    }


    /**
     * [getProfilById description]
     * @param   [type]  $id  [description]
     * @return  [type]  [description]
     */
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
     * @throws UserException
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

        $this->getPdo()->makeUpdate($requete, $param);

    }


    /**
     * @param $avatar
     * @return mixed
     * @throws \Exception
     */
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
                move_uploaded_file($avatar['tmp_name'], DIR_IMG . basename($avatar['name']));
                return $avatar['name'];
            } else {
                throw new \Exception('Extension de fichier non autorisé', 69);
            }
        } else {
            throw new \Exception('La taille du fichier est trop volumineuse', 69);
        }
    }




    /**
     * Set Ready state ...
     *
     * Ready state define who have selected a deck and search for match
     *
     * @param  int  $id     User ID
     * @param  int  $ready  0= not ready, 1=ready for fight , default value = 0
     */
    public function setReady(int $id, int $ready = 0) :void
    {
        if ($this->getProfilById($id)->getReady() !== $ready) {
            $response = $this->getPdo()->makeUpdate(
                'UPDATE `user` SET `user_ready` = :ready WHERE `user_id` = :id',
                [
                    ':ready' => [$ready, PDO::PARAM_INT],
                    ':id'    => [$id, PDO::PARAM_INT]
                ]
            );

            if ($response === 0) {
                throw new UserException("Un probléme est survenu lors du match making, merci de contacter un administrateur");
            } else {
                throw new UserException("La recherche est en cours, merci de patienter.");
            }
        } else {
            //ready already define, so we need to reset him sonewhere
        }
    }


    /**
     * select a ready User as opponent in database
     * @return  mixed  TRUE = User,  FALSE = boolean
     */
    public function getOpponent(int $idPlayer)
    {
        $opponent =null;
        $response = $this->getPdo()->makeSelect(
            'SELECT * FROM `user` WHERE `user_ready` = :int AND `user_id` != :idPlayer',
            [
                'int' => [1, PDO::PARAM_INT],
                ':idPlayer' => $idPlayer
            ]
        );

        if ($response) {
            $rand = rand(0,count($response) - 1);
            $opponent = $response[$rand];
        }
        return new User($opponent);
    }

}
