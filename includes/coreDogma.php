<?php
    /**
     * coreDogma.php
     *
     * functions that relate to API interaction, file system interaction, and
     * displaying stuff, among other things, and more!
     *
     */

    require_once(__DIR__ . "/constants.php");

	/**
	 * Queries CS50 Courses API for all courses
	 * WARNING: This is an API use.
	 * For more information see https://manual.cs50.net/api/courses/
	 */
	function apicourses($apikey = NULL)
	{
	    // defaults
	    $apikey = is_null($apikey) ? APIKEY : $apikey;

	    // download and decode JSON from CS50 Courses API
	    $cs = "http://api.cs50.net/courses/3/courses?key={$apikey}&output=json";
	    $json = file_get_contents($cs);
	    // if there is a download error
	    if ($json === false)
	    {
	        http_response_code(503);
	        exit;
	    }
	    $output = json_decode($json,true);
	    // if not JSON
	    if ($output === false)
	    {
	        http_response_code(500);
	        exit;
	    }

	    return $output;
	}

	/**
	 * Imports and decodes a json, returns as variable
	 * WARNING: File System Interaction
	 * Folder refers to what directory
	 */
	function loadfromcache($filename, $folder = NULL)
	{
	    // defaults
	    $url = is_null($folder) ? CACHELOC : $folder;

	    // prepare file read
	    $url .= "{$filename}";
	    $json = file_get_contents($url);
	    // if read error
	    if ($json === false)
	    {
	        http_response_code(503);
	        exit;
	    }
	    $output = json_decode($json,true);
	    // of not a json
	    if ($output === false)
	    {
	        http_response_code(500);
	        exit;
	    }
	    return $output;
	}

	/**
	 * Renders template, passing in values.
	 * With thanks to CS50 Problem Set Seven!
	 */
	function render($template, $values = [])
	{
	    // if template exists, render it
	    if (file_exists("../templates/$template"))
	    {
	        // extract, if any,  variables into local scope
	        extract($values);

	        // prepare header search fields
	        $buildings = listbuildings();

	        // render header
	        require("../templates/header.php");

	        // render template
	        require("../templates/$template");

	        // render footer
	        require("../templates/footer.php");
	    }

	    // else err
	    else
	    {
	        trigger_error("Invalid template: $template", E_USER_ERROR);
	    }
	}

	/**
	 * Queries HarvardMaps API for all courses
	 * WARNING: This is an API use.
	 * For more information see https://manual.cs50.net/api/maps/
	 */
	function apimap($apikey = NULL)
	{
	    // defaults
	    $apikey = is_null($apikey) ? APIKEY : $apikey;

	    // download and decode JSON from Harvard Maps API
	    $cs = "http://api.cs50.net/maps/2/buildings?output=json&key={$apikey}";
	    $json = file_get_contents($cs);
	    // if there is a download error
	    if ($json === false)
	    {
	        http_response_code(503);
	        exit;
	    }
	    $output = json_decode($json,true);
	    // if not JSON
	    if ($output === false)
	    {
	        http_response_code(500);
	        exit;
	    }

	    return $output;
	}
?>
