<section id="create-account">
    
<h1>Creer un compte pour jouer</h1>

<form method="POST" action=''>
    <label>Votre Pseudo : </label>
    <input type='text' name='login' required value='<?php if (isset($_POST['login'])) echo htmlspecialchars($_POST['login']) ?>'>
    <label>Votre email : </label>
    <input type='email' name='email' required value='<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>'>
    <label>Votre mot de passe :</label>
    <input type="password" name='password' required>
    <input type='submit' name='' value='Soumettre'>
</form>

<a href="../web/index.php">Retour à l'accueil</a>

</section>