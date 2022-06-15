<?php
session_start();
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location:../index.php');
    exit;
}
include_once '../connections/connection.php';
$id = $_SESSION['user_ID'];
$type = $_SESSION['user_type'];

$query2 = mysqli_query($conn, "SELECT * FROM semester WHERE status <> 'archive' ORDER BY sem_ID DESC");
$sem_ID = mysqli_fetch_assoc($query2);


if (isset($_POST['value'])) {
    $_SESSION['semester'] = $_POST['value'];
    $semID =  $_SESSION['semester'];
} else {
    $_SESSION['semester'] = $sem_ID['sem_ID'];
    $semID =  $_SESSION['semester'];
}
if (isset($_POST['document'])) {
    $_SESSION['doctype'] = "document";
}
if (isset($_POST['report'])) {
    $_SESSION['doctype'] = "report";
}

?>

<style>
    body {
        overflow: hidden;
        overflow-y: scroll;
        padding-right: 0px !important;
    }

    .model-open {
        overflow: hidden;
        overflow-y: scroll;
        padding-right: 0px !important;
    }
</style>
<input hidden id="sem-value" type="text" value="<?php echo $semID ?>">

<!-- Share Modal -->

<!-- Modal -->
<div class="modal fade" tabindex="-1" id="modal-share" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <h5 class="modal-title w-100 text-center" style="color:white">Share to other user</h5>
            </div>
            <div class="modal-body">
                <form method="post" role="form">
                    <div class="text-center filename mb-3">
                    </div>

                    <input hidden type="text" id="file-id">
                    <input hidden type="text" id="docu-type">
                    <p hidden id="docu-name"></p>

                    <label>Share this file to:</label>
                    <input type="text" class="form-control username" placeholder="Enter Username">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="dismiss-share">Close</button>
                <button type="button" class="btn btn-primary" id="confirm-share">Share</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Add file Modal -->
<div id="uploadModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <h4 class="modal-title w-100 text-center" style="color:white">File Upload</h4>
                <button type="button" class="bx bx-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method='post' enctype="multipart/form-data" id="upload_form">
                    <p class="reg">
                    <div id="message"></div>
                    </p>
                    <input hidden type="text" name="doctype" id="doctype-hidden">
                    <input type='file' name='file' id='file' class='form-control mb-1'>
                    <font class="ml-4 mb-5" style="font-size: 13px" color="red">.doc .docx .ppt .pptx .xls .xlsx .pdf .csv .txt (7mb max)*</font>
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <div id="course-handle" class="mt-3">
                        <label for="usertype">Choose Course:</label>
                        <select class="custom-select course mb-3" id="file-course" name="course">
                            <option value="BSIT">BSIT</option>
                            <option value="BSCS">BSCS</option>
                        </select>
                    </div>
                    <input type='submit' class='btn btn-success' name="submit" value='Upload' id='btn_upload'>
                </form>
            </div>

        </div>

    </div>
</div>

<!-- delete file modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="confirmDel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title w-100 text-center" style="color:white">Delete File</h5>
            </div>
            <form method="post" role="form">
                <input hidden type=" text" id="dataDel">
                <div class="modal-body">
                    <p>Are you sure you want to move this file to trash?</p>
                    <p class="fileTodel"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm-del" class="btn btn-danger">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .document-label {
        /* font-size: 30px; */
        font-weight: bold;

        border-radius: 5px;
        letter-spacing: 5px;
        text-transform: capitalize;
    }

    .container-fluid.label {
        -moz-box-shadow: 0 0 5px #999;
        background-color: white;
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;
    }
</style>
<div class="container-fluid label pl-sm-3 p-sm-2">
    <h4 class="document-label text-center"><?php echo $_SESSION['doctype'] . 's'; ?></h4>
</div>

<hr>

