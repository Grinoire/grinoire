<section id="section-login">
    <!--    --><? //= errorMessage() ?>
    <div id="section-login-wrapper">

        <h1>SE CONNECTER</h1>
<!--        todo: (ALEX) - faire l'autentification par pseudo -->
        <form id="form-login" method="POST" action=''>
            <div>
                <label>PSEUDO/EMAIL : </label>
                <input class="form-login-input" type='text' required="required" name='email'
                       value='<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>'>
            </div>
            <div>
                <label>MOT DE PASSE :</label>
                <input class="form-login-input" type="password" required="required" name='password'>
            </div>
            <div id="form-login-div-submit">
                <input id="form-login-submit" type='submit' name='' value='Soumettre'>
            </div>
        </form>

        <a id="lien-vers-accueil-login" href='../web/index.php'>Retour à l'accueil</a>

    </div>
</section>
