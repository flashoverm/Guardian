<?php
require_once 'mail.php';
require_once 'db_connect.php';
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));

function mail_insert_event($event_uuid, $manager_uuid) {
	global $db;
	global $config;

	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "Neuer Wache eingestellt";
	$body = "Eine neue Wache wurde eingestellt: \n\n" . $link;
	// TODO add more infos of the event (evtl: as html)

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
	// TODO add more infos of the event (evtl: as html)

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

	$link = $config ["urls"] ["baseUrl"] . "/guardian/event_details.php?id=" . $event_uuid;
	$subject = "In Wache eingeschrieben";
	$body = "Sie haben sich in einer Wache eingeschrieben: " . $link;
	// TODO add more infos of the event (evtl: as html)

	send_mail ( $user_email, $subject, $body );

	if ($config ["settings"] ["mgrmailonsubscription"]) {
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

	$query = "SELECT COUNT(*) AS empty_pos FROM staff WHERE user = NULL AND event = '" . $event_uuid . "'";
	$result = $db->query ( $query );
	$count = $result->fetch_row () [0];
	if ($count == 0) {
		$subject = "Wache voll belegt";
		$body = "Eine von Ihnen erstellte Wache ist voll belegt: " . $link;
	} else {
		$body = "Jemand hat sich in eine von Ihnen erstellte Wache eingeschrieben: " . $link;
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
	// TODO add more infos of the event (evtl: as html)

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
	$body = "Für Sie wurde ein Zugang als Wachbeuaftragter angelegt: \nLogin: " . $mail_manager . " \nPasswort: " . $password;
	send_mail ( $mail_manager, $subject, $body );
}

function mail_reset_password($manager_uuid, $password) {
	global $db;

	$subject = "Passwort zurückgesetzt";
	$body = "Ihr Passwort wurde zurückgesetzt auf: " . $password;

	$query = "SELECT * FROM user WHERE uuid = '" . $manager_uuid . "'";
	$result = $db->query ( $query );
	if ($result) {
		if (mysqli_num_rows ( $result )) {
			$user = $result->fetch_object ();
			send_mail ( $user->email, $subject, $body );
			$result->free ();
		}
	}
}

?>