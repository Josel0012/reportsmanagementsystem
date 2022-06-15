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
if ($_SESSION['user_type'] == 'system_administrator') {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE `user_ID` <> '{$_SESSION['user_ID']}' ORDER BY `is_active` DESC ,last_name ASC");
} elseif ($_SESSION['user_type'] == 'school_dean') {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE user_type <> 'system_administrator' and `user_ID` <> '{$_SESSION['user_ID']}' ORDER BY `is_active` DESC ,last_name ASC");
}
// $query2 = mysqli_query($conn, "SELECT * FROM users WHERE is_active = 0 ");
?>

<style>
    body {
        overflow: hidden;
        overflow-y: scroll;
        padding-right: 0px !important;
    }

    .model-open {
        overflow: hidden;
        overflow-y: scroll;
        padding-right: 0px !important;
    }

    .dataTables_scrollHeadInner,
    .table {
        width: 100% !important;
    }

    .btn-archive-out {
        overflow: hidden;
        white-space: nowrap;
    }

    .users_table {
        background: rgb(226, 226, 226);
        padding: 40px 20px;
        -moz-box-shadow: 0 0 5px #999;
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;
    }

    .container-fluid.label {
        -moz-box-shadow: 0 0 5px #999;
        /* background-color: white; */
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;

    }

    .document-label {
        /* font-size: 30px; */
        font-weight: bold;
        border-radius: 5px;
        letter-spacing: 5px;
        text-transform: capitalize;

    }
</style>


<!-- <div class="container-fluid label p-3">
    <h4 class="document-label text-center">Users</h4>
</div> -->

<div class="container-fluid label pl-sm-3 p-sm-2">
    <h4 class="document-label text-center">Users</h4>
</div>
<hr>

<div class="">
    <button type=" button" id="addUser" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#modalForm"><i class="bx bx-plus"></i> Add User</button>
</div>
<!-- Modal -->
<div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header header-adduser">
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
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-user-rectangle'></i></span>
                        </div>
                        <input type="text" class="form-control" id="inputEmail" name="username" placeholder="Enter email" />
                    </div>
                    <!-- <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-user-rectangle'></i></span>
                        </div>
                        <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Enter username" />
                    </div>
                    <div class="input-group mb-3 pass">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-lock'></i></span>
                        </div>
                        <input type="password" class="form-control" id="inputPassword" name="pass" placeholder="Enter password" />
                    </div>
                    <div class="input-group mb-3 cpass">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-lock-open'></i></span>
                        </div>
                        <input type="password" class="form-control" id="inputConfirmPass" placeholder="Confirm password" />
                    </div> -->
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

                        <button type="button" name="create_user" class="btn btn-success submitBtn" id="btn_create">Submit</button>
                        <button type="button" name="create_user" class="btn btn-primary updateBtn" id="btn_update">Update</button>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->

        </div>
    </div>
</div>
<!-- 
    end of modal -->

<!-- confirm delete user modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="confirmDel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title w-100 text-center" style="color:white">Deactivate User</h5>
            </div>
            <form method="post" role="form">
                <input hidden type=" text" id="data-id">
                <div class="modal-body">
                    <p>Are you sure you want to deactivate this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm-del" class="btn btn-danger">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- confirm delete end -->

<!-- confirm edit password  modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="confirmEditpass">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-blue">
                <h4 class="modal-title w-100 text-center" style="color:white">Reset user password</h4>
            </div>
            <form method="post" role="form" id="form-edit-pass">
                <input hidden type="text" id="data-id-pass">
                <div class="modal-body w-100 text-center">
                    <p>Are you sure you want to reset password for this user?</p>
                    <!-- <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-id-card'></i></span>
                        </div>
                        <input type="password" class="form-control" id="new-pass" name="firstname" placeholder="Enter new password" />
                    </div> -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-id-card'></i></span>
                        </div>
                        <input type="password" class="form-control adminpass" id="adminpass" name="adminpass" placeholder="Enter system administrator password" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm-edit-pass" class="btn btn-primary confirm-edit-pass">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- confirm edit password end -->
<hr>
<div class="row">

    <div class="user_table">
        <?php include("../tabs/user_table.php"); ?>
    </div>
    <!-- <button type="button" class="btn btn-secondary mt-2 mb-2 toggle-deact" data-dismiss="modal">Show Deactivated Users</button> -->
    <!-- include("../tabs/deact_user_table.php"); -->
</div>



