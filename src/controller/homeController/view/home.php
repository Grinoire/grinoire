<div id="home-container">

    <div id="home-wrapper-container">
        <?php
        if(validMessage()) {
            ?>
            <span><?= validMessage() ?></span>
            <?php
        }
        ?>
        <header>
            <img src="img/grinoire/logo.png">
        </header>

        <section id="home-section">

            <div id="home-section-wrapper">

                <div id="container-home-form">

                    <p>Entrez dans l'histoire...</p>

                    <img src="img/grinoire/container-form"/>

                    <div id="home-form">
                        <a href="?c=Home&amp;a=createAccount">Créer un compte</a>
                        <a href="?c=Home&amp;a=login">SE CONNECTER</a>
                    </div>

                </div>

            </div>
        </section>

        <footer>
            <div id="div-footer">
                <img src="img/grinoire/footer-logo.png"/>
                <p>©2018 OBJECTIF 3W. TOUS DROITS RÉSERVÉS.<br>
                    Toutes les marques citées appartiennent à leur propriétaire.
                </p>
                <img src="img/grinoire/footer-pegy.png"/>
            </div>
        </footer>

    </div>

</div>

