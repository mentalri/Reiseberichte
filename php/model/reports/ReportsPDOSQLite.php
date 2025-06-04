<?php

namespace php\model\reports;

global $abs_path;

use PDO;
use php\model\DatabaseHandler;
use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;
use php\model\profiles\Profile;
use php\model\profiles\Profiles;
use php\model\reports\ReportsDAO;

require_once $abs_path.'/php/model/DatabaseHandler.php';
require_once $abs_path.'/php/model/profiles/Profiles.php';
require_once $abs_path.'/php/model/profiles/Profile.php';
require_once $abs_path.'/php/model/reports/ReportsDAO.php';
require_once $abs_path.'/php/model/reports/Report.php';
require_once $abs_path.'/php/model/reports/Comment.php';
require_once $abs_path.'/php/model/reports/Rating.php';
require_once $abs_path.'/exceptions.php';

class ReportsPDOSQLite extends DatabaseHandler implements ReportsDAO
{
    public static function getInstance(): ReportsPDOSQLite
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }
    private function __construct()
    {
        //private constructor to enforce singleton pattern
    }

    protected function create(PDO $db): void
    {
        Profiles::getInstance()->getProfiles(); // Ensure Profiles is initialized
        if($this->createTable($db, 'reports', "
            CREATE TABLE IF NOT EXISTS reports (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                author INTEGER NOT NULL,
                date INTEGER NOT NULL,
                title TEXT NOT NULL,
                location TEXT NOT NULL,
                description TEXT NOT NULL,
                pictures TEXT,
                tags TEXT,
               FOREIGN KEY (author) REFERENCES users(id) ON DELETE CASCADE
            )
        ")){
            error_log("Created reports table.");
            $this->addReport(
                1, // author ID
                time(), // current timestamp
                "Erster Bericht", // title
                "Berlin, Deutschland", // location
                "Dies ist der erste Bericht.", // description
                ["resources/picture_icon.png"], // pictures
                ["Stadt", "Sightseeing"] // tags
            );
            $this->addReport(4,strtotime("2023-10-04"),"Wiener Schnitzel","Wien,Österreich", "Das ist ein Dummy-Eintrag");
            $this->addReport(1,strtotime("2023-10-03"),"Die Alpen","Alpen,Österreich", "Das ist ein Dummy-Eintrag");
            $this->addReport(2,strtotime("2023-10-02"),"Die Nordsee","Nordsee,Deutschland", "Das ist ein Dummy-Eintrag");
        }

        $this->createTable($db, 'ratings', "
            CREATE TABLE IF NOT EXISTS ratings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                rateable_id INTEGER NOT NULL,
                rateable_type TEXT NOT NULL,
                user_id INTEGER NOT NULL,
                rating INTEGER NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        $this->createTable($db, 'comments', "
            CREATE TABLE IF NOT EXISTS comments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                rateable_id INTEGER NOT NULL,
                rateable_type TEXT NOT NULL,
                user_id INTEGER NOT NULL,
                text TEXT NOT NULL,
                date INTEGER NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");
    }

    /**
     * @inheritDoc
     * @throws MissingEntryException
     */
    public function getReports($location, $perimeter, $rating, $tags, $date, $date2, $sorting, $count, $page, $authors): array
    {
        $query = "SELECT * FROM reports WHERE 1=1";
        $params = [];

        if ($location) {
            $query .= " AND location = :location";
            $params[':location'] = $location;
        }
        if ($date) {
            $query .= " AND date >= :date";
            $params[':date'] = $date;
        }
        if ($date2) {
            $query .= " AND date <= :date2";
            $params[':date2'] = $date2;
        }
        if ($tags && is_array($tags)) {
            $query .= " AND (";
            foreach ($tags as $i => $tag) {
                $query .= "tags LIKE :tag$i OR ";
                $params[":tag$i"] = "%($tag)%";
            }
            $query = rtrim($query, ' OR ') . ')'; // Remove the last 'OR'
        }
        if ($authors && is_array($authors)) {
            $in = implode(',', array_fill(0, count($authors), '?'));
            $query .= " AND author IN ($in)";
            $params = array_merge($params, $authors);
        }
        if ($rating) {
            $query .= " AND id IN (SELECT rateable_id FROM ratings WHERE rating >= ? AND rateable_type = 'report')";
            $params[] = $rating;
        }
        if ($sorting) { // rating missing
            if ($sorting=='date_asc') {
                $query .= " ORDER BY date ASC";
            } else {
                $query .= " ORDER BY date DESC"; // default
            }
        }
        if ($count && $page) {
            $offset = ($page - 1) * $count;
            $query .= " LIMIT ? OFFSET ?";
            $params[] = (int)$count;
            $params[] = (int)$offset;
        }

        $results = $this->select($this->getConnection(),$query, array_values($params));
        $reports = array_map(fn($row) => new Report(
            $row['id'],
            Profiles::getInstance()->getProfile($row['author']),
            $row['date'],
            $row['title'],
            $row['location'],
            $row['description'],
            explode(',', $row['pictures'] ?? ''),
            array_map(fn($str)=>trim($str,"()"), explode(',', $row['tags'] ?? ''))
        ), $results);
        // Load ratings and comments for each report
        foreach ($reports as $report) {
            foreach ($this->loadRatingByRateableId($report->getId(), 'report') as $rating) {
                $report->getRatings()[] = $rating;
            }
            foreach ($this->loadCommentsByRateableId($report->getId(), 'report') as $comment) {
                $report->getComments()[] = $comment;
            }
        }
        if($sorting && str_contains($sorting, 'rating')) {
            if($sorting == 'rating_asc') {
                usort($reports, fn($a, $b) => $a->getRating() <=> $b->getRating());
            } else {
                usort($reports, fn($a, $b) => $b->getRating() <=> $a->getRating());
            }
        }
        return $reports;
    }

    /**
     * @inheritDoc
     */
    public function getReport($id): Report
    {
        $query = "SELECT * FROM reports WHERE id = ?";
        $result = $this->select($this->getConnection(),$query, [$id]);

        if (empty($result)) {
            throw new MissingEntryException("Report with ID $id not found.");
        }

        $row = $result[0];
        $report =  new Report(
            $row['id'],
            Profiles::getInstance()->getProfile($row['author']),
            $row['date'],
            $row['title'],
            $row['location'],
            $row['description'],
            explode(',', $row['pictures'] ?? ''),
            array_map(fn($str)=>trim($str,"()"), explode(',', $row['tags'] ?? ''))
        );
        // Load ratings and comments for the report
        foreach ($this->loadRatingByRateableId($id, 'report') as $rating) {
            $report->getRatings()[] = $rating;
        }
        foreach ($this->loadCommentsByRateableId($id, 'report') as $comment) {
            $report->getComments()[] = $comment;
        }
        return $report;
    }

    /**
     * Loads all ratings for a given rateable_id and rateable_type.
     * @param int $rateable_id
     * @param string $rateable_type
     * @return array
     * @throws InternalErrorException|MissingEntryException
     */
    private function loadRatingByRateableId(int $rateable_id, string $rateable_type): array
    {
        $db = $this->getConnection();
        $query = "SELECT * FROM ratings WHERE rateable_id = ? AND rateable_type = ?";
        $results = $this->select($db, $query, [$rateable_id, $rateable_type]);
        return array_map(function($row) {
            return new Rating(
                $row['id'],
                $row['rateable_id'],
                $row['rateable_type'],
                Profiles::getInstance()->getProfile($row['user_id']),
                $row['rating']
            );
        }, $results);
    }
    /**
     * Loads all comments for a given rateable_id and rateable_type.
     * @param int $rateable_id
     * @param string $rateable_type
     * @return array
     * @throws InternalErrorException|MissingEntryException
     */
    private function loadCommentsByRateableId(int $rateable_id, string $rateable_type): array
    {
        $db = $this->getConnection();
        $query = "SELECT * FROM comments WHERE rateable_id = ? AND rateable_type = ?";
        $results = $this->select($db, $query, [$rateable_id, $rateable_type]);
        $comments = array_map(function($row) {
            return new Comment(
                $row['id'],
                $row['rateable_id'],
                $row['rateable_type'],
                Profiles::getInstance()->getProfile($row['user_id']),
                $row['text'],
                $row['date']
            );
        }, $results);
        // Load ratings for each comment
        foreach ($comments as $comment) {
            foreach ($this->loadRatingByRateableId($comment->getId(), 'comment') as $rating) {
                $comment->getRatings()[] = $rating;
            }
        }
        //Load all comments for each comment
        foreach ($comments as $comment) {
            foreach ($this->loadCommentsByRateableId($comment->getId(), 'comment') as $subComment) {
                $comment->getComments()[] = $subComment;
            }
        }
        return $comments;
    }
    /**
     * @inheritDoc
     */
    public function getRatedReports($profile_id): array
    {
        $query = "SELECT * FROM reports WHERE id IN (SELECT rateable_id FROM ratings WHERE user_id = ? AND rateable_type = 'report')";
        $results = $this->select($this->getConnection(),$query, [$profile_id]);

        return array_map(fn($row) => new Report(
            $row['id'],
            Profiles::getInstance()->getProfile($row['author']),
            $row['date'],
            $row['title'],
            $row['location'],
            $row['description'],
            explode(',', $row['pictures'] ?? ''),
            array_map(fn($str)=>trim($str,"()"), explode(',', $row['tags'] ?? ''))
        ), $results);
    }

    /**
     * @param $author
     * @param $date
     * @param $title
     * @param $location
     * @param $description
     * @param string[] $pictures
     * @param array $tags     * @inheritDoc
     */
    public function addReport($author_id, $date, $title, $location, $description, $pictures=["resources/picture_icon.png"], $tags = []): int
    {
        $db = $this->getConnection();
        $query = "INSERT INTO reports (author, date, title, location, description, pictures, tags) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $pictures_str = implode(',', $pictures);
        $tags_str = implode(',', array_map(fn($tag) => "($tag)", $tags)); // Wrap each tag in parentheses

        $this->insert($db,$query, [
            $author_id,
            $date,
            $title,
            $location,
            $description,
            $pictures_str,
            $tags_str
        ]);

        return (int)$db->lastInsertId();
    }

    /**
     * @inheritDoc
     */
    public function deleteReport($id): void
    {
        $db = $this->getConnection();
        $query = "DELETE FROM reports WHERE id = ?";
        $this->delete($db,$query, [$id]);

        try {
            // Also delete associated ratings and comments
            $this->delete($db,"DELETE FROM ratings WHERE rateable_id = ? AND rateable_type = 'report'", [$id]);
            $this->delete($db,"DELETE FROM comments WHERE rateable_id = ? AND rateable_type = 'report'", [$id]);
        }catch (MissingEntryException $e) {
            // If no ratings or comments exist, we can ignore this exception
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteProfileDataFromReports($id): void
    {
        $db = $this->getConnection();
        // Delete all reports authored by the profile
        $result = $this->select($db,"SELECT id FROM reports WHERE author = ?", [$id]);
        foreach ($result as $row) {
            $this->deleteReport($row['id']);
        }

        try {
            // Delete all ratings and comments made by the profile
            $this->delete($db,"DELETE FROM ratings WHERE user_id = ?", [$id]);
            $this->delete($db,"DELETE FROM comments WHERE user_id = ?", [$id]);
        }catch (MissingEntryException $e) {
            // If no ratings or comments exist, we can ignore this exception
        }

    }

    /**
     * @inheritDoc
     */
    public function createComment($rateable_id,$rateable_type, $user_id, $text): void
    {
        $db = $this->getConnection();
        $query = "INSERT INTO comments (rateable_id, rateable_type, user_id, text, date) VALUES (?, ?, ?, ?, ?)";
        $this->insert($db,$query, [
            $rateable_id,
            $rateable_type,
            $user_id,
            $text,
            time() // current timestamp
        ]);
    }

    /**
     * @inheritDoc
     */
    public function createRating($rateable_id,$rateable_type, $user_id, $rating): void
    {
        $db = $this->getConnection();
        $result = $this->select($db,"SELECT id FROM ratings WHERE rateable_id = ? AND rateable_type = ? AND user_id = ?", [
            $rateable_id,
            $rateable_type,
            $user_id
        ]);
        if (!empty($result)) {
            // Update existing rating
            $rating_id = $result[0]['id'];
            $this->updateRating($rating_id, $rating);
            return;
        }
        $query = "INSERT INTO ratings (rateable_id, rateable_type, user_id, rating) VALUES (?, ?, ?, ?)";
        $this->insert($db,$query, [
            $rateable_id,
            $rateable_type,
            $user_id,
            $rating
        ]);
    }

    /**
     * @inheritDoc
     */
    public function deleteComment($rateable_id): void
    {
        $db = $this->getConnection();
        $query = "DELETE FROM comments WHERE id = ?";
        $this->delete($db,$query, [$rateable_id]);

        $this->delete($db,"DELETE FROM ratings WHERE rateable_id = ? AND rateable_type = 'comment'", [$rateable_id]);
        $this->delete($db,"DELETE FROM comments WHERE rateable_id = ? AND rateable_type = 'comment'", [$rateable_id]);
    }

    /**
     * @inheritDoc
     */
    public function deleteRating($rating_id): void
    {
        $db = $this->getConnection();
        $query = "DELETE FROM ratings WHERE id = ?";
        $this->delete($db,$query, [$rating_id]);
    }

    /**
     * @inheritDoc
     */
    public function updateReport($id, $title, $location, $description, $pictures, $tags): void
    {
        $db = $this->getConnection();
        $query = "UPDATE reports SET title = ?, location = ?, description = ?, pictures = ?, tags = ? WHERE id = ?";
        $pictures_str = implode(',', $pictures);
        $tags_str = implode(',', array_map(fn($tag) => "($tag)", $tags)); // Wrap each tag in parentheses

        $this->update($db,$query, [
            $title,
            $location,
            $description,
            $pictures_str,
            $tags_str,
            $id
        ]);
    }

    /**
     * @inheritDoc
     */
    public function updateRating($id, $rating): void
    {
        $db = $this->getConnection();
        $query = "UPDATE ratings SET rating = ? WHERE id = ?";

        $this->update($db,$query, [
            $rating,
            $id
        ]);
    }
}