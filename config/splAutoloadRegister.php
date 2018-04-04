<?php
/**
 * loadClass - Inclure un fichier en fonction de son nom
 * @param   string  $className
 * @return
**/
function loadClass( $className )
{
    $path = ['../src/class/', '../src/class/classManager/'];
    $fileName = $className . '.php';

    foreach ($path as $chemin)
    {
        if( file_exists( $chemin . $fileName ) )
        {
            require_once( $chemin . $fileName );
            return;
        }
    }
}
spl_autoload_register( 'loadClass' ); // On lance la procédure d'auto-chargement des classes avec la fonction "includeClass" en callback
