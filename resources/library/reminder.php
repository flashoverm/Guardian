<?php

echo "Path: " . realpath ( dirname ( __FILE__ ) );

require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once 'db_event.php';
require_once 'mail_controller.php';

$events = get_all_active_events();

echo "Running reminder function";

send_mail("markus@thral.de", "Running reminder", "see subject");

foreach ( $events as $event ) {
       
    if(!is_event_full($event->uuid)){
        
        $date = date_create($event->date);
        date_sub($date, new DateInterval( "P10D" ));
        
        send_mail("markus@thral.de", "Running reminder - Event: " . $event->uuid, $date->format("d.m.Y") . " - " . date("d.m.Y"));
        
        if($date->format("d.m.Y") == date("d.m.Y")){
            //Send reminder mail
            //mail_not_full($event->uuid);
            send_mail("markus@thral.de", "Sending reminder - Event: " . $event->uuid, $date->format("d.m.Y") . " - " . date("d.m.Y"));
        }
    }
}
    