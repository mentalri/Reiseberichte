<?php
require_once 'Rateable.php';
class Report extends Rateable
{
    private $title;
    private $location;
    private $description;
    private $date;
    private $pictures;
    
    public function __construct($id,$author, $date,$title, $location, $description, $pictures=[])
    {
        parent::__construct($id, $author);
        $this->id = $id;
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
        $this->date = $date;
        $this->pictures = $pictures;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getTitle(){
        return $this->title;
    }
    public function getLocation(){
        return $this->location;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getPictures(){
        return $this->pictures;
    }
    public function getAuthor()
    {
        return $this->user;
    }
    public function getDate()
    {
        return $this->date;
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
