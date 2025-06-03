<?php
namespace php\model\reports;

use php\model\profiles\Profile;

require_once 'Rateable.php';
class Comment extends Rateable {
    private int $rateableId; // ID of the report or comment being commented on
    private string $rateableType; // Type of the rateable entity (e.g., 'report', 'comment')
    private string $text;
    public function __construct(int $id, int $rateableId,string $rateableType, Profile $user, string $text, int $date) {
        parent::__construct($id, $user,$date);
        $this->rateableId = $rateableId;
        $this->rateableType = $rateableType;
        $this->text = $text;
        $this->date = $date;
    }

    public function getText(): string{
        return $this->text;
    }
    public function getRateableId(): int{
        return $this->rateableId;
    }
    public function getRateableType(): string {
        return $this->rateableType;
    }
}
