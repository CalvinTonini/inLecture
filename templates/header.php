<!DOCTYPE html>

<html>

    <head>

        <!-- jQuery -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css" />
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <!-- http://www.jnathanson.com/index.cfm?page=jquery/clockpick/ClockPick -->
        <script src="http://www.jnathanson.com/pages/jquery/clockpick/1.2.9/jquery.clockpick.1.2.9.min.js"></script>
        <link rel="stylesheet" href="http://www.jnathanson.com/pages/jquery/clockpick/1.2.9/jquery.clockpick.1.2.9.css" type="text/css">
        <!-- local sources -->
        <link href="cssjs/styles2.css" rel="stylesheet" />
        <script src="cssjs/scripts2.js"></script> 

        <title>inLecture</title>

    </head>
    <body>

        <div class="container">

            <div id="top">
                <h1>inLecture</h1>
                <form action="index.php" method="GET">
                    <div id="box">
                        <div class="form-group">
                            <select autofocus class="form-control" name="building">
                                <option value="">Select Building</option>
                                <?php foreach ($buildings as $building): ?>
                                <option value='<?= $building ?>'>
                                    <?= $building ?></option>
                                <?php endforeach ?>
                            </select>
                            <select class="form-control" name="day">
                                <option value="">Default: Today</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                            </select>
                            <input id="clockpick" class="form-control" name="time" placeholder="Default: Current Time" type="text"/>
                            <input class="form-control" name="fringe" value="Yes" type="checkbox"/>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Search</button>
                        </div>
                    </div>
                    </fieldset>
                </form>
            </div>

            <div id="middle">