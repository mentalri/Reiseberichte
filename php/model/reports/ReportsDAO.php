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
    public function addReport($author, $date, $title, $location, $description,$pictures): Report;
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
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function createComment($rateable_id, $user_id, $text): void;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function createRating($rateable_id, $user_id, $rating): void;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteComment($rateable_id): void;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteRating($rating_id): void;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function updateReport($id, $title, $location, $description, $pictures, $tags): Report;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function updateRating($id, $rating): void;
}