<?php
    // configuration
    require_once("../../includes/config.php");

	// get data    
	// $thecourses = apicourses($apikey);
	// $thecourses = coursefilter(loadfromcache("courses.json"));

	// $output = listbuildings();
	$needle = "Science Center";
	$output = buildingcmp("Winthrop House");
	// $outcourses = oncepast("13:25:00", "3", $needle);

	// print
    header("Content-type: application/json");
    print(json_encode($output, JSON_PRETTY_PRINT));
    // print(sizeof($outcourses));
    // print(json_encode($output));
?>