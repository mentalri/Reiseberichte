<?php 
class Profile {
    private $id;
    private $username;
    private $email;
    private $password;

    public function __construct($id, $username, $email, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
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
    public function updateProfile($username, $email, $password) {
        $this->username = $username;
        $this->email = $email;
        if ($password) {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
    }
}

?>