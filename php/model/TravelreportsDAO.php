<?php
/**
 * Exception classes for error handling
 */
// Exception for internal system errors
class InternalErrorException extends Exception { }

// Exception for when requested entries aren't found
class MissingEntryException extends Exception { }

/**
 * TravelreportsDAO - Data Access Object interface
 * Defines the contract for all data access implementations
 */
interface TravelreportsDAO {
    /**
     * Report management methods
     */
    // Get reports with various filtering options
    public function getReports($location, $perimeter, $rating, $tags, $date, $date2, $sorting, $count, $page, $authors);
    
    // Get a specific report by ID
    public function getReport($id);
    
    // Get reports rated by a specific user
    public function getRatedReports($profile_id);
    
    // Create a new report
    public function addReport($author, $date, $title, $location, $description, $pictures);
    
    // Update an existing report
    public function updateReport($report);
    
    // Delete a report
    public function deleteReport($id);
    
    /**
     * User profile management methods
     */
    // Get all user profiles
    public function getProfiles();
    
    // Get a specific profile by ID
    public function getProfile($id);
    
    // Get a profile by email address
    public function getProfileByEmail($email);
    
    // Create a new user profile
    public function addProfile($username, $email, $password);
    
    // Update an existing profile
    public function updateProfile($id, $username, $email, $password);
    
    // Delete a profile
    public function deleteProfile($id);
    
    /**
     * Interaction methods (comments and ratings)
     */
    // Create a comment on a rateable item
    public function createComment($rateable_id, $user_id, $text);
    
    // Create or update a rating for a rateable item
    public function createRating($rateable_id, $user_id, $rating);
}
?>