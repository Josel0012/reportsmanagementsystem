<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once '../connections/connection.php';

$query1 = mysqli_query($conn, "SELECT * FROM semester WHERE status <> 'archive' ORDER BY sem_ID DESC");

$result = mysqli_fetch_assoc($query1);
$sem = $result['sem_ID'];

$id = $_SESSION['user_ID'];
if ($_SESSION['user_type'] == 'system_administrator' || $_SESSION['user_type'] == 'school_dean') {
    $query2 = mysqli_query($conn, "SELECT * FROM files WHERE sem_ID = '$sem' and doc_type ='document'  and is_archived = 0 ");
    $query3 = mysqli_query($conn, "SELECT * FROM files WHERE sem_ID = '$sem' and doc_type ='report'  and is_archived = 0 ");
    $query4 = mysqli_query($conn, "SELECT * FROM users");
    $fileCount = mysqli_num_rows($query2);
    $reportCount = mysqli_num_rows($query3);
    $userCount = mysqli_num_rows($query4);
} elseif ($_SESSION['user_type'] == 'faculty_member') {
    $query2 = mysqli_query($conn, "SELECT * FROM files WHERE user_ID = '$id' and doc_type ='document' and sem_ID = '$sem' and is_archived = 0");
    $query3 = mysqli_query($conn, "SELECT * FROM files WHERE user_ID = '$id' and doc_type ='report' and sem_ID = '$sem' and is_archived = 0");
    $fileCount = mysqli_num_rows($query2);
    $reportCount = mysqli_num_rows($query3);
    $userCount = "--";
} elseif ($_SESSION['user_type'] == 'program_coordinator') {
    $query2 = mysqli_query($conn, "SELECT * FROM files WHERE  sem_ID = '$sem' and doc_type ='document' and course = (SELECT course_users FROM users WHERE user_ID = '{$_SESSION['user_ID']}') and is_archived = 0 ");
    $query3 = mysqli_query($conn, "SELECT * FROM files WHERE  sem_ID = '$sem' and doc_type ='report' and course = (SELECT course_users FROM users WHERE user_ID = '{$_SESSION['user_ID']}') and is_archived = 0");
    $query4 = mysqli_query($conn, "SELECT * FROM users");
    $fileCount = mysqli_num_rows($query2);
    $reportCount = mysqli_num_rows($query3);
    $userCount = mysqli_num_rows($query4);
}

$query5 = mysqli_query($conn, "SELECT *
FROM shared 
INNER JOIN files
ON shared.file_id = files.file_id WHERE `to` = '{$_SESSION['username']}'");
$sharedFiles =  mysqli_num_rows($query5);



$sql = "SELECT * FROM users";
$result2 = mysqli_query($conn, $sql);
$chart_data = "";
while ($row = mysqli_fetch_array($result2)) {

    $userName[]  = $row['First_name'];
    $uploaded[] =  $row['upload_count'];
}



?>
<div class="container-fluid">
    <div class="row">
        <?php if ($_SESSION['user_type'] == 'system_administrator' || $_SESSION['user_type'] == 'school_dean') {
        ?>
            <div class="col-lg-4 col-sm-6 cardo">
                <div class="card-box bg-blue">
                    <div class="inner">
                        <p style="font-size:40px; font-weight: bold"><?php echo $userCount; ?></p>
                        <p> Users </p>
                    </div>
                    <div class="icon">
                        <i class="bx bx-user" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer view-users">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        <?php } else {
        ?>
            <div class="col-lg-4 col-sm-6">
                <div class="card-box bg-blue">
                    <div class="inner">
                        <p style="font-size:40px; font-weight: bold"><?php echo $sharedFiles; ?></p>
                        <p> Shared </p>
                    </div>
                    <div class="icon">
                        <i class="bx bx-share-alt" aria-hidden="true"></i>
                    </div>
                    <a href="#" class="card-box-footer view-shared">View More<i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

        <?php
        }
        ?>
        <div class="col-lg-4 col-sm-6">
            <div class="card-box bg-green">
                <div class="inner">
                    <p style="font-size:40px; font-weight: bold"><?php echo $reportCount; ?></p>
                    <p> Reports </p>
                </div>
                <div class="icon">
                    <i class="bx bxs-report" aria-hidden="true"></i>
                </div>
                <a href="#" class="card-box-footer view-reports">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card-box bg-orange">
                <div class="inner">
                    <p style="font-size:40px; font-weight: bold"><?php echo $fileCount; ?></p>
                    <p> Documents </p>
                </div>
                <div class="icon">
                    <i class='bx bx-folder' aria-hidden="true"></i>
                </div>
                <a href="#" class="card-box-footer view-docs">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-red">
                <div class="inner">
                    <h3> 723 </h3>
                    <p> Faculty Strength </p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div> -->
    </div>
    <?php if ($_SESSION['user_type'] == 'system_administrator' || $_SESSION['user_type'] == 'school_dean') { ?>
        <div class="bg-white pt-3">
            <div class="col-md-12" style="width:100%; text-align:center">
                <!-- <h2 class="page-header">Analytics Reports </h2>
    <div>Product </div> -->
                <canvas style="height:400px" id="chartjs_bar"></canvas>
            </div>
        </div>
    <?php } ?>
</div>


<!-- <script src="https://code.jquery.com/jquery-1.9.1.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.view-users').on('click', function() {
            $("#user_tab").trigger('click')
            return false;
        });
        $('.view-shared').on('click', function() {
            $("#shared").trigger('click')
            return false;
        });
        $('.view-reports').on('click', function() {
            $("#report_tab").trigger('click')
            return false;
        });
        $('.view-docs').on('click', function() {
            $("#doc_tab").trigger('click')
            return false;
        });

        var ctx = document.getElementById("chartjs_bar").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($userName); ?>,
                datasets: [{
                    backgroundColor: [
                        "#5969ff",
                        "#ff407b",
                        "#25d5f2",
                        "#ffc750",
                        "#2ec551",
                        "#ff004e",
                        "#7040fa",

                    ],
                    data: <?php echo json_encode($uploaded); ?>,
                }]
            },
            options: {
                legend: {
                    display: false,
                    position: 'bottom',

                    labels: {
                        label: "ffsdf",
                        fontColor: '#71748d',
                        fontFamily: 'Circular Std Book',
                        fontSize: 14,
                    },
                },
                title: {
                    display: true,
                    text: 'Total file uploaded by each user'
                },
                maintainAspectRatio: false,

            }
        });
    })
</script>