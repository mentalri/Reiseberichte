<?php
/**
 * Path Initialization Script (path.php)
 * Creates a standardized absolute path reference for consistent file inclusion
 * Normalizes directory separators to forward slashes for cross-platform compatibility
 */
$abs_path = str_replace("\\", "/", __DIR__);
?>