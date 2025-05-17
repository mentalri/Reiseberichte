<?php
/**
 * Profile class - User account representation with social features
 * Manages user data and social relationships (following/followers)
 */
class Profile {
    // Core user properties
    private int $id;            // Unique user identifier
    private string $username;   // Display name
    private string $email;      // Email address
    private string $password;   // Hashed password
    private array $followers;   // Users following this profile
    private array $following;   // Users this profile follows
    
    /**
     * Constructor - Creates a user profile with optional social connections
     * Automatically hashes the password using BCRYPT
     */
    public function __construct($id, $username, $email, $password, $followers = [], $following = []) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->followers = $followers;
        $this->following = $following;
    }
    
    // Basic getter methods with type declarations
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
    
    public function getFollowers(): array {
        return $this->followers;
    }
    
    public function getFollowing() {
        return $this->following;
    }
    
    /**
     * Social relationship management methods
     */
    // Add a profile to following list (and add self to their followers)
    public function follow($profile): void {
        if (!$this->isFollowing($profile)) {
            $this->following[] = $profile;
            $profile->followers[] = $this;
        }
    }
    
    // Remove a profile from following list (and remove self from their followers)
    public function unfollow($profile): void {
        if (($key = array_search($profile, $this->following)) !== false) {
            unset($this->following[$key]);
            unset($profile->followers[array_search($this, $profile->followers)]);
        }
    }
    
    // Remove a follower from followers list (and remove self from their following)
    public function removeFollower($follower): void {
        if (($key = array_search($follower, $this->followers)) !== false) {
            unset($this->followers[$key]);
            unset($follower->following[array_search($this, $follower->following)]);
        }
    }
    
    // Check relationship status methods
    public function isFollowing($profile): bool {
        return in_array($profile, $this->following);
    }
    
    public function hasFollower($profile): bool {
        return in_array($profile, $this->followers);
    }
    
    /**
     * Get all reports created by this user
     */
    public function getReports() {
        return Travelreports::getInstance()->getReports(null, null, null, null, null, null, null, null, 0, [$this->id]);
    }
    
    /**
     * Update profile information with optional password change
     */
    public function updateProfile($username, $email, $password): void {
        $this->username = $username;
        $this->email = $email;
        if ($password) {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
    }
}
?>