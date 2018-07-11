<?php
declare(strict_types=1);

namespace grinoire\web;
use Exception;


require_once '../config/ini.php';
require_once '../config/autoload.php';
require_once '../src/common/commonFunction.php';

session_start();

if (isset($_GET["deconnexion"])) {
    //reboot the selected deck in BDD
//            $userManager = new UserManager();
//            $userManager->setSelectedDeck((int)$this->getSession('userConnected'));
    //unset session
    $_SESSION["APP_NAME"] = array();
    session_unset(APP_NAME);
    redirection('?ctrl=Home&a=home');
}

//Define controller -> merge default controller whith $_GET, so data are update if user need other view
$controller = array_merge(['c' => "Home", "a" => "home"], $_GET);

/**
 * On defini le chemin d'acces au controller
 * On controlle l'existence de la class et de la method requise
 * On charge la classe et execute la method ou retourne une {exception}
 */
$nomController = 'grinoire\\src\\controller\\' . lcfirst($controller['c']) . 'Controller\\' . $controller['c'] . 'Controller';
$nomAction = $controller['a'] . 'Action';

try {
    if (class_exists($nomController) AND method_exists($nomController, $nomAction)) {
        $c = new $nomController($_GET, $_POST);
        $c->$nomAction();
    } else {
        throw new Exception('Methode ou classe inaccessible !!');
    }
}
catch (Exception $e)
{
    $error = $e->getMessage();
}
