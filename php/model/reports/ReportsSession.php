<?php

namespace php\model\reports;

global $abs_path;

use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;
use php\model\profiles\Profiles;

require_once $abs_path.'/php/model/profiles/Profiles.php';
require_once $abs_path.'/php/model/profiles/Profile.php';
require_once $abs_path.'/php/model/reports/ReportsDAO.php';
require_once $abs_path.'/php/model/reports/Report.php';
require_once $abs_path.'/php/model/reports/Comment.php';
require_once $abs_path.'/php/model/reports/Rating.php';
require_once $abs_path.'/php/model/exceptions/InternalErrorException.php';
require_once $abs_path.'/php/model/exceptions/MissingEntryException.php';


class ReportsSession implements ReportsDAO
{
    const NO_ENTRY_FOUND_WITH_RATEABLE_ID = "No entry found with rateable_id: ";
    private static ReportsSession $instance;
    public static function getInstance(): ReportsSession
    {
        if (!isset(self::$instance)) {
            self::$instance = new ReportsSession();
        }
        return self::$instance;
    }

    /**
     * @var Report[]
     */
    private array $reports = [];
    /** @var Comment[] */
    private array $comments = [];
    private int $rateableId = 0;
    /** @var Rating[] */
    private array $ratings = [];
    private int $ratingId = 0;
    /** @var string[] */
    private array $images = [];
    /** @var string[] */
    private array $tags = [];
    private function __construct()
    {
        if (!isset($_SESSION['reports'])) {
            $profiles = Profiles::getInstance();
            try {
                $this->addReport($profiles->getProfile(0),strtotime("2023-10-07"),"Ein Tag in London","London,England", "Das ist ein Dummy-Eintrag",["resources/picture_icon.png"],["Stadt"]);
                $this->addReport($profiles->getProfile(1),strtotime("2023-10-06"),"Paris mal anders","Paris,Frankreich", "Das ist ein Dummy-Eintrag");
                $this->addReport($profiles->getProfile(2),strtotime("2023-10-05"),"Berlin 2025","Berlin,Deutschland", "Das ist ein Dummy-Eintrag");
                $this->addReport($profiles->getProfile(3),strtotime("2023-10-04"),"Wiener Schnitzel","Wien,Österreich", "Das ist ein Dummy-Eintrag");
                $this->addReport($profiles->getProfile(0),strtotime("2023-10-03"),"Die Alpen","Alpen,Österreich", "Das ist ein Dummy-Eintrag");
                $this->addReport($profiles->getProfile(1),strtotime("2023-10-02"),"Die Nordsee","Nordsee,Deutschland", "Das ist ein Dummy-Eintrag");

                $this->createRating($this->reports[0]->getId(),0, 5);
                $this->createRating($this->reports[0]->getId(),1, 4);
                $this->createComment($this->reports[0]->getId(),0, "Das ist ein Dummy-Kommentar");
                $this->createComment($this->reports[0]->getId(),1, "Das ist ein Dummy-Kommentar");
            }catch (MissingEntryException | InternalErrorException) {
                // This should never happen, as we are creating the reports in the constructor
            }

        } else {
            $this->reports = unserialize($_SESSION['reports']);
            $this->comments = unserialize($_SESSION['comments']);
            $this->ratings = unserialize($_SESSION['ratings']);
            $this->images = unserialize($_SESSION['images']);
            $this->tags = unserialize($_SESSION['tags']);
            $this->rateableId = count($this->reports) + count($this->comments);
            $this->ratingId = count($this->ratings);
            $profilesDao = Profiles::getInstance();
            foreach ($this->reports as $report) {
                $this->reports[$report->getId()] = new Report(
                    $report->getId(),
                    $profilesDao->getProfile($report->getAuthor()->getId()),
                    $report->getDate(),
                    $report->getTitle(),
                    $report->getLocation(),
                    $report->getDescription(),
                    [],
                    []
                );
            }
            foreach ($this->comments as $comment) {
                $this->comments[$comment->getId()] = new Comment(
                    $comment->getId(),
                    $comment->getRateableId(),
                    $profilesDao->getProfile($comment->getUser()->getId()),
                    $comment->getText(),
                    $comment->getDate()
                );
            }
            foreach ($this->ratings as $rating) {
                $this->ratings[$rating->getId()] = new Rating(
                    $rating->getId(),
                    $rating->getRateableId(),
                    $profilesDao->getProfile($rating->getUser()->getId()),
                    $rating->getRating()
                );
            }
            foreach ($this->comments as $comment) {
                foreach ($this->reports as $report) {
                    if ($report->getId() == $comment->getRateableId()) {
                        $report->getComments()[] = $comment;
                        continue 2;
                    }
                }
                foreach ($this->comments as $c) {
                    if ($c->getId() == $comment->getRateableId()) {
                        $c->getComments()[] = $comment;
                        continue 2;
                    }
                }
            }
            foreach ($this->ratings as $rating) {
                foreach ($this->reports as $report) {
                    if ($report->getId() == $rating->getRateableId()) {
                        $report->getRatings()[] = $rating;
                        continue 2;
                    }
                }
                foreach ($this->comments as $c) {
                    if ($c->getId() == $rating->getRateableId()) {
                        $c->getRatings()[] = $rating;
                        continue 2;
                    }
                }
            }
            foreach ($this->images as $image) {
                foreach ($this->reports as $report) {
                    if ($report->getId() == $image['id']) {
                        $report->getPictures()[] = $image['path'];
                        continue 2;
                    }
                }
            }
            foreach ($this->tags as $tag) {
                foreach ($this->reports as $report) {
                    if ($report->getId() == $tag['id']) {
                        $report->getTags()[] = $tag['tag'];
                        continue 2;
                    }
                }
            }
        }
    }
    public function __destruct()
    {
        $_SESSION['reports'] = serialize($this->reports);
        $_SESSION['comments'] = serialize($this->comments);
        $_SESSION['ratings'] = serialize($this->ratings);
        $_SESSION['images'] = serialize($this->images);
        $_SESSION['tags'] = serialize($this->tags);
        $_SESSION['rateableId'] = $this->rateableId;
        $_SESSION['ratingId'] = $this->ratingId;
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
                default:
                    return;
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
                    continue 2; // Skip to the next report if a rating is found
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
        throw new MissingEntryException(self::NO_ENTRY_FOUND_WITH_RATEABLE_ID . $id);
    }

