<?php

namespace php\model\profiles;

global $abs_path;

use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;
use php\model\reports\Reports;
use php\model\reports\ReportsSession;

require_once $abs_path.'/php/model/profiles/ProfilesDAO.php';
require_once $abs_path.'/php/model/profiles/Profile.php';
require_once $abs_path.'/php/model/reports/Reports.php';
require_once $abs_path.'/php/model/reports/Report.php';
require_once $abs_path.'/exceptions.php';



class ProfilesSession implements ProfilesDAO
{
    private static ProfilesSession $instance;
    public static function getInstance(): ProfilesSession
    {
        if (!isset(self::$instance)) {
            self::$instance = new ProfilesSession();
        }
        return self::$instance;
    }
    private function __construct()
    {
        try {
        if(!isset($_SESSION['profiles'])) {
            $hans = $this->addProfile("Hans","hans@mail.com","Hans12345678");
            $lisa = $this->addProfile("Lisa","lisa@mail.com","Lisa12345678");
            $max = $this->addProfile("Max","max@mail.com","Max12345678");
            $anna = $this->addProfile("Anna","anna@mail.com","Anna12345678");
            $tom = $this->addProfile("Tom","tom@mail.com","Tom12345678");
            $sophie = $this->addProfile("Sophie","sophie@mail.com","Sophie12345678");

            // Hans follows Lisa, Max, Anna, Tom
            $this->followProfile($hans->getId(), $lisa->getId());
            $this->followProfile($hans->getId(), $max->getId());
            $this->followProfile($hans->getId(), $anna->getId());
            $this->followProfile($hans->getId(), $tom->getId());

            // Lisa follows Max, Anna, Tom
            $this->followProfile($lisa->getId(), $max->getId());
            $this->followProfile($lisa->getId(), $anna->getId());
            $this->followProfile($lisa->getId(), $tom->getId());

            // Max follows Lisa, Hans, Sophie
            $this->followProfile($max->getId(), $lisa->getId());
            $this->followProfile($max->getId(), $hans->getId());
            $this->followProfile($max->getId(), $sophie->getId());

        }else {
            $this->profiles = unserialize($_SESSION['profiles']);
            $this->following = unserialize($_SESSION['following']);
            $this->profileId = $_SESSION['profileId'];
            foreach ($this->profiles as $profile) {
                $this->profiles[$profile->getId()] = new Profile(
                    $profile->getId(),
                    $profile->getUsername(),
                    $profile->getEmail(),
                    $profile->getPassword(),
                    $profile->getProfilePicture(),
                    $profile->getDescription()
                );
            }
            foreach ($this->following as $follow) {
                $follower = $this->getProfile($follow['followerId']);
                $followed = $this->getProfile($follow['followedId']);
                $follower->getFollowing()[] = $followed;
                $followed->getFollowers()[] = $follower;
            }
        }
        }catch (InternalErrorException | MissingEntryException) {
            // we can ignore this error as we are just initializing the session.
        }
    }
    public function __destruct()
    {
        $_SESSION['profiles'] = serialize($this->profiles);
        $_SESSION['following'] = serialize($this->following);
        $_SESSION['profileId'] = $this->profileId;
    }
    /**
     * @var $profiles Profile[]
     */
    private array $profiles = [];
    private int $profileId = 0;
    private array $following = [];
    /**
     * @return Profile[]
     */
    public function getProfiles(): array
    {
        return $this->profiles;
    }
    /**
     * @throws MissingEntryException
     */
    public function getProfile($id): Profile
    {
        if(isset($this->profiles[$id])) {
            return $this->profiles[$id];
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
        $profile = new Profile($this->profileId++, $username, strtolower($email), password_hash($password, PASSWORD_DEFAULT));
        $this->profiles[$profile->getId()] = $profile;
        return $profile;
    }

    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteProfile($id): void
    {
        $reportsDAO = Reports::getInstance();
        $reportsDAO->deleteProfileDataFromReports($id);
        foreach ($this->following as $key => $follow) {
            if ($follow['followerId'] == $id || $follow['followedId'] == $id) {
                unset($this->following[$key]);
            }
        }
        unset($this->profiles[$id]);
    }

    /**
     * @throws MissingEntryException
     */
    public function updateProfile($id, $username, $email, $password,$profile_picture,$description): Profile
    {
        $profile = $this->getProfile($id);

        $this->profiles[$profile->getId()] = new Profile(
            $profile->getId(),
            $username,
            strtolower($email),
            $password,
            $profile_picture,
            $description,
            $profile->getFollowers(),
            $profile->getFollowing()
        );
        return $profile;
    }


    public function followProfile($followerId, $followedId): void
    {
        $this->following[] = ['followerId' => $followerId, 'followedId' => $followedId];
    }
    /**
     * @throws MissingEntryException
     */
    public function unfollowProfile($followerId, $followedId): void{
        foreach ($this->following as $key => $follow) {
            if ($follow['followerId'] == $followerId && $follow['followedId'] == $followedId) {
                unset($this->following[$key]);
                return;
            }
        }
        throw new MissingEntryException("No following entry found with follower_ID: " . $followerId . " and followed_ID: " . $followedId);
    }
}
