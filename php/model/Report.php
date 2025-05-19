<?php
require_once 'Rateable.php';
class Report extends Rateable
{
    private string $title;
    private string $location;
    private string $description;
    /** @var string[] */
    private array $pictures;
    /** @var string[] */
    private array $tags;
    
    public function __construct($id, $author, $date, $title, $location, $description, $pictures, $tags)
    {
        parent::__construct($id, $author, $date);
        $this->id = $id;
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
        $this->pictures = $pictures;
        $this->tags = $tags;
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
    /** @return string[] */
    public function getPictures(): array{
        return $this->pictures;
    }
    public function getAuthor(): Profile{
        return $this->getUser();
    }
    public function getTags(): array
    {
        return $this->tags;
    }
    
    public function update($title, $location, $description, $pictures): void
    {
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;;
        $this->pictures = $pictures;
    }
}
