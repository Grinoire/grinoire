<?php
//TODO: IMPLEMENTER METHODE SUPER GLOBALE FILE
declare(strict_types=1);

namespace grinoire\src\controller\homeController;

use grinoire\src\controller\CoreController;
use grinoire\src\model\UserManager;
use Exception;

/**
 *
 */
class HomeController extends CoreController
{
    public function __construct($get = [], $post = [])
    {
        parent::__construct($get, $post);
    }



    /**
     * Initialise la vue de la page d'acceul -> vue home.php
     */
    public function homeAction()
    {
        $this->init(__FILE__, __FUNCTION__);
        $this->render(true);
    }

    /**
     * affiche la vue de création de compte
     * controlle si l'utilisateur a saisir les valeurs
     * et redirige sur l'accueil si les posts sont corrects
     */
    public function createAccountAction()
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            if (array_key_exists('email', $this->getPost()) and array_key_exists('login', $this->getPost()) and array_key_exists('password', $this->getPost())) {
                $userManager = new UserManager();
                $userManager->setConnectionUser(htmlspecialchars($this->post['email']), htmlspecialchars($this->post['login']), htmlspecialchars($this->post['password']));
                $this->render(true, 'home'); //display home after create account
            } else {
                $this->render(true); //show view createAccount
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }


    /**
     *  affiche la page de connexion
     *  Permet de se connecter au jeu
     */
    public function loginAction()
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            if (array_key_exists('email', $this->getPost()) and array_key_exists('password', $this->getPost())) {
                $user = new UserManager();
                $arrayUser = $user->getUserDataBase(htmlspecialchars($this->post['email']), htmlspecialchars($this->post['password']));
                if (!$arrayUser) {
                    throw new Exception('<span style="justify-content: center;background-color: lightcoral;display: flex;color: white;">login ou password incorrect</span>');
                } else {
                    $this->session['grinoire']['userConnected'] = $arrayUser[0]['user_id']; //Je prefere passer par un getter de ma class UserManager pour récupérer mon object
                    redirection('?c=Home&a=grinoire');
                }
            } else {
                $this->render(true);
            }
        } catch (Exception $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true);
        }
    }

    /**
     * Affiche l'accueil du jeu grinoire une fois connecté
     */
    public function grinoireAction()
    {
        $this->init(__FILE__, __FUNCTION__);
        $this->render(true);

        if (array_key_exists('deconnexion', $this->getGet())) {
            $this->setSession('userConnected', array());
            session_destroy();
            $this->homeAction();
        }
    }

    /**
     * @return  [type]  [description]
     */
    public function profilAction()
    {
        $this->init(__FILE__, __FUNCTION__);

        $profil = new UserManager();
        $user = $profil->getProfilById($this->getSession('userConnected'));

        try {
            if ((isset($this->post['lastName']) and isset($this->post['firstName'])) and (isset($this->post['mail']) and isset($this->post['login'])) and (isset($this->post['password']) and isset($_FILES['avatar'])) and (isset($_FILES['avatar']) and $_FILES['avatar']['error'] == 0)) {
                $avatar = $profil->pictureProfilUser($_FILES['avatar']);
                $profil->updateProfilUserById(
                    htmlspecialchars($this->post['lastName']),
                    htmlspecialchars($this->post['firstName']),
                    htmlspecialchars($this->post['mail']),
                    htmlspecialchars($this->post['login']),
                    htmlspecialchars($this->post['password']),
                    $avatar,
                    htmlspecialchars($this->getSession('userConnected'))
                );
                $user = $profil->getProfilById(htmlspecialchars($this->getSession('userConnected')));
                var_dump($user);
                redirection('?c=Home&a=profil');
            } else {
                $data = [];
                $data['user'] = $user;
                $this->render(true, 'profil', $data);
            }
        } catch (Exception $e) {
            $this->setSession('error', $e->getMessage());
        }
    }
}
