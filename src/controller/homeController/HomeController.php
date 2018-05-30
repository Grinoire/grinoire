<?php
//TODO: IMPLEMENTER METHODE SUPER GLOBALE FILE
declare(strict_types=1);

namespace grinoire\src\controller\homeController;

use grinoire\src\exception\UserException;

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
     * display default home view
     */
    public function homeAction()
    {
        $this->init(__FILE__, __FUNCTION__);
        $this->render(true);
    }

    /**
     * Display create account view & send data to sql if fields are valid
     *
     * Redirection to home if account created, else throw Exception
     */
    public function createAccountAction() :void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            if (array_key_exists('email', $this->getPost()) and array_key_exists('login', $this->getPost()) and array_key_exists('password', $this->getPost())) {
                $userManager = new UserManager();
                $userManager->setConnectionUser(htmlspecialchars($this->post['email']), htmlspecialchars($this->post['login']), htmlspecialchars($this->post['password']));
                $this->render(true, 'home'); //Display home after create account
            } else {
                $this->render(true); //View createAccount
            }
        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true); //View createAccount
        } catch (\Exception $e) {
            getErrorMessageDie();

        }
    }


    /**
     *  Display Login view & send data to sql if fields are valid
     */
    public function loginAction() :void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            if (array_key_exists('email', $this->getPost()) and array_key_exists('password', $this->getPost())) {
                $user = new UserManager();
                $arrayUser = $user->getUserDataBase(htmlspecialchars($this->post['email']), htmlspecialchars($this->post['password']));
                if (!$arrayUser) {
                    throw new UserException('<span style="justify-content: center;background-color: lightcoral;display: flex;color: white;">login ou password incorrect</span>');
                } else {
                    $this->session['grinoire']['userConnected'] = $arrayUser[0]['user_id']; //Je prefere passer par un getter de ma class UserManager pour récupérer mon object
                    redirection('?c=Home&a=grinoire');
                }
            } else {
                $this->render(true);
            }
        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true);
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }

    /**
     * Display home connected view
     */
    public function grinoireAction() :void
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
     * Display view profil & send data to sql if fields are valid
     */
    public function profilAction() :void
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

            } else {
                $data = [];
                $data['user'] = $user;
                $this->render(true, 'profil', $data);
            }

        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            redirection('?c=Home&a=profil');
        } catch (\Exception $e) {
            getErrorMessageDie($e);

        }
    }

}
