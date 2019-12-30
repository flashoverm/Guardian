<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/mail_body.php";
require_once LIBRARY_PATH . "/log.php";

require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/SMTP.php";
require_once "phpmailer/src/Exception.php";

function init_mail() {
	global $config;

	$mail = new PHPMailer ( true );
	$mail->isSMTP ();
	$mail->Host = $config ["mail"] ["host"];
	$mail->SMTPAuth = true;
	$mail->Username = $config ["mail"] ["username"];
	$mail->Password = $config ["mail"] ["password"];
	$mail->SMTPSecure = $config ["mail"] ["secure"];
	$mail->Port = $config ["mail"] ["port"];
	$mail->setFrom ( $config ["mail"] ["fromaddress"], $config ["mail"] ["fromname"] );
	$mail->CharSet = 'utf-8';     
	//$mail->SMTPDebug = 2;
	return $mail;
}

function send_mail($to, $subject, $body, $attachment = NULL) {
	global $util;
	
	try{
		$mail = init_mail ();

		$mail->addAddress ( $to );
		$mail->Subject = $subject;
		$mail->Body = $body . $util["footer"];
		
		if($attachment != NULL){
			$mail->AddAttachment($attachment, $name = basename($attachment),  $encoding = 'base64', $type = 'application/pdf');
		}
	
		if(!$mail->send ()){
			throw new Exception;
		}
		log_message("Mail sent to " . $to . ": " . $subject);
		
	}catch(Exception $e){
	    log_message("Cant send mail to " . $to . ": " . $subject);
	    
		echo "<script language='javascript'> 
				alert('Eine E-Mail konnte nicht gesendet werden');
			</script>";
		return false;
	}
	
	return true;

}

function send_mails($recipients, $subject, $body, $attachment = NULL) {
	$noError = true;
	foreach (filter_deactivated($recipients) as $to) {
		if(!send_mail($to->email, $subject, $body, $attachment)){
			$noError = false;
		}
	}
	return $noError;
}

function filter_deactivated($unfiltered){
    $filtered = array ();
    
    foreach ($unfiltered as $user) {
        if($user->loginenabled == 1 && isset($user->password)){
            $filtered [] = $user;
        }
    }
    return $filtered;
}

?>