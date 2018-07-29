<div class="card creature" style="background-image: url(img/cards/<?= $card->getBg() ?>)">
    <span class="creature-life"><?= $card->getLife() - $card->getDamageReceived() ?></span>
    <span class="creature-attack"><?= $card->getAttack() ?></span>
    <span class="creature-mana"><?= $card->getMana() ?></span>
</div>
