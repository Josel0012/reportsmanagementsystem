<style>
    .dataTables_scrollHeadInner,
    .table {
        width: 100% !important;
    }

    /* .btn-archive-out {
        overflow: hidden;
        white-space: nowrap;
    } */

    /* .users_table {
        background: rgb(189, 189, 189);
        padding: 40px 20px;
        -moz-box-shadow: 0 0 5px #999;
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;
    } */
    .toggle-vis.foo {
        color: #bfc5cb;
    }
</style>




<div class="user_table deact">
    <div class="container label mb-4 p-sm-2">
        <h6 class="document-label text-center">Deactivated Users</h6>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>USER ID</th>
                <th>FIRST NAME</th>
                <th>LAST NAME</th>
                <th>USERNAME</th>
                <th class="text-right">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $num_ID = 0;
            while ($row = mysqli_fetch_assoc($query2)) {
                $num_ID = $num_ID + 1;
                $id = $row['user_ID'];
                $name = $row['First_name'];
                $lastname = $row['last_name'];
                $username = $row['username'];
                $usertype = $row['user_type'];
            ?>

                <tr>
                    <th scope="row"><?php echo $num_ID; ?></th>
                    <td> <?php echo $id; ?></td>
                    <td> <?php echo $name; ?></td>
                    <td> <?php echo $lastname; ?></td>
                    <td> <?php echo $username; ?></td>

                    <td class="text-right no-wrap">
                        <a href="#">
                            <button type="button" class="btn btn-danger tbl-btn btn-activate waves-effect waves-light" data-id="<?php echo $id; ?>" id="activate" data-toggle="modal" data-target="#">
                                <i class='bx bx-toggle-right'></i>
                            </button>
                        </a>
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
    $(document).ready(function() {
        var table = $('.table').DataTable({
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                scrollY: 200,
                scrollX: true,
                scrollCollapse: true,
            }

        );
        $('a.toggle-vis').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('foo');

            // Get the column API object
            var column = table.column($(this).attr('data-column'));

            // Toggle the visibility
            column.visible(!column.visible());
        });
    })
</script>