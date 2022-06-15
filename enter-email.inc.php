<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include_once("connections/connection.php");
if (isset($_POST["email"]) && (empty($_POST['email']))) {
    echo "<script>alert('Enter your email')</script>";
}
if (isset($_POST["email"]) && (!empty($_POST["email"]))) {
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $error = "";
    if (!$email) {
        $error .= "<p>Invalid email address please type a valid email address!</p>";
    } else {
        $sel_query = "SELECT * FROM `users` WHERE email_users='" . $email . "'";
        $results = mysqli_query($conn, $sel_query);
        $row = mysqli_num_rows($results);
        if ($row == "") {
            $error .= "<p>No user is registered with this email address!</p>";
        }
    }
    if ($error != "") {
        echo "<div class='error'>" . $error . "</div>
   <br /><a href='javascript:history.go(-1)'>Go Back</a>";
    } else {

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        date_default_timezone_set('Asia/Manila');
        $expFormat = mktime(
            date("H"),
            date("i"),
            date("s"),
            date("m"),
            date("d") + 1,
            date("Y")
        );
        $expDate = date("Y-m-d H:i:s", $expFormat);
        $key = md5(2418 * 2 . $email);
        $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
        $key = $key . $addKey;
        // Insert Temp Table
        mysqli_query(
            $conn,
            "INSERT INTO `pwdreset` (`email`, `key`, `expDate`)VALUES ('" . $email . "', '" . $key . "', '" . $expDate . "');"
        );

        $output = '<p>Dear user,</p>';
        $output .= '<p>Please click on the following link to reset your password.</p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p><a href="localhost/filemanagementsystems1/reset-password.php?key=' . $key . '&email=' . $email . '&action=reset"target="_blank">localhost/filemanagementsystems1/reset-password.php?key=' . $key . '&email=' . $email . '&action=reset</a></p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p>Please be sure to copy the entire link into your browser.The link will expire after 1 day for security reason.</p>';
        $output .= '<p>If you did not request this forgotten password email, no action is needed, your password will not be reset. However, you may want to log into your account and change your security password as someone may have guessed it.</p>';
        $output .= '<p>Thanks,</p>';
        $output .= '<p>FMS_SCS</p>';
        $body = $output;
        $subject = "Password Recovery - FMS_SCS";

        $email_to = $email;
        $fromserver = "noreply@fmsscs.com";

        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com"; // Enter your host here
        $mail->SMTPAuth = true;
        $mail->Username = "fmsscs.dept@gmail.com"; // Enter your email here
        $mail->Password = "yuebdajuorcznaqa"; //Enter your password here
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('fmsscs.dept@gmail.com', 'FMS_SCS');
        $mail->AddAddress($email_to);

        $mail->IsHTML(true);

        $mail->FromName = "FMS_SCS";
        $mail->Sender = $fromserver; // indicates ReturnPath header
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "<div class='error'><p>An email has been sent to you with instructions on how to reset your password.</p></div><br/><br/><br/>";
        }
    }
} else {

?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <div class="container p-5" style="width: 500px;background:#eee">
        <h4 class="text-center">WFMS_SCS Reset Password</h4>
        <form method="post" action="" name="reset"><br /><br />
            <label><strong>Enter Your Email Address:</strong></label><br /><br />
            <input type="email" name="email" class="form-control email" placeholder="username@email.com" />
            <br /><br />
            <input type="submit" class="btn btn-primary w-100 send-email" value="Reset Password" />
        </form>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
    </div>

<?php } ?>