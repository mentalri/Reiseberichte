<?php 
require_once 'Rateable.php';
class Comment extends Rateable {
    private $text;
    private $date;

    public function __construct($id, $user, $text, $date) {
        parent::__construct($id, $user);
        $this->text = $text;
        $this->date = $date;
    }

    public function getId() {
        return $this->id;
    }

    public function getUser() {
        return $this->user;
    }

    public function getText() {
        return $this->text;
    }

    public function getDate() {
        return $this->date;
    }
}

?>