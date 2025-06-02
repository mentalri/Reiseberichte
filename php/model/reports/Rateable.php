<?php
namespace php\model\reports;
use php\model\profiles\Profile;

class Rateable {
    protected int $id;
    protected Profile $user;
    protected int $date;
    /** @var Rating[] */
    protected array $ratings;
    /** @var Comment[] */
    protected array $comments;

    public function __construct(int $id, Profile $user, int $date) {
        $this->id = $id;
        $this->user = $user;
        $this->date = $date;
        $this->ratings = [];
        $this->comments = [];
    }

    public function getId(): int{
        return $this->id;
    }

    public function getUser(): Profile{
        return $this->user;
    }

    public function getDate(): int{
        return $this->date;
    }
    /** @return Rating[] */
    public function &getRatings(): array
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
    /** @return Comment[] */
    public function &getComments(): array
    {
        return $this->comments;
    }
}
