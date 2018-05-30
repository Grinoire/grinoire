
<h1>LOADING....</h1>

<?php
use grinoire\src\model\UserManager;

$u = new UserManager();
$i = $u->getProfilById($_SESSION['grinoire']['userConnected']);
var_dump($i);
echo "<br><br><h2>DECK :</h2>";
var_dump($_SESSION["grinoire"]["deck"]);
?>
