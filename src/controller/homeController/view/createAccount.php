 <section id="create-account-section">
            <!--TODO : faire une confirmation de mot de passe (2eme champ)-->

            <div id="create-account-section-wrapper">

                <h1 id="titre-creer-compte" >CREER UN COMPTE</h1>

                <form id="create-account-form" method="POST" action=''>
                    <div>
                    <label>PSEUDO : </label>
                    <input id="create-account-pseudo" class="create-account-input-form" type='text' name='login' required
                           value='<?php if (isset($_POST['login'])) echo htmlspecialchars($_POST['login']) ?>'>
                    </div>
                    <div>
                    <label>EMAIL : </label>
                    <input id="create-account-email" class="create-account-input-form" type='email' name='email' required
                           value='<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>'>
                    </div>
                    <div>
                    <label>MOT DE PASSE :</label>
                    <input id="create-account-password" class="create-account-input-form" type="password" name='password' required>
                    </div>
                    <div id="create-account-submit-div">
                    <input id="create-account-submit" type='submit' name='' value='SOUMETTRE'>
                    </div>
                </form>

                <a id="lien-vers-accueil-create-account" href="../web/index.php">Retour à l'accueil</a>

            </div>
        </section>
