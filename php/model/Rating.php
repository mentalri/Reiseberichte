<?php
    class Rating {
        private int $id;
        private Profile|null $user;
        private int $rating;

        public function __construct($id, $user, $rating) {
            $this->id = $id;
            $this->user = $user;
            $this->rating = $rating;
        }
        public function getId(): int
        {
            return $this->id;
        }
        public function getUser(): Profile|null
        {
            return $this->user;
        }
        public function getRating(): int
        {
            return $this->rating;
        }
        public function setRating($rating): void
        {
            $this->rating = $rating;
        }
    }
?>