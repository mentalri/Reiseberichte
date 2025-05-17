<?php
/**
 * Multiple Carousel Styles Generator (multiple-carousel-styles.php)
 * Generates custom carousel animations for multiple reports in a listing
 * Iterates through all reports and includes the carousel style generator for each
 */

// Skip generation if no reports are available
if (!isset($reports)){
    return;
}

// Iterate through each report and generate its carousel styles
foreach ($reports as $report) {
    include "carousel-style.php";  // Include the carousel style generator for each report
}