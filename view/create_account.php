<?php

?>
<!--  ------------------- CSS ----------  -->
<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    h1{
        text-align: center;
    }
    form{
        display: flex;
        flex-direction: column;
        width: 700px;
        margin: auto;
        margin-top: 100px;
    }
    p{
        text-align: center;
        margin-top: 100px;
    }
</style>
<!-- ---------------------------------------- -->


<h1>Creer un compte pour jouer</h1>

<form method="POST" action=''>
    <label>Votre email : </label>
    <input type='email' name='email' required value='<?php if(isset($_POST['email'])) echo $_POST['email'] ?>'>
    <label>Votre mot de passe :</label>
    <input type="password" name='password' required>
    <input type='submit' name='' value='Soumettre'>
</form>

<p><a href="../web/index.php">Retour à l'accueil</a></p>