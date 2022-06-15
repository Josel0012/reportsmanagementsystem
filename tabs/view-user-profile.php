<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once '../connections/connection.php';

if (isset($_POST['userid'])) {
    $_SESSION['user_profile'] = $_POST['userid'];
}
$user_profile = $_SESSION['user_profile'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE users.user_ID = '$user_profile'") or die(mysqli_error($conn));
$profile = mysqli_fetch_assoc($query);
$query2 = mysqli_query($conn, "SELECT img_name FROM profile_img WHERE user_ID = '$user_profile'");
$row = mysqli_fetch_assoc($query2);
$num_rows = mysqli_num_rows($query2);
$img = $row['img_name'];

$query3 = mysqli_query($conn, "SELECT name,date_uploaded FROM files WHERE user_ID = '$user_profile'");

?>

<link rel="stylesheet" href="../css/profile.css">

<div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header header-adduser" style="color:white;background:#00c0ef">
                <h4 class="modal-title w-100 text-center" style="color:white" id="myModalLabel">Add User</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>

            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <!-- <p class="statusMsg"></p> -->
                <form method="post" role="form" id="reg-form">
                    <input hidden type="text" id="data-id-edituser">
                    <p class="reg">
                    <div id="register_output"></div>
                    </p>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-id-card'></i></span>
                        </div>
                        <input type="text" class="form-control" id="inputName" name="firstname" placeholder="Enter name" />
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-id-card'></i></span>
                        </div>
                        <input type="text" class="form-control" id="inputLastName" name="lastname" placeholder="Enter lastname" />
                    </div>
                    <!-- <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-user-rectangle'></i></span>
                        </div>
                        <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Enter username" />
                    </div>
                    -->
                    <div class="user-type">
                        <select class="custom-select user_type" id="user_type" name="user_type">
                            <!-- <option selected>Choose User Type</option> -->
                            <option value="system_administrator">System Administrator</option>
                            <option value="program_coordinator">Program Coordinator</option>
                            <option value="school_dean">School Dean</option>
                            <option value="faculty_member">Faculty Member</option>
                        </select>
                    </div>
                    <div id="course-handle" class="mt-md-3">
                        <label for="usertype">Choose Program:</label>
                        <select class="custom-select course" id="course" name="course">
                            <option value="BSIT">BSIT</option>
                            <option value="BSCS">BSCS</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                        <button type="button" name="create_user" class="btn btn-primary updateBtn" id="btn_update">Update</button>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="container-fluid bg-white mb-4 user-home">
        <a href="#" id="dashboard" class="back mb-5 ">
            <span>Dashboard</span>
        </a>
        <a>/</a>
        <a href="#" id="users" class="back mb-5 ">
            <span>Users</span>
        </a>
    </div>
    <div class="container-fluid bg-white">
        <div class="row pb-2">
            <div class="header bg-green text-center p-1 mb-3" style="color: white">USER PROFILE</div>
            <div class="col-xs-4 col-md-3 text-center">

                <?php if ($num_rows > 0) { ?>
                    <img class="m-2 img-fluid rounded-circle" src=<?php echo "../img/{$img}"; ?> style="height:150px; width: 150px" alt="">
                <?php } else { ?>
                    <img class="m-2 img-fluid rounded-circle" src=<?php echo "../img/profile.png"; ?> style="height:150px; width: 150px" alt="">
                <?php } ?>
            </div>
            <div class="col-xs-4 col-md-4 mt-3">
                <h3><?php echo $profile['First_name'] . ' ' . $profile['last_name'] ?></h3>
                <p class="mb-lg-5">
                    <?php
                    if ($profile['user_type'] == "system_administrator") {
                        echo "System_Administrator";
                    } elseif ($profile['user_type'] == "program_coordinator") {
                        echo "Program Coordinator {$profile['course_users']}";
                    } elseif ($profile['user_type'] == "school_dean") {
                        echo "School Dean";
                    } else {
                        echo "Faculty Member";
                    }
                    ?>
                </p>
                <span><button type="button" name="" id="" data-id="<?php echo $user_profile ?>" class="btn btn-success btn-edituser" data-toggle="modal" data-target="#modalForm" btn-lg btn-block">Edit User Info</button></span>
                <span><a href="../login-credentials.php?user_id=<?php echo $user_profile ?>" target="_blank"><button type="button " name="" id="" data-id="<?php echo $user_profile ?>" class="btn btn-primary btn-email-users" btn-lg btn-block">FMS_SCS Login Info</button> </a></span>


            </div>
            <div class="col-xs-4 col-md-5 mt-2">
                <span class="email-display"><b>Email: </b><?php echo $profile['email_users'] ?></span><br>
                <span><b>Mobile no: </b><?php echo $profile['phone'] ?></span><br>
                <span class="address-display"><b>Address: </b><?php echo $profile['address'] ?></span><br>
                <span class="date-display"><b>Date joined: </b><?php echo $profile['date_joined'] ?></span><br>
                <span><a href="../email-login-info.php?user_id=<?php echo $user_profile ?>" target="_blank"><button type="button " name="" id="" data-id="<?php echo $user_profile ?>" class="btn btn-secondary btn-email-credentials mt-sm-4" btn-lg btn-block">Email user for Login info</button></a></span>

            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-sm-7">
            <table class="table bg-white " style="width: 60%">
                <thead>
                    <tr>
                        <th>Uploaded Files</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fileInfo = mysqli_fetch_assoc($query3)) {

                    ?>
                        <tr>
                            <td><?php echo $fileInfo['name'] ?></td>
                            <td><?php echo $fileInfo['date_uploaded'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        $("#users").click(function() {
            $("#content").load("../tabs/user_tab.php");
            return false;
        });

        $("#dashboard").click(function() {
            $("#content").load("../tabs/dashboard_tab.php");
            return false;
        });

        $('#user_type').on('change', function() {
            var u_type = $('#user_type').val();
            if (u_type == 'program_coordinator') {
                $('#course-handle').show();
            } else {
                $('#course-handle').hide();
            }
        });
        $('.btn-edituser').on('click', function() {
            var editid = $(this).data("id");
            $.ajax({
                url: '../tabs/user_process.php?',
                method: 'POST',
                data: {
                    editUserprofile: 1,
                    editid: editid,
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#data-id-edituser').val(editid);
                    for (var i = 0; i < data.length; i++) {
                        var name = data[i].First_name;
                        var lastname = data[i].last_name;
                        // var username = data[i].username;
                        var usertype = data[i].user_type;
                        // console.log(username);

                        $('#inputName').val(name);
                        $('#inputLastName').val(lastname);
                        // $('#inputUsername').val(username);
                        $('#user_type').val(data[i].user_type);
                        if (usertype == 'program_coordinator') {
                            $('#course-handle').show();
                        } else {
                            $('#course-handle').hide();
                        }
                    }
                }
            });

        });

        $('.updateBtn').on('click', function() {
            var userID = $('#data-id-edituser').val();
            var firstname = $('#inputName').val();
            var lastname = $('#inputLastName').val();
            var user_name = $('#inputUsername').val();
            var usertype = $('#user_type').val();
            if (usertype == 'program_coordinator') {
                var course = $('#course').val();
            } else {
                var course = " ";
            }
            $.ajax({
                url: '../tabs/user_process.php?',
                method: 'POST',
                data: {
                    userID: userID,
                    firstname: firstname,
                    lastname: lastname,
                    user_name: user_name,
                    usertype: usertype,
                    course: course,
                },
                success: function(data) {
                    alert(data);
                    $("#content").load("../tabs/view-user-profile.php");
                    $(".modal-backdrop").hide();

                }

            });


        });

    })
    $('.btn-email-users').on('click', function() {
        var editid = $(this).data("id");
        $.ajax({
            url: '../tabs/user_process.php?',
            method: 'POST',
            data: {
                emailUser: 1,
                editid: editid,
            },
            success: function(data) {
                $('#data-id-edituser').val(editid);
                for (var i = 0; i < data.length; i++) {
                    var name = data[i].First_name;
                    var lastname = data[i].last_name;
                    // var username = data[i].username;
                    var usertype = data[i].user_type;
                    // console.log(username);

                    $('#inputName').val(name);
                    $('#inputLastName').val(lastname);
                    // $('#inputUsername').val(username);
                    $('#user_type').val(data[i].user_type);
                    if (usertype == 'program_coordinator') {
                        $('#course-handle').show();
                    } else {
                        $('#course-handle').hide();
                    }
                }
            }
        });


    });
</script>