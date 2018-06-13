<section id="profil">

    <span style="text-align:center;color:white;background-color: red;font-size:30px;"><?= errorMessage() ?></span>
    <span style="text-align:center;color:white;background-color: green;font-size:30px;"><?= validMessage() ?></span>

    <h1>PAGE PROFIL</h1>
    <a href="?c=Home&a=grinoire">HOME</a>
    <br>
    <br><br>


    <span>Vous êtes inscrit depuis le <?= $user->getInscription() ?></span>
    <br>
    <span>Partie gagnée : <?= $user->getWinnedGame() ?></span>
    <br>
    <span>Partie perdu : </span>
    <br>
    <span>Nombre de partie joué : <?= $user->getPlayedGame() ?></span>
    <br>
    <br>
    <br>
    <form action="" method="POST" enctype="multipart/form-data">
        <?php
        if (!$user->getAvatar()) {
            ?>
            <label title="Modifier avatar" for="file" class="label-file"><img id="avatar-img"
                                                      src="img/avatar/default/avatar-default.png"/></label>
            <input id="file" class="input-file" type="file" name="avatar" value="">
            <?php
        } else {
            ?>
            <label title="Modifier avatar" for="file" class="label-file"><img id="avatar-img"
                                                      src="img/avatar/<?= $user->getAvatar() ?>"/></label>
            <input id="file" class="input-file" type="file" name="avatar" value="">
            <?php
        }
        ?>
        <label>Pseudo</label>
        <input type="text" name="login" value="<?= $user->getLogin() ?>">
        <label>Mot de passe</label>
        <input type="password" name="password" value="<?= $user->getPassword() ?>">
        <label>Nom</label>
        <input type="text" name="lastName" value="<?= $user->getLastName() ?>">
        <label>Prénom</label>
        <input type="text" name="firstName" value="<?= $user->getFirstName() ?>">
        <label>Email</label>
        <input type="text" name="mail" value="<?= $user->getMail() ?>">
        <input type="submit" value="modifier profil">
    </form>
</section>
