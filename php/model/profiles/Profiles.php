<?php

namespace php\model\profiles;

require_once 'ProfilesDAO.php';
require_once 'ProfilesSession.php';

class Profiles
{
    public static function getInstance(): ProfilesDAO
    {
        return ProfilesSession::getInstance();
    }
}