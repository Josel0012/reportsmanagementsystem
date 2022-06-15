<?php
session_start();
include("../connections/connection.php");
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location:../index.php');
    exit;
}
$usertype = $_SESSION['user_type'];

// if ($_SESSION['user_type'] == 'system_administrator' || $_SESSION['user_type'] == 'school_dean') {
//     $query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE  is_archived = 1");
// } elseif ($_SESSION['user_type'] == 'faculty_member') {
//     $query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE files.user_ID = '{$_SESSION['user_ID']}' and  is_archived = 1");
// } elseif ($_SESSION['user_type'] == 'program_coordinator') {
//     $query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE files.course = (SELECT course_users FROM users WHERE user_ID = '{$_SESSION['user_ID']}') and is_archived = 1");
// }
$query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE  is_archived = 1 and files.deleter_id ='{$_SESSION['user_ID']}'");
?>
<style>
    .users_table {
        /* background: white; */
        padding: 40px 20px;
        -moz-box-shadow: 0 0 5px #999;
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;
    }

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

    .container-fluid.label {
        -moz-box-shadow: 0 0 5px #999;
        /* background-color: white; */
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;
    }

    .document-label {
        font-weight: bold;

        border-radius: 5px;
        letter-spacing: 5px;
        text-transform: capitalize;
    }

    td {
        font-size: 14px;
    }

    th {
        font-size: 14px;
    }

    .tbl-btn {
        padding: 0px;
        padding-left: 5px;
        padding-right: 5px;
    }

    .toggle-vis.foo {
        color: #bfc5cb;
    }
</style>
<div class="container-fluid label p-sm-2 ">
    <h4 class="document-label text-center">Trash</h4>
</div>
<hr>
<div class="modal fade" tabindex="-1" role="dialog" id="confirmDel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title w-100 text-center" style="color:white">Delete File</h5>
            </div>
            <form method="post" role="form">
                <input hidden type=" text" id="dataDel">
                <input hidden type="text" id="docu-type">
                <input hidden type="text" id="file-name">
                <div class="modal-body">
                    <p>Are you sure you want to permanently delete this file? This can't be undone! </p>
                    <p class="fileTodel"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm-del" class="btn btn-danger">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="users_table">
    <div class="text-center mb-2" style="color:blue">
        <span style="color:black;font-weight:bold">Show and hide column</span>
        <a href="#" class="toggle-vis" data-column="0">#</a> - <a href="#" class="toggle-vis" data-column="1">FILE ID</a> - <a href="#" class="toggle-vis" data-column="2">UPLOADER</a> - <a href="#" class="toggle-vis" data-column="3">FILENAME</a> - <a href="#" class="toggle-vis" data-column="4">FILE TYPE</a> - <a href="#" class="toggle-vis" data-column="5">DOCUMENT TYPE</a> - <a href="#" class="toggle-vis" data-column="6"> DEPARTMENT</a> - <a href="#" class="toggle-vis" data-column="7">DATE UPLOADED</a> - <a href="#" class="toggle-vis" data-column="8"> ACTION</a>
    </div>
    <table class="table table-striped" id="viewdata">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"> FILE ID</th>
                <th scope="col"> UPLOADER</th>
                <th scope="col"> FILENAME</th>
                <th scope="col"> FILE TYPE</th>
                <th scope="col"> DOCUMENT TYPE</th>
                <th scope="col"> DEPARTMENT</th>
                <th scope="col"> DATE UPLOADED</th>
                <th class="text-right">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $num_ID = 0;
            while ($row = mysqli_fetch_assoc($query)) {
                $num_ID = $num_ID + 1;
                $file_ID = $row['file_id'];
                $uploader = $row['user_ID'];
                $up_Name = $row['First_name'];
                $name = $row['name'];
                $ftype = $row['file_type'];
                $dtype = $row['doc_type'];
                $course = $row['course'];
                $date = $row['date_uploaded'];
            ?>
                <tr>
                    <th scope="row"><?php echo $num_ID; ?></th>
                    <td> <?php echo $file_ID; ?></td>
                    <td> <?php echo $up_Name; ?></td>
                    <td> <?php echo $name; ?></td>
                    <td> <?php echo $ftype; ?></td>
                    <td> <?php echo $dtype; ?></td>
                    <td> <?php echo $course; ?></td>
                    <td> <?php echo $date; ?></td>
                    <td class="text-right no-wrap">
                        <button type="button" id="recover-file" class="btn btn-primary tbl-btn waves-effect waves-light btn-recover-file"><i class="bx bxs-archive-out"></i></button>
                        <button type="button" id="delete" class="btn btn-danger tbl-btn btn-delfiles" data-toggle="modal" data-target="#confirmDel"><i class="bx bxs-trash-alt"></i></button>
                    </td>
                </tr>
            <?php
            }
            mysqli_close($conn);
            ?>
        </tbody>
        <!-- <tfoot>
        <tr>
            <th scope="col">#</th>
            <th scope="col"> FILE ID</th>
            <th scope="col"> UPLOADER</th>
            <th scope="col"> FILENAME</th>
            <th scope="col"> FILE TYPE</th>
            <th scope="col"> DOCUMENT TYPE</th>
            <th scope="col"> DEPARTMENT</th>
            <th scope="col"> DATE UPLOADED</th>
            <th class="text-right">ACTION</th>
        </tr>
    </tfoot> -->
    </table>

</div>
<script>
    $(document).ready(function() {
        var table = $('.table').DataTable({
            lengthMenu: [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            scrollY: 200,
            scrollX: true,
            scrollCollapse: true,
        });
        $('a.toggle-vis').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('foo');
            // Get the column API object
            var column = table.column($(this).attr('data-column'));

            // Toggle the visibility
            column.visible(!column.visible());
        });

        $('.table tbody').on('click', '.btn-recover-file', function() {
            var data = table.row($(this).parents('tr')).data();
            var recover = data[1];
            $.ajax({
                url: '../tabs/document_process.php?',
                method: 'POST',
                data: {
                    recover: recover,
                },
                success: function(data) {
                    alert(data);
                    if (data.indexOf("recovered") >= 0) {
                        $("#content").load("../tabs/trash_tab.php");
                    }
                }
            });

        });

        $('.table tbody').on('click', '.btn-delfiles', function() {
            var data = table.row($(this).parents('tr')).data();
            var delfile = data[1];
            var filename = data[3];
            var docu = data[5];
            // console.log(delfile);
            $('#dataDel').val(delfile);
            $('#file-name').val(filename);
            $('.fileTodel').html(filename + ' ?');
            $('#docu-type').val(docu);

        });

        $('#confirm-del').on('click', function() {
            var permanentDel = $('#dataDel').val();
            var docuType = $('#docu-type').val();
            var filename = $('#file-name').val();
            // var username = $('#username').val();
            $.ajax({
                url: '../tabs/document_process.php?',
                method: 'POST',
                data: {
                    permanentDel: permanentDel,
                    docuType: docuType,
                    filename: filename,
                },
                success: function(data) {
                    alert(data);
                    if (data.indexOf("has") >= 0) {
                        $("#uploadModal").modal("hide");
                        $("#content").load("../tabs/trash_tab.php");
                        $(".modal-backdrop").hide();
                    }
                }
            });

        })


    });
</script>