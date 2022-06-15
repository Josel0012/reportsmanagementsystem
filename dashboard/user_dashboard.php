<?php
session_start();
if (!isset($_SESSION['loggedIN'])) {
    header('Location:../login.php');
    exit();
}
if ($_SESSION['user_type'] != 'faculty_member') {
    header('Location:../login.php');
    exit();
}
include_once '../connections/connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>File Management System for the School of Computer Studies</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet" />

    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="../css/style1.css" rel="stylesheet" />


    <!--boxicons-->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>


    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="../js/scripts.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>

</head>

<body>

    <!-- confirm edit password  modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="change-pass">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-blue">
                    <h4 class="modal-title w-100 text-center">Change password</h4>
                </div>
                <form method="post" role="form" id="form-edit-pass">
                    <input hidden type="text" id="data-id-pass">
                    <div class="modal-body w-100 text-center">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class='bx bx-key'></i></span>
                            </div>
                            <input type="password" class="form-control" id="old-pass" placeholder="Enter old password" />
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class='bx bxs-id-card'></i></span>
                            </div>
                            <input type="password" class="form-control" id="new-pass" minlength="8" placeholder="Enter new password" />
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class='bx bxs-id-card'></i></span>
                            </div>
                            <input type="password" class="form-control" id="c-new-pass" minlength="8" placeholder="Confirm Password" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="confirm-edit-pass" class="btn btn-primary">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- confirm edit password end -->
    <?php $query = mysqli_query($conn, "SELECT img_name FROM profile_img WHERE user_ID = '{$_SESSION['user_ID']}'");
    $row = mysqli_fetch_assoc($query);
    $num_rows = mysqli_num_rows($query);
    $img = $row['img_name'];
    ?>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end" id="sidebar-wrapper">
            <div class="sidebar-heading bg-dark text-center">FMSSCS CCT</div>

            <!-- <div class="container-fluid d-flex justify-content-center align-items-center logo">
                <img class="card_image" src="../img/scs_logo.png" alt="cct logo">
            </div> -->
            <?php if ($num_rows > 0) { ?>
                <div class="container-fluid d-flex justify-content-center align-items-center logo">
                    <img class="card_image rounded-circle" src=<?php echo "../img/{$img}"; ?> alt="profile_photo">
                </div>
            <?php } else { ?>
                <div class="container-fluid d-flex justify-content-center align-items-center logo">
                    <img class="card_image rounded-circle" src=<?php echo "../img/scs_logo.png"; ?> alt="profile_photo">
                </div>
            <?php } ?>
            <div class="name_lastname text-center mb-3"><?php echo $_SESSION['username'] ?></div>

            <div class="list-group list-group-flush ">
                <a href="#" id="dashboard_tab" class="list-group-item list-group-item-action list-group-item-light active p-3">
                    <i class='bx bx-desktop'></i>
                    <span class="links_name">Dashboard</span>
                </a>

                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class=" list-group-item list-group-item-action list-group-item-light p-3 doc-list dropdown-toggle" id="toggle-document"> <i class='bx bx-folder icon'></i>
                    <span class="links_name mr-3">Files</span></a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li>
                        <a href="#" data-toggle="tooltip" data-placement="right" title="Documents" id="doc_tab" class="docs list-group-item list-group-item-action list-group-item-light p-3">
                            <i class='bx bxs-folder-open ml-3'></i>
                            <span class="links_name">Documents</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-toggle="tooltip" data-placement="right" title="Reports" id="report_tab" class="docs list-group-item list-group-item-action list-group-item-light p-3">
                            <i class='bx bxs-report ml-3'></i>
                            <span class="links_name">Reports</span>
                        </a>
                    </li>
                </ul>

                <a href="#" data-toggle="tooltip" data-placement="right" title="Archive" id="archive" class="list-group-item list-group-item-action list-group-item-light p-3">
                    <i class='bx bx-archive-in'></i>
                    <span class="links_name">Archive</span>
                </a>
                <a href="#" data-toggle="tooltip" data-placement="right" title="Shared Files" id="shared" class="list-group-item list-group-item-action list-group-item-light p-3">
                    <i class='bx bx-share-alt'></i>
                    <span class="links_name">Shared</span>
                </a>
                <a href="#" data-toggle="tooltip" data-placement="right" title="Trash" id="trash" class="list-group-item list-group-item-action list-group-item-light p-3">
                    <i class="bx bxs-trash-alt"></i>
                    <span class="links_name">Trash</span>
                </a>

            </div>
        </div>


        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">

                    <i class="bx bx-menu" id="sidebarToggle"></i>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="bx bx-menu"></span></button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <span class="title">Web-based File Management System for the School of Computer Studies</span>
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <div class="notif">
                                <?php include '../tabs/users-notifications.inc.php' ?>
                            </div>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome!
                                    <!-- <i class='bx bx-user'></i> -->
                                    <span><?php echo $_SESSION['First_name'] . " " . $_SESSION['last_name'] ?></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item change-pass" id="change-password" data-toggle="modal" data-target="#change-pass" href="#"><i class='bx bxs-lock-alt mr-2'></i>Change Password</a>
                                    <a class="dropdown-item profile-settings" id="profile-settings" href="#"><i class='bx bxs-cog mr-2'></i>Settings</a>
                                    <hr class="m-2">
                                    <a class="dropdown-item" href="../logout.php"><i class='bx bx-log-out mr-2'></i>Log Out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page content-->
            <link rel="stylesheet" href="../css/style.css">
            <div class="container-fluid  p-xl-4" id="content">
                <?php include("../tabs/dashboard_tab.php"); ?>

            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#change-password').on('click', function() {
                $('#form-edit-pass').trigger('reset');
            });

            $('#confirm-edit-pass').on('click', function() {
                var oldpass = $('#old-pass').val();
                var newpass2 = $('#new-pass').val();
                var cnewpass = $('#c-new-pass').val();

                if (newpass2 == "" || cnewpass == "") {
                    alert("Please enter and confirm newpassword");
                } else if (newpass2 != cnewpass) {
                    alert("Password doesn't match!");
                } else {
                    $.ajax({
                        url: '../tabs/user_process.php?',
                        method: 'POST',
                        data: {
                            oldpass: oldpass,
                            newpass2: newpass2,
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            id = data[0];
                            uid = data[1];
                            message = data[2];
                            // // $("#content").load("../tabs/user_tab.php");
                            // // $(".modal-backdrop").hide();
                            alert(message);
                            if (message.indexOf("successfully") >= 0) {
                                window.location.href = '../logout.php';
                            }



                        },
                    });
                }
            });
        });
    </script>
    <script src="../js/dashboardsFunctions.js"></script>
</body>

</html>