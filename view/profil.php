<?php
declare(strict_types=1);
session_start();

$profil = new UserManager();
$user = $profil->getProfilById($_SESSION['grinoire']['userConnected']);
$messageErreur = '';

try {
    if (isset($_POST['lastName']) AND isset($_POST['firstName']) AND isset($_POST['mail']) AND isset($_POST['login']) AND isset($_POST['password']) AND isset($_FILES['avatar']) AND isset($_FILES['avatar']) AND $_FILES['avatar']['error'] == 0) {

//        $img = move_uploaded_file($_FILES['avatar']['tmp_name'], 'img/avatar/' . basename($_FILES['avatar']['name']));

        $updateProfil = new UserManager();
        $avatar = $updateProfil->pictureProfilUser($_FILES['avatar']);
        $updateProfil->updateProfilUserById(htmlspecialchars($_POST['lastName']), htmlspecialchars($_POST['firstName']), htmlspecialchars($_POST['mail']), htmlspecialchars($_POST['login']), htmlspecialchars($_POST['password']), $avatar, htmlspecialchars($_SESSION['grinoire']['userConnected']));
//        $updateProfil->updateProfilUser(htmlspecialchars($_POST['lastName']), htmlspecialchars($_POST['firstName']), htmlspecialchars($_POST['mail']), htmlspecialchars($_POST['login']), htmlspecialchars($_POST['password']), htmlspecialchars($_FILES['avatar']['name']), htmlspecialchars($_SESSION['grinoire']['userConnected']));
        $user = $profil->getProfilById(htmlspecialchars($_SESSION['grinoire']['userConnected']));
    }
} catch (Exception $e) {
    if($e->getCode() == 69) {
        $messageErreur = $e->getMessage();
    } else {
        die($e->getMessage());
    }

}

?>

<section id="profil">

<h1>PAGE PROFIL</h1>
<a href="?section=grinoire">HOME</a>
<br>
<span style="text-align:center;color:white;background-color: red;font-size:30px;"><?= $messageErreur ?></span>
<br><br><br><br>
<?php if (!$user->getAvatar()) {

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