    public function addReport($author, $date, $title, $location, $description,$pictures=["resources/picture_icon.png"], $tags=[]): Report
    {
        $report = new Report($this->rateableId++, $author, $date, $title, $location, $description,$pictures,$tags);
        $this->reports[$report->getId()] = $report;
        foreach ($pictures as $picture) {
            $this->images[] = ['id' => $report->getId(), 'path' => $picture];
        }
        foreach ($tags as $tag) {
            $this->tags[] = ['id' => $report->getId(), 'tag' => $tag];
        }
        return $report;
    }

    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteReport($id): void
    {
        $report = $this->getReport($id);
        unset($this->reports[$report->getId()]);
        foreach ($report->getComments() as $comment) {
            if ($comment->getRateableId() == $id) {
                $this->deleteComment($comment->getId());
            }
        }
        foreach ($this->ratings as $key => $rating) {
            if ($rating->getRateableId() == $id) {
                $this->deleteRating($rating->getId());
            }
        }
        foreach ($this->images as $key => $image) {
            if ($image['id'] == $id) {
                unset($this->images[$key]);
            }
        }
        foreach ($this->tags as $key => $tag) {
            if ($tag['id'] == $id) {
                unset($this->tags[$key]);
            }
        }
    }

    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteProfileDataFromReports($id): void
    {
        foreach ($this->reports as $report) {
            if ($report->getAuthor()->getId() == $id) {
                $this->deleteReport($report->getId());
            }
        }
        foreach ($this->comments as $key => $comment) {
            if ($comment->getUser()->getId() == $id) {
                $this->deleteComment($comment->getId());
            }
        }
        foreach ($this->ratings as $key => $rating) {
            if ($rating->getUser()->getId() == $id) {
                unset($this->ratings[$key]);
            }
        }
    }

    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function createComment($rateable_id, $user_id, $text): void
    {
        if(!isset($this->reports[$rateable_id]) && !isset($this->comments[$rateable_id])) {
            throw new MissingEntryException(self::NO_ENTRY_FOUND_WITH_RATEABLE_ID . $rateable_id);
        }
        $comment = new Comment($this->rateableId++,$rateable_id, Profiles::getInstance()->getProfile($user_id), $text, time());
        $this->comments[$comment->getId()] = $comment;
    }

    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function createRating($rateable_id, $user_id, $rating): void
    {
        if($this->handleRating($this->reports,$rateable_id, $user_id,$rating)) {return;}
        if($this->handleRating($this->comments,$rateable_id, $user_id,$rating)) {return;}
        throw new MissingEntryException(self::NO_ENTRY_FOUND_WITH_RATEABLE_ID . $rateable_id);
    }

