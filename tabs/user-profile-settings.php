<?php
session_start();
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location:../index.php');
    exit;
}
include_once '../connections/connection.php';
?>
<link rel="stylesheet" href="../css/profile.css">

<!-- Button trigger modal -->


<!-- Modal -->
<div id="modelId" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <h4 class="modal-title w-100 text-center" style="color:white">Upload Photo</h4>
                <button type="button" class="bx bx-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method='post' enctype="multipart/form-data" id="upload_photo">
                    <p class="reg">
                    <div id="message"></div>
                    </p>
                    <input hidden type="text" name="doctype" id="doctype-hidden">
                    <input type='file' name='file' id='file' class='form-control mb-1'>
                    <font class="ml-4 mb-5" style="font-size: 13px" color="red">(png only; 300px)</font>
                    <div>
                        <b>Convert image here: </b><br>
                        <a href="https://www.iloveimg.com/compress-image">https://www.iloveimg.com/compress-image</a>
                    </div>

                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <input type='submit' class='btn btn-success mt-2' name="submit" value='Upload' id='btn_upload'>
                </form>
            </div>

        </div>

    </div>
</div>
<?php $query = mysqli_query($conn, "SELECT img_name FROM profile_img WHERE user_ID = '{$_SESSION['user_ID']}'");
$row = mysqli_fetch_assoc($query);
$num_rows = mysqli_num_rows($query);
$img = $row['img_name'];
?>
<style>
    .container-fluid.label {
        -moz-box-shadow: 0 0 5px #999;
        /* background-color: white; */
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;

    }

    .document-label {
        /* font-size: 30px; */
        font-weight: bold;
        border-radius: 5px;
        letter-spacing: 5px;
        text-transform: capitalize;

    }
</style>
<div class="container-fluid label pl-sm-3 p-sm-2">
    <h4 class="document-label text-center">Edit Profile</h4>
</div>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <!-- style="height:250px; width: 250px" -->
                <?php if ($num_rows > 0) { ?>
                    <img class="img-fluid rounded-circle mt-5" style="height:225px; width: 225px" src=<?php echo "../img/{$img}"; ?>>
                <?php } else { ?>
                    <img class="rounded-circle mt-5" style="height:225px; width: 225px" src=<?php echo "../img/profile.png"; ?>>
                <?php } ?>
                <div class="camera-icon w-100 text-center">
                    <button class="btn bxs-camera-icon " data-toggle="modal" data-target="#modelId"><i class='bx bxs-camera'></i></button>
                </div>
                <span class="font-weight-bold"><?php echo $_SESSION['First_name'] ?></span>
                <span class="text-black-50" style="word-break:break-all"><?php echo $_SESSION['email_users'] ?></span>
            </div>
        </div>
        <?php
        $queryInfo = mysqli_query($conn, "SELECT * FROM users WHERE user_ID = '{$_SESSION['user_ID']}'");
        $rowInfo = mysqli_fetch_assoc($queryInfo);

        ?>
        <div class="col-md-6 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Info</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control profile-name" placeholder="First name" value="<?php echo $rowInfo['First_name'] ?>" disabled></div>
                    <div class="col-md-6"><label class="labels">Lastname</label><input type="text" class="form-control profile-lastname" value="<?php echo $rowInfo['last_name'] ?>" placeholder="Last name" disabled></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Username</label><input type="text" class="form-control profile-username" placeholder="enter a new username" value="<?php echo $rowInfo['username'] ?>" disabled></div>
                    <!-- <div class="col-md-12"><label class="labels">Education</label><input type="text" class="form-control" placeholder="education" value=""></div> -->
                </div>
                <div class="row mt-3">
                    <!-- <div class="col-md-12"><label class="labels">PhoneNumber</label><input type="text" class="form-control" placeholder="enter phone number" value=""></div>
                    <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control" placeholder="enter address" value=""></div> -->
                    <div class="col-md-12"><label class="labels">Email Address</label><input type="text" class="form-control profile-email" placeholder="enter your email address" value="<?php echo $rowInfo['email_users'] ?>"></div>
                    <!-- <div class="col-md-12"><label class="labels">Education</label><input type="text" class="form-control" placeholder="education" value=""></div> -->
                </div>

                <!-- <div class="row mt-3">
                    <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control" placeholder="country" value=""></div>
                    <div class="col-md-6"><label class="labels">State/Region</label><input type="text" class="form-control" value="" placeholder="state"></div>
                </div> -->

            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><span>Other Info</span></div><br>
                <div class="col-md-12"><label class="labels">Phone Number</label><input type="text" class="form-control profile-phone" placeholder="Phone Number" maxlength="11" value="<?php echo $rowInfo['phone'] ?>"></div> <br>
                <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control profile-address" placeholder="Address" value="<?php echo $rowInfo['address'] ?>"></div>
                <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="button">Save Profile</button></div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!-- <script src="../js/dashboardsFunctions.js"></script> -->
<script src="../js/profileSettings.js"></script>