<?php

namespace php\model\profiles;

use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;

interface ProfilesDAO
{
    /**
     * @throws InternalErrorException
     * @return Profile[]
     */
    public function getProfiles(): array;

    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function getProfile($id): Profile;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function getProfileByEmail($email): Profile;
    /**
     * @throws InternalErrorException
     */
    public function addProfile($username, $email, $password): Profile;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function deleteProfile($id);
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function updateProfile($id, $username, $email, $password, $profile_picture, $description): Profile;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function followProfile($followerId, $followedId): void;
    /**
     * @throws MissingEntryException
     * @throws InternalErrorException
     */
    public function unfollowProfile($followerId, $followedId): void;
}
