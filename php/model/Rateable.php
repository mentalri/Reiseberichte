<?php
class Rateable {
    protected int $id;
    protected Profile $user;
    protected array $ratings;
    protected array $comments;

    public function __construct($id, $user) {
        $this->id = $id;
        $this->user = $user;
        $this->ratings = [];
        $this->comments = [];
    }

    public function getId(): int{
        return $this->id;
    }

    public function getUser(): Profile{
        return $this->user;
    }
    public function addRating($rating): void
    {
        $this->ratings[] = $rating;
    }
    public function removeRating($ratingId): void
    {
        foreach ($this->ratings as $key => $rating) {
            if ($rating->getId() == $ratingId) {
                unset($this->ratings[$key]);
                break;
            }
        }
    }
    public function getRatings(): array
    {
        return $this->ratings;
    }
    public function getRating(): float|int
    {
        
        $sum = array_reduce($this->ratings, function ($carry, $item) {
            return $carry + $item->getRating();
        }, 0);
        $count = count($this->ratings);
        return $count > 0 ? $sum / $count : 0;
    }
    public function addComment($comment): void
    {
        $this->comments[] = $comment;
    }
    public function removeComment($commentId): void
    {
        foreach ($this->comments as $key => $comment) {
            if ($comment->getId() == $commentId) {
                unset($this->comments[$key]);
                break;
            }
        }
    }
    public function getComments(): array
    {
        return $this->comments;
    }
}
?>