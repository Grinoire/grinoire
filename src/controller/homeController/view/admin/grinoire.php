<div id="grinoire-liste-utilisateur">

<p>Liste des utilisateurs</p>
<ul>
<?php
foreach ($data['users'] as $value) {

    ?>
        <li>
            Login : <a href="?c=Home&a=profil&id=<?= $value->getId() ?>"><?= $value->getLogin() ?></a>
            <span>Email : <?= $value->getMail() ?></span>
        </li>
    <!---->
    <?php
}
?>
</ul>

</div>
<?php
//var_dump($data['users']);