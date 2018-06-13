<section id="log-in">
    <?= errorMessage() ?>
<h1>Vous connectez</h1>

<form method="POST" action=''>
    <label>Votre email : </label>
    <input type='text' required="required" name='email' value='<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>'>
    <label>Votre mot de passe :</label>
    <input type="password" required="required" name='password'>
    <input type='submit' name='' value='Soumettre'>
</form>

<a href='../web/index.php'>Retour à l'accueil</a>
</section>
