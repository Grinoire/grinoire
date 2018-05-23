<?php
declare(strict_types=1);

spl_autoload_register(function($nameSpace){
   if(strstr($nameSpace, '\\'))
   {
       $explode = explode('\\', $nameSpace);
       if($explode[0] == APP_NAME)
       {
           unset($explode[0]);
       }
       $path = implode('/', $explode);

       require '../' . $path . '.php';
   }
});
