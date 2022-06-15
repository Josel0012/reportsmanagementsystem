<?php
session_start();
include_once("../connections/connection.php");
$uid = $_SESSION['user_ID'];

$semQuery = mysqli_query($conn, "SELECT sem_ID FROM semester WHERE status = 'active'");
$semRow = mysqli_fetch_assoc($semQuery);
$semIdquery = $semRow['sem_ID'];
if (isset($_POST['addUser'])) {
    // $user_name = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    // $pass_word = $_POST['password'];
    $usertype =  $_POST['usertype'];
    // $cpassword = $_POST['cpassword'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $pass_word = "scsfaculty1234";
    $hash = password_hash($pass_word, PASSWORD_DEFAULT);

    $random = rand(1000, 9999);
    $username = strtolower($firstname[0] . $lastname) . $random;
    $user_name = str_replace(' ', '', $username);

    $query = mysqli_query($conn, "SELECT user_ID FROM users WHERE username ='$user_name'");
    $num = mysqli_num_rows($query);

    if (!$firstname || !$lastname) {
        echo "All fields required.";
    }
    // elseif ($cpassword != $pass_word) {
    //     echo "Password doesn't match.";
    // }
    else {
        if ($num == 1) {
            echo "Username already exist.";
        } else {
            if ($usertype == "school_dean") {
                $query = mysqli_query($conn, "UPDATE users SET user_type = 'faculty_member' WHERE user_type = 'school_dean'");
            } elseif ($usertype == "program_coordinator") {
                $query = mysqli_query($conn, "UPDATE users SET user_type = 'faculty_member',course_users='' WHERE user_type = 'program_coordinator' course_users = '$course'");
            }
            date_default_timezone_set('Asia/Manila');
            $today = date("Y-m-d H:i:s");
            $sql = "INSERT INTO users(First_name,last_name,username,email_users,pass,user_type, course_users, `is_active`,`date_joined`)VALUES ('$firstname','$lastname','$user_name','$email', '$hash', '$usertype', '$course',1,'$today')";

            echo "<script>alert('Successful')</script> ";

            if (mysqli_query($conn, $sql)) {
                $query5 = mysqli_query($conn, "INSERT into notifications(`sem_ID`,`user_ID`,`receiver_ID`,`type`,`message`,`added_action`,`notif_date`)VALUES('$semIdquery','{$_SESSION['user_ID']}',0, 'added','added  a new user','$firstname $lastname','$today')") or die(mysqli_error($conn));
                exit("success");
            }



            // echo $sql;
            // mysqli_query($conn, $sql) or die("database error:" . mysqli_error($conn) . "qqq" . $sql);
            // exit("success");
        }
    }
}

if (isset($_POST['del'])) {
    $id = $_POST['del'];
    // $query = mysqli_query($conn, "DELETE FROM users WHERE user_ID = '$id'") or die(mysqli_error($conn));
    // echo "User successfully deleted!";
    $query = mysqli_query($conn, "UPDATE users SET is_active = 0 WHERE user_ID = '$id'") or die(mysqli_error($conn));
    echo "User deactivated!";
}
if (isset($_POST['recover_id'])) {
    $id = $_POST['recover_id'];
    // $query = mysqli_query($conn, "DELETE FROM users WHERE user_ID = '$id'") or die(mysqli_error($conn));
    // echo "User successfully deleted!";
    $query = mysqli_query($conn, "UPDATE users SET is_active = 1 WHERE user_ID = '$id'") or die(mysqli_error($conn));
    echo "User activated!";
}

