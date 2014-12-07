<h2>Results for: <?php print_r($title); ?></h2>
<h3>Currently in Lecture:</h3>
<table class="table table-striped">

    <thead>
        <tr>
            <th>Course Code</th>
            <th>Title</th>
            <th>Faculty</th>
            <th>Time</th>
            <th>Room Number</th>
        </tr>
    </thead>
    
    <tbody>
<?php foreach ($output as $courses): ?>

    <tr>
        <td><?= $courses["fieldnumber"] ?></td>
        <td><?= $courses["title"] ?></td>
        <td><?= $courses["faculty"] ?></td>
        <td><?= $courses["begin_time"] ?> to <?= $courses["end_time"] ?></td>
        <td><?= $courses["room"] ?></td>
    </tr>

<?php endforeach ?>
 
    </tbody>

</table>
<h3>Just out of Lecture:</h3>
<table class="table table-striped">

    <thead>
        <tr>
            <th>Course Code</th>
            <th>Title</th>
            <th>Faculty</th>
            <th>Time</th>
            <th>Room Number</th>
        </tr>
    </thead>
    
    <tbody>
<?php foreach ($before as $courses): ?>

    <tr>
        <td><?= $courses["fieldnumber"] ?></td>
        <td><?= $courses["title"] ?></td>
        <td><?= $courses["faculty"] ?></td>
        <td><?= $courses["begin_time"] ?> to <?= $courses["end_time"] ?></td>
        <td><?= $courses["room"] ?></td>
    </tr>

<?php endforeach ?>
 
    </tbody>

</table>
<h3>Soon to be in Lecture:</h3>
<table class="table table-striped">

    <thead>
        <tr>
            <th>Course Code</th>
            <th>Title</th>
            <th>Faculty</th>
            <th>Time</th>
            <th>Room Number</th>
        </tr>
    </thead>
    
    <tbody>
<?php foreach ($after as $courses): ?>

    <tr>
        <td><?= $courses["fieldnumber"] ?></td>
        <td><?= $courses["title"] ?></td>
        <td><?= $courses["faculty"] ?></td>
        <td><?= $courses["begin_time"] ?> to <?= $courses["end_time"] ?></td>
        <td><?= $courses["room"] ?></td>
    </tr>

<?php endforeach ?>
 
    </tbody>

</table>