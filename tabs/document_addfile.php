<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location:../index.php');
    exit;
}
include_once '../connections/connection.php';
$semQuery = mysqli_query($conn, "SELECT sem_ID FROM semester WHERE status = 'active'");
$semRow = mysqli_fetch_assoc($semQuery);
$semIdquery = $semRow['sem_ID'];
if (isset($_POST['sem'])) {
    $id = $_SESSION['user_ID'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $ext_tmp = explode(".", $fileName);
    $extension = end($ext_tmp);
    $finalName = $ext_tmp[0];
    $extension = strtolower($extension);
    $fileSize = $_FILES['file']['size'];
    $doc_type = $_SESSION['doctype'];
    $course = $_POST['course'];
    $semester = $_POST['sem'];
    $fileError = $_FILES['file']['error'];

    $uploads_dir = '../uploads';
    $path = '../uploads/' . $fileName;

    $tmp_name = strtolower($fileName);
    $query = mysqli_query($conn, "SELECT * FROM files WHERE name = '$tmp_name'");
    $name = mysqli_fetch_assoc($query);
    $name2 = strtolower($name['name']);
    $doct = ($name['name']);

    $query2 = mysqli_query($conn, "SELECT * FROM files WHERE (name = '$doct'  or name =  '$finalName($id).$extension') and user_ID = '$id' and doc_type = '$doc_type' and sem_ID = '$semester'");
    $num_row = mysqli_num_rows($query2);

    // && $doct == $doc_type
    $maxFilesize = ((1024 * 1024) * 7);

    if ($num_row > 0) {
        echo ("Error! File already exists");
        // echo "<script>alert('Error! File already exists.')</script>";
    } else {
        if (file_exists($path)) {
            $nameToinsert =  $finalName . '(' . $id . ').' . $extension;
            $status = 1;
        } else {
            $nameToinsert = $fileName;
            $status = 0;
        }

        $query = mysqli_query($conn, "SELECT upload_count FROM users WHERE `user_ID` = '$id'");
        $upload_count = mysqli_fetch_assoc($query);
        $total_upload = $upload_count['upload_count'];
        $total_count = $total_upload + 1;

        if ($semester != "") {
            $sql = "INSERT into files(`name`,`user_ID`,`size`,`file_type`, `doc_type`, `sem_ID`,`course`) VALUES('$nameToinsert','$id','$fileSize','$extension', '$doc_type','$semester','$course')";
            if (mysqli_query($conn, $sql)) {
                $sql2 = mysqli_query($conn, "SELECT `file_id`,`name` FROM files WHERE name = '$nameToinsert' ");
                $row_sql2 = mysqli_fetch_assoc($sql2);
                if ($status == 1) {
                    move_uploaded_file($fileTmpName, $uploads_dir . '/' . $finalName . '(' . $id . ').' . $extension);
                } else {
                    move_uploaded_file($fileTmpName, $uploads_dir . '/' . $fileName);
                }
                echo ucwords($doc_type) . " Successfully uploaded";

                $query3 = mysqli_query($conn, "UPDATE users SET upload_count = '$total_count' WHERE user_ID = '$id'") or die(mysqli_error($conn));
                $query4 = mysqli_query($conn, "SELECT `user_ID`,First_name FROM users WHERE user_ID = '$id'");
                $row = mysqli_fetch_assoc($query4);
                $fname = $row['First_name'];
                date_default_timezone_set('Asia/Manila');
                $today = date("Y-m-d H:i:s");

                $query5 = mysqli_query($conn, "INSERT into notifications(`sem_ID`,`user_ID`,`type`,`message`,`added_action`,`department`,`status`,`notif_date`)VALUES('$semIdquery','$id', 'upload','uploaded a $doc_type','$nameToinsert','$course',0,'$today')");
            } else {
                echo "Error uploading files! ";
            }
        } else {
            echo "<script>alert('Error! Semester not started')</script>";
        }
    }
}

if (isset($_POST['uploadphoto'])) {
    $id = $_SESSION['user_ID'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $ext_tmp = explode(".", $fileName);
    $extension = end($ext_tmp);
    $finalName = $ext_tmp[0];
    $extension = strtolower($extension);
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];




    $query = mysqli_query($conn, "SELECT `user_ID`,`img_name` FROM profile_img WHERE user_ID ='$id'");
    $row = mysqli_fetch_assoc($query);
    $num_row = mysqli_num_rows($query);
    $image_name = $row['img_name'];
    $random1 = rand(1000, 10000);
    $random2 = rand(1000, 10000);

    $nameToinsert =  $random1 . "-" . $random2 . "." . $extension;
    $uploads_dir = '../img';
    $path = '../img/' . $image_name;

    if ($num_row > 0) {
        $status = unlink($path);
        if ($status) {
            $query2 = mysqli_query($conn, "UPDATE profile_img  SET img_name = '$nameToinsert' WHERE user_ID ='$id' ");
            move_uploaded_file($fileTmpName, $uploads_dir . '/' . $nameToinsert);
        } else {
            echo "Can't delete files due to an error";
        }
    } else {
        echo $nameToinsert;
        $query2 = mysqli_query($conn, "INSERT into profile_img(`user_ID`,`img_name`)VALUES('$id','$nameToinsert') ") or die(mysqli_error($conn));
        move_uploaded_file($fileTmpName, $uploads_dir . '/' . $nameToinsert);
    }
    echo "Profile Photo successfully uploaded";
}
