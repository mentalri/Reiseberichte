<?php
/**
 * TravelreportsDummy - In-memory implementation of TravelreportsDAO
 * Provides dummy data and session-based persistence for development/testing
 */
class TravelreportsDummy implements TravelreportsDAO
{
    // Singleton implementation
    private static ?TravelreportsDummy $instance = null;
    
    /**
     * Singleton getter - ensures only one instance exists
     */
    public static function getInstance(): ?TravelreportsDummy
    {
        if (self::$instance == null) {
            self::$instance = new TravelreportsDummy();
        }
        return self::$instance;
    }
    
    // Data collections and ID counters
    private array $reports = array();
    private array $comments = array();
    private int $rateable_id = 0;

    private array $profiles = array();
    private int $profile_id = 0;
    
    private array $ratings = array();
    private int $rating_id = 0;
    
    private array $pictures = array();
    private int $picture_id = 0;

    /**
     * Constructor - Initializes data from session or creates dummy data
     * Loads existing data from session if available, otherwise creates sample data
     */
    private function __construct()
    {
        if (isset($_SESSION["reports"]) && isset($_SESSION["profiles"])) {
            // Restore data from session
            $this->reports = unserialize($_SESSION["reports"]);
            $this->profiles = unserialize($_SESSION["profiles"]);
            $this->profile_id = $_SESSION["profile_id"];
            $this->rateable_id = $_SESSION["rateable_id"];
        } else {
            // Create sample users
            $hans = $this->addProfile("Hans","hans@mail.com","Hans12345678");
            $lisa = $this->addProfile("Lisa","lisa@mail.com","Lisa12345678");
            $max = $this->addProfile("Max","max@mail.com","Max12345678");
            $anna = $this->addProfile("Anna","anna@mail.com","Anna12345678");
            $tom = $this->addProfile("Tom","tom@mail.com","Tom12345678");
            $sophie = $this->addProfile("Sophie","sophie@mail.com","Sophie12345678");

            // Create sample follow relationships
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
            
            // Create sample reports
            $this->addReport($hans,strtotime("2023-10-07"),"Ein Tag in London","London,England", "Das ist ein Dummy-Eintrag",["resources/picture_icon.png"],["Stadt"]);
            $this->addReport($lisa,strtotime("2023-10-06"),"Paris mal anders","Paris,Frankreich", "Das ist ein Dummy-Eintrag");
            $this->addReport($max,strtotime("2023-10-05"),"Berlin 2025","Berlin,Deutschland", "Das ist ein Dummy-Eintrag");
            $this->addReport($anna,strtotime("2023-10-04"),"Wiener Schnitzel","Wien,Österreich", "Das ist ein Dummy-Eintrag");
            $this->addReport($hans,strtotime("2023-10-03"),"Die Alpen","Alpen,Österreich", "Das ist ein Dummy-Eintrag");
            $this->addReport($anna,strtotime("2023-10-02"),"Die Nordsee","Nordsee,Deutschland", "Das ist ein Dummy-Eintrag");
            
            // Add sample ratings and comments
            $this->createRating($this->reports[0]->getId(),0, 5);
            $this->createRating($this->reports[0]->getId(),1, 4);
            $this->createComment($this->reports[0]->getId(),0, "Das ist ein Dummy-Kommentar");
            $this->createComment($this->reports[0]->getId(),1, "Das ist ein Dummy-Kommentar");
        }
    }
    
    /**
     * Destructor - Saves data to session when object is destroyed
     */
    public function __destruct()
    {
        $_SESSION["reports"] = serialize($this->reports);
        $_SESSION["profiles"] = serialize($this->profiles);
        $_SESSION["profile_id"] = $this->profile_id;
        $_SESSION["rateable_id"] = $this->rateable_id;
    }
    
