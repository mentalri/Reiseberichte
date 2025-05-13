<?php 
    class Rating {
        private $id;        
        private $user;
        private $rating;

        public function __construct($id, $user, $rating) {
            $this->id = $id;
            $this->user = $user;
            $this->rating = $rating;
        }
        public function getId() {
            return $this->id;
        }
        public function getUser() {
            return $this->user;
        }
        public function getRating() {
            return $this->rating;
        }
        public function setRating($rating) {
            $this->rating = $rating;
        }
    }
?>