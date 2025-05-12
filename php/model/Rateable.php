<?php 
class Rateable {
    protected $id;
    protected $user;
    protected $ratings;
    protected $comments;

    public function __construct($id, $user) {
        $this->id = $id;
        $this->user = $user;
        $this->ratings = [];
        $this->comments = [];
    }

    public function getId() {
        return $this->id;
    }

    public function getUser() {
        return $this->user;
    }
    public function addRating($rating) {
        $this->ratings[] = $rating;
    }
    public function removeRating($ratingId) {
        foreach ($this->ratings as $key => $rating) {
            if ($rating->getId() == $ratingId) {
                unset($this->rating[$key]);
                break;
            }
        }
    }
    public function getRatings() {
        return $this->ratings;
    }
    public function getRating() {
        
        $sum = array_reduce($this->ratings, function ($carry, $item) {
            return $carry + $item->getRating();
        }, 0);
        $count = count($this->ratings);
        return $count > 0 ? $sum / $count : 0;
    }
    public function addComment($comment) {
        $this->comments[] = $comment;
    }
    public function removeComment($commentId) {
        foreach ($this->comments as $key => $comment) {
            if ($comment->getId() == $commentId) {
                unset($this->comments[$key]);
                break;
            }
        }
    }
    public function getComments() {
        return $this->comments;
    }
}
?>