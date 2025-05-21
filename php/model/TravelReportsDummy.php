<?php
    if (!isset($abs_path)) {
        require_once "../../path.php";
    }
    require_once $abs_path . "/php/model/Report.php";
    require_once $abs_path . "/php/model/Rating.php";
    require_once $abs_path . "/php/model/Comment.php";
    require_once $abs_path . "/php/model/Profile.php";
    require_once $abs_path . "/php/model/TravelreportsDAO.php";
    class TravelreportsDummy implements TravelreportsDAO
    {
        private static ?TravelreportsDummy $instance = null;
        public static function getInstance(): ?TravelreportsDummy
        {
            if (self::$instance == null) {
                self::$instance = new TravelreportsDummy();
            }
            return self::$instance;
        }
        /* @var Report[] */
        private array $reports = array();
        /** @var Comment[] */
        private array $comments = array();
        private int $rateable_id = 0;

        /** @var Profile[] */
        private array $profiles = array();
        private int $profile_id = 0;
        
        private array $ratings = array();
        private int $rating_id = 0;

        /**
         * @throws InternalErrorException
         * @throws MissingEntryException
         */
        private function __construct()
        {
            if (isset($_SESSION["reports"]) && isset($_SESSION["profiles"])) {
                $this->reports = unserialize($_SESSION["reports"]);
                $this->profiles = unserialize($_SESSION["profiles"]);
                $this->profile_id = $_SESSION["profile_id"];
                $this->rateable_id = $_SESSION["rateable_id"];

                #$this->getProfile(0)->updateProfile("Hans2","hans@mail.com",null);
                //Profile arent updated in the reports, for more details view README.md
                foreach ($this->reports as $report) {
                    $report->setUser($this->getProfile($report->getUser()->getId()));
                }
            }else{
                $hans = $this->addProfile("Hans","hans@mail.com","Hans12345678");
                $lisa = $this->addProfile("Lisa","lisa@mail.com","Lisa12345678");
                $max = $this->addProfile("Max","max@mail.com","Max12345678");
                $anna = $this->addProfile("Anna","anna@mail.com","Anna12345678");
                $tom = $this->addProfile("Tom","tom@mail.com","Tom12345678");
                $sophie = $this->addProfile("Sophie","sophie@mail.com","Sophie12345678");

                $hans->follow($lisa);
                $hans->follow($max);
                $hans->follow($anna);
                $hans->follow($tom);
                $lisa->follow($max);
                $lisa->follow($anna);
                $lisa->follow($tom);
                $max->follow($lisa);
                $max->follow($hans);
                $max->follow($sophie);
                
                $this->addReport($hans,strtotime("2023-10-07"),"Ein Tag in London","London,England", "Das ist ein Dummy-Eintrag",["resources/picture_icon.png"],["Stadt"]);
                $this->addReport($lisa,strtotime("2023-10-06"),"Paris mal anders","Paris,Frankreich", "Das ist ein Dummy-Eintrag");
                $this->addReport($max,strtotime("2023-10-05"),"Berlin 2025","Berlin,Deutschland", "Das ist ein Dummy-Eintrag");
                $this->addReport($anna,strtotime("2023-10-04"),"Wiener Schnitzel","Wien,Österreich", "Das ist ein Dummy-Eintrag");
                $this->addReport($hans,strtotime("2023-10-03"),"Die Alpen","Alpen,Österreich", "Das ist ein Dummy-Eintrag");
                $this->addReport($anna,strtotime("2023-10-02"),"Die Nordsee","Nordsee,Deutschland", "Das ist ein Dummy-Eintrag");

                $this->createRating($this->reports[0]->getId(),0, 5);
                $this->createRating($this->reports[0]->getId(),1, 4);
                $this->createComment($this->reports[0]->getId(),0, "Das ist ein Dummy-Kommentar");
                $this->createComment($this->reports[0]->getId(),1, "Das ist ein Dummy-Kommentar");

            }
        }
        public function __destruct()
        {
            $_SESSION["reports"] = serialize($this->reports);
            $_SESSION["profiles"] = serialize($this->profiles);
            $_SESSION["profile_id"] = $this->profile_id;
            $_SESSION["rateable_id"] = $this->rateable_id;
        }
        /** @return Report[] */
        public function getReports($location, $perimeter, $rating, $tags, $date, $date2, $sorting, $count,$page, $authors): array
        {
            $filteredReports = $this->reports;
            // Filter by author if provided
            if (isset($authors)) {

                $filteredReports = array_filter($filteredReports, function ($report) use ($authors) {
                    return in_array($report->getAuthor()->getId(), $authors);
                });
            }
            // Filter by location if provided
            if (isset($location)) {
                $filteredReports = array_filter($filteredReports, function ($report) use ($location) {
                    return str_contains($report->getLocation(), $location);
                });
            }
            // Filter by rating if provided
            if (isset($rating)) {
                $filteredReports = array_filter($filteredReports, function ($report) use ($rating) {
                    return $report->getRating() >= $rating;
                });
            }
            // Filter by tags if provided
            if (isset($tags)) {
                $filteredReports = array_filter($filteredReports, function ($report) use ($tags) {
                    foreach ($tags as $tag) {
                        if (in_array($tag, $report->getTags())) {
                            return true;
                        }
                    }
                    return false;
                });
            }

            // Filter by date range if both dates are provided
            if (!empty($date) && !empty($date2)) {
                $startDate = strtotime($date);
                $endDate = strtotime($date2);

                $filteredReports = array_filter($filteredReports, function ($report) use ($startDate, $endDate) {
                    $reportDate = $report->getDate();
                    return $reportDate >= $startDate && $reportDate <= $endDate;
                });
            }

            // Limit the number of reports if count is provided and greater than 0
            if (!empty($count) && is_numeric($count) && $count > 0) {
                $filteredReports = array_slice($filteredReports, $count*$page, $count);
            }
            // Sort the reports based on the sorting parameter
            $this->sortReports($sorting,$filteredReports);

            return $filteredReports;
        }
        private function sortReports($sorting,&$filteredReports): void
        {
            if(isset($sorting)){
                switch ($sorting) {
                    case "date_desc":
                        usort($filteredReports, function ($a, $b) {
                            return $b->getDate() <=> $a->getDate();
                        });
                        break;
                    case "date_asc":
                        usort($filteredReports, function ($a, $b) {
                            return $a->getDate() <=> $b->getDate();
                        });
                        break;
                    case "rating_desc":
                        usort($filteredReports, function ($a, $b) {
                            return $b->getRating() <=> $a->getRating();
                        });
                        break;
                    case "rating_asc":
                        usort($filteredReports, function ($a, $b) {
                            return $a->getRating() <=> $b->getRating();
                        });
                        break;
                }
            }
        }
        /** @return Report[] */
        public function getRatedReports($profile_id): array
        {
            $ratedReports = array();
            foreach ($this->reports as $entry) {
                foreach ($entry->getRatings() as $rating) {
                    if ($rating->getUser()->getId() == $profile_id) {
                        $ratedReports[] = $entry;
                    }
                }
            }
            return $ratedReports;
        }

        /**
         * @throws MissingEntryException
         */
        public function getReport($id): Report
        {
            foreach ($this->reports as $entry) {
                if ($entry->getId() == $id) {
                    return $entry;
                }
            }
            throw new MissingEntryException("No entry found with rateable_id: " . $id);
        }

        public function addReport($author, $date, $title, $location, $description,$pictures=["resources/picture_icon.png"], $tags=[]): Report
        {
            $report = new Report($this->rateable_id++, $author, $date, $title, $location, $description,$pictures,$tags);
            $this->reports[] = $report;
            return $report;
        }

        /**
         * @throws MissingEntryException
         */
        public function deleteReport($id): void
        {
            foreach ($this->reports as $key => $report) {
                if ($report->getId() == $id) {
                    unset($this->reports[$key]);
                    return;
                }
            }
            throw new MissingEntryException("No entry found with rateable_id: " . $id);
        }
        /** @return Profile[] */
        public function getProfiles():array
        {
            return $this->profiles;
        }

        /**
         * @throws MissingEntryException
         */
        public function getProfile($id): Profile
        {
            foreach ($this->profiles as $entry) {
                if ($entry->getId() == $id) {
                    return $entry;
                }
            }
            throw new MissingEntryException("No entry found with profile_ID: " . $id);
        }

        /**
         * @throws MissingEntryException
         */
        public function getProfileByEmail($email): Profile
        {
            $email = strtolower($email);
            foreach ($this->profiles as $entry) {
                if ($entry->getEmail() == $email) {
                    return $entry;
                }
            }
            throw new MissingEntryException("No entry found with email: " . $email);
        }

        /**
         * @throws InternalErrorException
         */
        public function addProfile($username, $email, $password): Profile
        {
            if (empty($username) || empty($email) || empty($password)) {
                throw new InternalErrorException("Profile is empty");
            }
            foreach ($this->profiles as $entry) {
                if ($entry->getEmail() == strtolower($email)) {
                    throw new InternalErrorException("Profile with email already exists");
                }
            }
            $profile = new Profile($this->profile_id++, $username, strtolower($email), $password);
            $this->profiles[] = $profile;
            return $profile;
        }

        /**
         * @throws MissingEntryException
         */
        public function deleteProfile($id): void
        {
            foreach ($this->profiles as $key => $profile) {
                if ($profile->getId() == $id) {
                    foreach ($this->reports as $report) {
                        if ($report->getAuthor()->getId() == $id) {
                            $this->deleteReport($report->getId());
                        }
                        foreach ($report->getRatings() as $rating) {
                            if ($rating->getUser()->getId() == $id) {
                                $report->removeRating($rating->getId());
                            }
                        }
                        foreach ($report->getComments() as $comment) {
                            if ($comment->getUser()->getId() == $id) {
                                $report->removeComment($comment->getId());
                            }
                        }
                    }
                    foreach ($profile->getFollowing() as $following) {
                        $following->unfollow($profile);
                    }
                    foreach ($profile->getFollowers() as $follower) {
                        $follower->removeFollower($profile);
                    }
                    unset($this->profiles[$key]);
                    return;
                }
            }
            throw new MissingEntryException("No entry found with profile_ID: " . $id);
        }

        /**
         * @throws MissingEntryException
         */
        public function createComment($rateable_id, $user_id, $text): void
        {
            foreach ($this->reports as $entry) {
                if ($entry->getId() == $rateable_id) {
                    $comment = new Comment($this->rateable_id++, $this->getProfile($user_id), $text, time());
                    $entry->addComment($comment);
                    return;
                }
            }
            foreach ($this->comments as $entry) {
                if ($entry->getId() == $rateable_id) {
                    $comment = new Comment($this->rateable_id++, $this->getProfile($user_id), $text, time());
                    $entry->addComment($comment);
                    return;
                }
            }
            throw new MissingEntryException("No entry found with rateable_id: " . $rateable_id);
        }

        /**
         * @throws MissingEntryException
         */
        public function createRating($rateable_id, $user_id, $rating): void
        {
            if($this->handleRating($this->reports,$rateable_id, $user_id,$rating)) {return;}
            if($this->handleRating($this->comments,$rateable_id, $user_id,$rating)) {return;}
            throw new MissingEntryException("No entry found with rateable_id: " . $rateable_id);
        }

        /**
         * @throws MissingEntryException
         */
        private function handleRating($array, $rateable_id, $user_id, $rating): bool
        {
            foreach ($array as $entry) {
                if ($entry->getId() == $rateable_id) {
                    foreach ($entry->getRatings() as $rate) {
                        if ($rate->getUser()->getId() == $user_id) {
                            $rate->setRating($rating);
                            return true;
                        }
                    }
                    $rating = new Rating($this->rating_id++, $this->getProfile($user_id), $rating);
                    $entry->addRating($rating);
                    return true;
                }
            }
            return false;
        }
    }
?>
