<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_connect.php";
require_once LIBRARY_PATH . "/mail_body.php";
require_once LIBRARY_PATH . "/mail.php";

require_once LIBRARY_PATH . '/class/EventReport.php';
require_once LIBRARY_PATH . '/class/ReportUnit.php';
require_once LIBRARY_PATH . '/class/ReportUnitStaff.php';


function mail_insert_event($event_uuid, $creator_uuid, $publish) {
	global $config;
	global $bodies;

	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "Neue Wache eingestellt" . event_subject($event_uuid);
	
	$body =  $bodies["event_insert"] . $link;

	$creator = get_user( $creator_uuid );

	$sendOK = send_mail ( $creator->email, $subject, $body );
	
	$event = get_event( $event_uuid );
	if ($event->engine != $creator->engine){
	    $assignedOk = mail_assigned_event($event);
	    $sendOK = $sendOK && $assignedOk;
	}
	
	if ($publish) {  
	    $publishOK = mail_publish_event ( $event_uuid, $creator_uuid );
	    $sendOK = $sendOK && $publishOK;
	}
	return $sendOK;
}

function mail_assigned_event($event) {
    global $config;
    global $bodies;
    
    $link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event->uuid;
    $subject = "Neue Wache zugewiesen" . event_subject($event->uuid);
    
    $body = $bodies["event_assign"] . $link;
    
    $recipients = get_manager_of_engine($event->engine);
    
    return send_mails($recipients, $subject, $body);
}

function mail_publish_event($event_uuid, $creator_uuid) {
	global $config;
	global $bodies;
		
	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "Neue Wache veröffentlicht" . event_subject($event_uuid);
	
	$body = $bodies["event_publish"] . $link;
	
	$event = get_event( $event_uuid );
		
	$recipients = get_manager_except_engine_and_creator($event->engine, $creator_uuid);

	return send_mails($recipients, $subject, $body);
}

function mail_subscribe_staff_user($event_uuid, $user_email, $user_engine_uuid, $send_mail) {
	global $config;
	global $bodies;
	
	$link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
	$subject = "In Wache eingeschrieben" . event_subject($event_uuid);
	
	$body = $bodies["event_subscribe"] . $link;

	if($config ["settings"] ["usermailonsubscription"] && $send_mail){
		send_mail ( $user_email, $subject, $body );
	}

	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		$body = $bodies["event_subscribe_manager"] . $link;

		$recipients = get_manager_of_engine($user_engine_uuid);
		send_mails($recipients, $subject, $body);
	}

	if (is_event_full($event_uuid)) {
	    $subject = "Wache voll belegt" . event_subject($event_uuid);
		
		$body = $bodies["event_full"] . $link;
		
	} else if ($config ["settings"] ["creatormailonsubscription"]) {
		
		$body = $bodies["event_subscribe_engine"] . $link;
		
	} else {
		return;
	}

	$creator = get_events_creator($event_uuid);
	send_mail($creator->email, $subject, $body);
}

function mail_not_full($event_uuid) {
    global $config;
    global $bodies;
    
    $link = $config ["urls"] ["baseUrl"] . "/event_details.php?id=" . $event_uuid;
    $subject = "Erinnerung: Wache nicht voll belegt" . event_subject($event_uuid);
    
    $body = $bodies["event_not_full"] . $link;
        
    $creator = get_events_creator($event_uuid);
    return send_mail($creator->email, $subject, $body);
}

function mail_remove_staff_user($staff_uuid, $event_uuid) {
	global $config;
	global $bodies;
	
	$link = $config ["urls"] ["baseUrl"] . "/guardian/event_details.php?id=" . $event_uuid;
	$subject = "Aus Wache entfernt" . event_subject($event_uuid);
	
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
	$subject = "Wache abgesagt" . event_subject($event_uuid);
	
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
	global $bodies;
	$subject = "Passwort zurückgesetzt";
	
	$body = $bodies["manager_reset_password"] . $bodies["password"] . $password . $bodies["manager_reset_password2"];

	$manager = get_user($manager_uuid);
	send_mail ($manager->email, $subject, $body );
}

function mail_send_report($report){	
	$subject = "Wachbericht";
	$body = $report->toMail();
	
	$engine = get_engine_from_name($report->engine);
	
	$managerList = get_manager_of_engine($engine->uuid);
	if(sizeof($managerList) > 0){
		send_mails($managerList, $subject, $body);
		return true;
	}
	return false;
}

function event_subject($event_uuid){
    global $config;
    $event = get_event($event_uuid);
    
    $subject = " - " 
        . date($config ["formats"] ["date"], strtotime($event->date)) . " " 
            . date($config ["formats"] ["time"], strtotime($event->start_time)) . " Uhr "
                . get_eventtype($event->type)->type;
    
    return $subject;
}
?>