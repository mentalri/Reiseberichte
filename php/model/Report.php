<?php
require_once 'Rateable.php';
class Report extends Rateable
{
    private string $title;
    private string $location;
    private string $description;
    private int $date;
    private array $pictures;
    private array $tags;
    
    public function __construct($id,$author, $date,$title, $location, $description, $pictures, $tags)
    {
        parent::__construct($id, $author);
        $this->id = $id;
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
        $this->date = $date;
        $this->pictures = $pictures;
        $this->tags = $tags;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getTitle(): string{
        return $this->title;
    }
    public function getLocation(): string{
        return $this->location;
    }
    public function getDescription(): string{
        return $this->description;
    }
    public function getPictures(): array{
        return $this->pictures;
    }
    public function getAuthor(): Profile{
        return $this->user;
    }
    public function getDate(): int{
        return $this->date;
    }
    public function getTags(): array
    {
        return $this->tags;
    }
    
    public function update($title, $location, $description, $pictures, $ratings, $comments)
    {
        $this->$title = $title;
        $this->$location = $location;
        $this->$description = $description;;
        $this->pictures = $pictures;
        $this->ratings = $ratings;
        $this->comments = $comments;
    }
}
