<section id="log-in">

<h1>Vous connectez</h1>

<form method="POST" action=''>
    <label>Votre email : </label>
    <input type='text' name='email' value='<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>'>
    <label>Votre mot de passe :</label>
    <input type="password" name='password'>
    <input type='submit' name='' value='Soumettre'>
</form>

<a href='../web/index.php'>Retour à l'accueil</a>
    <span style="text-align:center;color:white;background-color: red;font-size:30px;"><?= errorMessage() ?></span>
</section>