<!--<span style="text-align:center;color:white;background-color: red;font-size:30px;">--><? //= errorMessage() ?><!--</span>-->
<!--<span style="text-align:center;color:white;background-color: green;font-size:30px;">--><? //= validMessage() ?><!--</span>-->
<section id="profil-section">
    <div id="profil-section-wrapper">

        <span id="profil-pseudo-baniere"><?= $user->getLogin() ?></span>
        <img id="profil-contour-avatar" src="img/grinoire/profil/contour-avatar.png"/>


        <div id="profil-form-container">
            <div id="profil-statistique-joueur">
                <span>Inscrit depuis le : <?= $user->getInscription() ?></span>

                <span>Parties gagnées : <?= $user->getWinnedGame() ?></span>

                <span>Parties perdues : </span>

                <span>Nombre de parties jouées: <?= $user->getPlayedGame() ?></span>
            </div>

            <form id="profil-form" action="" method="POST" enctype="multipart/form-data">
                <?php
                if (!$user->getAvatar()) {
                    ?>
                    <div class="avatar-img">
                        <label title="Modifier avatar" for="file" class="label-file">
                        </label>
                        <input id="file" class="input-file" type="file" name="avatar" value="">
                    </div>
                    <img class="avatar" src="img/avatar/default/avatar-default.png"/>
                    <?php
                } else {
                    ?>
                    <div class="avatar-img">
                        <label title="Modifier avatar" for="file" class="label-file">
                        </label>
                        <input id="file" class="input-file" type="file" name="avatar" value="">
                    </div>
                    <img class="avatar" src="img/avatar/<?= $user->getAvatar() ?>"/>
                    <?php
                }
                ?>
                <input id="profil-submit" type="submit" value="MODIFIER">
                <div id="profil-input-text-container">

                    <div>
                        <label>Pseudo :</label>
                        <input id="profil-input-login" class="input-profil" type="text" name="login" value="<?= $user->getLogin() ?>">
                    </div>
                    <div>
                        <label>Nom :</label>
                        <input id="profil-input-nom" class="input-profil" type="text" name="lastName" value="<?= $user->getLastName() ?>">
                    </div>
                    <div>
                        <label>Prénom :</label>
                        <input id="profil-input-prenom" class="input-profil" type="text" name="firstName" value="<?= $user->getFirstName() ?>">
                    </div>
                    <div>
                        <label>Email :</label>
                        <input id="profil-input-email" class="input-profil" type="text" name="mail" value="<?= $user->getMail() ?>">
                    </div>
                    <div>
                        <label>Mot de passe :</label>
                        <input id="profil-input-password" class="input-profil" type="password" name="password" value="<?= $user->getPassword() ?>">
                    </div>

                </div>

            </form>

            <a id="profil-lien-vers-accueil" href="?c=Home&a=grinoire">Retour à l'accueil</a>

        </div>

    </div>
</section>
