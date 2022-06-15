<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location:./login.php');
    exit;
}
require_once __DIR__ . '/vendor/autoload.php';

echo $_GET['user_id'];
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
<sethtmlpageheader name="myHTMLHeader1Even" page="E" value="on" />
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

    $mpdf->Output('login-credentials.pdf', 'I');
}
