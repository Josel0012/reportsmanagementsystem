<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once '../connections/connection.php';
$id = $_SESSION['user_ID'];
$type = $_SESSION['user_type'];

$semQuery = mysqli_query($conn, "SELECT sem_ID FROM semester WHERE status = 'active'");
$semRow = mysqli_fetch_assoc($semQuery);
$semIdquery = $semRow['sem_ID'];

// if (isset($_POST['doctype'])) {
//     $doctype = $_POST['doctype'];
//     $semID = $_POST['sem'];

//     if ($_SESSION['user_type'] == 'admin') {
//         $query = mysqli_query($conn, "SELECT * FROM files WHERE doc_type='{$_SESSION['doctype']}' and sem_ID = '$semID'");
//     } elseif ($_SESSION['user_type'] == 'user') {
//         $query = mysqli_query($conn, "SELECT * FROM files WHERE user_ID = (SELECT user_ID FROM users WHERE user_ID = '$id') and doc_type ='$doctype' and sem_ID = '$semID' ");
//     } elseif ($_SESSION['user_type'] == 'program_coordinator') {
//         $query = mysqli_query($conn, "SELECT * FROM files WHERE doc_type='$doctype' and sem_ID = '$semID'");
//     }
//     include("../tabs/document_table.php");
// }

if (isset($_GET['file_id'])) {
    $id = $_GET['file_id'];
    if (isset($_SESSION['user_ID'])) {
        $uID = $_SESSION['user_ID'];
        $uName =  $_SESSION['user_type'];
        if ($uName == 'faculty_member') {
            $sql = "SELECT * FROM files WHERE file_id=$id and user_ID = $uID";
        } elseif ($uName == 'system_administrator' || $uName == 'school_dean') {
            $sql = "SELECT * FROM files WHERE file_id=$id";
        } elseif ($uName == 'program_coordinator') {
            $sql = "SELECT * FROM files WHERE file_id=$id and files.course = (SELECT course_users FROM users WHERE user_ID = '{$_SESSION['user_ID']}')";
        }
        $result = mysqli_query($conn, $sql);
        $file = mysqli_fetch_assoc($result);
        $filepath = '../uploads/' . $file['name'];
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            ob_clean();
            flush();
            readfile($filepath);
            exit;
        }
    } else {
        exit('Error! Only for logged in users');
    }
    // fetch file to download from database
}
// ob_end_flush();

if (isset($_GET['sharedfile_id'])) {
    $id = $_GET['sharedfile_id'];
    if (isset($_SESSION['user_ID'])) {
        $uID = $_SESSION['user_ID'];
        $uName =  $_SESSION['user_type'];

        $sql = "SELECT * FROM files WHERE file_id=$id";

        $result = mysqli_query($conn, $sql);
        $file = mysqli_fetch_assoc($result);
        $filepath = '../uploads/' . $file['name'];
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            ob_clean();
            flush();
            readfile($filepath);
            exit;
        }
    } else {
        exit('Error! Only for logged in users');
    }
    // fetch file to download from database
}
// ob_end_flush();

// if (isset($_POST['fileid'])) {
//     $fileID = $_POST['fileid'];
//     $query = "SELECT name FROM files WHERE file_id = '$fileID'";
//     $result = mysqli_query($conn, $query);
//     $file = mysqli_fetch_assoc($result);
//     $name = $file['name'];
//     echo "$name";
// }

if (isset($_POST['fileidShare'])) {
    $fileID = $_POST['fileidShare'];
    $filename = $_POST['filename'];
    $uname = $_POST['username'];
    $docuType = $_POST['docuType'];

    $fname = $_SESSION['First_name'];
    $query = "SELECT  username,First_name,`user_ID` FROM users WHERE username = '$uname'";
    $result = mysqli_query($conn, $query);
    $file = mysqli_fetch_assoc($result);
    $uID = $file['user_ID'];
    $num_row = mysqli_num_rows($result);

    if ($num_row == 0) {
        echo "This user does not exist";
    } else {
        $sql = "INSERT into shared(`file_id`,`from`,`to`)VALUES('$fileID','{$_SESSION['user_ID']}','$uID')";
        if (mysqli_query($conn, $sql)) {
            date_default_timezone_set('Asia/Manila');
            $today = date("Y-m-d H:i:s");

            $query5 = mysqli_query($conn, "INSERT into notifications(`sem_ID`,`user_ID`,`receiver_ID`,`type`,`message`,`added_action`,`notif_date`)VALUES('$semIdquery','{$_SESSION['user_ID']}','$uID', 'shared','shared you a $docuType','$filename','$today')") or die(mysqli_error($conn));
            echo "Successful sharing file ";
        } else {
            die(mysqli_error($conn));
            echo "Failed to share file";
        }
    }
}

