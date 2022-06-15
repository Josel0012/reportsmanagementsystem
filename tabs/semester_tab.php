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
        background-color: white;
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

<!-- confirm archive modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="confirmArchive">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title w-100 text-center" style="color:white">Archive Semester</h5>
            </div>
            <form method="post" role="form">
                <input hidden type=" text" id="sem-id">
                <input hidden type=" text" id="acad-year">
                <div class="modal-body">
                    <p>Are you sure you want to archive this academic year? This will also clear the log activities for this academic year.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm-archive" class="btn btn-danger confirm-archive">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- confirm archive end -->
<div class="container-fluid label p-sm-2">
    <h4 class="document-label text-center">Semester</h4>
</div>
<hr>
<div class="container-fluid form-sem pt-3 mt-3">
    <div class="row">
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class="custom-select" id="inputGroupSelect">
                    <option selected value="0">Choose Semester</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                </select>
            </div>
        </div>
        <!-- <div class="col-sm-2 mb-3">
            <input type="text" class="form-control" id="yr-start" placeholder="Year Start">
        </div>
        <div class="col-sm-2 mb-3">
            <input type="text" class="form-control" id="yr-end" placeholder="Year End">
        </div> -->
        <div class="col-sm-4">
            <div class="input-group mb-3">
                <select class="custom-select" id="yearSelect">
                    <?php
                    $starting_year  = date('Y', strtotime('-2 year'));
                    $ending_year = date('Y', strtotime('+10 year'));
                    for ($starting_year; $starting_year <= $ending_year; $starting_year++) {
                        $year_end = $starting_year + 1;
                        if (date('Y') == $starting_year) { //is the loop currently processing this year?
                            $selected = 'selected'; //if so, save the word "selected" into a variable
                        } else {
                            $selected = ''; //otherwise, ensure the variable is empty
                        }
                        //then include the variable inside the option element
                        echo '<option ' . $selected . ' value="' . $starting_year . '-' . $year_end . '">' . $starting_year . '-' . $year_end  . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-2 mb-3">
            <button type="button" id="btn-add-sem" class="btn w-100 btn-success">+ Add Academic Year</button>
        </div>
    </div>
</div>

<hr>

<?php
include_once '../connections/connection.php';
$query = mysqli_query($conn, "SELECT * FROM semester WHERE status <> 'archive' ORDER BY sem_ID DESC");

?>

<?php include("../tabs/semester_table.php"); ?>

<script>
    $('document').ready(function() {
        var table = $('.table').DataTable();

        $('#btn-add-sem').on('click', function() {
            var sem = $('#inputGroupSelect').val();
            var year_range = $('#yearSelect').val();
            if (sem == 0 || year_range == "") {
                alert("Choose Semester!");
            } else {
                $.ajax({
                    url: '../tabs/semester_process.php',
                    method: 'POST',
                    data: {
                        sem: sem,
                        year_range: year_range,
                    },
                    success: function(data) {
                        alert(data);
                        $('#content').load('../tabs/semester_tab.php');
                    }
                });

            }

        });

        $('.table tbody').on('click', '.btn-archive', function() {
            var data = table.row($(this).parents('tr')).data();
            var id = data[1];
            var acad_year = data[2] + " " + data[3];

            $('#sem-id').val(id);
            $('#acad-year').val(acad_year);
        });

        $('.confirm-archive').on('click', function() {
            var id = $('#sem-id').val();
            var acad_year = $('#acad-year').val();
            $.ajax({
                url: '../tabs/semester_process.php',
                method: 'POST',
                data: {
                    id: id,
                    acad_year: acad_year,
                },
                success: function(data) {
                    alert(data);
                    $('#content').load('../tabs/semester_tab.php');
                    $(".modal-backdrop").hide();
                }
            });
        })
    });
</script>