if (isset($_POST['adminpass'])) {
    $id = $_POST['idpass'];
    $adminpass = $_POST['adminpass'];
    $newpass = "scsfaculty1234";

    $query = mysqli_query($conn, "SELECT pass FROM users WHERE user_ID = '$uid'");
    $row = mysqli_fetch_assoc($query);
    if (password_verify($adminpass, $row['pass'])) {
        $hash2 = password_hash($newpass, PASSWORD_DEFAULT);
        $query = mysqli_query($conn, "UPDATE users SET pass = '$hash2' WHERE user_ID = '$id'") or die(mysqli_error($conn));
        $dataset = array(
            0 => $id,
            1 => $uid,
            2 => "Password successfully changed!",

        );
        echo json_encode($dataset);
    } else {
        $dataset = array(
            0 => $id,
            1 => $uid,
            2 => "Unable to reset! Invalid system administrator password",

        );
        echo json_encode($dataset);
    }
}

if (isset($_POST['newpass2'])) {
    $id = $_SESSION['user_ID'];
    $old_pass = $_POST['oldpass'];
    $newpass = $_POST['newpass2'];
    // echo $newpass;
    $hash2 = password_hash($newpass, PASSWORD_DEFAULT);

    $query = mysqli_query($conn, "SELECT pass FROM users WHERE  `user_ID` ='$id'");
    $row = mysqli_fetch_assoc($query);

    if (password_verify($old_pass, $row['pass'])) {
        $query = mysqli_query($conn, "UPDATE users SET pass = '$hash2' WHERE user_ID = '$id'") or die(mysqli_error($conn));

        $dataset = array(
            0 => $id,
            1 => $uid,
            2 => "Password successfully changed!",

        );
        echo json_encode($dataset);
    } else {
        $dataset = array(
            0 => $id,
            1 => $uid,
            2 => "Old password doesn't match!",

        );
        echo json_encode($dataset);
    }
}

if (isset($_POST['id_edit_user'])) {
    $id = $_POST['id_edit_user'];
    $userArray = array();
    $query = mysqli_query($conn, "SELECT * FROM users  WHERE user_ID = '$id'") or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($query)) {
        $userArray[] = $row;
    }
    echo json_encode($userArray);
}

if (isset($_POST['userID'])) {
    $id = $_POST['userID'];
    // $user_name = $_POST['user_name'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $usertype =  $_POST['usertype'];
    $course = $_POST['course'];

    // $query = mysqli_query($conn, "SELECT username FROM users WHERE username = '$user_name' and user_ID <> '$id'");
    // $num = mysqli_num_rows($query);

    if (!$firstname || !$lastname) {
        echo "All fields required.";
    } else {
        if ($usertype == "school_dean") {
            $query = mysqli_query($conn, "UPDATE users SET user_type = 'faculty_member' WHERE user_type = 'school_dean'");
        } elseif ($usertype == "program_coordinator") {
            $query = mysqli_query($conn, "UPDATE users SET user_type = 'faculty_member',course_users='' WHERE user_type = 'program_coordinator' and course_users = '$course'");
        }
        $query = mysqli_query($conn, "UPDATE users SET First_name = '$firstname', last_name = '$lastname', user_type ='$usertype', course_users = '$course' WHERE users.user_ID = '$id'") or die(mysqli_error($conn));

        echo "User information updated";
        // echo $sql;

    }
}

if (isset($_POST['editprofile'])) {
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email =  $_POST['email'];
    $phone = $_POST['phoneProfile'];
    $address = $_POST['addressProfile'];

    $query = mysqli_query($conn, "UPDATE users SET First_name = '$name', last_name = '$lastname', username = '$username', email_users = '$email', phone = '$phone', `address` = '$address' WHERE users.user_ID = '{$_SESSION['user_ID']}'") or die(mysqli_error($conn));
    echo "Profile updated! You will be logged out";
}

if (isset($_POST['editUserprofile'])) {
    $id = $_POST['editid'];


    $query = mysqli_query($conn, "SELECT * FROM users WHERE `user_ID` = '$id'") or die(mysqli_error($conn));
    $userArray = array();
    while ($row = mysqli_fetch_array($query)) {
        $userArray[] = $row;
    }
    echo json_encode($userArray);
}
