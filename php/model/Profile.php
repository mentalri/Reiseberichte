<?php
class Profile {
    private $id;
    private $username;
    private $email;
    private $password;
    private $friends;

    public function __construct($id, $username, $email, $password, $friends = []) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->friends = $friends;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }
    public function getFriends() {
        return $this->friends;
    }
    public function updateProfile($username, $email, $password) {
        $this->username = $username;
        $this->email = $email;
        if ($password) {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
    }
}
?>