<?php
class Profile {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $profilePicture;
    private string $description;
    /** @var Profile[] */
    private array $followers;
    /** @var Profile[] */
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

    public function setProfilePicture(string $imagePath): void
    {
        $this->profilePicture = $imagePath;
    }
    public function getDescription(): string{
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    /** @return Profile[] */
    public function getFollowers(): array
    {
        return $this->followers;
    }
    /** @return Profile[] */
    public function getFollowing(): array{
        return $this->following;
    }
    public function follow($profile): void
    {
        if (!$this->isFollowing($profile)) {
            $this->following[] = $profile;
            $profile->followers[] = $this;
        }
    }
    public function unfollow($profile): void
    {
        if (($key = array_search($profile, $this->following)) !== false) {
            unset($this->following[$key]);
            unset($profile->followers[array_search($this, $profile->followers)]);
        }
    }
    public function removeFollower($follower): void
    {
        $follower->unfollow($this);
    }
    public function isFollowing($profile): bool
    {
        return in_array($profile, $this->following);
    }
    public function hasFollower($profile): bool
    {
        return in_array($profile, $this->followers);
    }
    /** @return Report[] */
    public function getReports(): array
    {
        return Travelreports::getInstance()->getReports(null, null, null, null, null, null, null, null, 0, [$this->id]);
    }
    public function updateProfile($username, $email, $password): void
    {
        $this->username = $username;
        $this->email = $email;
        if (!empty($password)) {
            $this->password = $password;
        }
    }


}
?>