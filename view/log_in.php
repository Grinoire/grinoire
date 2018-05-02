<?php
try {
    if (isset($_POST['email']) AND isset($_POST['password'])) {
        $user = new UserManager();
        $arrayUser = $user->getUserDataBase(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['password']));
        if (!$arrayUser) {
            echo '<span style="justify-content: center;background-color: lightcoral;display: flex;color: white;">login ou password incorrect</span>';
        } else {
            session_start();
            $_SESSION['grinoire']['userConnected'] = $arrayUser[0]['user_id']; //Je prefere passer par un getter de ma class UserManager pour récupérer mon object
            redirection('?section=grinoire');
        }
    }
} catch (Exception $e) {
    $e->getMessage();
}
?>

<section id="log-in">
    
<h1>Vous connectez</h1>

<form method="POST" action=''>
    <label>Votre email : </label>
    <input type='email' name='email' value='<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>'>
    <label>Votre mot de passe :</label>
    <input type="password" name='password'>
    <input type='submit' name='' value='Soumettre'>
</form>

<a href='../web/index.php'>Retour à l'accueil</a>

</section>