<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('phpmailer/src/Exception.php');
include('phpmailer/src/PHPMailer.php');
include('phpmailer/src/SMTP.php');

//connecting the database
$dbconn = pg_connect("host=localhost dbname=LabAssetManagement user=postgres password=12345")or die('Could not connect: ' . pg_last_error());

function sanitize_input($text){
    $text = htmlspecialchars($text);
    $text = trim($text);
    $text = stripslashes($text);

    return $text;
}

function query($query){
    global $dbconn;
    $result = pg_query($query);
    $rows = [];
    while($row = pg_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

function sendMail($receiver, $subyek, $message){
    $email_sender = 'maria.auleria@binus.edu';
    $password = 'Code-lyoko1';
    $name_sender = 'no-reply';

    $email_receiver = $receiver;
    $subjek = $subyek;
    $pesan = $message;

    $mail = new PHPMailer();
    $mail->isSMTP();

    $mail->Host = 'smtp.office365.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tsl';
    // $mail->SMTPDebug = 2;

    $mail->Username = $email_sender;
    $mail->Password = $password;
    $mail->setFrom('asset.management@binus.ac.id', $name_sender);
    
    $mail->addAddress($email_receiver);
    $mail->Subject = $subjek;
    $mail->Body = $pesan;

    return $mail->send();
    
}

?>