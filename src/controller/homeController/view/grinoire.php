<section id="grinoire-section">

    <?php
    if ($data['admin']['role_power'] <= 10) {
        require '../src/controller/homeController/view/admin/grinoire.php';
    }
    ?>
    <div id="grinoire-section-wrapper">
        <img id="box-contour" src="img/grinoire/contour-grinoire.png"/>

        <div id="grinoire-liens">
            <div id="grinoire-liens-wrapper">
                <a id="grinoire-deconnexion" href="?c=Home&a=grinoire&deconnexion">Déconnexion</a>
                <img id="img-cerlce-deco" src="img/grinoire/cercle.png"/>
                <a id="grinoire-jouer" href="?c=Game&a=selectDeck">JOUER</a>
                <img id="img-cercle-jouer" src="img/grinoire/cercle-jouer.png"/>
                <a id="grinoire-profil" href="?c=Home&a=profil">Profil</a>
                <img id="img-cercle-profil" src="img/grinoire/cercle.png"/>
            </div>
        </div>

        <p id="grinoire-texte">Entrez dans l'histoire...</p>

    </div>
</section>

