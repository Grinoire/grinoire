<div class="blazon creature" data-id="<?= $card->getId() ?>" style="background-image: url(img/blazon/<?= $card->getBg() ?>)">
    <span class="creature-life"><?= $card->getLife() - $card->getDamageReceived() ?></span>
    <span class="creature-attack"><?= $card->getAttack() ?></span>
    <span class="creature-mana"><?= $card->getMana() ?></span>
</div>
