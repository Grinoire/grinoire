<button id="admin-button">Liste des utilisateurs</button>

<div id="grinoire-liste-utilisateur" class="hidden">

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