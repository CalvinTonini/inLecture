<?php

    /**
     * config.php
     *
     * Configuration.
     *
     */

    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // Set timezone to United States/Eastern
    date_default_timezone_set('America/New_York');

    // requirements
    require_once(__DIR__ . "/constants.php");
    require_once(__DIR__ . "/courseDogma.php");
    require_once(__DIR__ . "/coreDogma.php");
    require_once(__DIR__ . "/mapDogma.php");

?>
