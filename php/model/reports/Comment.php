<?php
namespace php\model\reports;

use php\model\profiles\Profile;

require_once 'Rateable.php';
class Comment extends Rateable {
    private int $rateableId; // ID of the report or comment being commented on
    private string $text;
    public function __construct(int $id, int $rateableId, Profile $user, string $text, int $date) {
        parent::__construct($id, $user,$date);
        $this->rateableId = $rateableId;
        $this->text = $text;
        $this->date = $date;
    }

    public function getText(): string{
        return $this->text;
    }
    public function getRateableId(): int{
        return $this->rateableId;
    }
}
