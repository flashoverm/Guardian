<?php
require_once 'mail.php';
require_once 'db_connect.php';
require_once 'db_engines.php';
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));

require_once LIBRARY_PATH . '/class/EventReport.php';
require_once LIBRARY_PATH . '/class/ReportUnit.php';
require_once LIBRARY_PATH . '/class/ReportUnitStaff.php';


function mail_insert_event($event_uuid, $manager_uuid, $informOther) {
	global $db;
	global $config;

	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "Neuer Wache eingestellt";
	$body = "Eine neue Wache wurde eingestellt: \n\n" . $link;

	$query = "SELECT email FROM user WHERE uuid = '" . $manager_uuid . "'";

	$result = $db->query ( $query );
	if ($result) {
		if (mysqli_num_rows ( $result )) {
			while ( $email = $result->fetch_row () ) {
				send_mail ( $email [0], $subject, $body );
			}
			$result->free ();
		}
	}

	if ($informOther) {
		mail_publish_event ( $event_uuid, $manager_uuid );
	}
}

function mail_publish_event($event_uuid, $manager_uuid) {
	global $db;
	global $config;

	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "Neuer Wache veröffentlicht";
	$body = "Eine neue Wache wurde veröffentlicht: \n\n" . $link;

	$query = "SELECT email FROM user WHERE ismanager = TRUE AND NOT uuid = '" . $manager_uuid . "'";

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

function mail_delete_event($event_uuid) {
	global $db;

	$subject = "Wache abgesagt";
	$body = "Eine Wache bei der Sie sich eingetragen haben wurde abgesagt!";

	$query = "SELECT email FROM user, staff WHERE user.uuid = staff.user AND staff.event = '" . $event_uuid . "'";
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

function mail_subscribe_staff_user($event_uuid, $user_email, $user_engine_uuid) {
	global $db;
	global $config;

	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "In Wache eingeschrieben";
	$body = "Sie haben sich in einer Wache eingeschrieben: " . $link;

	send_mail ( $user_email, $subject, $body );

	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		$body = "Jemand aus Ihrem Zug hat sich in eine Wache eingeschrieben: " . $link;

		$query = "SELECT email FROM user WHERE ismanager = TRUE AND engine = '" . $user_engine_uuid . "'";
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

	$query = "SELECT COUNT(*) AS empty_pos FROM staff WHERE user IS NULL AND event = '" . $event_uuid . "'";
	$result = $db->query ( $query );
	$count = $result->fetch_row () [0];
	if ($count == 0) {
		$subject = "Wache voll belegt";
		$body = "Eine von Ihnen erstellte Wache ist voll belegt: " . $link;
	} else if ($config ["settings"] ["mgrmailonsubscription"]) {
		$body = "Jemand hat sich in eine von Ihnen erstellte Wache eingeschrieben: " . $link;
	} else {
		return;
	}

	$query = "SELECT email FROM user, events WHERE events.manager = user.uuid AND events.uuid = '" . $event_uuid . "'";
	$result = $db->query ( $query );
	if ($result) {
		if (mysqli_num_rows ( $result )) {
			$email = $result->fetch_row ();
			send_mail ( $email [0], $subject, $body );
			$result->free ();
		}
	}
}

function mail_remove_staff_user($staff_uuid, $event_uuid) {
	global $db;
	global $config;

	$link = $config ["urls"] ["baseUrl"] . "/guardian/event_details.php?id=" . $event_uuid;
	$subject = "Aus Wache entfernt";
	$body = "Sie wurden durch den Wachbeauftragten von der Wache entfernt: " . $link;

	$query = "SELECT * FROM user, staff WHERE user.uuid = staff.user AND staff.uuid = '" . $staff_uuid . "'";
	$result = $db->query ( $query );
	if ($result) {
		if (mysqli_num_rows ( $result )) {
			$user = $result->fetch_object ();
			send_mail ( $user->email, $subject, $body );
			$result->free ();

			global $mail_engine_manager_on_subscription;
			if ($mail_engine_manager_on_subscription) {
				$body = "Jemand aus Ihrem Zug wurde durch den Wachbeauftragten von der Wache entfernt: " . $link;

				$query = "SELECT email FROM user WHERE ismanager = TRUE AND engine = '" . $user->engine . "'";
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
	}
}

function mail_add_manager($mail_manager, $password) {
	$subject = "Zugangsdaten Wachbauftragter";
	$body = "Für Sie wurde ein Zugang als Wachbeauftragter angelegt: \nLogin: " . $mail_manager . " \nPasswort: " . $password;
	send_mail ( $mail_manager, $subject, $body );
}

function mail_reset_password($manager_uuid, $password) {
	global $db;

	$subject = "Passwort zurückgesetzt";
	$body = "Ihr Passwort wurde zurückgesetzt auf: " . $password . "\n Sie können es im Portal in ihr Wunschkennwort ändern.";

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
	$body = $report->toString();
		
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