<?php

namespace php\model\profiles;

global $abs_path;

require_once $abs_path.'/php/model/profiles/ProfilesDAO.php';
require_once $abs_path.'/php/model/profiles/ProfilesPDO.php';
require_once $abs_path.'/php/model/profiles/ProfilesSession.php';


class Profiles
{
    public static function getInstance(): ProfilesDAO
    {
        return ProfilesPDO::getInstance();
    }
}