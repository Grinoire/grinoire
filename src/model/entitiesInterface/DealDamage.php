<?php
declare(strict_types= 1);

namespace grinoire\src\model\entitiesInterface;


/**
 *  Ajoute des methodes au Hero et aux Card pour gere les dommages
 */
trait DealDamage {

    /**
     * Valeur de retour coup normal
     * @var  int
     */
    protected $hit = 1;

    /**
     * Valeur de retour si la cible est morte
     * @var  int
     */
    protected $dead = 2;


    /**
     * Si on ne ce tape pas nous meme, on ajoute les dommage a la cible
     * @param   mixed  $target  Cible
     * @return  int
     */
    function giveDamage($target) :int {
        //si on ne ce tape pas nous meme
        if ($target->getId() !== $this->getId()) {
            return $target->receiveDamage($this->getAttack());
        } else {
            // TODO: return something?????
        }
    }

    /**
     * Ajoute des dommage, selon la valeur d'attaque en parametre
     *
     * Si mort return 2 else return 1
     *
     * @param   int   $value    valeur d'attaque a ajoute au dommage subi
     * @return  int
     */
    function receiveDamage(int $value) :int {
        $this->setDamageReceived($this->getDamageReceived() + $value);
        if  ($this->getDamageReceived() >= $this->getLife()) {
            return $this->dead;
        }
        return $this->hit;
    }


    /**
     * Verifie si la cible est bien une instance valide
     * @param   mixed   $target   Cible
     * @return  bool
     */
    function isValidTarget($target) :bool {
        if (gettype($target) === 'object') {
            return true; //// TODO: Test insuffisant???
        }
        return false;
    }
}