// if(isset($_POST['delfile'])){
//     $id = $_POST['delfile'];
//     $query = "SELECT name FROM files WHERE file_id = '$id'";
//     $result = mysqli_query($conn, $query);
//     $file = mysqli_fetch_assoc($result);
//     $name = $file['name'];
//     echo "$name";
// }

if (isset($_POST['permanentDel'])) {
    $fileID = $_POST['permanentDel'];
    $filename = $_POST['filename'];
    $docuType = $_POST['docuType'];

    $query = "SELECT  name FROM files WHERE file_id = '$fileID'";
    $result = mysqli_query($conn, $query);
    $file = mysqli_fetch_assoc($result);
    $num_row = mysqli_num_rows($result);
    $filepath = '../uploads/' . $file['name'];
    $name = $file['name'];

    $status = unlink($filepath);
    if (!$status) {
        echo ("$name cannot be deleted due to an error");
    } else {
        $query3 = mysqli_query($conn, "SELECT upload_count FROM users WHERE `user_ID` = (SELECT user_ID FROM files WHERE file_id =  '$fileID')");
        $upload_count = mysqli_fetch_assoc($query3);
        $total_upload = $upload_count['upload_count'];
        $total_count = $total_upload - 1;

        $query4 = mysqli_query($conn, "UPDATE users SET upload_count = '$total_count' WHERE user_ID = (SELECT user_ID FROM files WHERE file_id =  '$fileID')") or die(mysqli_error($conn));

        date_default_timezone_set('Asia/Manila');
        $today = date("Y-m-d H:i:s");

        $query5 = mysqli_query($conn, "INSERT into notifications(`sem_ID`,`user_ID`,`type`,`message`,`added_action`,`notif_date`)VALUES('$semIdquery','{$_SESSION['user_ID']}', 'delete','deleted a $docuType','$filename','$today')") or die(mysqli_error($conn));

        $query6 = mysqli_query($conn, "DELETE FROM notifications WHERE `added_action` = '$filename' and `type` = 'upload'") or die(mysqli_error($conn));

        $query = mysqli_query($conn, "DELETE FROM files WHERE file_id = '$fileID'") or die(mysqli_error($conn));
        $query2 = mysqli_query($conn, "DELETE FROM shared WHERE file_id = '$fileID'") or die(mysqli_error($conn));
        $query7 = mysqli_query($conn, "DELETE FROM files WHERE `name` = '$filename'") or die(mysqli_error($conn));

        echo ("$name has been deleted");
    }
}

if (isset($_POST['fileDel'])) {
    $fileID = $_POST['fileDel'];

    $query = "SELECT  name FROM files WHERE file_id = '$fileID'";
    $result = mysqli_query($conn, $query);
    $file = mysqli_fetch_assoc($result);
    $num_row = mysqli_num_rows($result);
    $name = $file['name'];

    $query4 = mysqli_query($conn, "UPDATE files SET is_archived = 1,deleter_id = '{$_SESSION['user_ID']}' WHERE `file_id` = '$fileID'") or die(mysqli_error($conn));

    echo ("$name has been moved to trash");
}

if (isset($_POST['recover'])) {
    $fileID = $_POST['recover'];

    $query = "SELECT  name FROM files WHERE file_id = '$fileID'";
    $result = mysqli_query($conn, $query);
    $file = mysqli_fetch_assoc($result);
    $num_row = mysqli_num_rows($result);
    $name = $file['name'];

    $query4 = mysqli_query($conn, "UPDATE files SET is_archived = 0 WHERE `file_id` = '$fileID'") or die(mysqli_error($conn));


    echo ("$name recovered from trash");
}
