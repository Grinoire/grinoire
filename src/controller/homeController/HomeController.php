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
    public function createAccountAction(): void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            if (array_key_exists('email', $this->getPost()) and array_key_exists('login', $this->getPost()) and array_key_exists('password', $this->getPost())) {
                $userManager = new UserManager();
                if ($userManager->checkLoginInDataBase($_POST['login'])) {//on controlle si le pseudo existe deja en base de donnees
                    throw new UserException('<span style="justify-content: center;background-color: lightcoral;display: flex;color: white;padding: 2rem;">Votre pseudo est déjà utilisé !</span>');
                } elseif ($userManager->checkMailInDataBase($_POST['email'])) {//on controlle si le mail existe en data base
                    throw new UserException('<span style="justify-content: center;background-color: lightcoral;display: flex;color: white;padding: 2rem;">L\'email est déjà utilisé !</span>');
                    //si le login et le mail n'existe pas en base de données alors on set l'utilisateur en base de données
                } elseif ((!$userManager->checkLoginInDataBase($_POST['login'])) AND (!$userManager->checkMailInDataBase($_POST['email']))) {
                    $userManager->setConnectionUser(htmlspecialchars($this->post['email']), htmlspecialchars($this->post['login']), htmlspecialchars($this->post['password']));
                    $this->render(true, 'home'); //Display home after create account
                }
            } else {
                $this->render(true); //View createAccount
            }
        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            $this->render(true); //View createAccount
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }


    /**
     *  Display Login view & send data to sql if fields are valid
     */
    public function loginAction(): void
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            if (array_key_exists('email', $this->getPost()) and array_key_exists('password', $this->getPost())) {
                $user = new UserManager();
                $userManager = new UserManager();
                $arrayUser = $user->getUserDataBase(htmlspecialchars($this->post['email']), htmlspecialchars($this->post['password']));
                if (!$arrayUser) {
                    throw new UserException('<span style="justify-content: center;background-color: lightcoral;display: flex;color: white;">Pseudo ou mot de passe incorrect !</span>');
                } else {
                    $this->session['grinoire']['userConnected'] = $arrayUser->getId(); //TODO : A TERMINER (Alex)
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
    public function grinoireAction(): void
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
    public function profilAction(): void
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
