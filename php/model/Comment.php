<?php 
require_once 'Rateable.php';
class Comment extends Rateable {
    private string $text;

    public function __construct($id, $user, $text, $date) {
        parent::__construct($id, $user,$date);
        $this->text = $text;
        $this->date = $date;
    }

    public function getText(): string{
        return $this->text;
    }
}

?>