    /**
     * Report retrieval with comprehensive filtering options
     */
    public function getReports($location, $perimeter, $rating, $tags, $date, $date2, $sorting, $count, $page, $authors): array
    {
        $filteredReports = $this->reports;
        
        // Apply various filters sequentially
        if (isset($authors)) {
            // Filter by author
            $filteredReports = array_filter($filteredReports, function ($report) use ($authors) {
                return in_array($report->getAuthor()->getId(), $authors);
            });
        }
        
        if (isset($location)) {
            // Filter by location
            $filteredReports = array_filter($filteredReports, function ($report) use ($location) {
                return str_contains($report->getLocation(), $location);
            });
        }
        
        if (isset($rating)) {
            // Filter by minimum rating
            $filteredReports = array_filter($filteredReports, function ($report) use ($rating) {
                return $report->getRating() >= $rating;
            });
        }
        
        if (isset($tags)) {
            // Filter by tags
            $filteredReports = array_filter($filteredReports, function ($report) use ($tags) {
                foreach ($tags as $tag) {
                    if (in_array($tag, $report->getTags())) {
                        return true;
                    }
                }
                return false;
            });
        }

        if (!empty($date) && !empty($date2)) {
            // Filter by date range
            $startDate = strtotime($date);
            $endDate = strtotime($date2);

            $filteredReports = array_filter($filteredReports, function ($report) use ($startDate, $endDate) {
                $reportDate = $report->getDate();
                return $reportDate >= $startDate && $reportDate <= $endDate;
            });
        }

        // Apply sorting
        $this->sortReports($sorting, $filteredReports);
        
        // Apply pagination
        if (!empty($count) && is_numeric($count) && $count > 0) {
            $filteredReports = array_slice($filteredReports, $count*$page, $count);
        }

        return $filteredReports;
    }
    
    /**
     * Helper method to sort reports by different criteria
     */
    private function sortReports($sorting, &$filteredReports): void
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
    
    /**
     * Get reports rated by a specific user
     */
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
     * Get a single report by ID
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

    /**
     * Create a new report
     */
    public function addReport($author, $date, $title, $location, $description, $pictures=["resources/picture_icon.png"], $tags=[]): Report
    {
        $report = new Report($this->rateable_id++, $author, $date, $title, $location, $description, $pictures, $tags);
        $this->reports[] = $report;
        return $report;
    }

    /**
     * Update a report (not implemented)
     */
    public function updateReport($report_id)
    {
        throw new InternalErrorException("Not implemented yet");
    }

    /**
     * Delete a report by ID
     */
    public function deleteReport($id): void
    {
        foreach ($this->reports as $key => $entry) {
            if ($entry->getId() == $id) {
                unset($this->reports[$key]);
                return;
            }
        }
        throw new MissingEntryException("No entry found with rateable_id: " . $id);
    }
    
    /**
     * Profile management methods
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    public function getProfile($id): Profile
    {
        foreach ($this->profiles as $entry) {
            if ($entry->getId() == $id) {
                return $entry;
            }
        }
        throw new MissingEntryException("No entry found with profile_ID: " . $id);
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

    public function updateProfile($id, $username, $email, $password): void
    {
        foreach ($this->profiles as &$entry) {
            if ($entry->getId() == $id) {
                $entry = new Profile($id, $username, $email, $password);
                return;
            }
        }
        throw new MissingEntryException("No entry found with profile_ID: " . $id);
    }
    
    public function deleteProfile($id): void
    {
        foreach ($this->profiles as $key => $entry) {
            if ($entry->getprofile_Id() == $id) {
                unset($this->profiles[$key]);
                return;
            }
        }
        throw new MissingEntryException("No entry found with profile_ID: " . $id);
    }

    /**
     * Create a comment on a rateable item (report or comment)
     */
    public function createComment($rateable_id, $user_id, $text): void
    {
        // First check if rateable_id is a report
        foreach ($this->reports as $entry) {
            if ($entry->getId() == $rateable_id) {
                $comment = new Comment($this->rateable_id++, $this->getProfile($user_id), $text, time());
                $entry->addComment($comment);
                return;
            }
        }
        
        // Then check if rateable_id is a comment (nested comments)
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
     * Create or update a rating for a rateable item
     */
    public function createRating($rateable_id, $user_id, $rating): void
    {
        // Try to handle rating for reports
        if($this->handleRating($this->reports, $rateable_id, $user_id, $rating)) {
            return;
        }
        
        // Try to handle rating for comments
        if($this->handleRating($this->comments, $rateable_id, $user_id, $rating)) {
            return;
        }
        
        throw new MissingEntryException("No entry found with rateable_id: " . $rateable_id);
    }

    /**
     * Helper method for creating/updating ratings
     * Handles both cases: updating existing rating or creating a new one
     */
    private function handleRating($array, $rateable_id, $user_id, $rating): bool
    {
        foreach ($array as $entry) {
            if ($entry->getId() == $rateable_id) {
                // Check if user already rated this item
                foreach ($entry->getRatings() as $rate) {
                    if ($rate->getUser()->getId() == $user_id) {
                        // Update existing rating
                        $rate->setRating($rating);
                        return true;
                    }
                }
                
                // Create new rating
                $rating = new Rating($this->rating_id++, $this->getProfile($user_id), $rating);
                $entry->addRating($rating);
                return true;
            }
        }
        return false;
    }
}
?>