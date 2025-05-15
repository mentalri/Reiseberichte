<?php 
class InternalErrorException extends Exception
{
}
class MissingEntryException extends Exception
{
}
interface TravelreportsDAO
{
    public function getReports($location, $perimeter, $rating, $tags, $date, $date2, $sorting, $count,$page, $authors);
    public function getReport($id);
    public function getRatedReports($profile_id);
    public function addReport($author, $date, $title, $location, $description,$pictures);
    public function updateReport($report);
    public function deleteReport($id);

    public function getProfiles();
    public function getProfile($id);
    public function getProfileByEmail($email);
    public function addProfile($username, $email, $password);
    public function updateProfile($id, $username, $email, $password);
    public function deleteProfile($id);

    public function createComment($rateable_id, $user_id, $text);
    public function createRating($rateable_id, $user_id, $rating);
}

?>