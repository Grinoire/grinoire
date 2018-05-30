<?php
declare(strict_types = 1);

namespace grinoire\config;


/**
 * --------------------------------------------------
 *     Sets which PHP errors are reported (http://php.net/manual/fr/function.error-reporting.php)
 * ------------------------------------------------------
 */
error_reporting(E_ALL);


/**
 * ------------------------------------------------------------------------------------------------------------------------
 *     If the version of the operating system (provided by the pre-defined constants PHP_OS) corresponds to a Windows
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('PHP_EOL'))                {define('PHP_EOL', "\n");}
if (!defined('PATH_SEPARATOR'))         {define('PATH_SEPARATOR', ':');}
if (!defined('DIRECTORY_SEPARATOR'))    {define('DIRECTORY_SEPARATOR', "/");}



/**
 * -----------------------------------------------------------
 *     Defines the folder separator connected to the system
 * ------------------------------------------------------------------
 */
if (!defined('DS'))         {define('DS', DIRECTORY_SEPARATOR);}


/**
 * --------------------------------------------------
 *     Defines connection to database
 * ------------------------------------------------------
 */
if (!defined('HOST_DB'))    {define('HOST_DB', 'localhost');}
if (!defined('NAME_DB'))    {define('NAME_DB', 'grinoire');}
if (!defined('LOGIN_DB'))   {define('LOGIN_DB', 'root');}
if (!defined('MDP_DB'))     {define('MDP_DB', '');}


/**
 * --------------------------------------------------
 *     Defines APP
 * ------------------------------------------------------
 */
if (!defined('APP_NAME'))   {define('APP_NAME', 'grinoire');}
if (!defined('DEBUG'))      {define('DEBUG', 'DEV');}


/**
 * --------------------------------------------------
 *     Defines directory
 * ------------------------------------------------------
 */
if (!defined('DOMAIN'))     {define('DOMAIN', (dirname(__FILE__)));}
if (!defined('DIR_VIEW'))   {define('DIR_VIEW', dirname(dirname(__FILE__)) . '\view\\');}
if (!defined('DIR_CTRL'))   {define('DIR_CTRL', dirname(dirname(__FILE__)) . '\src\controller\\');}
if (!defined('DIR_IMG'))    {define('DIR_IMG', dirname(dirname(__FILE__)) . '\web\img\avatar\\');}
