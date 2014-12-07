<!--

index.php

Text Form of website.

-->
<?php

    // configuration
    require_once("../includes/config.php");

    // if a building has been selected
    if (!empty($_GET["building"]))
    {
        // check for optional day and time, else use current day and time
        $day = ($_GET["day"] === "") ? date("N") : $_GET["day"];
        $time = ($_GET["time"] === "") ? date("H:i:s") : $_GET["time"];
        // search using criteria, format output for table
        $output = purification($day, supercmp($time, $day, $_GET["building"]));
        // title in form of building, day, time
        $title = $_GET["building"] . ", " . date("l", strtotime("Sunday +{$day} days")) .
                " @ " . $time;
        // if person has checked for courses just out and about to begin
        if (isset($_GET["fringe"]))
        {
            // search similarly
            $after = purification($day, earlyedition($time, $day,
                $_GET["building"]));
            $before = purification($day, oncepast($time, $day,
                $_GET["building"]));
            // render everything
            render("triple.php", ["output" => $output, "before" => $before,
                "after" => $after, "title" =>
                $title]);
        }
        else
        {
            // else render the one thing
            render("single.php", ["output" => $output, "title" => $title]);
        }
    }
    else
    {
        // if not building selected, render null instructions
        render("instructions.php");
    }
?>
