<?php
namespace php\controller;
global $abs_path;

use php\model\exceptions\InternalErrorException;
use php\model\reports\Reports;

require_once $abs_path . "/php/model/reports/Reports.php";
require_once $abs_path . "/php/model/exceptions/InternalErrorException.php";

class IndexController
{
    public function request(): array
    {
        try {
            $page = $_GET["page"] ?? 0;
            $location = $_GET["location"] ?? null;
            $perimeter = $_GET["perimeter"] ?? null;
            $rating = $_GET["rating"] ?? null;
            $tags = $_GET["tags"] ?? null;
            $date = $_GET["date"] ?? null;
            $date2 = $_GET["date2"] ?? null;
            $sorting = $_GET["sorting"] ?? "date_desc";
            $count = $_GET["count"] ?? 10;
            return Reports::getInstance()
                ->getReports($location, $perimeter, $rating, $tags, $date, $date2, $sorting, $count, $page, null);
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
            return [];
        }
    }
}
