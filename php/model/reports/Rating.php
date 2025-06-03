<?php
namespace php\model\reports;
use php\model\profiles\Profile;

class Rating {
    private int $id;
    private int $rateableId; // ID of the report or comment being rated
    private string $rateableType; // Type of the rateable entity (e.g., 'report', 'comment')
    private Profile|null $user;
    private int $rating;

    public function __construct(int $id, int $rateableId, string $rateableType, ?Profile $user, int $rating) {
        $this->id = $id;
        $this->rateableId = $rateableId;
        $this->rateableType = $rateableType;
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
    public function getRateableType(): string {
        return $this->rateableType;
    }
}
