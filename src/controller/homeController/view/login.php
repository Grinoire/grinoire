<section id="login-section">
    <div id="login-section-wrapper">

        <h1 id="titre-se-connecter">SE CONNECTER</h1>

        <span id="login-span"></span>

        <form id="login-form" method="POST" action=''>
            <div>
                <label>PSEUDO/EMAIL : </label>
                <input id="login-form-email" class="login-form-input" type='text' name='email/pseudo'
                       value='<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>'>
            </div>
            <div>
                <label>MOT DE PASSE :</label>
                <input id="login-form-password" class="login-form-input" type="password" name='password'>
            </div>
            <div id="login-form-div-submit">
                <input id="login-form-submit" type='submit' name='' value='SOUMETTRE'>
            </div>
        </form>

        <a id="login-lien-vers-accueil" href='../web/index.php'>Retour à l'accueil</a>

    </div>
</section>
