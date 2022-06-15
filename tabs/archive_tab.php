<?php
session_start();
include_once '../connections/connection.php';
$query = mysqli_query($conn, "SELECT * FROM semester WHERE status = 'archive' ORDER BY sem_ID DESC");
?>
<style>
    .dataTables_scrollHeadInner,
    .table {
        width: 100% !important;
    }

    /* .btn-archive-out {
        overflow: hidden;
        white-space: nowrap;
    } */

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

    /* .tbl-btn {
        padding: 0px;
        padding-left: 5px;
        padding-right: 5px;
    } */
</style>

<div class="container-fluid label p-sm-2">
    <h4 class="document-label text-center">Archived Semester</h4>
</div>
<hr>
<div class="users_table">
    <table class="table table-striped" id="viewdata">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"> SEM ID</th>
                <th scope="col"> SEMESTER</th>
                <th scope="col" class="text-right">ACTION</th>
                <!-- <th scope="col"> Document type</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $num_ID = 0;
            while ($row = mysqli_fetch_assoc($query)) {
                $num_ID = $num_ID + 1;
                $sem_ID = $row['sem_ID'];
                $sem_Name =  $row['sem'] . ' ' . $row['academic_year'];
            ?>
                <tr>
                    <th scope="row"><?php echo $num_ID; ?></th>
                    <td> <?php echo $sem_ID; ?></td>
                    <td> <?php echo $sem_Name; ?></td>
                    <?php if ($_SESSION['user_type'] == 'system_administrator' || $_SESSION['user_type'] == 'school_dean') {
                    ?>
                        <td class="text-right">
                            <button type="button" id="archive" class="btn btn-warning btn-archive-out tbl-btn" data-id="<?php echo $sem_ID ?>"><i class='bx bxs-archive-out'></i><span class="ml-2">Restore</span></button>
                            <button type="button" id="archive" class="btn btn-view-files bg-primary tbl-btn" data-id="<?php echo $sem_ID ?>"><i class='bx bxs-book-content'></i><span class="ml-2">View Files</span></button>
                        </td>
                    <?php
                    } else {
                    ?>
                        <td class="text-right">
                            <button type="button" id="archive" class="btn btn-view-files bg-primary" data-id="<?php echo $sem_ID ?>"><i class='bx bxs-book-content pr-sm-3'></i>View Files</button>
                        </td>
                    <?php
                    }
                    ?>
                </tr>
            <?php
            }
            mysqli_close($conn);
            ?>
        </tbody>
    </table>

</div>



<script>
    $('document').ready(function() {

        $('.table').DataTable({
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                scrollY: 500,
                // scrollX: true,
                scrollCollapse: true,
            }

        );

        var table = $('.table').DataTable();

        $('.table tbody').on('click', '.btn-archive-out', function() {
            var data = table.row($(this).parents('tr')).data();
            var id2 = data[1];
            var acad_year = data[2];
            $.ajax({
                url: '../tabs/semester_process.php',
                method: 'POST',
                data: {
                    id2: id2,
                    acad_year: acad_year,
                },
                success: function(data) {
                    alert(data);
                    $('#content').load('../tabs/archive_tab.php');
                }
            });

        })

        $('.table tbody').on('click', '.btn-view-files', function() {
            var data = table.row($(this).parents('tr')).data();
            var viewid = data[1];
            console.log(viewid);
            $.ajax({
                url: '../tabs/viewfiles_tab.php',
                method: 'POST',
                data: {
                    viewid: viewid,
                },
                success: function(data) {
                    $('#content').load('../tabs/viewfiles_tab.php');
                }
            });
        })
    });
</script>