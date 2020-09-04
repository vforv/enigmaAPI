<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();

//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'server.hostingo.me';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'no-reply@fk-buducnost.me';                 // SMTP username
$mail->Password = 'fQurCJ24mKl2';                           // SMTP password
// $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$message = "";
$status = "false";
$name = "";
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['name'] != '' and $_POST['email'] != '' and $_POST['message'] != '') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $subject = 'Nova Poruka | Kontakt forma';

        // $botcheck = $_POST['form_botcheck'];

        $toemail = 'info@fk-buducnost.me'; // Your Email Address
        $toname = 'FK Budućnost'; // Your Name

        $mail->CharSet = 'UTF-8';
        $mail->SetFrom('no-reply@fk-buducnost.me', $name . ' - FK Budućnost Kontakt forma');
        $mail->AddReplyTo($email, $name);
        $mail->AddAddress($toemail, $toname);
        $mail->Subject = $subject;

        $name = isset($name) ? "Name: $name<br><br>" : '';
        $email = isset($email) ? "Email: $email<br><br>" : '';
        $message = isset($message) ? "Message: <br><br> $message<br><br>" : '';

        $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Poruka poslata sa stranice: ' . $_SERVER['HTTP_REFERER'] : '';

        $body = "$name $email $message $referrer";
        $name = $_POST['name'];
        $mail->MsgHTML($body);
        $sendEmail = $mail->Send();

        if ($sendEmail == true) {
            $message = 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.';
            $status = "true";
        } else {
            $message = 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '';
            $status = "false";
        }
    } else {
        $message = 'Please <strong>Fill up</strong> all the Fields and Try Again.';
        $status = "false";
    }
} else {
    $message = 'An <strong>unexpected error</strong> occured. Please Try Again later.';
    $status = "false";
}

$status_array = array('message' => $message, 'status' => $status);
echo json_encode($status_array);