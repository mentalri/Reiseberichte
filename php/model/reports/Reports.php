<?php

namespace php\model\reports;

require_once $abs_path.'/php/model/reports/ReportsDAO.php';
require_once $abs_path.'/php/model/reports/ReportsSession.php';
require_once $abs_path.'/php/model/reports/ReportsPDOSQLite.php';

class Reports
{
    public static function getInstance(): ReportsDAO
    {
        return ReportsPDOSQLite::getInstance();
    }
}