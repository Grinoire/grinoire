<!--
$user = extracted data from HomeController
-->
<section id="profil">
    <h1>PAGE PROFIL</h1>
    <a href="?c=Home&a=grinoire">HOME</a>
    <br>
    <span style="text-align:center;color:white;background-color: red;font-size:30px;"><?= errorMessage() ?></span>
    <br><br><br><br>
    <?php
    if (!$user->getAvatar()) {
    } else {
        ?>
        <img id="profil-avatar" src="img/avatar/<?= $user->getAvatar() ?>"/>
        <br>
        <?php
    }
    ?>

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
        <label>Avatar</label>
        <input type="file" name="avatar" value="">
        <label>Pseudo</label>
        <input type="text" name="login" value="<?= $user->getLogin() ?>">
        <label>Mot de passe</label>
        <input type="text" name="password" value="<?= $user->getPassword() ?>">
        <label>Nom</label>
        <input type="text" name="lastName" value="<?= $user->getLastName() ?>">
        <label>Prénom</label>
        <input type="text" name="firstName" value="<?= $user->getFirstName() ?>">
        <label>Email</label>
        <input type="text" name="mail" value="<?= $user->getMail() ?>">
        <input type="submit" value="modifier profil">
    </form>
</section>
