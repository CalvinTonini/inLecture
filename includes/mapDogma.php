<?php
    /**
     * mapDogma.php
     *
     * functions that relate to HarvardMaps API data manipulation and return
     *
     */

    require_once(__DIR__ . "/constants.php");

	/**
	 * Searches a Harvard Maps API for locations within given rectangular
	 * boundaries
	 * based on CS50 problem set 8
	 * uses map data from intermatch()
	 */
	function geosearch($sw_lat, $ne_lat, $sw_lng, $ne_lng)
	{
	    // defaults
	    $json = intermatch();

	    // start working the dataset
	    $output = [];
	    if ($sw_lng <= $ne_lng)
        {
            foreach ($json as $location)
            {
            	// doesn't cross the antimeridian
            	if ($sw_lat <= $location["lat"] && $location["lat"] <=
            		$ne_lat && ($sw_lng <= $location["lng"] && $location["lng"]
            		<= $ne_lng))
            	{
            		$output[] = $location;
            	}
            }
        }
        else
        {
            foreach ($json as $location)
            {
            	// crosses the antimeridian
            	if ($sw_lat <= $location["lat"] && $location["lat"] <= $ne_lat
            		&& ($sw_lng <= $location["lng"] || $location["lng"] <=
            		$ne_lng))
            	{
            		$output[] = $location;
            	}
            }
        }

	    return $output;
	}

	/**
	 * Returns only map objects that are on a given list of locations, using
	 * the current time and day of week
	 * NOTE: Requires exact spelling match. If buildings have differing
	 * spellings, negative hit.
	 */
	function intermatch($map = NULL, $locations = NULL)
	{
	    // defaults
	    $map = is_null($map) ? loadfromcache(MAPJSON) : $map;
	    $locations = is_null($locations) ? listbuildings(timedatematch(date("H:i:s"), date("N"))) : $locations;

	    // start working the dataset
	    $output = [];
	    foreach ($map as $mapobject)
	    {
	    	foreach ($locations as $location)
	    	{
	    		if ($mapobject["name"] === $location)
	    		{
	    			$output[] = $mapobject;
	    		}
	    	}
	    }

	    return $output;
	}
?>
