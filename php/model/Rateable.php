<?php
/**
 * Rateable - Abstract base class for content that can be rated and commented on
 * Provides common functionality for ratings and comments management
 */
class Rateable {
    // Core properties
    protected $id;        // Unique identifier
    protected $user;      // Associated user/owner
    protected $ratings;   // Collection of ratings
    protected $comments;  // Collection of comments
    
    /**
     * Constructor - Initializes a rateable object with empty ratings and comments
     */
    public function __construct($id, $user) {
        $this->id = $id;
        $this->user = $user;
        $this->ratings = [];
        $this->comments = [];
    }
    
    /**
     * Basic getter methods
     */
    public function getId() {
        return $this->id;
    }
    
    public function getUser() {
        return $this->user;
    }
    
    /**
     * Rating management methods
     */
    // Add a new rating
    public function addRating($rating) {
        $this->ratings[] = $rating;
    }
    
    // Remove a rating by ID
    public function removeRating($ratingId) {
        foreach ($this->ratings as $key => $rating) {
            if ($rating->getId() == $ratingId) {
                unset($this->rating[$key]);  // Note: Potential bug - should be $this->ratings[$key]
                break;
            }
        }
    }
    
    // Get all ratings
    public function getRatings() {
        return $this->ratings;
    }
    
    /**
     * Calculate average rating
     * Returns 0 if no ratings exist
     */
    public function getRating() {
        $sum = array_reduce($this->ratings, function ($carry, $item) {
            return $carry + $item->getRating();
        }, 0);
        $count = count($this->ratings);
        return $count > 0 ? $sum / $count : 0;
    }
    
    /**
     * Comment management methods
     */
    // Add a new comment
    public function addComment($comment) {
        $this->comments[] = $comment;
    }
    
    // Remove a comment by ID
    public function removeComment($commentId) {
        foreach ($this->comments as $key => $comment) {
            if ($comment->getId() == $commentId) {
                unset($this->comments[$key]);
                break;
            }
        }
    }
    
    // Get all comments
    public function getComments() {
        return $this->comments;
    }
}
?>