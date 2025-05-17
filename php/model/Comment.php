<?php
require_once 'Rateable.php';

/**
 * Comment class - Represents user comments on travel reports
 * Extends the Rateable class to inherit rating functionality
 */
class Comment extends Rateable {
    // Properties
    private $text;    // The comment content
    private $date;    // Timestamp when comment was created
    
    /**
     * Constructor - Creates a new comment object
     * 
     * @param int $id Unique identifier for the comment
     * @param User $user The user who created the comment
     * @param string $text The content of the comment
     * @param int $date Timestamp of when the comment was created
     */
    public function __construct($id, $user, $text, $date) {
        parent::__construct($id, $user);  // Call parent constructor for ID and user
        $this->text = $text;
        $this->date = $date;
    }
    
    // Getter methods
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