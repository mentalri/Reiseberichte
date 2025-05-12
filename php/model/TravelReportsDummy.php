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
        private static $instance = null;
        public static function getInstance()
        {
            if (self::$instance == null) {
                self::$instance = new TravelreportsDummy();
            }
            return self::$instance;
        }
        
        private $reports = array();
        private $comments = array();
        private $rateable_id = 0;

        private $profiles = array();
        private $profile_id = 0;
        
        private $ratings = array();
        private $rating_id = 0;
        
        private $pictures = array();
        private $picture_id = 0;


        private function __construct()
        {
            if (isset($_SESSION["reports"]) && isset($_SESSION["profiles"])) {
                $this->reports = unserialize($_SESSION["reports"]);
                $this->profiles = unserialize($_SESSION["profiles"]);
                $this->profile_id = $_SESSION["profile_id"];
                $this->rateable_id = $_SESSION["rateable_id"];
            }else{                
                $this->addProfile("Hans","hans@mail.com","Hans12345678");
                $this->addProfile("Lisa","lisa@mail.com","Lisa12345678");
                $this->addProfile("Max","max@mail.com","Max12345678");
                $this->addProfile("Anna","anna@mail.com","Anna12345678");
                $this->addProfile("Tom","tom@mail.com","Tom12345678");
                $this->addProfile("Sophie","sophie@mail.com","Sophie12345678");
                
                $this->addReport($this->getProfile(0),strtotime("2023-10-01"),"Ein Tag in London","London,England", "Das ist ein Dummy-Eintrag");
                $this->addReport($this->getProfile(1),strtotime("2023-10-01"),"Paris mal anders","Paris,Frankreich", "Das ist ein Dummy-Eintrag");
                $this->addReport($this->getProfile(2),strtotime("2023-10-01"),"Berlin 2025","Berlin,Deutschland", "Das ist ein Dummy-Eintrag");
                $this->addReport($this->getProfile(3),strtotime("2023-10-01"),"Wiener Schnitzel","Wien,Österreich", "Das ist ein Dummy-Eintrag");
                $this->addReport($this->getProfile(0),strtotime("2023-10-01"),"Die Alpen","Alpen,Österreich", "Das ist ein Dummy-Eintrag");
                $this->addReport($this->getProfile(3),strtotime("2023-10-01"),"Die Nordsee","Nordsee,Deutschland", "Das ist ein Dummy-Eintrag");
                
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
        public function getReports($sorting, $count, $date, $date2, $author)
        {
            $filteredReports = $this->reports;
            
            // Filter by author if provided
            if (isset($author)) {
                
                $filteredReports = array_filter($filteredReports, function ($report) use ($author) {
                    return $report->getAuthor()->getId() === $author;
                });
            }

            // Filter by date range if both dates are provided
            if (!empty($date) && !empty($date2)) {
                $startDate = strtotime($date);
                $endDate = strtotime($date2);

                $filteredReports = array_filter($filteredReports, function ($report) use ($startDate, $endDate) {
                    if (!isset($report['date'])) return false;
                    $reportDate = strtotime($report['date']);
                    return $reportDate >= $startDate && $reportDate <= $endDate;
                });
            }

            // Limit the number of reports if count is provided and greater than 0
            if (!empty($count) && is_numeric($count) && $count > 0) {
                $filteredReports = array_slice($filteredReports, 0, $count);
            }

            return $filteredReports;
        }
        public function getRatedReports($profile_id)
        {
            $ratedReports = array();
            foreach ($this->reports as $entry) {
                foreach ($entry->getRatings() as $rating) {
                    if ($rating->getUser()->getId() == $profile_id) {
                        array_push($ratedReports, $entry);
                    }
                }
            }
            return $ratedReports;
        }
        public function getReport($rateable_id)
        {
            foreach ($this->reports as $entry) {
                if ($entry->getId() == $rateable_id) {
                    return $entry;
                }
            }
            throw new MissingEntryException("No entry found with rateable_id: " . $rateable_id);
        }

        public function addReport($author, $date, $title, $location, $description,$pictures=["resources/picture_icon.png"])
        {
            $report = new Report($this->rateable_id++, $author, $date, $title, $location, $description,$pictures);
            array_push($this->reports, $report);
            return $report;
        }

        public function updateReport($report_id) 
        {
            throw new InternalErrorException("Not implemented yet");
        }

        public function deleteReport($rateable_id)
        {
            foreach ($this->reports as $key => $entry) {
                if ($entry->getId() == $rateable_id) {
                    unset($this->reports[$key]);
                    return;
                }
            }
            throw new MissingEntryException("No entry found with rateable_id: " . $rateable_id);
        }
        public function getProfiles()
        {
            return $this->profiles;
        }
        public function getProfile($profile_id)
        {
            foreach ($this->profiles as $entry) {
                if ($entry->getId() == $profile_id) {
                    return $entry;
                }
            }
            throw new MissingEntryException("No entry found with profile_ID: " . $profile_id);
        }
        public function getProfileByEmail($email)
        {
            $email = strtolower($email);
            foreach ($this->profiles as $entry) {
                if ($entry->getEmail() == $email) {
                    return $entry;
                }
            }
            throw new MissingEntryException("No entry found with email: " . $email);
        }
        public function addProfile($username, $email, $password)
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
        public function updateProfile($id, $username, $email, $password)
        {
            foreach ($this->profiles as &$entry) {
                if ($entry->getId() == $id) {
                    $entry = new Profile($id, $username, $email, $password);
                    return;
                }
            }
            throw new MissingEntryException("No entry found with profile_ID: " . $id);
        }
        public function deleteProfile($profile_id)
        {
            foreach ($this->profiles as $key => $entry) {
                if ($entry->getprofile_Id() == $profile_id) {
                    unset($this->profiles[$key]);
                    return;
                }
            }
            throw new MissingEntryException("No entry found with profile_ID: " . $profile_id);
        }

        public function createComment($rateable_id, $user_id, $text){
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
        public function createRating($rateable_id, $user_id, $rating)
        {
            foreach ($this->reports as $entry) {
                if ($entry->getId() == $rateable_id) {
                    foreach ($entry->getRatings() as $rate) {
                        if ($rate->getUser()->getId() == $user_id) {
                            throw new InternalErrorException("User already rated this report");
                        }
                    }
                    $rating = new Rating($this->rating_id++, $this->getProfile($user_id), $rating);
                    $entry->addRating($rating);
                    return;
                }
            }
            foreach ($this->comments as $entry) {
                if ($entry->getId() == $rateable_id) {
                    foreach ($entry->getRatings() as $rate) {
                        if ($rate->getUser()->getId() == $user_id) {
                            throw new InternalErrorException("User already rated this comment");
                        }
                    }
                    $rating = new Rating($this->rating_id++, $this->getProfile($user_id), $rating);
                    $entry->addRating($rating);
                    return;
                }
            }
            throw new MissingEntryException("No entry found with rateable_id: " . $rateable_id);
        }
    }
?>