<?php

/**
 *  [ redige l'utilisateur ]
 *  @param  string  $destination
 */
function redirection( string $destination ) :void
{
    header("Location: " . $destination);
    die();
}



/**
 *  CATCH Exception : echo() AND die() ]
 *  @param   OBJ-new-Exception
 *  @return  void
 */
function getErrorMessageDie( $e ) :void
{
    echo $e->getMessage();
    die();
}



/**
 *  [ retourne la string en ajoutant un <br> a la fin ]
 *  @param   mixed  $msg
 *  @return  string
 */
function br( $msg ) :string
{
    return $msg.'<br>';
}

function errorMessage()
{
    $msg = null;
    if(isset($_SESSION['grinoire']['error']) AND !empty($_SESSION['grinoire']['error']))
    {
        $msg = $_SESSION['grinoire']['error'];
        unset($_SESSION['grinoire']['error']);
    }
    return $msg;
}
