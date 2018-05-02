<?php
require_once("../config/ini.php");
require_once("../config/splAutoloadRegister.php");
require_once("../src/common/commonFunction.php");
session_start(); ?>
<h1>LOADING....</h1>

<?php
var_dump($_SESSION["grinoire"]["deck"]);
?>
