<?php
//TODO: IMPLEMENTER METHODE SUPER GLOBALE FILE
declare(strict_types=1);

namespace grinoire\src\controller\homeController;

use Exception;
use grinoire\src\controller\CoreController;
use grinoire\src\exception\UserException;
use grinoire\src\model\AdminManager;
use grinoire\src\model\DeckManager;
use grinoire\src\model\GameManager;
use grinoire\src\model\UserManager;

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
        $this->setNewLayout('template-home/');
        // require '../view/template-home/header.php';
        $this->render(true, 'home');
        // require '../view/template-home/footer.php';
    }

    /**
     * Display create account view & send data to sql if fields are valid
     * Redirection to home if account created, else throw Exception
     */
    public function createAccountAction()
    {
        $this->init(__FILE__, __FUNCTION__);
        try {
            if (array_key_exists('email', $this->getPost()) and array_key_exists('login', $this->getPost()) and array_key_exists('password', $this->getPost())) {
                $userManager = new UserManager();           //On instancie le UserManager
                if ((!$userManager->checkLoginInDataBase($_POST['login'])) AND (!$userManager->checkMailInDataBase($_POST['email']))) { //si le login et le mail ne sont pas en base de données
                    $userManager->setConnectionUser(htmlspecialchars($this->post['email']), htmlspecialchars($this->post['login']), htmlspecialchars($this->post['password']));
                    $this->setSession('msgValid', 'Votre compte a été créé avec succes');
                    redirection('index.php');
                }
            } else {
                $this->setNewLayout('template-home/');
                // require '../view/template-home/header.php';
                $this->render(true, 'createAccount');
                // require '../view/template-home/footer.php';
            }
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }

    public function createAccountAjaxAction()
    {
        if (array_key_exists('email', $this->getPost()) and array_key_exists('login', $this->getPost()) and array_key_exists('password', $this->getPost())) {
            $userManager = new UserManager();
            if ($userManager->checkLoginInDataBase($_POST['login'])) {//on controlle si le pseudo existe deja en base de donnees
                echo 'Votre pseudo est déjà utilisé !';
            } elseif ($userManager->checkMailInDataBase($_POST['email'])) {//on controlle si le mail existe en data base
                echo 'L\'email est déjà utilisé !';
            }
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
            if (array_key_exists('email/pseudo', $this->getPost()) and array_key_exists('password', $this->getPost())) {
                $userManager = new UserManager();
                if ($userManager->checkMailInDataBase($this->post['email/pseudo']) || $userManager->checkLoginInDataBase($this->post['email/pseudo']) AND $userManager->checkPasswordInDataBase($this->post['password'])) {
                    $user = $userManager->getUserDataBase(htmlspecialchars($this->post['email/pseudo']), htmlspecialchars($this->post['password']));
                    $this->session['grinoire']['userConnected'] = $user->getId();
                    redirection('?c=Home&a=grinoire');
                }else{
                    redirection('?c=Home&a=login');
                }
            } else {
                $this->setNewLayout('template-home/');
                // require '../view/template-home/header.php';
                $this->render(true, 'login');
                // require '../view/template-home/footer.php';
            }
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }

    public function loginAjaxAction()
    {
        if (array_key_exists('email/pseudo', $this->getPost()) and array_key_exists('password', $this->getPost())) {
            $userManager = new UserManager();
            if (!$userManager->checkMailInDataBase($this->post['email/pseudo']) || !$userManager->checkLoginInDataBase($this->post['email/pseudo']) AND !$userManager->checkPasswordInDataBase($this->post['password'])) {
                echo 'Votre email ou mot de passe est incorrect !';
            }
        }
    }

    /**
     * Display home connected view
     */
    public function grinoireAction(): void
    {
        $this->init(__FILE__, __FUNCTION__);

        $adminManager = new AdminManager();
        $data['admin'] = $adminManager->getPowerById((int)$this->getSession('userConnected'));
        $data['action'] = $adminManager->getActionByRole($data['admin']['role_name']);

        $data['users'] = $adminManager->getAllUser();

        if (array_key_exists('deconnexion', $this->getGet())) {

                $userManager = new UserManager();
                $userId = (int)$this->getSession('userConnected');                 //on stock l'id du joueur connecte
                $gameId = $userManager->getUserById($userId)->getGameIdFk();        //on recupere l'id de la partie en BDD

                if ($gameId !== NULL) {                                             //verifie que l'id de la partie est valide
                    $userManager->resetData($userId);                               //reinitialise les valeurs de l'utilisateur en BDD (gameFk, deckFk)
                    $gameManager = new GameManager();
                    $gameManager->resetData($gameId);                               //actualise les données liés a la partie en BDD (status)
                    $deckManager = new DeckManager();
                    $deckManager->resetData($userId);                               //Efface la copie du deck et ses cartes genere temporairement (carte, hero)
                }
        
            $this->setSession(APP_NAME, array());                               //Vide la session liée a l'application
            session_unset();                                                    //Efface les sessions de l'utilisateur
            redirection('?c=Home&a=home');                                      //Redirige vers la vue connection
        } else { //Sinon on affiche la vue de l'acceuil
            $this->setNewLayout('template-home/');
            // require '../view/template-home/header.php';
            $this->render(true, 'grinoire', $data);
            // require '../view/template-home/footer.php';
        }
    }


    /**
     * Display view profil & send data to sql if fields are valid
     */
    public function profilAction()
    {
        $this->init(__FILE__, __FUNCTION__);

        $profilManager = new UserManager();

        if (isset($this->get['id'])) {
            $myUser = $profilManager->getProfilById($this->get['id']);
            $id = $myUser->getId();
        } else {
            $myUser = $profilManager->getProfilById($this->getSession('userConnected'));
            $id = $myUser->getId();
        }

        try {
            if ((isset($this->post['lastName']) AND isset($this->post['firstName'])) AND (isset($this->post['mail']) AND isset($this->post['login'])) AND isset($this->post['password'])) {
                if ((isset($_FILES['avatar']) AND $_FILES['avatar']['error'] == 0)) {                                   //on vérifit que l'avatar est en POST
                    if ($profilManager->isValidLoginProfil($this->post['login'], $id)) {                 //on vérifit que le login n'existe pas en base de données
                        throw new UserException('Le pseudo est déjà utilisé');
                        redirection("?c=Home&a=profil&id=" . $id);
                    } elseif ($profilManager->isValidMailProfil($this->post['mail'], $id)) {              //vérif que le mail n'existe pas en base de données
                        throw new UserException('Ce mail existe déjà, veuillez entrer un autre mail');
                        redirection("?c=Home&a=profil&id=" . $id);
                    } else {
                        $avatar = $profilManager->pictureProfilUser($_FILES['avatar']);

                        $profilManager->updateProfilUserById(
                            htmlspecialchars($this->post['lastName']),
                            htmlspecialchars($this->post['firstName']),
                            htmlspecialchars($this->post['mail']),
                            htmlspecialchars($this->post['login']),
                            htmlspecialchars($this->post['password']),
                            $avatar,
                            $id
                        );
                        $this->setSession('msgValid', 'Le profil a bien été mis a jour');                                   ////message de validation pour l'update du profil

                        redirection("?c=Home&a=profil&id=" . $id);                                          //Lors de la validation, on redirige vers la page profil,

                    }                                                                                                   //il n'y aura pas de post donc lors de l'execution, on ira directement dans le else
                } else {                                                                                                //Si l'avatar n'est pas en POST
                    if ($profilManager->isValidLoginProfil($this->post['login'], $id)) {                  //vérif login existe pas en bdd
                        throw new UserException('Le pseudo est déjà utilisé');
                        redirection("?c=Home&a=profil&id=" . $id);
                    } elseif ($profilManager->isValidMailProfil($this->post['mail'], $id)) {               //mail != en bdd
                        throw new UserException('Ce mail existe déjà, veuillez entrer un autre mail');
                        redirection("?c=Home&a=profil&id=" . $id);
                    } else {
                        $profilManager->updateProfilUserById(
                            htmlspecialchars($this->post['lastName']),
                            htmlspecialchars($this->post['firstName']),
                            htmlspecialchars($this->post['mail']),
                            htmlspecialchars($this->post['login']),
                            htmlspecialchars($this->post['password']),
                            $avatar = "",
                            $id
                        );
                        $this->setSession('msgValid', 'Le profil a bien été mis a jour');                                   //message de validation pour l'update du profil
                        redirection("?c=Home&a=profil&id=" . $id);                                                 //Lors de la validation, on redirige vers la page profil,
                    }                                                                                                   //il n'y aura pas de post donc lors de l'execution, on ira directement dans le else
                }
            } else {                                                                                                    //else de sortie, récupère l'utilisateur par l'id stocker en sesssion
                if (isset($_GET['id'])) {
                    $data['user'] = $profilManager->getProfilById($_GET['id']);
                } else {
                    $data['user'] = $profilManager->getProfilById($this->getSession('userConnected'));
                }
                $this->setNewLayout('template-home/');
                // require '../view/template-home/header.php';
                $this->render(true, 'profil', $data);
                // require '../view/template-home/footer.php';
            }
        } catch (UserException $e) {
            $this->setSession('error', $e->getMessage());
            redirection("?c=Home&a=profil&id=" . $id);
        } catch (\Exception $e) {
            getErrorMessageDie($e);
        }
    }

//    public function profilAjaxAction()
//    {
//        $profilManager = new UserManager();
//        if (isset($_GET['id'])) {
//            $myUser = $profilManager->getProfilById($_GET['id']);
//            $id = $myUser->getId();
//        } else {
//            $myUser = $profilManager->getProfilById($this->getSession('userConnected'));
//            $id = $myUser->getId();
//        }
//        if ((isset($this->post['lastName']) AND isset($this->post['firstName'])) AND (isset($this->post['mail']) AND isset($this->post['login'])) AND isset($this->post['password'])) {
//            if ((isset($_FILES['avatar']) AND $_FILES['avatar']['error'] == 0)) {
//                if ($profilManager->isValidLoginProfil($this->post['login'], $id)) {
//                    echo 'Le pseudo est déjà utilisé';
//                } elseif ($profilManager->isValidMailProfil($this->post['mail'], $id)) {
//                    echo 'Ce mail existe déjà, veuillez entrer un autre mail';
//                }
//            } else {
//                if ($profilManager->isValidLoginProfil($this->post['login'], $id)) {
//                    echo 'Le pseudo est déjà utilisé';
//                } elseif ($profilManager->isValidMailProfil($this->post['mail'], $id)) {
//                    echo 'Ce mail existe déjà, veuillez entrer un autre mail';
//                }
//            }
//        }
//    }

}
