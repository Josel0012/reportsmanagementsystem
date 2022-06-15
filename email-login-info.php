<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location:./login.php');
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];

    include_once 'connections/connection.php';
    $query = mysqli_query($conn, "SELECT * FROM users WHERE `user_ID` = '$id'");
    $info = mysqli_fetch_assoc($query);
    date_default_timezone_set('Asia/Manila');
    $today = date("F - d , Y");

    $name = $info['First_name'];
    $username = $info['username'];
    $lastname = $info['last_name'];
    $email = $info['email_users'];
    if ($info['user_type'] == 'school_dean') {
        $userType = "School Dean";
    } elseif ($info['user_type'] == "program_coordinator") {
        $userType = "Program Coordinator {$info['course_users']}";
    } elseif ($info['user_type'] == "system_administrator") {
        $userType = "School Dean";
    } else {
        $userType = "Faculty Member";
    }

    // $data = "";
    // $data .= "Good day! <strong>" . $name . $lastname . "</strong><br>";
    // $data .= "<br><br>You are now part of the SCS FAMILY. Here is your login credentials</br>";
    // $data .= "<br><strong>Username: </strong>" . $username . "<br><strong>Password: </strong>scs_faculty1234";

    // $mpdf = new \Mpdf\Mpdf();
    // $mpdf->WriteHTML($data);
    // $mpdf->Output();

    $html = '
<!-- defines the headers/footers - this must occur before the headers/footers are set -->
<!--mpdf
<htmlpageheader name="myHTMLHeader1">
<table width="100%" style="border-bottom: 1px solid #eee; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td width="20%"><img src="./img/cct.jpg.png" style="width:100px;height:100px"></td>
<td width="60%" align="center">
<div style="color:red;font-size:14px;margin-top:0px">
<span>CITY COLLEGE OF TAGAYTAY<span><br>
<span>Akle St., Kaybagal South, Tagaytay City<span><br>
<span>Telephone No. (046) 483 – 0470 / (046) 483 – 0672<span><br>
<span>SCHOOL OF COMPUTER STUDIES<span>
</div>
</td>
<td width="20%" style="text-align: right;"><img src="./img/scslogo.png" style="width:100px;height100px"></td>
</tr></table>
</htmlpageheader>
mpdf-->

<!-- set the headers/footers - they will occur from here on in the document -->
<!--mpdf
<sethtmlpageheader name="myHTMLHeader1" page="O" value="on" show-this-page="1" />

mpdf-->

<p style="margin-left:65px"><b>WEB-BASED FILE MANAGEMENT SYTEM LOGIN INFORMATION<b></p>
<br>
<p style="text-align: right;">' . $today . '</p>
<span>GREETINGS!</span><br><br>
<span><b>' . $name . " " . $lastname . '</b></span><br>
<span>' . $userType . '</span><br><br>


<p style = "text-indent: 50px">
    Welcome to the SCS family! This letter will offer you with your login credentials for the SCS Departments File Management System.</p>
<p style = "text-indent: 50px">
    A device with an internet connection is required to use the app. Navigate to this address in your browser: <a href="http://filemanagementsystemscs.epizy.com/">http://filemanagementsystemscs.epizy.com/</a>
</p>
<hr>
<p style = "text-indent: 50px">
The username and password shown below are temporary and can be changed after logging in:
</p>
<p style="text-indent: 50px"><b>Username:</b> ' . $username . '</p>
<p style="margin-left: 50px"><b>Password:</b> scs_faculty1234</p><br>

<p style="text-align: right;">
Regards,<br>
FMS_SCS
</p>

';



    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'c',
        'format' => 'A4',
        'margin_left' => 32,
        'margin_right' => 25,
        'margin_top' => 47,
        'margin_bottom' => 47,
        'margin_header' => 10,
        'margin_footer' => 10,
    ]);

    // $mpdf->mirrorMargins = 1;    // Use different Odd/Even headers and footers and mirror margins

    $mpdf->WriteHTML($html);
    $pdf = $mpdf->Output('', 'S');


    $enquirydata = [
        'email' => $email,
        'body' => $html,
    ];

    function sendEmail($pdf, $enquirydata)
    {

        $mail = new PHPMailer(true);
        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'fmsscs.dept@gmail.com';                     //SMTP username
            $mail->Password   = 'yuebdajuorcznaqa';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            $mail->addStringAttachment($pdf, 'Log-in_Info.pdf');

            //Recipients
            $mail->setFrom('fmsscs.dept@gmail.com', 'FMS_SCS');
            $mail->addAddress($enquirydata['email']);     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            $mail->addReplyTo('fmsscs.dept@gmail.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            $body = "";
            $body = "<p>GREETINGS!</p><br>";
            $body .= "<p style='text-indent: 50px'>Welcome to the SCS family! Your account is now available. The attachment below contains your temporary username and password.</p><br>";
            $body .= "<p>Thank you.</p>";

            //Content
            $mail->isHTML(true);
            $mail->FromName = "FMS_SCS";                                //Set email format to HTML
            $mail->Subject = 'FMS_SCS Login Credentials';
            $mail->Body    = $body;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Email has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    sendEmail($pdf, $enquirydata);
}
