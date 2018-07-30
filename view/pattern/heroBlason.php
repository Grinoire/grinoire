<div class="hero" data-id="<?= $hero->getId() ?>" style="background-image: url(img/heros/<?= $hero->getBg() ?>)">
    <span class="hero-life"><?= $hero->getLife() - $hero->getDamageReceived() ?></span>
</div>
