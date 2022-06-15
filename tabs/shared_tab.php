<?php
session_start();
include_once '../connections/connection.php';
$name = $_SESSION['username'];
$type = $_SESSION['user_type'];
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location:../index.php');
    exit;
}

// $query = mysqli_query($conn, "SELECT * FROM files WHERE file_id IN (SELECT file_id FROM shared WHERE `to` = '{$_SESSION['username']}')");
// $result = mysqli_fetch_assoc($query);
$query = mysqli_query($conn, "SELECT *
FROM shared 
INNER JOIN files
ON shared.file_id = files.file_id 
INNER JOIN users
ON users.user_ID = shared.from
WHERE `to` = '{$_SESSION['user_ID']}' ORDER BY share_ID DESC ");
?>
<style>
    .dataTables_scrollHeadInner,
    .table {
        width: 100% !important;
    }

    .btn-archive-out {
        overflow: hidden;
        white-space: nowrap;
    }

    .users_table {
        /* background: white; */
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
</style>

<div class="container-fluid label p-sm-2">
    <h4 class="document-label text-center">Shared Files</h4>
</div>
<hr>
<div class="users_table">
    <table class="table table-striped" id="viewdata">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"> FILE ID</th>
                <th scope="col"> FILENAME</th>
                <th scope="col"> FILE TYPE</th>
                <th scope="col"> SENDER</th>
                <th scope="col"> RECEIVER</th>
                <th class="text-right">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $num_ID = 0;
            while ($row = mysqli_fetch_assoc($query)) {
                $num_ID = $num_ID + 1;
                $file_ID = $row['file_id'];
                $name = $row['name'];
                $ftype = $row['file_type'];
                $sender = $row['First_name'];
                $receiver = $_SESSION['First_name'];

            ?>
                <tr>
                    <th scope="row"><?php echo $num_ID; ?></th>
                    <td> <?php echo $file_ID; ?></td>
                    <td> <?php echo $name; ?></td>
                    <td> <?php echo $ftype; ?></td>
                    <td> <?php echo $sender; ?></td>
                    <td> <?php echo $receiver; ?></td>
                    <td class="text-right no-wrap">
                        <a href="../tabs/document_process.php?sharedfile_id=<?php echo $file_ID ?>"><button type="button" id="download" class="btn btn-success tbl-btn"><i class='bx bxs-download'></i></button></a>
                    </td>
                </tr>
            <?php
            }
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>

<script>
    $('.table').DataTable({
        // lengthMenu: [
        //     [5, 10, 25, 50, 100],
        //     [5, 10, 25, 50, 100]
        // ],
        scrollY: 200,
        scrollX: true,
        scrollCollapse: true,
    });
</script>