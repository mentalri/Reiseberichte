<?php

namespace php\model\profiles;

use PDO;
use PDOException;
use php\model\DatabaseHandler;
use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;
use php\model\reports\Reports;

require_once $abs_path.'/php/model/DatabaseHandler.php';
require_once $abs_path.'/php/model/profiles/ProfilesDAO.php';
require_once $abs_path.'/php/model/profiles/Profile.php';
require_once $abs_path.'/php/model/reports/Reports.php';
require_once $abs_path.'/php/model/reports/Report.php';
require_once $abs_path.'/exceptions.php';

class ProfilesPDOSQLite extends DatabaseHandler implements ProfilesDAO
{


    public static function getInstance(): ProfilesPDOSQLite
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new ProfilesPDOSQLite();
        }
        return $instance;
    }
    private function __construct()
    {
        // Constructor is private to enforce singleton pattern
    }



    protected function create(PDO $db): void
    {
        try {
            // Check and create 'users' table if missing
            if($this->createTable($db, 'users', "
                CREATE TABLE IF NOT EXISTS `users` (
                  `id` integer PRIMARY KEY,
                  `username` varchar(255) UNIQUE NOT NULL,
                  `email` varchar(255) UNIQUE NOT NULL,
                  `password` varchar(255) NOT NULL,
                  `profile_picture` varchar(255),
                  `description` varchar(255),
                  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                );
            ")){
                $hans = $this->addProfile("Hans","hans@mail.com","Hans12345678");
                $lisa = $this->addProfile("Lisa","lisa@mail.com","Lisa12345678");
                $max = $this->addProfile("Max","max@mail.com","Max12345678");
                $anna = $this->addProfile("Anna","anna@mail.com","Anna12345678");
                $tom = $this->addProfile("Tom","tom@mail.com","Tom12345678");
                $sophie = $this->addProfile("Sophie","sophie@mail.com","Sophie12345678");
            }
            // Check and create 'followers' table if missing
            if($this->createTable($db, 'followers', "
                CREATE TABLE IF NOT EXISTS  `followers` (
                  `follower_id` integer NOT NULL,
                  `followed_id` integer NOT NULL,
                  FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
                  FOREIGN KEY (followed_id) REFERENCES users(id) ON DELETE CASCADE,
                  PRIMARY KEY (follower_id, followed_id)
                );
            ")){
                // Hans follows Lisa, Max, Anna, Tom
                $this->followProfile(1, 2);
                $this->followProfile(1, 3);
                $this->followProfile(1, 4);
                $this->followProfile(1, 5);

                // Lisa follows Max, Hans, Tom
                $this->followProfile(2, 3);
                $this->followProfile(2, 1);
                $this->followProfile(2, 5);

                // Max follows Lisa, Hans, Sophie
                $this->followProfile(3, 2);
                $this->followProfile(3, 1);
                $this->followProfile(3, 6);
            }

        } catch (PDOException|InternalErrorException $e) {
            // Handle error if needed
        }
    }



    public function getProfiles(): array
    {
        try {
            $db = $this->getConnection();
            $result = $this->select($db, "SELECT * FROM `users`");
            $profiles = [];
            foreach ($result as $row) {
                $profile = new Profile(
                    $row['id'],
                    $row['username'],
                    $row['email'],
                    $row['password'],
                    $row['profile_picture'] ?? "resources/profile-icon.png",
                    $row['description'] ?? "",
                );
                $profiles[$profile->getId()] = $profile;
            }
            $result = $this->select($db, "SELECT * FROM `followers`");
            foreach ($result as $row) {
                $followerId = $row['follower_id'];
                $followedId = $row['followed_id'];
                if (isset($profiles[$followerId]) && isset($profiles[$followedId])) {
                    $follower = $profiles[$followerId];
                    $followed = $profiles[$followedId];
                    $follower->getFollowing()[] = $followed;
                    $followed->getFollowers()[] = $follower;
                }else{
                    throw new InternalErrorException("Follower or followed profile not found in the database.");
                }
            }
            return $profiles;
        } catch (PDOException $exc) {
            throw new InternalErrorException();
        }
    }

    public function getProfile($id): Profile
    {
        try {
            $db = $this->getConnection();
            $result = $this->select($db, "SELECT * FROM `users` WHERE id = ?", [$id]);
            if (empty($result)) {
                throw new InternalErrorException("No entry found with profile_ID: " . $id);
            }
            $row = $result[0];
            $profile =  new Profile(
                $row['id'],
                $row['username'],
                $row['email'],
                $row['password'],
                $row['profile_picture'] ?? "resources/profile-icon.png",
                $row['description'] ?? ""
            );
            // Fetch followers
            $followersResult = $this->select($db, "SELECT * FROM `followers` WHERE followed_id = ?", [$id]);
            foreach ($followersResult as $followerRow) {
                $profile->getFollowers()[] = $followerRow['follower_id'];
            }
            // Fetch following
            $followingResult = $this->select($db, "SELECT * FROM `followers` WHERE follower_id = ?", [$id]);
            foreach ($followingResult as $followingRow) {
                $profile->getFollowing()[] = $followingRow['followed_id'];
            }
            return $profile;
        } catch (PDOException) {
            throw new InternalErrorException();
        }
    }

    public function getProfileByEmail($email): Profile
    {
        try {
            $db = $this->getConnection();
            $result = $this->select($db, "SELECT * FROM `users` WHERE email = ?", [$email]);
            if (empty($result)) {
                throw new InternalErrorException("No entry found with profile_ID: " . $email);
            }
            $row = $result[0];
            $profile =  new Profile(
                $row['id'],
                $row['username'],
                $row['email'],
                $row['password'],
                $row['profile_picture'] ?? "resources/profile-icon.png",
                $row['description'] ?? ""
            );
            // Fetch followers
            $followersResult = $this->select($db, "SELECT * FROM `followers` WHERE followed_id = ?", [$profile->getId()]);
            foreach ($followersResult as $followerRow) {
                $profile->getFollowers()[] = $followerRow['follower_id'];
            }
            // Fetch following
            $followingResult = $this->select($db, "SELECT * FROM `followers` WHERE follower_id = ?", [$profile->getId()]);
            foreach ($followingResult as $followingRow) {
                $profile->getFollowing()[] = $followingRow['followed_id'];
            }
            return $profile;
        } catch (PDOException) {
            throw new InternalErrorException();
        }
    }

    public function addProfile($username, $email, $password): Profile
    {
        try {
            $db = $this->getConnection();
            $sql = "INSERT INTO `users` (username, email, password) VALUES (?, ?, ?)";
            $params = [$username, strtolower($email), password_hash($password, PASSWORD_DEFAULT)];
            $id = $this->insert($db, $sql, $params);
            return new Profile($id, $username, strtolower($email), $password);
        } catch (PDOException) {
            throw new InternalErrorException();
        }
    }

    public function deleteProfile($id): void
    {
        Reports::getInstance()->deleteProfileDataFromReports($id);
        try {
            $db = $this->getConnection();
            // Delete from followers table
            $this->delete($db, "DELETE FROM `followers` WHERE follower_id = ? OR followed_id = ?", [$id, $id]);
            // Delete from users table
            $this->delete($db, "DELETE FROM `users` WHERE id = ?", [$id]);
        } catch (PDOException) {
            throw new InternalErrorException();
        }

    }

    public function updateProfile($id, $username, $email, $password, $profile_picture, $description): Profile
    {
        try {
            $db = $this->getConnection();
            $sql = "UPDATE `users` SET username = ?, email = ?, password = ?, profile_picture = ?, description = ? WHERE id = ?";
            $params = [
                $username,
                strtolower($email),
                password_hash($password, PASSWORD_DEFAULT),
                $profile_picture,
                $description,
                $id
            ];
            $this->update($db, $sql, $params);
            return new Profile($id, $username, strtolower($email), $password, $profile_picture, $description);
        } catch (PDOException) {
            throw new InternalErrorException();
        }
    }

    public function followProfile($followerId, $followedId): void
    {
        try {
            $db = $this->getConnection();
            // Check if the follower and followed profiles exist
            $r = $this->select($db, "SELECT * FROM `users` WHERE id = ?", [$followerId]);
            if (empty($r)) {
                throw new MissingEntryException("No entry found with profile_ID: " . $followerId);
            }
            $r = $this->select($db, "SELECT * FROM `users` WHERE id = ?", [$followedId]);
            if (empty($r)) {
                throw new MissingEntryException("No entry found with profile_ID: " . $followedId);
            }
            // Insert into followers table
            $sql = "INSERT INTO `followers` (follower_id, followed_id) VALUES (?, ?)";
            $params = [$followerId, $followedId];
            $this->insert($db, $sql, $params);
        } catch (PDOException) {
            throw new InternalErrorException();
        }
    }

    public function unfollowProfile($followerId, $followedId): void
    {
        try {
            $db = $this->getConnection();
            // Check if the follower and followed profiles exist
            $r = $this->select($db, "SELECT * FROM `users` WHERE id = ?", [$followerId]);
            if (empty($r)) {
                throw new MissingEntryException("No entry found with profile_ID: " . $followerId);
            }
            $r = $this->select($db, "SELECT * FROM `users` WHERE id = ?", [$followedId]);
            if (empty($r)) {
                throw new MissingEntryException("No entry found with profile_ID: " . $followedId);
            }
            // Delete from followers table
            $sql = "DELETE FROM `followers` WHERE follower_id = ? AND followed_id = ?";
            $params = [$followerId, $followedId];
            $this->delete($db, $sql, $params);
        } catch (PDOException) {
            throw new InternalErrorException();
        }
    }
}