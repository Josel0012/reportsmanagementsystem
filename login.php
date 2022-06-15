<?php
session_start();
include("connections/connection.php");
// To check the type of user
if (isset($_SESSION['loggedIN'])) {
    if ($_SESSION['user_type'] == 'system_administrator') {
        header('Location:dashboard/dashboard_admin.php');
        exit();
        // echo ("<script>location.href = '../dashboard/dashboard.php';</script>");
        // exit();
    } else if ($_SESSION['user_type'] == 'faculty_member') {
        header('Location:dashboard/user_dashboard.php');
        exit();
        // echo ("<script>location.href = '../dashboard/user_dashboard.php';</script>");
        // exit();
    } else if ($_SESSION['user_type'] == 'school_dean') {
        header('Location:dashboard/school_dean_dashboard.php');
        exit();
    } else if ($_SESSION['user_type'] == 'program_coordinator') {
        header('Location:dashboard/coordinator_dashboard.php');
        exit();
    }
}
// when the Log In button is clicked
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' and is_active = 1");
    $type = mysqli_fetch_assoc($query);
    $userid = $type['user_ID'];
    $login_user = $type['user_type'];
    $name = $type['First_name'];
    $lastname = $type['last_name'];
    $emailusers = $type['email_users'];
    $query2 = mysqli_query($conn, "SELECT * FROM users WHERE BINARY username='$username' AND user_type='$login_user'");
    $num_row = mysqli_num_rows($query2);

    if ($num_row > 0) {
        $row = mysqli_fetch_array($query);
        while ($row = mysqli_fetch_array($query2)) {

            if (password_verify($password, $row['pass'])) {
                $_SESSION['loggedIN'] = '1';
                $_SESSION['user_ID'] = $userid;
                $_SESSION['user_type'] = $login_user;
                $_SESSION['First_name'] = $name;
                $_SESSION['username'] = $username;
                $_SESSION['last_name'] = $lastname;
                $_SESSION['email_users'] = $emailusers;
                exit('<font color = "green">Success</font>');
            } else {
                exit('<font color = "red">Invalid password!</font>');
            }
        }
    } else {
        exit('<font color = "red">Invalid username!</font>');
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>FMS_SCS</title>
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style.css">
</head>

<body id="login-page">
    <div class="login-page">
        <div class="filter"></div>
    </div>
    <div class="container p-5 d-flex justify-content-center align-items-center">
        <div class="container-fluid d-flex justify-content-center align-items-center p-4">
            <form action="">
                <div class="form-group text-center" style="color: #34b7a7; font-size: 30px; font-weight: 500">
                    <img class="img-fluid" src="./img/scs_logo.png" alt="" />
                </div>
                <div class="form-group text-center" style="color: #34b7a7; font-size: 30px; font-weight: 500">
                    <div>LOGIN</div>
                </div>
                <div class="warning text-center">
                    <p id="response"></p>
                </div>
                <div class="form-group">
                    <i class="bx bxs-user"></i>
                    <input type="text" class="form-control" id="username" placeholder="Username" />
                </div>
                <div class="form-group">
                    <i class="bx bxs-lock"></i>
                    <input type="password" class="form-control" id="password" aria-describedby="basic-addon1" placeholder="Password" />
                </div>
                <div class="row mt-5">
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <a style="color: #34b7a7" href="enter-email.inc.php">Forgot password?</a>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <button id="login" type="button" class="btn">Log in</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="footer d-flex align-items-center justify-content-center">
        <div class="title text-center">
            Web-based File Management System for School of Computer Studies
            <div class="text-center mt-4">
                © Copyright FMS-SCS. All Rights Reserved
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script type="text/javascript">
        // function showPassword() {
        //     var x = document.getElementById("password");
        //     if (x.type === "password") {
        //         x.type = "text";
        //     } else {
        //         x.type = "password";
        //     }
        // }
        $(document).ready(function() {

            $("#login").on('click', function() {
                var username = $("#username").val();
                var password = $("#password").val();

                if (username == "" || password == "") {
                    alert("Please check your inputs");
                } else {
                    $.ajax({
                        url: 'login.php',
                        method: 'POST',
                        data: {
                            login: 1,
                            loggedin: 1,
                            username: username,
                            password: password,
                        },
                        success: function(response) {
                            $("#response").html(response);

                            if (response.indexOf('Success') >= 0) {
                                location.reload('login.php');
                            }
                        },
                        dataType: 'text',
                    });
                }
            });
        });
    </script>
</body>

</html>