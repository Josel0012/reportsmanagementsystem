<style>
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

    .btn-activate i {
        color: white;
    }

    .toggle-vis.foo {
        color: #bfc5cb;
    }
</style>

<?php if ($_SESSION['user_type'] == 'system_administrator') { ?>
    <div class="text-center" style="color:blue">
        <span style="color:black;font-weight:bold">Show and hide column</span>
        <a href="#" class="toggle-vis" data-column="0">#</a> - <a href="#" class="toggle-vis" data-column="1">USER ID</a> - <a href="#" class="toggle-vis" data-column="2">FIRST NAME</a> - <a href="#" class="toggle-vis" data-column="3">LAST NAME</a> - <a href="#" class="toggle-vis" data-column="4">USERNAME</a> - <a href="#" class="toggle-vis" data-column="5">EMAIL</a> - <a href="#" class="toggle-vis" data-column="6">PASSWORD</a> - <a href="#" class="toggle-vis" data-column="7"> USER TYPE</a> - <a href="#" class="toggle-vis" data-column="8">STATUS</a> - <a href="#" class="toggle-vis" data-column="9">ACTION</a>
    </div>

    <table class="table table-striped ">
        <thead>
            <tr>
                <th>#</th>
                <th>USER ID</th>
                <th>LAST NAME</th>
                <th>FIRST NAME</th>
                <th>USERNAME</th>
                <th>EMAIL</th>
                <th>PASSWORD</th>
                <th>USER TYPE</th>
                <th>STATUS</th>
                <th class="text-right">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $num_ID = 0;
            while ($row = mysqli_fetch_assoc($query)) {
                $num_ID = $num_ID + 1;
                $id = $row['user_ID'];
                $name = $row['First_name'];
                $lastname = $row['last_name'];
                $username = $row['username'];
                $email = $row['email_users'];
                $usertype = $row['user_type'];
            ?>

                <tr>
                    <th scope="row"><?php echo $num_ID; ?></th>
                    <td> <?php echo $id; ?></td>
                    <td> <?php echo $lastname; ?></td>
                    <td> <?php echo $name; ?></td>
                    <td> <?php echo $username; ?></td>
                    <td> <?php echo $email; ?></td>

                    <td class="no-wrap"><button type="button" class="btn btn-primary tbl-btn edit-pass waves-effect waves-light" data-id="<?php echo $row['user_ID']; ?> " id="editPass" data-tooltip="tooltip" data-placement="bottom" title="Reset password" data-toggle="modal" data-target="#confirmEditpass"><i class='bx bxs-edit-alt'>
                            </i></button>Reset Password</td>
                    <td> <?php echo $usertype; ?></td>
                    <td> <?php echo ($row['is_active'] == 1) ? "active" : "inactive"; ?></td>
                    <td class="text-right no-wrap">

                        <!-- <a href="#"><button type="button" class="btn btn-primary tbl-btn btn-edit" data-toggle="modal" data-target="#modalForm" data-id="<?php echo $row['user_ID']; ?>"><i class='bx bxs-edit-alt' id="edit"></i></button></a> -->

                        <a href="#"><button type="button" class="btn btn-primary tbl-btn btn-viewuser" data-id="<?php echo $row['user_ID']; ?>" data-tooltip="tooltip" data-placement="bottom" title="view profile"><i class='bx bxs-show' id="edit"></i></i></button></a>

                        <a href="#">

                            <?php
                            if ($row["is_active"] == 1) {
                                echo ' <button type="button" class="btn btn-danger tbl-btn btn-delete waves-effect waves-light" data-id="<?php echo $id; ?>" id="delete" data-tooltip="tooltip" data-placement="bottom" title="deactivate" data-toggle="modal" data-target="#confirmDel" >
                                <i class="bx bxs-toggle-left"></i>
                            </button>';
                            } else {
                                echo ' <button type="button" class="btn btn-secondary tbl-btn btn-activate waves-effect waves-light" data-id="<?php echo $id; ?>" id="activate" data-tooltip="tooltip" data-placement="bottom" title="activate">
                                <i class="bx bx-toggle-right"></i>';
                            }
                            ?>

                        </a>
                    </td>

                </tr>
            <?php
            }
            ?>
        </tbody>
        <!-- <tfoot>
        <tr>
            <th>#</th>
            <th>USER ID</th>
            <th>FIRST NAME</th>
            <th>LAST NAME</th>
            <th>USERNAME</th>
            <th>PASSWORD</th>
            <th>USER TYPE</th>
            <th class="text-right">ACTION</th>
        </tr>
    </tfoot> -->
    </table>
<?php } else { ?>
    <div class="text-center" style="color:blue">
        <span style="color:black;font-weight:bold">Show and hide column</span>
        <a href="#" class="toggle-vis" data-column="0">#</a> - <a href="#" class="toggle-vis" data-column="1">USER ID</a> - <a href="#" class="toggle-vis" data-column="2">FIRST NAME</a> - <a href="#" class="toggle-vis" data-column="3">LAST NAME</a> - <a href="#" class="toggle-vis" data-column="4">USERNAME</a> - <a href="#" class="toggle-vis" data-column="5">EMAIL</a> - <a href="#" class="toggle-vis" data-column="6">USER TYPE</a> - <a href="#" class="toggle-vis" data-column="7">STATUS</a> - <a href="#" class="toggle-vis" data-column="8"> ACTION</a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>USER ID</th>
                <th>LAST NAME</th>
                <th>FIRST NAME</th>

                <th>USERNAME</th>
                <th>EMAIL</th>
                <th>USER TYPE</th>
                <th>STATUS</th>
                <th class="text-right">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $num_ID = 0;
            while ($row = mysqli_fetch_assoc($query)) {
                $num_ID = $num_ID + 1;
                $id = $row['user_ID'];
                $name = $row['First_name'];
                $lastname = $row['last_name'];
                $username = $row['username'];
                $email = $row['email_users'];
                $usertype = $row['user_type'];
            ?>

                <tr>
                    <th scope="row"><?php echo $num_ID; ?></th>
                    <td> <?php echo $id; ?></td>

                    <td> <?php echo $lastname; ?></td>
                    <td> <?php echo $name; ?></td>
                    <td> <?php echo $username; ?></td>
                    <td> <?php echo $email; ?></td>
                    <td> <?php echo $usertype; ?></td>
                    <td> <?php echo ($row['is_active'] == 1) ? "active" : "inactive"; ?></td>



                    <td class="text-right no-wrap">
                        <a href="#"><button type="button" class="btn btn-primary tbl-btn btn-edit" data-toggle="modal" data-target="#modalForm" data-id="<?php echo $row['user_ID']; ?>"><i class='bx bxs-edit-alt' id="edit"></i></button></a>

                        <a href="#">
                            <?php
                            if ($row["is_active"] == 1) {
                                echo ' <button type="button" class="btn btn-danger tbl-btn btn-delete waves-effect waves-light" data-id="<?php echo $id; ?>" id="delete" data-toggle="modal" data-target="#confirmDel">
                                <i class="bx bxs-toggle-left"></i>
                            </button>';
                            } else {
                                echo ' <button type="button" class="btn btn-secondary tbl-btn btn-activate waves-effect waves-light" data-id="<?php echo $id; ?>" id="activate">
                                <i class="bx bx-toggle-right"></i>';
                            }
                            ?>
                        </a>
                    </td>

                </tr>
            <?php
            }
            ?>
        </tbody>
        <!-- <tfoot>
        <tr>
            <th>#</th>
            <th>USER ID</th>
            <th>FIRST NAME</th>
            <th>LAST NAME</th>
            <th>USERNAME</th>
            <th>PASSWORD</th>
            <th>USER TYPE</th>
            <th class="text-right">ACTION</th>
        </tr>
    </tfoot> -->
    </table>

<?php } ?>
<script>
    $(document).ready(function() {
        $('[data-tooltip="tooltip"]').tooltip({
            trigger: 'hover',
        });
        $('[data-tooltip="tooltip"]').on('click', function() {
            $(this).tooltip('dispose');
        });

        var table = $('.table').DataTable({
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                scrollY: true,
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