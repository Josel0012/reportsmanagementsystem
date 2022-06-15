<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once '../connections/connection.php';

if (isset($_POST['notifID'])) {
    $notifID = $_POST['notifID'];
    $docuType = $_POST['docuType'];


    if ($_SESSION['user_type'] == 'school_dean') {
        $query = mysqli_query($conn, "UPDATE notifications SET status_dean = 1 WHERE notif_ID = '$notifID'");
    } else {
        $query = mysqli_query($conn, "UPDATE notifications SET status = 1 WHERE notif_ID = '$notifID'");
    }


    echo $docuType;
}
