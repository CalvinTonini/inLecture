<?php
    /**
     * query.php
     *
     * using query for building, return all buildings holding classes at
     * that time and day
     *
     */

    // configuration
    require_once("../../includes/config.php");

    // ensure proper usage
    if (!isset($_GET["id"]))
    {
        http_response_code(400);
        exit;
    }
	$day = date("N");
    $time = date("H:i:s");
    // search using criteria, format output for table
    $outplace = purification($day, supercmp($time, $day, $_GET["id"]));

	// print
    header("Content-type: application/json");
    print(json_encode($outplace, JSON_PRETTY_PRINT));
?>