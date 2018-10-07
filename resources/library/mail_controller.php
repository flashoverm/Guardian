<?php
require_once 'mail.php';
require_once 'mail_body.php';
require_once 'db_connect.php';
require_once 'db_engines.php';
require_once 'db_user.php';
require_once 'db_event.php';

require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));

require_once LIBRARY_PATH . '/class/EventReport.php';
require_once LIBRARY_PATH . '/class/ReportUnit.php';
require_once LIBRARY_PATH . '/class/ReportUnitStaff.php';


function mail_insert_event($event_uuid, $manager_uuid, $informOther) {
	global $config;
	global $bodies;

	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "Neuer Wache eingestellt";
	
	$body =  $bodies["event_insert"] . $link;

	$manager = get_user( $manager_uuid );
	send_mail ( $manager->email, $subject, $body );

	if ($informOther) {
		mail_publish_event ( $event_uuid, $manager_uuid );
	}
}

function mail_publish_event($event_uuid, $manager_uuid) {
	global $config;
	global $bodies;
	
	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "Neuer Wache veröffentlicht";
	
	$body = $bodies["event_publish"] . $link;
	
	$manager = get_user($manager_uuid);
	
	$recipients = get_manager_except_engine($manager->engine);
	send_mails($recipients, $subject, $body);
}

function mail_subscribe_staff_user($event_uuid, $user_email, $user_engine_uuid, $send_mail) {
	global $db;
	global $config;
	global $bodies;
	
	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "In Wache eingeschrieben";
	
	$body = $bodies["event_subscribe"] . $link;

	if($config ["settings"] ["usermailonsubscription"] && $send_mail){
		send_mail ( $user_email, $subject, $body );
	}

	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		$body = $bodies["event_subscribe_manager"] . $link;

		$recipients = get_manager_of_engine($user_engine_uuid);
		send_mails($recipients, $subject, $body);
	}

	$query = "SELECT COUNT(*) AS empty_pos FROM staff WHERE user IS NULL AND event = '" . $event_uuid . "'";
	$result = $db->query ( $query );
	$count = $result->fetch_row () [0];
	if ($count == 0) {
		$subject = "Wache voll belegt";
		
		$body = $bodies["event_full"] . $link;
		
	} else if ($config ["settings"] ["creatormailonsubscription"]) {
		
		$body = $bodies["event_subscribe_engine"] . $link;
		
	} else {
		return;
	}

	$creator = get_events_creator($event_uuid);
	send_mail($creator, $subject, $body);
}

function mail_remove_staff_user($staff_uuid, $event_uuid) {
	global $config;
	global $bodies;
	
	$link = $config ["urls"] ["baseUrl"] . "/guardian/event_details.php?id=" . $event_uuid;
	$subject = "Aus Wache entfernt";
	
	$body = $bodies["event_unscribe"] . $link;

	$user = get_staff_user($staff_uuid);
	send_mail ( $user->email, $subject, $body );
	
	$body = $bodies["event_unscribe_engine"] . $link;

	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		$recipients = get_manager_of_engine($user->engine);
		send_mails($recipients, $subject, $body);
	}
}

function mail_delete_event($event_uuid) {
	global $config;
	global $bodies;
	
	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "Wache abgesagt";
	
	$body = $bodies["event_delete"] . $link;
	
	$recipients = get_events_staff($event_uuid);
	send_mails($recipients, $subject, $body);
}

function mail_add_manager($mail_manager, $password) {
	global $bodies;
	
	$subject = "Zugangsdaten Wachbauftragter";
	
	$body = $bodies["manager_add"] . $bodies["login"] . $mail_manager . $bodies["password"] . $password . $bodies["manager_add2"];
	
	send_mail ( $mail_manager, $subject, $body );
}

function mail_reset_password($manager_uuid, $password) {
	global $db;
	global $bodies;
	$subject = "Passwort zurückgesetzt";
	
	$body = $bodies["manager_reset_password"] . $bodies["password"] . $password . $bodies["manager_reset_password2"];

	$query = "SELECT email FROM user WHERE uuid = '" . $manager_uuid . "'";
	$result = $db->query ( $query );
	if ($result) {
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_row ();
			send_mail ( $data [0], $subject, $body );
			$result->free ();
		}
	}
}

function mail_send_report($report){
	global $config;
	global $db;
	
	$subject = "Wachbericht";
	
	$body = $report->toMail();
		
	if($report->engine == $config ["backoffice"]){
		send_mail ( $report->engine, $subject, $body );
	} else {
		$engine = get_engine_from_name($report->engine);
		
		$query = "SELECT email FROM user WHERE ismanager = TRUE AND engine = '" . $engine->uuid . "'";
		$result = $db->query ( $query );
		if ($result) {
			if (mysqli_num_rows ( $result )) {
				while ( $email = $result->fetch_row () ) {
					send_mail ( $email [0], $subject, $body );
				}
				$result->free ();
			}
		}
	}

}
?>