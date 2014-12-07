<?php

    /**
     * courseDogma.php
     *
     * functions that relate to CS50 courses API data manipulation and return
     *
     */

    require_once(__DIR__ . "/constants.php");

    /**
     * Filters a variable of courses to only include those that exist
     * Input per pure https://manual.cs50.net/api/courses/ spec
     * outputs a modified form
     * Noteably: It drops a bunch of fields, and concatenates faculty name info
     */
    function coursefilter(array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? loadfromcache(COURSESJSON) : $courses;

        $output = [];
        // for every course
        foreach ($courses as $course)
        {
            // match courses that are scheduled and assigned
            if (!empty($course["term"]) &&
                !empty($course["schedule"]) &&
                !empty($course["locations"]))
            {
                // list all faculty members in a given course 
                $faculty = str_replace('  ', ' ', trim(implode(' ', array(
                        $course["faculty"][0]["first"],
                        $course["faculty"][0]["middle"],
                        $course["faculty"][0]["last"],
                        $course["faculty"][0]["suffix"]))));

                for ($i = 1; $i < count($course["faculty"]); $i++)
                { 
                    $faculty .= ', ' . str_replace('  ', ' ', trim(implode(' ',
                        array(
                        $course["faculty"][$i]["first"],
                        $course["faculty"][$i]["middle"],
                        $course["faculty"][$i]["last"],
                        $course["faculty"][$i]["suffix"]))));
                } 

                // retain only certain fields
                $output[] = [
                    "cat_num" => $course["cat_num"],
                    "fieldnumber" => $course["field"] . ' ' . $course["number"],
                    "title" => $course["title"],
                    "faculty" => $faculty,
                    "schedule" => $course["schedule"],
                    "locations" => $course["locations"]
                ];
            }
        }

        return $output;
    }

    /**
    * Return a list of buildings mentioned in a CS50 courses variable
    * It will be in alpha order
    */
    function listbuildings(array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? coursefilter() : $courses;

        // filter
        $output = [];
        foreach ($courses as $course)
        {
            foreach ($course["locations"] as $buildings)
            {
                // only the building name
                $output[] = $buildings["building"];
            }
        }

        // clean up and return, remove dupes and alpha
        $output = array_values(array_unique($output));
        sort($output);
        return $output;
    }

    /**
    * Match all courses in a given building.
    * input and return should be formatted to
    * https://manual.cs50.net/api/courses/ or coursefilter()
    * Needle should be a string building name (preferably exact match to
    * something from listbuildings)
    */
    function buildingcmp($needle, array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? coursefilter() : $courses;

        // searching
        $output = [];
        foreach ($courses as $course)
        {
            // reset the bool
            $matched = false;
            // check all locations of that course
            foreach ($course["locations"] as $location)
            {
                if ($location["building"] === $needle)
                {
                    // if that course is in the building at some point
                    $matched = true;
                }
            }

            // if one or more of the meetings of the course have matched
            // we use the boolean to ensure that a course is not added multiple
            // times because of multiple meetings in a week in the same building
            if ($matched === true)
            {
                // output
                $output[] = $course;
            }
        }

        // return
        return $output;
    }

    /**
    * Match all courses in session on a given day of the week
    * input and return should be formatted per
    * https://manual.cs50.net/api/courses/ or coursefilter()
    * day should be a numeral, Monday being 1
    */
    function daycmp($day, array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? coursefilter() : $courses;

        // searching
        $output = [];
        foreach ($courses as $course)
        {
            // reset the bool
            $matched = false;
            // check all meeting dates
            foreach ($course["schedule"] as $schedule)
            {
                if ($schedule["day"] === $day)
                {
                    // if that course on that day at some point
                    $matched = true;
                }
            }

            // if one or more of the days of the course have matched
            // we use the boolean to ensure that a course is not added multiple
            // times because of multiple meetings in a week in the same building
            if ($matched === true)
            {
                $output[] = $course;
            }
        }

        // return
        return $output;
    }

    /**
    * Match all courses in session at a given location on a given day
    * input and return should be formatted per
    * https://manual.cs50.net/api/courses/ or coursefilter()
    * Needle should be a string building name (preferably exact match to
    * something from listbuildings) and day should be a numeral, Monday being 1
    */
    function crossmatch($day, $needle, array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? coursefilter() : $courses;

        // searching
        $output = [];
        foreach ($courses as $course)
        {
            // reset the bools and such
            $daymatch = false;
            $locationmatch = false;
            $type = NULL;
            // first check all locations of that course
            foreach ($course["schedule"] as $schedule)
            {
                if ($schedule["day"] === $day)
                {
                    // if that course on that day at some point
                    $daymatch = true;
                    // we want to ensure that we match type to type
                    // and not mismatch say a lecture time and seminar location
                    // though this only affects few courses
                    $type = $schedule["type"];
                }
            }

            // check all meeting dates as well
            foreach ($course["locations"] as $location)
            {
                // if course in that building of that same type of meeting
                if ($location["building"] === $needle
                    && $location["type"] === $type)
                {
                    $locationmatch = true;
                }
            }
            // if one or more of the meetings of the course have matched
            if ($daymatch === true && $locationmatch === true)
            {
                $output[] = $course;
            }
        }

        // return
        return $output;
    }

    /**
    * Match all courses in session at a given location at a given day and time
    * input and return should be formatted per
    * https://manual.cs50.net/api/courses/ or coursefilter()
    * Needle should be a string building name (preferably exact match to
    * something from listbuildings) and day should be a numeral, Monday being 1
    * Time should be a string formatted "00:00" or "00:00:00"
    */
    function supercmp($time, $day, $needle, array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? coursefilter() : $courses;

        // match down day and location first
        // thus we only need to deal with time of day check
        $courses = crossmatch($day, $needle, $courses);

        // searching
        $output = [];
        foreach ($courses as $course)
        {
            // reset the bools and such
            $daymatch = false;
            $checkone = false;
            $checktwo = false;
            $begin_time;
            $end_time;

            // check all locations of that course
            foreach ($course["schedule"] as $schedule)
            {
                if ($schedule["day"] === $day)
                {
                    // if that course on that day at some point
                    $daymatch = true;
                    // save the begin and end time for checking
                    $begin_time = $schedule["begin_time"];
                    $end_time = $schedule["end_time"];
                }
            }

            // time check
            if ($daymatch === true)
            {
                // check that the begin time is before the given time
                if (!timecmp($begin_time, $time))
                {
                    $checkone = true;
                }

                // check that the end time is after the given time
                if (timecmp($end_time, $time))
                {
                    $checktwo = true;
                }

                // make sure both have returned true
                if ($checkone === true && $checktwo === true)
                {
                    // add it to output
                    $output[] = $course;
                }
            }
        }
        // return
        return $output;
    }

    /**
    * Compares two times to see if the former is greater than the later.
    * With thanks to: http://roshanbh.com.np/2007/12/
    *        date-or-time-comparision-in-php.html
    * input time should be in form "00:00" or "00:00:00", returns boolean
    */
    function timecmp($timeone, $timetwo)
    {
        // convert to time
        $timeone = strtotime($timeone);
        $timetwo = strtotime($timetwo);

        // make comparison
        // must be positive to return true
        return (($timeone - $timetwo) >= 0);
    }

    /**
    * Return courses about to begin.
    * for input output info specification see supercmp()
    */
    function earlyedition($time, $day, $needle, array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? coursefilter() : $courses;

        // match down day and location
        $courses = crossmatch($day, $needle, $courses);

        // what classes are currently in session
        $inclass = supercmp($time, $day, $needle, $courses);

        // what classes will be in session in 60 minutes
        // jump factor 1 means go into the future
        $jumpedtime = timejump($time, 1);
        $futuretense = supercmp($jumpedtime, $day, $needle, $courses);

        // do a subtraction of future classes - current classes, return output
        return coursediff($futuretense, $inclass);
    }

    /**
    * Advances or withdraws time
    * With thanks to: http://stackoverflow.com/questions/2767068
    *       -adding-30-minutes-to-time-formatted-as-hi-in-php
    * We jump one hour, because FAS classes are 60 minutes minimum anyway
    * per FAS faculty handbook
    * intime should be in the for "00:00:00" or "00:00", $jumpfactor should be
    * the number 1 or 2, 1 meaning forward, 2 meaning back
    * returns a time similarly formatted to intime
    */
    function timejump($intime, $jumpfactor)
    {
        // convert input time to something 
        $time = strtotime($intime);

        if ($jumpfactor === 1)
        {
            // forward!
            return date("H:i:s", strtotime("+60 minutes", $time));
        }
        else if ($jumpfactor === 2)
        {
            // retreat!
            return date("H:i:s", strtotime("-60 minutes", $time));
        }
        else
        {
            // if incorrect usage
            exit;
        }
    }

    /**
    * Compare two lists of courses and return difference of [A] - [B]
    * Assumes that both lists have the same schema.
    * NOTE: not a true comparision, does not give info about [B] - [A]
    * input and output should be formatted to
    * https://manual.cs50.net/api/courses/ or coursefilter()
    */
    function coursediff($courses1, $courses2)
    {
        // initialize array for differences
        $output = [];
        // go through the first array
        foreach($courses1 as $course)
        {
            // check if cat_num exists in other array
            if (!catnumexists($course["cat_num"], $courses2))
            {
                // if false, add to output
                $output[] = $course;
            }
        }
        // return $output
        return $output;
    }

    /**
    * Return courses just ended.
    * see earlyedition(), it's just like that except in the other direction
    */
    function oncepast($time, $day, $needle, array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? coursefilter() : $courses;

        // match down day and location
        $courses = crossmatch($day, $needle, $courses);

        // what classes are currently in session
        $inclass = supercmp($time, $day, $needle, $courses);

        // what classes were in session 60 minutes ago
        $jumpedtime = timejump($time, 2);
        $pasttense = supercmp($jumpedtime, $day, $needle, $courses);

        // and compare
        return coursediff($pasttense, $inclass);
    }

    /**
    * Check if a cat_num exists in a given set of courses
    * catnum should be course cat number, courses should be per
    * https://manual.cs50.net/api/courses/ or coursefilter()
    * returns a bool
    */
    function catnumexists($catnum, $courses)
    {
        foreach ($courses as $course)
        {
            if ($course["cat_num"] === $catnum)
            {
                return true;
            }
        }
        return false;
    }

    /**
    * Purify to only a given day's location and times
    * input should be https://manual.cs50.net/api/courses/ or coursefilter()
    *   further, input should be supercmp() to a time, because this function
    * doesn't handle multiple meetings in a single day very well (known issue)
    * Returns a modified form, reducing down the locations and schedule
    * subarrays down to single key/values.
    * 
    */
    function purification($day, array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? coursefilter() : $courses;

        $output = [];
        // for every course
        foreach ($courses as $course)
        {
            // go through each meeting of course
            for ($i = 0; $i < count($course["schedule"]); $i++)
            {
                // if meeting is on the day wanted
                if ($course["schedule"][$i]["day"] === $day)
                {
                    // check every location that the course meets
                    for ($j = 0; $j < count($course["locations"]); $j++)
                    {
                        // look for location of the same meeting type
                        // as previously identified meeting on certain day
                        if ($course["locations"][$j]["type"]
                            === $course["schedule"][$i]["type"])
                        {
                            // output in modified form
                            $output[] = [
                                "cat_num" => $course["cat_num"],
                                "fieldnumber" => $course["fieldnumber"],
                                "title" => $course["title"],
                                "faculty" => $course["faculty"],
                                "begin_time" =>
                                    $course["schedule"][$i]["begin_time"],
                                "end_time" =>
                                    $course["schedule"][$i]["end_time"],
                                "building" =>
                                    $course["locations"][$j]["building"],
                                "room" => $course["locations"][$j]["room"]
                            ];
                        }
                    }
                }
            } 
        }
        return $output;
    }

    /**
    * Match all courses in session at at a given day and time
    * input and return should be formatted per
    * https://manual.cs50.net/api/courses/ or coursefilter()
    * day should be a numeral, Monday being 1
    * Time should be a string formatted "00:00" or "00:00:00"
    */
    function timedatematch($time, $day, array $courses = NULL)
    {
        // defaults
        $courses = is_null($courses) ? coursefilter() : $courses;

        // daycmp, so we only need to match the time of day
        $courses = daycmp($day, $courses);

        // searching
        $output = [];
        foreach ($courses as $course)
        {
            // reset the bools and such
            $daymatch = false;
            $checkone = false;
            $checktwo = false;
            $begin_time;
            $end_time;

            // check all locations of that course
            foreach ($course["schedule"] as $schedule)
            {
                if ($schedule["day"] === $day)
                {
                    // if that course on that day at some point
                    $daymatch = true;
                    // save the begin and end time for checking
                    $begin_time = $schedule["begin_time"];
                    $end_time = $schedule["end_time"];
                }
            }

            // time check
            if ($daymatch === true)
            {
                // check that the begin time is before the given time
                if (!timecmp($begin_time, $time))
                {
                    $checkone = true;
                }

                // check that the end time is after the given time
                if (timecmp($end_time, $time))
                {
                    $checktwo = true;
                }

                // make sure both have returned true
                if ($checkone === true && $checktwo === true)
                {
                    // add it to output
                    $output[] = $course;
                }
            }
        }
        // return
        return $output;
    }
?>
