<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once '../connections/connection.php';
$semQuery = mysqli_query($conn, "SELECT sem_ID FROM semester WHERE status = 'active'");
$semRow = mysqli_fetch_assoc($semQuery);
$semIdquery = $semRow['sem_ID'];

$query = mysqli_query($conn, "SELECT * FROM notifications INNER JOIN users  ON notifications.user_ID = users.user_ID WHERE type = 'shared'  and `receiver_ID` = '{$_SESSION['user_ID']}' and sem_ID = '$semIdquery' ORDER BY notif_date DESC LIMIT 15");
$count1 = mysqli_num_rows($query);

$query2 = mysqli_query($conn, "SELECT * FROM notifications INNER JOIN users  ON notifications.user_ID = users.user_ID  WHERE type = 'shared'  and `receiver_ID` = '{$_SESSION['user_ID']}' and `status` = 0 and sem_ID = '$semIdquery' ORDER BY notif_date DESC LIMIT 15");
$count = mysqli_num_rows($query2);

?>
<style>
    .dropdown-item.notif-item {
        max-width: auto;
        min-width: 320px;
        word-wrap: break-word;
        white-space: normal;
        word-break: normal;
    }

    .dropdown-menu::-webkit-scrollbar {
        width: 5px;
    }
</style>
<li class="nav-item dropdown">
    <a class="nav-link ml-lg-2" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="container d-flex text-center w-100 p-0" style="justify-content:center">
            <i class='bx bx-bell p-0' style="font-size:20px"></i>
            <?php if ($count > 0) { ?>
                <span class="badge badge-danger text-center" style="font-size:10px;color:white"><?php echo $count ?></span>
            <?php } ?>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-end p-2" style="background:#f4e8ea;max-height: 700px;overflow-y:scroll;" aria-labelledby="navbarDropdown">
        <div class="text-center " style="font-weight:bold;color:black">Notifications

        </div>
        <?php
        while ($row = mysqli_fetch_assoc($query)) {
            $date = date('F d Y', strtotime($row['notif_date']));
            if ($row['status'] == 0) {
        ?>
                <a class="dropdown-item notif-item" style="border-radius:10px;background:#b6d3f2;color:black" href="#" data-id="<?php echo $row['notif_ID'] ?>" data-type="<?php echo $row['message'] ?>">
                    <small style="color:#007893;font-size:12px"><i><?php echo $date ?></i></small></br>
                    <small> <?php echo " <b>{$row['First_name']} {$row['last_name']}</b> {$row['message']} <b>{$row['added_action']} </b>" ?></small>
                </a>
            <?php } else { ?>
                <a class="dropdown-item notif-item" style="border-radius:10px" href="#" data-id="<?php echo $row['notif_ID'] ?>" data-type="<?php echo $row['message'] ?>">
                    <small style="color:#007893;font-size:12px"><i><?php echo $date ?></i></small></br>
                    <small> <?php echo "<b>{$row['First_name']} {$row['last_name']}</b> {$row['message']} <b>{$row['added_action']} </b>" ?></small>
                </a>
            <?php
            } ?>
            <div class="dropdown-divider"></div>
        <?php
        }
        if ($count1 == 0) {
        ?>
            <a class="dropdown-item notif-item text-center" style="border-radius:10px" href="#">
                No New Notifications
            </a>
        <?php
        }
        ?>
        <div class="text-center">
            <a href="#" style="color:blue">Mark all as read</>
            </a>
        </div>
    </div>
</li>
<script>
    $(document).ready(function() {
        $('.notif-item').on('click', function() {
            var notifID = $(this).data("id");
            var docuType = $(this).data("type");
            $.ajax({
                url: '../tabs/fetch_data.php?',
                method: 'POST',
                data: {
                    notifID: notifID,
                    docuType: docuType,
                },
                success: function(data) {
                    $(".notif").load("../tabs/users-notifications.inc.php");
                    $("#shared").trigger('click');
                },
            });
        });
    });
</script>