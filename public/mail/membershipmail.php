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
$_POST = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['name'] != '' and $_POST['email'] != '') {

        $name = $_POST['name'];
        $dateOfBirth = $_POST['dateOfBirth'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $delivery = $_POST['delivery'];

        $subject = 'Nova Poruka | Članstvo forma';


        $toemail = 'kristina.cvijovic@fk-buducnost.me'; // Your Email Address
        $toname = 'FK Budućnost'; // Your Name

        $mail->CharSet = 'UTF-8';
        $mail->SetFrom('no-reply@fk-buducnost.me', $name . ' - FK Budućnost Članstvo forma');
        $mail->AddReplyTo($email, $name);
        $mail->AddAddress($toemail, $toname);
        $mail->Subject = $subject;

        $name = isset($name) ? "Ime: $name<br><br>" : '';
        $dateOfBirth = isset($dateOfBirth) ? "Datum rođenja: $dateOfBirth<br><br>" : '';
        $email = isset($email) ? "Email: $email<br><br>" : '';
        $phone = isset($phone) ? "Telefon: $phone<br><br>" : '';
        $address = isset($address) ? "Adresa: $address<br><br>" : '';
        $city = isset($city) ? "Grad: $city<br><br>" : '';
        $country = isset($country) ? "Država: $country<br><br>" : '';
        $delivery = isset($delivery) ? "Način plaćanja i dostave: $delivery<br><br>" : '';

        $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Poruka poslata sa stranice: ' . $_SERVER['HTTP_REFERER'] : '';

        $body = "$name $dateOfBirth $email $phone $address $city $country $delivery $referrer";
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
