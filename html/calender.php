<?php
require_once realpath(dirname(__FILE__) . "/../resources/config.php");
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_event.php";

if (isset($_GET['id'])) {

    $variables = array(
        'title' => 'Download Kalenderdatei',
        'secured' => false
    );
    
    $uuid = trim($_GET['id']);
    $event = get_event($uuid);
    $type = get_eventtype($event->type)->type;

    // iCal date format: yyyymmddThhiissZ
    // PHP equiv format: Ymd\This
    // The Function
    function dateToCal($date, $time)
    {
        $data =  date_create_from_format('Y-m-d H:i:s', $date . " " . $time);            
        return date_format($data, 'Ymd\This') . 'Z';
    }
           
    // Build the ics file
    $ical = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:GuardianByFFLandshutDE
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VEVENT
UID:wachverwaltung@feuerwehr-landshut.de
LOCATION:' . addslashes($type) . '
DESCRIPTION:' . addslashes("Weitere Infos unter " . $config ["urls"] ["baseUrl"] . "/event_details.php?id=".$event->uuid) . '
URL;VALUE=URI:' . $config ["urls"] ["baseUrl"] . "/event_details.php?id=".$event->uuid . '
SUMMARY:' . addslashes($type . " " . $event->title) . '
DTSTART:' . dateToCal($event->date, $event->start_time) . '
DTEND:' . dateToCal($event->date, $event->end_time) . '
DTSTAMP:' . date_format(date_create("now"), 'Ymd\This')  . 'Z
END:VEVENT
END:VCALENDAR';


    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename=event.ics');
    echo $ical;
}

?>