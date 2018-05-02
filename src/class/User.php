<?php

declare(strict_types=1);

/**
 *
 * ATTENTION, en PHP 7.1 quand on utilise le typage, on force le NULL
 * dans les SETTERS avec "   ?    "    <--------    !!!!!!
 * ---------------------------------------------------------
 *
 */
class User
{

    /**
     *
     * ATTRIBUE
     *
     */
    protected $id;
    protected $lastName;
    protected $firstName;
    protected $mail;
    protected $login;
    protected $password;
    protected $inscription;
    protected $winnedGame;
    protected $playedGame;
    protected $avatar;
    /**
     *
     * ATTRIBUE FORKEY
     *
     */
    protected $deckId;
    protected $roleId;


    public function __construct(array $data)
    {

        $this->hydration($data);

    }

    /**
     *
     * @param array $data
     *
     */
    private function hydration(array $dataUser): void
    {

        foreach ($dataUser as $attribut => $val) {

            $listeNomDecoupe = explode('_', $attribut);
            unset($listeNomDecoupe[0]);

            $nomSetter = 'set';

            foreach ($listeNomDecoupe as $nom) {
                $nomSetter .= ucfirst($nom);
            }

            if (method_exists($this, $nomSetter)) {
                if (is_numeric($val) AND $attribut !== 'user_password') {
                    $val = (int)$val;
                }
                $this->$nomSetter($val);
            }
        }
    }

    /*
     * 
     * 
     * GETTER
     * 
     * 
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getInscription(): string
    {
        return $this->inscription;
    }

    public function getWinnedGame(): ?int
    {
        return $this->winnedGame;
    }

    public function getPlayedGame(): ?int
    {
        return $this->playedGame;
    }

    public function getDeckId(): int
    {
        return $this->deck;
    }

    public function getRoleId(): int
    {
        return $this->role;
    }

    public function getAvatar() : ?string
    {
        return $this->avatar;
    }


    /**
     *
     *
     * SETTER
     *
     *
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setInscription(string $inscription): void
    {
        $this->inscription = $inscription;
    }

    public function setWinnedGame(?int $winnedGame): void
    {
        $this->winnedGame = $winnedGame;
    }

    public function setPlayedGame(?int $playedGame): void
    {
        $this->playedGame = $playedGame;
    }

    public function setDeckId(?int $deck): void
    {
        $this->deck = $deck;
    }

    public function setRoleId(int $role): void
    {
        $this->role = $role;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }


}
