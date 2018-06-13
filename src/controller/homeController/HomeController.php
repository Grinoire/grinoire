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
    public function createAccountAction()
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
                    $this->setSession('msgValid', '<span style="justify-content: center;display: flex;color: green;padding: 2rem;font-size: 2rem">Votre compte a été créé avec succes</span>');
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
     * Display Login view & send data to sql if fields are valid
     * @throws Exception
     *
     */
    public function loginAction()
    {
        $this->init(__FILE__, __FUNCTION__);

        try {
            if (array_key_exists('email', $this->getPost()) and array_key_exists('password', $this->getPost())) {
                $userManager = new UserManager();
                if (!$userManager->checkMailInDataBase($this->post['email']) || !$userManager->checkPasswordInDataBase($this->post['password'])) {
                    throw new UserException('<span style="justify-content: center;background-color: lightcoral;display: flex;color: white;">Votre email ou mot de passe est incorrect !</span>');
                } else {
                    $user = $userManager->getUserDataBase(htmlspecialchars($this->post['email']), htmlspecialchars($this->post['password']));
                    $this->session['grinoire']['userConnected'] = $user->getId();
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

        if (array_key_exists('deconnexion', $this->getGet())) {
            $this->setSession('userConnected', array());
            session_destroy();
            $this->homeAction();
        } else {
            $this->render(true);
        }
    }

    /**
     * Display view profil & send data to sql if fields are valid
     */
    public function profilAction()
    {
        $this->init(__FILE__, __FUNCTION__);

        $profilManager = new UserManager();
        $myUser = $profilManager->getProfilById($this->getSession('userConnected'));

        try {
            if ((isset($this->post['lastName']) AND isset($this->post['firstName'])) AND (isset($this->post['mail']) AND isset($this->post['login'])) AND isset($this->post['password'])) {
                if ((isset($_FILES['avatar']) AND $_FILES['avatar']['error'] == 0)) {                                   //on vérifit que l'avatar est en POST
                    if($profilManager->isValidLoginProfil($this->post['login'], $myUser->getLogin())){                  //on vérifit que le login n'existe pas en base de données
                        throw new UserException('Le pseudo est déjà utilisé');
                        redirection('?c=Home&a=profil');
                    }elseif ($profilManager->isValidMailProfil($this->post['mail'], $myUser->getMail())) {              //vérif que le mail n'existe pas en base de données
                        throw new UserException('Ce mail existe déjà, veuillez entrer un autre mail');
                    }else{
                    $avatar = $profilManager->pictureProfilUser($_FILES['avatar']);
                    $profilManager->updateProfilUserById(
                        htmlspecialchars($this->post['lastName']),
                        htmlspecialchars($this->post['firstName']),
                        htmlspecialchars($this->post['mail']),
                        htmlspecialchars($this->post['login']),
                        htmlspecialchars($this->post['password']),
                        $avatar,
                        $this->getSession('userConnected')
                    );
                    $this->setSession('msgValid', 'Le profil a bien été mis a jour');                                   ////message de validation pour l'update du profil
                    redirection('?c=Home&a=profil');                                                         //Lors de la validation, on redirige vers la page profil,
                    }                                                                                                   //il n'y aura pas de post donc lors de l'execution, on ira directement dans le else
                } else {                                                                                                //Si l'avatar n'est pas en POST
                    if($profilManager->isValidLoginProfil($this->post['login'], $myUser->getLogin())){                  //vérif login existe pas en bdd
                        throw new UserException('Le pseudo est déjà utilisé');
                        redirection('?c=Home&a=profil');
                    }elseif($profilManager->isValidMailProfil($this->post['mail'], $myUser->getMail())) {               //mail != en bdd
                        throw new UserException('Ce mail existe déjà, veuillez entrer un autre mail');
                    }else{
                    $profilManager->updateProfilUserById(
                        htmlspecialchars($this->post['lastName']),
                        htmlspecialchars($this->post['firstName']),
                        htmlspecialchars($this->post['mail']),
                        htmlspecialchars($this->post['login']),
                        htmlspecialchars($this->post['password']),
                        $avatar = "",
                        $this->getSession('userConnected')
                    );
                    $this->setSession('msgValid', 'Le profil a bien été mis a jour');                                   //message de validation pour l'update du profil
                    redirection('?c=Home&a=profil');                                                         //Lors de la validation, on redirige vers la page profil,
                    }                                                                                                   //il n'y aura pas de post donc lors de l'execution, on ira directement dans le else
                }
            } else {                                                                                                    //else de sortie, récupère l'utilisateur par l'id stocker en sesssion
                $data = [];
                $data['user'] = $profilManager->getProfilById($this->getSession('userConnected'));
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
