<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once '../resources/library/db_event.php';
require_once '../resources/library/mail_controller.php';

$events = get_all_active_events();

foreach ( $events as $event ) {
   
    if(!is_event_full($event->uuid)){
        
        $date = date_create($event->date);
        date_sub($date, new DateInterval( "P10D" ));
        
        if($date->format("d.m.Y") == date("d.m.Y")){
            //Send reminder mail
            mail_not_full($event->uuid);
        }
    }
}
    