<?php
try {
    if (isset($_POST['email']) AND isset($_POST['login']) AND isset($_POST['password'])) {
        $userSetDataBase = new UserManager();
        $userSetDataBase->setConnectionUser($_POST['email'], $_POST['login'], $_POST['password']);
        header('Location: index.php');
        die();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<section id="create-account">
    
<h1>Creer un compte pour jouer</h1>

<form method="POST" action=''>
    <label>Votre Pseudo : </label>
    <input type='text' name='login' required value='<?php if (isset($_POST['login'])) echo $_POST['login'] ?>'>
    <label>Votre email : </label>
    <input type='email' name='email' required value='<?php if (isset($_POST['email'])) echo $_POST['email'] ?>'>
    <label>Votre mot de passe :</label>
    <input type="password" name='password' required>
    <input type='submit' name='' value='Soumettre'>
</form>

<a href="../web/index.php">Retour à l'accueil</a>

</section>