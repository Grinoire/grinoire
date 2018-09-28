<section id="profil-section">
    <div id="profil-section-wrapper">

        <span id="profil-pseudo-baniere"><?= $user->getLogin() ?></span>
        <!--        <img id="profil-contour-avatar" src="img/grinoire/profil/contour-avatar.png"/>-->
        <img id="profil-contour-avatar" src="img/grinoire/profil/bandeau.png"/>



        <div id="profil-form-container">


            <div id="profil-statistique-joueur">
                <?php
                date_default_timezone_set('Europe/Paris');
                setlocale(LC_TIME, 'fr_FR.utf8','fra');
                ?>
                <span>Inscrit depuis le : <?=  strftime("%A %d %B %Y", strtotime($user->getInscription())) ?></span>

                <span>Parties gagnées : <?php echo $user->getWinnedGame() != null ? $user->getWinnedGame() : 0 ?></span>

                <span>Parties perdues : <?php echo $user->getPlayedGame() == null ? 0 : $user->getPlayedGame() - $user->getWinnedGame() ?></span>

                <span>Nombre de parties jouées: <?php echo $user->getPlayedGame() != null ? $user->getPlayedGame() : 0 ?></span>
            </div>

            <span id="profilSpanMessage"><p id="profilErreurMessage" style="color: red;"><?= errorMessage() ?></p><p id="profilMessageValid" style="color: green;"><?= validMessage() ?></p></span>

            <form id="profil-form" action="" method="POST" enctype="multipart/form-data">
                <?php
                if (!$user->getAvatar()) {//si l'ustilisateur n'a pas d'avatar
                    ?>
                    <div class="avatar-img">
                        <label title="Modifier avatar" for="file" class="label-file">
                        </label>
                        <input id="file" class="input-file" type="file" name="avatar" value="">
                    </div>
                    <img class="avatar" src="img/avatar/default/avatar-default.png"/>
                    <?php
                } else {            //sinon l'utilisateur a un avatar
                    ?>
                    <div class="avatar-img">
                        <label title="Modifier avatar" for="file" class="label-file">
                        </label>
                        <input id="file" class="input-file" type="file" name="avatar" value="">
                    </div>
                    <img class="avatar" src="img/avatar/<?php echo $user->getAvatar() ?>"/>
                    <?php
                }
                ?>
                <input id="profil-submit" type="submit" value="MODIFIER">
                <div id="profil-input-text-container">

                    <div>
                        <label>Pseudo :</label>
                        <input id="profil-input-login" class="input-profil" type="text" name="login"
                               value="<?= htmlspecialchars($user->getLogin()) ?>">
                    </div>
                    <div>
                        <label>Nom :</label>
                        <input id="profil-input-nom" class="input-profil" type="text" name="lastName"
                               value="<?= htmlspecialchars($user->getLastName()) ?>">
                    </div>
                    <div>
                        <label>Prénom :</label>
                        <input id="profil-input-prenom" class="input-profil" type="text" name="firstName"
                               value="<?= htmlspecialchars($user->getFirstName()) ?>">
                    </div>
                    <div>
                        <label>Email :</label>
                        <input id="profil-input-email" class="input-profil" type="text" name="mail"
                               value="<?= htmlspecialchars($user->getMail()) ?>">
                    </div>
                    <div>
                        <label>Mot de passe :</label>
                        <input id="profil-input-password" class="input-profil" type="password" name="password"
                               value="<?= htmlspecialchars($user->getPassword()) ?>">
                    </div>

                </div>

            </form>

            <a id="profil-lien-vers-accueil" href="?c=Home&a=grinoire">Retour à l'accueil</a>

        </div>

    </div>
</section>
