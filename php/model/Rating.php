<?php
/**
 * Rating class - Represents a user's rating for content
 * Simple data object that stores rating value with user association
 */
class Rating {
    // Core properties
    private $id;       // Unique identifier
    private $user;     // User who created the rating
    private $rating;   // Numerical rating value
    
    /**
     * Constructor - Creates a new rating object
     * 
     * @param int $id Unique identifier for the rating
     * @param User $user The user who created the rating
     * @param int $rating The numerical rating value
     */
    public function __construct($id, $user, $rating) {
        $this->id = $id;
        $this->user = $user;
        $this->rating = $rating;
    }
    
    /**
     * Getter methods
     */
    public function getId() {
        return $this->id;
    }
    
    public function getUser() {
        return $this->user;
    }
    
    public function getRating() {
        return $this->rating;
    }
    
    /**
     * Update the rating value
     */
    public function setRating($rating) {
        $this->rating = $rating;
    }
}
?>