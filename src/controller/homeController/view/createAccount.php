<!--<div id="create-account-container">-->
<!---->
<!--    <div id="create-account-container-wrapper">-->
<!--        --><?//= errorMessage() ?>
<!---->
<!--        <header>-->
<!--            <img src="img/grinoire/logo.png">-->
<!--        </header>-->

        <section id="section-create-account">
            <!--TODO : faire une confirmation de mot de passe (2eme champ)-->
            <div id="section-create-account-wrapper">

                <h1>CREER UN COMPTE</h1>

                <form id="form-create-account" method="POST" action=''>
                    <div>
                    <label>PSEUDO : </label>
                    <input class="create-account-input-form" type='text' name='login' required
                           value='<?php if (isset($_POST['login'])) echo htmlspecialchars($_POST['login']) ?>'>
                    </div>
                    <div>
                    <label>EMAIL : </label>
                    <input class="create-account-input-form" type='email' name='email' required
                           value='<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>'>
                    </div>
                    <div>
                    <label>MOT DE PASSE :</label>
                    <input class="create-account-input-form" type="password" name='password' required>
                    </div>
                    <div id="submit-create-account-div">
                    <input id="submit-create-account" type='submit' name='' value='SOUMETTRE'>
                    </div>
                </form>

                <a id="lien-vers-accueil-create-account" href="../web/index.php">Retour à l'accueil</a>

            </div>
        </section>
<!--        <footer>-->
<!--            <div id="div-footer">-->
<!--                <img src="img/grinoire/footer-logo.png"/>-->
<!--                <p>©2018 OBJECTIF 3W. TOUS DROITS RÉSERVÉS.<br>-->
<!--                    Toutes les marques citées appartiennent à leur propriétaire.-->
<!--                </p>-->
<!--                <img src="img/grinoire/footer-pegy.png"/>-->
<!--            </div>-->
<!--        </footer>-->
<!---->
<!--    </div>-->
<!---->
<!---->
<!--</div>-->