    /**
     * @var Rateable[] $array
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    private function handleRating(array $array, int $rateable_id, int $user_id, int $rating): bool
    {

        foreach ($array as $entry) {
            if ($entry->getId() == $rateable_id) {
                foreach ($entry->getRatings() as $rate) {
                    if ($rate->getUser()->getId() == $user_id) {
                        $this->updateRating($rate->getId(), $rating);
                        return true;
                    }
                }
                $rating = new Rating($this->ratingId++,$rateable_id, Profiles::getInstance()->getProfile($user_id), $rating);
                $this->ratings[$rating->getId()] = $rating;
                return true;
            }
        }
        return false;
    }

    public function deleteComment($rateable_id): void
    {
        if (!isset($this->comments[$rateable_id])) {
            throw new MissingEntryException(self::NO_ENTRY_FOUND_WITH_RATEABLE_ID . $rateable_id);
        }
        foreach ($this->comments[$rateable_id]->getComments() as $comment) {
            $this->deleteComment($comment->getId());
        }
        unset($this->comments[$rateable_id]);
    }

    public function deleteRating($rating_id): void
    {
        if (!isset($this->ratings[$rating_id])) {
            throw new MissingEntryException("No entry found with rating_id: " . $rating_id);
        }
        unset($this->ratings[$rating_id]);
    }

    public function updateReport($id, $title, $location, $description, $pictures, $tags): Report
    {
        $report = $this->getReport($id);
        foreach ($this->images as $key => $image) {
            if ($image['id'] == $id) {
                unset($this->images[$key]);
            }
        }
        foreach ($this->tags as $key => $tag) {
            if ($tag['id'] == $id) {
                unset($this->tags[$key]);
            }
        }
        $this->reports[$id]= new Report(
            $id,
            $this->reports[$id]->getAuthor(),
            $this->reports[$id]->getDate(),
            $title,
            $location,
            $description,
            $pictures,
            $tags
        );
        foreach ($pictures as $picture) {
            $this->images[] = ['id' => $report->getId(), 'path' => $picture];
        }
        foreach ($tags as $tag) {
            $this->tags[] = ['id' => $report->getId(), 'tag' => $tag];
        }
        return $this->reports[$id];
    }
    /**
     * @throws MissingEntryException
     */
    public function updateRating($id, $rating): void{

        if(!isset($this->ratings[$id])) {
            throw new MissingEntryException("No entry found with rating_id: " . $id);
        }
        $this->ratings[$id] = new Rating(
            $id,
            $this->ratings[$id]->getRateableId(),
            $this->ratings[$id]->getUser(),
            $rating
        );
    }

}