<?php

declare(strict_types = 1);

/**
 * Description of User
 *
 * @author webuser1801
 */
class User {

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
    /**
     * 
     * ATTRIBUE FORKEY
     * 
     */
    protected $deck;
    protected $role;
    
    

    public function __construct(array $data) {

        $this->hydration($data);
        
    }

    /**
     * 
     * @param array $data
     * 
     */
    private function hydration(array $data): void {
        foreach ($data as $key => $val) {
            $nomSetter = 'set' . ucfirst($key);

            if (method_exists($this, $nomSetter)) {
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

    public function getId() : int {
        return $this->id;
    }

    public function getLastName() : string {
        return $this->lastName;
    }

    public function getFirstName() : string {
        return $this->firstName;
    }

    public function getMail() : string {
        return $this->mail;
    }

    public function getLogin() : string {
        return $this->login;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getInscription() : string {
        return $this->inscription;
    }

    public function getWinnedGame() : int {
        return $this->winnedGame;
    }

    public function getPlayedGame() : int {
        return $this->playedGame;
    }
    public function getDeck() : int {
        return $this->deck;
    }
    public function getRole() : int {
        return $this->role;
    }

    /**
     * 
     * 
     * SETTER
     * 
     * 
     */
    public function setId(int $id) : void {
        $this->id = $id;
    }

    public function setLastName(string $lastName) : void {
        $this->lastName = $lastName;
    }

    public function setFirstName(string $firstName) : void {
        $this->firstName = $firstName;
    }

    public function setMail(string $mail) : void {
        $this->mail = $mail;
    }

    public function setLogin(string $login) : void {
        $this->login = $login;
    }

    public function setPassword(string $password) : void {
        $this->password = $password;
    }

    public function setInscription(string $inscription) : void {
        $this->inscription = $inscription;
    }

    public function setWinnedGame(int $winnedGame) : void {
        $this->winnedGame = $winnedGame;
    }

    public function setPlayedGame(int $playedGame) : void {
        $this->playedGame = $playedGame;
    }
    public function setDeck(int $deck) : void {
        $this->deck = $deck;
    }
    public function setRole(int $role) : void {
        $this->role = $role;
    }

}
