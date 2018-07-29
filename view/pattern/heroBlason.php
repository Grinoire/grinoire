<div class="hero" style="background-image: url(img/heros/<?= $hero->getBg() ?>)">
    <span class="hero-life"><?= $hero->getLife() - $hero->getDamageReceived() ?></span>
</div>
