<?php
namespace php\model\profiles;
use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;
use php\model\reports\Report;
use php\model\reports\Reports;

class Profile {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $profilePicture;
    private string $description;
    /** @var int[] */
    private array $followers;
    /** @var int[] */
    private array $following;

    public function __construct($id, $username, $email, $password, $profilePicture="resources/profile-icon.png", $description="", $followers = [], $following = []) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->profilePicture = $profilePicture;
        $this->description = $description;
        $this->followers = $followers;
        $this->following = $following;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getUsername(): string{
        return $this->username;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function getPassword(): string{
        return $this->password;
    }
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function getDescription(): string{
        return $this->description;
    }

    /** @return int[] */
    public function &getFollowers(): array
    {
        return $this->followers;
    }
    /** @return int[] */
    public function &getFollowing(): array{
        return $this->following;
    }

    public function isFollowing(Profile $profile): bool
    {
        return in_array($profile->getId(), $this->following);
    }

}
