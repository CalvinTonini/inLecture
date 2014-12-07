<?php
	/**
	 * listbuildings.php
	 *
	 * Outputs all buildings holding scheduled lectures
	 * and seminars from dataset
	 */

    // configuration
    require_once("../../includes/config.php");

    // list all buildings
	$output = listbuildings();

	// print
    header("Content-type: application/json");
    print(json_encode($output, JSON_PRETTY_PRINT));
?>