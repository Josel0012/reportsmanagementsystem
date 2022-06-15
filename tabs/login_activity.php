<?php
session_start();
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location:../index.php');
    exit;
}
include_once '../connections/connection.php';
$semQuery = mysqli_query($conn, "SELECT sem_ID FROM semester WHERE status = 'active'");
$semRow = mysqli_fetch_assoc($semQuery);
$semIdquery = $semRow['sem_ID'];
$query = mysqli_query($conn, "SELECT * FROM notifications INNER JOIN users ON notifications.user_ID = users.user_ID  WHERE  sem_ID = '$semIdquery 'ORDER BY notif_ID DESC");
?>
<style>
    td {
        font-size: 14px;
    }

    th {
        font-size: 14px;
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
</style>

<!-- confirm delete user modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="confirmClear">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title w-100 text-center" style="color:white">Clear Logs</h5>
            </div>
            <form method="post" role="form">
                <input hidden type=" text" id="data-id">
                <div class="modal-body">
                    <p>It is advised to clear logs at the end of the academic year!</p>
                    <p>Are you sure you want to clear now? This action can't be undone</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm-clear" class="btn btn-danger">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- confirm delete end -->

<div class="container-fluid label p-sm-2">
    <h4 class="document-label text-center">Log Activities</h4>
</div>
<hr>
<div class="users_table">
    <table class="table table-striped" id="viewdata">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Action</th>
                <th scope="col">File name</th>
                <th scope="col">Date</th>

                <!-- <th scope="col" class="text-right">Action</th> -->
                <!-- <th scope="col"> Document type</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $num_ID = 0;
            while ($row = mysqli_fetch_assoc($query)) {
                $num_ID = $num_ID + 1;

                $username = "{$row['First_name']} {$row['last_name']}";
                $action = $row['message'];
                $time = $row['notif_date'];
                $name = $row['added_action'];
                $receiver = $row['receiver_ID'];

            ?>
                <tr>
                    <th scope="row"><?php echo $num_ID; ?></th>

                    <td> <?php echo $username; ?></td>

                    <!-- <td> < ?php echo $action; ?></td> -->
                    <td> <?php if ($row['type'] == 'shared') {
                                $sharedQuery = mysqli_query($conn, "SELECT First_name, last_name from users WHERE user_ID = '$receiver'");
                                $receiverRow = mysqli_fetch_assoc($sharedQuery);
                                $action = explode(" ", $action);
                                $action = end($action);
                                echo "shared a $action to {$receiverRow['First_name']} {$receiverRow['last_name']}";
                            } else {
                                echo $action;
                            } ?>
                    </td>
                    <td> <?php echo "<b>{$name}</b>" ?></td>
                    <td> <?php echo $time; ?></td>

                    <!-- <td class="text-right">
                        <button type="button" id="archive" class="btn btn-delete-log bg-white" data-id=""><i class='bx bxs-archive-out pr-sm-3'></i>Delete</button>
                    </td> -->
                </tr>
            <?php
            }
            mysqli_close($conn);
            ?>
        </tbody>
        <!-- <tfoot>
            <tr>
                <th scope="col">#</th>
                <th scope="col"> Log ID</th>
                <th scope="col"> User ID</th>
                <th scope="col"> Username</th>
                <th scope="col"> User IP</th>
                <th scope="col"> Login Time</th>
             
            </tr>
        </tfoot> -->
    </table>
    <!-- <div class="container-fluid text-right mt-4">
        <button type="button" name="clear" id="clear-logs" class="btn btn-secondary bg-white" style="color:#999" data-toggle="modal" data-target="#confirmClear">Clear Logs</button>
    </div> -->
</div>



<script>
    $('document').ready(function() {
        $('.table').DataTable({
                // lengthMenu: [
                //     [5, 10, 25, 50, 100],
                //     [5, 10, 25, 50, 100]
                // ],
                scrollY: 500,
                // scrollX: true,
                scrollCollapse: true,
            }

        );

        // $('#confirm-clear').on('click', function() {
        //     $.ajax({
        //         url: '../tabs/semester_process.php',
        //         method: 'POST',
        //         data: {
        //             clearLogs: 1,
        //         },
        //         success: function(data) {
        //             alert(data);
        //             $("#content").load("../tabs/login_activity.php");
        //             $(".modal-backdrop").hide();
        //         },
        //     });
        // });
    });
</script>