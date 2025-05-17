<?php
require_once 'Rateable.php';

/**
 * Report class - Represents a travel report/post
 * Extends Rateable to enable ratings and comments
 */
class Report extends Rateable {
    // Report specific properties
    private string $title;       // Report title
    private string $location;    // Geographic location
    private string $description; // Content/description text
    private int $date;           // Timestamp of creation
    private array $pictures;     // Image file paths
    private array $tags;         // Associated tags/categories
    
    /**
     * Constructor - Creates a new travel report
     */
    public function __construct($id, $author, $date, $title, $location, $description, $pictures, $tags)
    {
        parent::__construct($id, $author);
        $this->id = $id;               // Note: Redundant since parent constructor already sets this
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
        $this->date = $date;
        $this->pictures = $pictures;
        $this->tags = $tags;
    }
    
    /**
     * Getter methods with type declarations
     */
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
    
    public function getPictures(){
        return $this->pictures;
    }
    
    public function getAuthor(){
        return $this->user;
    }
    
    public function getDate(): int{
        return $this->date;
    }
    
    public function getTags(): array
    {
        return $this->tags;
    }
    
    /**
     * Update report content and metadata
     * Note: Contains a bug in property assignments
     */
    public function update($title, $location, $description, $pictures, $ratings, $comments)
    {
        $this->$title = $title;           // Bug: Incorrect syntax, should be $this->title
        $this->$location = $location;     // Bug: Incorrect syntax, should be $this->location
        $this->$description = $description; // Bug: Incorrect syntax, should be $this->description
        $this->pictures = $pictures;
        $this->ratings = $ratings;
        $this->comments = $comments;
    }
}