<div class="row">
    <div class="col-sm-4">
        <div class="input-group mb-3">
            <select class="custom-select" id="fetchSem" name="inputGroupSelect">
                <!-- <option selected>Choose Semester</option> -->
                <?php
                $query4 = mysqli_query($conn, "SELECT * FROM semester WHERE status <> 'archive' and sem_ID = $semID ORDER BY sem_ID DESC");
                $row4 = mysqli_fetch_assoc($query4);
                $options =  $row4['sem'] . ' ' . $row4['academic_year'];
                ?>
                <option class="bg-blue" selected="true" disabled="disabled" value=<?php echo $row4['sem_ID'] ?>><?php echo $options ?></option>
                <?php
                $query3 = mysqli_query($conn, "SELECT * FROM semester WHERE status <> 'archive' ORDER BY sem_ID DESC");
                while ($row2 = mysqli_fetch_assoc($query3)) {
                    $options =  $row2['sem'] . ' ' . $row2['academic_year'];

                ?>
                    <option value=<?php echo $row2['sem_ID'] ?>><?php echo $options ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>

    <span class="col-sm-3">
        <!-- Toggle add file modal -->
        <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#uploadModal"><i class="bx bx-plus"></i> Add File</button>
    </span>
    <!-- <?php echo $row2['sem_ID'] ?> -->
    <!-- Toggle add folder modal -->
    <span class="ml-xl-4">
        <button hidden type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#uni_modal"><i class="bx bx-plus"></i> Add Folder</button>
    </span>

</div>

<div class="user_table" id="user_table">
    <?php
    if ($_SESSION['user_type'] == 'system_administrator' || $_SESSION['user_type'] == 'school_dean') {
        // $query = mysqli_query($conn, "SELECT * FROM files WHERE doc_type='{$_SESSION['doctype']}' and sem_ID = '$semID'");
        $query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE doc_type='{$_SESSION['doctype']}' and sem_ID = '$semID' and is_archived = 0 ORDER BY file_id DESC");
    } elseif ($_SESSION['user_type'] == 'faculty_member') {
        $query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE files.user_ID = '$id' and doc_type ='{$_SESSION['doctype']}' and sem_ID = '$semID' and is_archived = 0 ORDER BY file_id DESC ");
    } elseif ($_SESSION['user_type'] == 'program_coordinator') {
        $query = mysqli_query($conn, "SELECT * FROM files INNER JOIN users ON files.user_ID = users.user_ID WHERE doc_type='{$_SESSION['doctype']}' and sem_ID = '$semID' and files.course = (SELECT course_users FROM users WHERE user_ID = '{$_SESSION['user_ID']}') and is_archived = 0 ORDER BY file_id DESC ");
    }
    include("../tabs/document_table.php");
    ?>
</div>

<script type="text/javascript">
    $('document').ready(function() {
        var table = $('.table').DataTable();
        //------------------------------------------------------ -------SHARE FILES
        $('.table tbody').on('click', '.btn-share', function() {
            var data = table.row($(this).parents('tr')).data();
            var fileid = data[1];
            var fileshare = data[3];
            var docu = data[5];

            $('#file-id').val(fileid);
            $('#docu-type').val(docu);
            // $('#dept').val(dept);
            $('#docu-name').html(fileshare);
            $('.filename').html(fileshare);
        });

        $('#confirm-share').on('click', function() {
            var fileidShare = $('#file-id').val();
            var username = $('.username').val();
            var docuType = $('#docu-type').val();
            var filename = $('#docu-name').text();
            // alert(filename);
            $.ajax({
                url: '../tabs/document_process.php?',
                method: 'POST',
                data: {
                    fileidShare: fileidShare,
                    username: username,
                    docuType: docuType,
                    filename: filename,
                },
                success: function(data) {
                    alert(data);
                    if (data.indexOf("Successful") >= 0) {
                        $("#uploadModal").modal("hide");
                        $("#content").load("../tabs/document_tab.php");
                        $(".modal-backdrop").hide();
                    }
                }
            });

        });

        // ------------------------------------------------------------------ CHANGE SEM
        $('#fetchSem').on('change', function() {
            var value = $(this).val();
            $.ajax({
                url: '../tabs/document_tab.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    value: value,
                },
                beforeSend: function() {
                    $('#user_table').html("<span><font color = 'blue'>Loading data...</font></span>");
                },
                success: function(data) {
                    $('#content').html(data);
                }
            });
        });
        // _______________________________________________________________________ UPLOAD FILES
        $('#upload_form').on('submit', function(event) {
            event.preventDefault();
            var sem = $('#sem-value').val();
            var course = $('#file-course').val();
            var form_data = new FormData(this);
            var file = $('#file')[0].files[0];
            var ext1 = $('#file').val();
            if (ext1 != "") {
                var totalSize = file.size;
            } else {
                var totalSize = 0;
            }

            var maxFilesize = ((1024 * 1024) * 7);
            form_data.append("sem", sem);
            form_data.append("course", course);
            var ext = ext1.split(".");
            ext = ext[ext.length - 1].toLowerCase();
            var fileExtension = ["doc", "docx", "ppt", "pptx", "xls", "xlsx", "pdf", "csv", "txt"];

            if (ext1 == "") { // check if file input is empty
                $('#message').html("Please choose a file to upload");
            } else if (fileExtension.lastIndexOf(ext) == -1) { // check if the file has a valid extension
                $('#message').html("Invalid file type");
                $("#file").val("");
            } else if (totalSize > maxFilesize) {
                $('#message').html("File too large! maximum of 7mb");
                $("#file").val("");
            } else {
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                $(".progress-bar").width(percentComplete + '%');
                                $(".progress-bar").html(percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    url: '../tabs/document_addfile.php', // <-- point to server-side PHP script 
                    method: 'POST',
                    data: form_data,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        // $('#message').html("Uploading...");
                        $(".progress-bar").width('0%');
                    },
                    success: function(data) {
                        $('#message').html(data);
                        if (data.indexOf('Successfully') >= 0) {
                            alert(data);
                            $("#uploadModal").modal("hide");
                            $("#content").load("../tabs/document_tab.php");
                            $(".modal-backdrop").hide();
                            if (data.indexOf("Document") >= 0) {
                                $("#doc_tab").trigger('click');
                            } else {
                                $("#report_tab").trigger('click')
                            };
                        } else if (data.indexOf('Error!') >= 0) {
                            $("#file").val("");
                            $(".progress-bar").width('0%');
                        }
                    }
                });
            }

        });

        // ------------------------------------------------------------------------ DEL

        $('.table tbody').on('click', '.btn-delfiles', function() {
            var data = table.row($(this).parents('tr')).data();
            var delfile = data[1];
            var filename = data[3];
            console.log(delfile);
            $('#dataDel').val(delfile);
            $('.fileTodel').html(filename + ' ?');

        })

        $('#confirm-del').on('click', function() {
            var fileDel = $('#dataDel').val();
            var username = $('#username').val();
            $.ajax({
                url: '../tabs/document_process.php?',
                method: 'POST',
                data: {
                    fileDel: fileDel,
                },
                success: function(data) {
                    alert(data);
                    if (data.indexOf("has") >= 0) {
                        $("#uploadModal").modal("hide");
                        $("#content").load("../tabs/document_tab.php");
                        $(".modal-backdrop").hide();
                    }
                }
            });

        })

    });
</script>