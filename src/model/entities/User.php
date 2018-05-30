<?php
declare(strict_types=1);

namespace grinoire\src\model\entities;

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
     * --------------------------------------------------
     *     PROPERTIES
     * ------------------------------------------------------
     */

    /**
     * @var  int
     */
    protected $id;

    /**
     * @var  string
     */
    protected $lastName;

    /**
     * @var  string
     */
    protected $firstName;

    /**
     * @var  string
     */
    protected $mail;

    /**
     * @var  string
     */
    protected $login;

    /**
     * @var  string
     */
    protected $password;

    /**
     * @var  string
     */
    protected $inscription;

    /**
     * @var  int
     */
    protected $winnedGame;

    /**
     * @var  int
     */
    protected $playedGame;

    /**
     * @var  string
     */
    protected $avatar;

    /**
     * @var  int
     */
    protected $deckIdFk;

    /**
     * @var  int
     */
    protected $roleIdFk;

    /**
     * @var  int
     */
    protected $gameIdFk;

    /**
     * 0 = not ready, 1 = ready
     * @var  int
     */
    protected $ready;


    /**
     * --------------------------------------------------
     *     MAGIC METHOD
     * ------------------------------------------------------
     */

    /**
     * User construct
     * @param  array  $data  Data neede for Hydratation
     */
    public function __construct(array $data)
    {

        $this->hydration($data);

    }


    /**
     * --------------------------------------------------
     *     METHOD
     * ------------------------------------------------------
     */


    /**
     * HYDRATATION USER
     * @param  array  $dataUser
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

    /**
     * --------------------------------------------------
     *     GETTERS
     * ------------------------------------------------------
     */

    /**
     * @return  int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return  string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return  string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return  string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @return  string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @return  string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return  string
     */
    public function getInscription(): string
    {
        return $this->inscription;
    }

    /**
     * @return  int|null
     */
    public function getWinnedGame(): ?int
    {
        return $this->winnedGame;
    }

    /**
     * @return  int|null
     */
    public function getPlayedGame(): ?int
    {
        return $this->playedGame;
    }

    /**
     * @return  int
     */
    public function getDeckIdFk(): int
    {
        return $this->deckIdFk;
    }

    /**
     * @return  int
     */
    public function getRoleIdFk(): int
    {
        return $this->roleIdFk;
    }

    /**
     * @return  string|null
     */
    public function getAvatar() : ?string
    {
        return $this->avatar;
    }


    /**
     * @return int|null
     */
    public function getGameIdFk(): ?int
    {
        return $this->gameIdFk;
    }

    /**
     * @return int
     */
    public function getReady(): int
    {
        return $this->ready;
    }


    /**
     * --------------------------------------------------
     *     SETTERS
     * ------------------------------------------------------
     */

    /**
     * @param  int  $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param  string|null  $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param  string|null  $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param  string  $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @param  string|null  $login
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * @param  string  $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param  string  $inscription
     */
    public function setInscription(string $inscription): void
    {
        $this->inscription = $inscription;
    }

    /**
     * @param  int|null  $winnedGame
     */
    public function setWinnedGame(?int $winnedGame): void
    {
        $this->winnedGame = $winnedGame;
    }

    /**
     * @param  int|null   $playedGame
     */
    public function setPlayedGame(?int $playedGame): void
    {
        $this->playedGame = $playedGame;
    }

    /**
     * @param  int|null   $deck
     */
    public function setDeckIdFk(?int $deck): void
    {
        $this->deckIdFk = $deck;
    }

    /**
     * @param  int  $role
     */
    public function setRoleIdFk(int $role): void
    {
        $this->roleIdFk = $role;
    }

    /**
     * @param  string|null  $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }


    /**
     * @param  int|null  $gameIdFk
     * @return static
     */
    public function setGameIdFk(?int $gameIdFk)
    {
        $this->gameIdFk = $gameIdFk;
        return $this;
    }


    /**
     * @param  int      $ready
     * @return static
     */
    public function setReady(int $ready)
    {
        $this->ready = $ready;
        return $this;
    }


}
