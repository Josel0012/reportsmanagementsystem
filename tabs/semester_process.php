<?php
session_start();
include_once("../connections/connection.php");
$semQuery = mysqli_query($conn, "SELECT sem_ID FROM semester WHERE status = 'active'");
$semRow = mysqli_fetch_assoc($semQuery);
$semIdquery = $semRow['sem_ID'];

if (isset($_POST['sem'])) {
    $sem = $_POST['sem'];
    $year_range = $_POST['year_range'];

    $query = mysqli_query($conn, "SELECT * FROM `semester` WHERE `sem` = '$sem' and `academic_year` = '$year_range'");
    $num_row = mysqli_num_rows($query);

    if ($num_row > 0) {
        echo "Error adding sem! This semester already exists";
    } else {
        $query3 = mysqli_query($conn, "UPDATE semester SET status = 'archive'") or die(mysqli_error($conn));

        $sql = "INSERT INTO semester(`sem`, `academic_year`)VALUES('$sem','$year_range')";

        if (mysqli_query($conn, $sql)) {
            date_default_timezone_set('Asia/Manila');
            $today = date("Y-m-d H:i:s");

            $query2 = mysqli_query($conn, "INSERT into notifications(`sem_ID`,`user_ID`,`type`,`message`,`added_action`,`notif_date`)VALUES('$semIdquery','{$_SESSION['user_ID']}','added','started a new academic year','$sem $year_range','$today')") or die(mysqli_error($conn));


            echo ("Semester successsfully added");
        } else {
            echo ("Error!");
        }
    }
}

if (isset($_POST['id'])) {
    $semID = $_POST['id'];
    $acad = $_POST['acad_year'];

    $query = mysqli_query($conn, "UPDATE semester SET status = 'archive' WHERE sem_ID = '$semID'") or die(mysqli_error($conn));

    date_default_timezone_set('Asia/Manila');
    $today = date("Y-m-d H:i:s");
    $query2 = mysqli_query($conn, "INSERT into notifications(`sem_ID`,`user_ID`,`type`,`message`,`added_action`,`notif_date`)VALUES('$semIdquery','{$_SESSION['user_ID']}','archived','archived an academic year','$acad','$today')") or die(mysqli_error($conn));

    echo "Semester Archived.";
}

if (isset($_POST['id2'])) {
    $semID = $_POST['id2'];
    $acad = $_POST['acad_year'];

    $query3 = mysqli_query($conn, "UPDATE semester SET status = 'archive'") or die(mysqli_error($conn));

    $query = mysqli_query($conn, "UPDATE semester SET status = 'active' WHERE sem_ID = '$semID'") or die(mysqli_error($conn));

    $query2 = mysqli_query($conn, "DELETE FROM notifications WHERE `added_action` = '$acad' and `type` = 'archived'") or die(mysqli_error($conn));
    echo "Semester Recovered from Archive.";
}
