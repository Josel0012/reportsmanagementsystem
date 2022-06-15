<style>
    td {
        font-size: 14px;
    }

    th {
        font-size: 14px;
    }
</style>
<div class="users_table">
    <table class="table" id="viewdata">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"> SEM ID</th>
                <th scope="col"> SEMESTER</th>
                <th scope="col"> ACADEMIC YEAR</th>

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
                $sem_Name = $row['sem'];
                $yrStart = $row['academic_year'];

            ?>
                <tr>
                    <th scope="row"><?php echo $num_ID; ?></th>
                    <td> <?php echo $sem_ID; ?></td>
                    <td> <?php echo $sem_Name; ?></td>
                    <td> <?php echo $yrStart; ?></td>
                    <td class="text-right">
                        <button type="button" id="archive" class="btn btn-danger btn-archive" data-id="<?php echo $sem_ID ?>" data-toggle="modal" data-target="#confirmArchive"><i class='bx bx-archive-in pr-sm-2'></i>Archive Sem</button>
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
            lengthMenu: [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            scrollY: 200,
            scrollX: true,
            scrollCollapse: true,
        }

    );
</script>