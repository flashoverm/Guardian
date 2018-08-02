<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/SMTP.php";

function init_mail(){
	$mail = new PHPMailer(true);
	$mail->isSMTP();
	$mail->Host = 'host33.checkdomain.de';
	$mail->SMTPAuth = true;
	$mail->Username = 'thral1';
	$mail->Password = 'Striker1';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	$mail->setFrom('markus@thral.de', 'Wachverwaltung Feuerwehr Landshut');
	return $mail;
}
	
function send_mail($to, $subject, $body){
	$mail = init_mail();

	$mail->addAddress($to);
	$mail->Subject = $subject;
	$mail->Body    = $body;

	$mail->send();
	echo 'Message has been sent';
}

function send_html_mail($to, $subject, $body){
	$mail = init_mail();

	$mail->isHTML(true);
	$mail->addAddress((string)$to);
	$mail->Subject = $subject;
	$mail->Body = $body;

	$mail->send();
	echo 'HTML Message has been sent';
}

?>