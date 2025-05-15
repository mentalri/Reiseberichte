<?php
class Profile {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private array $friends;

    public function __construct($id, $username, $email, $password, $friends = []) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->friends = $friends;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getUsername(): string{
        return $this->username;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function getPassword(): string{
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