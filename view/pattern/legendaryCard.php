<div class="card legendary" data-status="<?= $card->getStatus() ?>" data-id="<?= $card->getId() ?>" style="background-image: url(img/cards/<?= $card->getBg() ?>)">
    <span class="legendary-life life"><?= $card->getLife() - $card->getDamageReceived() ?></span>
    <span class="legendary-attack"><?= $card->getAttack() ?></span>
    <span class="legendary-mana "><?= $card->getMana() ?></span>
</div>
