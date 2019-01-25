<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/mail_body.php";

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

function send_mail($to, $subject, $body) {
	global $util;
	
	$mail = init_mail ();

	$mail->addAddress ( $to );
	$mail->Subject = $subject;
	$mail->Body = $body . $util["footer"];
	
	//echo "Mail to '" . $to . "' with subject '" . $subject . "'<br><br>Body: " . $body . $util["footer"] . "<br>";
    //echo "Mail to '" . $to . "<br>";
	
	
	try{
		if(!$mail->send ()){
			throw new Exception;
		}
	}catch(Exception $e){
		echo "<script language='javascript'> 
				alert('Eine E-Mail konnte nicht gesendet werden');
			</script>";
		return false;
	}
	
	return true;

}

function send_html_mail($to, $subject, $body) {
	global $util;
	
	$mail = init_mail ();

	$mail->isHTML ( true );
	$mail->addAddress ( ( string ) $to );
	$mail->Subject = $subject;
	$mail->Body = $body . $util["footer"];
	
	//echo "Mail to '" . $to . "' with subject '" . $subject . "'<br>Body: " . $body . $util["footer"] . "<br>";
	
	try{
		if(!$mail->send ()){
			throw new Exception;
		}
	}catch(Exception $e){	
		echo "<script language='javascript'>
				alert('Eine E-Mail konnte nicht gesendet werden');
			</script>";
		return false;
	}
	
	return true;
}

function send_mails($recipients, $subject, $body) {
	$noError = true;
	foreach (filter_deactivated($recipients) as $to) {
		if(!send_mail($to->email, $subject, $body)){
			$noError = false;
		}
	}
	return $noError;
}

function send_html_mails($recipients, $subject, $body) {
	$noError = true;
	foreach (filter_deactivated($recipients) as $to) {
		if(!send_html_mail($to->email, $subject, $body)){
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