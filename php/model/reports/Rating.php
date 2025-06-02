<?php
namespace php\model\reports;
use php\model\profiles\Profile;

class Rating {
    private int $id;
    private int $rateableId; // ID of the report or comment being rated
    private Profile|null $user;
    private int $rating;

    public function __construct($id,$rateableId, $user, $rating) {
        $this->id = $id;
        $this->rateableId = $rateableId;
        $this->user = $user;
        $this->rating = $rating;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getRateableId(): int
    {
        return $this->rateableId;
    }
    public function getUser(): Profile|null
    {
        return $this->user;
    }
    public function getRating(): int
    {
        return $this->rating;
    }
}
