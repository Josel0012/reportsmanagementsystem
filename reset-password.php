<?php
include_once("connections/connection.php");
if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"]) && ($_GET["action"] == "reset") && !isset($_POST["action"])) {

    $key = $_GET["key"];
    $email = $_GET["email"];

    date_default_timezone_set('Asia/Manila');
    $curDate = date("Y-m-d H:i:s");

    $query = mysqli_query($conn, "SELECT * FROM pwdreset WHERE `key` = '$key' and email = '$email'");
    $error = "";
    // $row = mysqli_fetch_assoc($query);
    $row = mysqli_num_rows($query);
    if ($row == "") {
        $error .= '<h2>Invalid Link</h2><p>The link is invalid/expired. Either you did not copy the correct link from the email, or you have already used the key in which case it is deactivated.</p><p><a href="index.php">Click here</a> to reset password.</p>';
    } else {
        $row = mysqli_fetch_assoc($query);
        $expDate = $row['expDate'];
        if ($expDate >= $curDate) {
?>
            <br />
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <div class="container p-5" style="width: 500px;background:#eee">
                <h4 class="text-center">WFMS_SCS Change Password</h4>
                <form method="post" action="" name="update">
                    <input type="hidden" name="action" value="update" />
                    <br /><br />
                    <label><strong>Enter New Password:</strong></label><br />
                    <input type="password" class="form-control" name="pass1" maxlength="15" required />
                    <br /><br />
                    <label><strong>Re-Enter New Password:</strong></label><br />
                    <input type="password" class="form-control" name="pass2" maxlength="15" required />
                    <br /><br />
                    <input hidden type="text" name="email" value="<?php echo $email; ?>" />
                    <input type="submit" class="btn btn-secondary w-100" value="Reset Password" />
                </form>
            </div>

<?php
        } else {
            $error .= "<h2>Link Expired</h2><p>The link is expired. You are trying to use the expired link which as valid only 24 hours (1 days after request).<br /><br /></p>";
        }
    }
    if ($error != "") {
        echo "<div class='error'>" . $error . "</div><br />";
    }
} // isset email key validate end

if (isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"] == "update")) {
    $error = "";
    $pass1 = mysqli_real_escape_string($conn, $_POST["pass1"]);
    $pass2 = mysqli_real_escape_string($conn, $_POST["pass2"]);
    $email = $_POST["email"];
    $curDate = date("Y-m-d H:i:s");
    if ($pass1 != $pass2) {
        $error .= "<p>Password do not match, both password should be same.<br /><br /></p>";
    }
    if ($error != "") {
        echo "<div class='error'>" . $error . "</div><br />";
    } else {
        $newpass = password_hash($pass1, PASSWORD_DEFAULT);

        mysqli_query($conn, "UPDATE `users` SET `pass`= '$newpass' WHERE `email_users` = '$email '") or  die(mysqli_error($conn));
        mysqli_query($conn, "DELETE FROM `pwdreset` WHERE `email`='$email'");

        echo '<div class="error"><p>Congratulations! Your password has been updated successfully.</p><p><a href="login.php">Click here</a> to Login.</p></div><br />';
    }
}
?>