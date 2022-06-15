<?php
session_start();
include("../connections/connection.php");
if (isset($_POST['viewid'])) {
    $_SESSION['id'] = $_POST['viewid'];
}
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location:../index.php');
    exit;
}

$semID =  $_SESSION['id'];
$usertype = $_SESSION['user_type'];

// if ($_SESSION['user_type'] == 'admin' || $_SESSION['user_type'] == 'school_dean') {
//     $query = mysqli_query($conn, "SELECT * FROM files WHERE  sem_ID = '$semID'");
// } elseif ($_SESSION['user_type'] == 'user') {
//     $query = mysqli_query($conn, "SELECT * FROM files WHERE user_ID = '{$_SESSION['user_ID']}'  and sem_ID = '$semID' ");
// } elseif ($_SESSION['user_type'] == 'program_coordinator') {
//     $query = mysqli_query($conn, "SELECT * FROM files WHERE  sem_ID = '$semID' and course = (SELECT course_users FROM users WHERE user_ID = '{$_SESSION['user_ID']}')");
// }
if ($_SESSION['user_type'] == 'system_administrator' || $_SESSION['user_type'] == 'school_dean') {
    $query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE  sem_ID = '$semID' and is_archived = 0");
} elseif ($_SESSION['user_type'] == 'faculty_member') {
    $query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE files.user_ID = '{$_SESSION['user_ID']}'  and sem_ID = '$semID' and is_archived = 0");
} elseif ($_SESSION['user_type'] == 'program_coordinator') {
    $query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE  sem_ID = '$semID' and files.course = (SELECT course_users FROM users WHERE user_ID = '{$_SESSION['user_ID']}') and is_archived = 0");
}
?>
<style>
    .users_table {
        /* background: white; */
        padding: 40px 20px;
        -moz-box-shadow: 0 0 5px #999;
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;
    }

    .back {
        font-size: 30px;
        color: #0275d8;
        text-decoration: none;
    }

    .back:hover {
        color: #999;
        text-decoration: none;
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

<a href="#" class="back mb-5 ">
    <span><i class='bx bx-left-arrow-alt'></i> Back</span>
</a>

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
                if ($ftype == 'docx' || $ftype == "doc") {
                    $fileIcon = "../img/docx.png";
                } elseif ($ftype == 'ppt' || $ftype == "pptx") {
                    $fileIcon = "../img/pptx.png";
                } elseif ($ftype == 'xls' || $ftype == "xlsx") {
                    $fileIcon = "../img/xls.png";
                } elseif ($ftype == 'pdf') {
                    $fileIcon = "../img/pdf.png";
                } elseif ($ftype == 'csv') {
                    $fileIcon = "../img/xls.png";
                }
            ?>
                <tr>
                    <th scope="row"><?php echo $num_ID; ?></th>
                    <td> <?php echo $file_ID; ?></td>
                    <td> <?php echo $up_Name; ?></td>
                    <td> <?php echo $name; ?></td>
                    <td> <img style="height:25px; width: 25px" src=<?php echo "../img/{$fileIcon}"; ?>></td>
                    <td> <?php echo $dtype; ?></td>
                    <td> <?php echo $course; ?></td>
                    <td> <?php echo $date; ?></td>
                    <td class="text-right no-wrap">
                        <!-- <button type="button" id="share" class="btn btn-secondary tbl-btn waves-effect waves-light btn-share" data-toggle="modal" data-target="#modal-share" data-id="<?php echo $file_ID; ?>"><i class="bx bxs-share-alt"></i></button> -->

                        <a href="../tabs/document_process.php?file_id=<?php echo $file_ID ?>"><button type="button" id="download" class="btn btn-success tbl-btn download "><i class="bx bxs-download"></i></button></a>

                        <!-- <button type="button" id="delete" class="btn btn-danger tbl-btn btn-delfiles" data-toggle="modal" data-target="#confirmDel"><i class="bx bxs-trash-alt"></i></button> -->
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
            scrollY: 500,
            // scrollX: true,
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
        $('.back').on('click', function() {
            $('#content').load('../tabs/archive_tab.php');
        })

    });
</script>