<script>
    $('document').ready(function() {
        $(".deact").hide();

        $(".toggle-deact").click(function() {
            $(".deact").toggle();
        });

        var table = $('.table').DataTable();
        // $('.table tbody').on('click', 'button', function() {
        //     var data = table.row($(this).parents('tr')).data();
        // });

        $('#course-handle').hide();
        $('updateBtn').hide();
        $('#user_type').on('change', function() {
            var u_type = $('#user_type').val();
            if (u_type == 'program_coordinator') {
                $('#course-handle').show();
            } else {
                $('#course-handle').hide();
            }
        });
        //------------------------------------------------------ -------ADD USER
        $('#addUser').on('click', function() {
            $('#course-handle').hide();
            $('.pass').show();
            $('.cpass').show();
            $('.submitBtn').show();
            $('.updateBtn').hide();
            $('#reg-form').trigger('reset');
            $('#myModalLabel').text('Add User');
            $(".header-adduser").css("background-color", "#00a65a");
        })

        $("#btn_create").on('click', function() {

            var firstname = $('#inputName').val();
            var lastname = $('#inputLastName').val();
            var username = $('#inputUsername').val();
            var email = $('#inputEmail').val();
            // var password = $('#inputPassword').val();
            // var cpassword = $('#inputConfirmPass').val();
            var usertype = $('#user_type').val();
            if (usertype == 'program_coordinator') {
                var course = $('#course').val();
            } else {
                var course = "";
            }
            if (IsEmail(email) == false) {
                $('#register_output').html("Invalid Email");
                return false;
            }
            $.ajax({
                url: '../tabs/user_process.php?',
                method: 'POST',
                data: {
                    addUser: 1,
                    // username: username,
                    firstname: firstname,
                    lastname: lastname,
                    usertype: usertype,
                    email: email,
                    // password: password,
                    // cpassword: cpassword,
                    course: course,
                },
                success: function(data) {
                    $('#register_output').html(data);

                    if (data.indexOf('success') >= 0) {
                        $("#content").load("../tabs/user_tab.php");
                        $(".modal-backdrop").hide();

                    }
                },
            });

            function IsEmail(email) {
                var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (!regex.test(email)) {
                    return false;
                } else {
                    return true;
                }
            }
        });
        //----------------------------------------------------------------------//DELETE USER
        $('.table tbody').on('click', '.btn-delete', function() {
            var data = table.row($(this).parents('tr')).data();
            var id = data[1];
            $('#data-id').val(id);
        })

        $('#confirm-del').on('click', function() {
            var del = $('#data-id').val();
            $.ajax({
                url: '../tabs/user_process.php?',
                method: 'POST',
                data: {
                    del: del,
                },
                success: function(data) {
                    alert(data);
                    $("#content").load("../tabs/user_tab.php");
                    $(".modal-backdrop").hide();
                },
            });
        });
        //------------------------------------------------------------------------RECOVER USER
        $('.table tbody').on('click', '.btn-activate', function() {
            var data = table.row($(this).parents('tr')).data();
            var recover_id = data[1];
            $('#data-id').val(recover_id);

            $.ajax({
                url: '../tabs/user_process.php?',
                method: 'POST',
                data: {
                    recover_id: recover_id,
                },
                success: function(data) {
                    alert(data);
                    $("#content").load("../tabs/user_tab.php");
                },
            });


        })
        //------------------------------------------------------------------------EDIT USER
        $('.table tbody').on('click', '.btn-edit', function() {
            $(".header-adduser").css("background-color", "#00c0ef");
            var data = table.row($(this).parents('tr')).data();
            var id_edit_user = data[1];
            $('.pass').hide();
            $('.cpass').hide();
            $('.submitBtn').hide();
            $('#myModalLabel').text('Edit User');
            // console.log(id_edit_user);
            $.ajax({
                url: '../tabs/user_process.php?',
                method: 'POST',
                data: {
                    id_edit_user: id_edit_user,
                },
                dataType: 'JSON',
                success: function(data) {
                    $('#data-id-edituser').val(id_edit_user);
                    $('.updateBtn').show();
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
        })

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
                    $("#content").load("../tabs/user_tab.php");
                    $(".modal-backdrop").hide();

                }

            });


        });
        //----------------------------------------------------------------------RESET PASS
        $('.table tbody').on('click', '.edit-pass', function() {
            var data = table.row($(this).parents('tr')).data();
            var id = data[1];
            $('#form-edit-pass').trigger('reset');
            $('#data-id-pass').val(id);
            $('.adminpass').val("");

        });

        $('.confirm-edit-pass').on('click', function() {
            var idpass = $('#data-id-pass').val();
            // var newpass = $('#new-pass').val();
            // var cnewpass = $('#c-new-pass').val();
            var adminpass = $('.adminpass').val();

            if (adminpass == "") {
                alert("Please enter your password");
            } else {
                $.ajax({
                    url: '../tabs/user_process.php?',
                    method: 'POST',
                    data: {
                        idpass: idpass,
                        adminpass: adminpass,
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        id = data[0];
                        uid = data[1];
                        message = data[2];
                        // // $("#content").load("../tabs/user_tab.php");
                        // // $(".modal-backdrop").hide();
                        alert(message);
                        // if (id == uid) {
                        //     window.location.href = '../logout.php';
                        // } else {
                        //     $("#content").load("../tabs/user_tab.php");
                        //     $(".modal-backdrop").hide();
                        // }
                        if (message.indexOf("successfully") >= 0) {
                            $("#content").load("../tabs/user_tab.php");
                            $(".modal-backdrop").hide();
                        } else {
                            $('.adminpass').val("");

                        }
                    },
                });
            }
        });

        //----------------------------------------------------------------------//VIEW USER
        $('.table tbody').on('click', '.btn-viewuser', function() {
            var data = table.row($(this).parents('tr')).data();
            var userid = data[1];

            $.ajax({
                url: '../tabs/view-user-profile.php?',
                method: 'POST',
                data: {
                    userid: userid,
                },
                success: function(data) {
                    $("#content").load("../tabs/view-user-profile.php");
                },
            });

        })
    });
</script>