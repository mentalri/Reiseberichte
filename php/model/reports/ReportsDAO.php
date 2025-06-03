<?php

namespace php\model\reports;

use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;

interface ReportsDAO
{
    /**
     * @throws InternalErrorException
     * @return Report[]
     */
    public function getReports($location, $perimeter, $rating, $tags, $date, $date2, $sorting, $count, $page, $authors): array;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function getReport($id): Report;
    /**
     * @throws InternalErrorException
     * @return Report[]
     */
    public function getRatedReports($profile_id): array;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function addReport($author_id, $date, $title, $location, $description, $pictures=["resources/picture_icon.png"], $tags=[]): int;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteReport($id);
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteProfileDataFromReports($id): void;
    /**
     * Creates a comment on a report or comment.
     * @param int $rateable_id The ID of the report or comment being commented on.
     * @param string $rateable_type The type of the rateable entity (e.g., 'report', 'comment').
     * @param int $user_id The ID of the user creating the comment.
     * @param string $text The text of the comment.
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function createComment(int $rateable_id, string $rateable_type, int $user_id, string $text): void;
    /**
     * Creates or changes a rating for a report or comment.
     * @param int $rateable_id The ID of the report or comment being rated.
     * @param string $rateable_type The type of the rateable entity (e.g., 'report', 'comment').
     * @param int $user_id The ID of the user creating the rating.
     * @param int $rating The rating value (e.g., 1-5).
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function createRating(int $rateable_id, string $rateable_type, int $user_id, int $rating): void;

    /**
     * Deletes a comment from a report or comment.
     * @param int $rateable_id The ID of the comment to delete.
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteComment(int $rateable_id): void;
    /**
     * Deletes a rating from a report or comment.
     * @param int $rating_id The ID of the rating to delete.
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteRating(int $rating_id): void;
    /**
     * Updates a report.
     * @param int $id The ID of the report to update.
     * @param string $title The new title of the report.
     * @param string $location The new location of the report.
     * @param string $description The new description of the report.
     * @param array $pictures The new pictures associated with the report.
     * @param array $tags The new tags associated with the report.
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function updateReport(int $id, string $title, string $location, string $description, array $pictures, array $tags): void;
    /**
     * Updates the rating for a report or comment.
     * @param int $id The ID of the rating to update.
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function updateRating(int $id, int $rating): void;
}