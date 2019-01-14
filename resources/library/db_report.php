<?php
require_once 'db_connect.php';

create_table_report();

function insert_report($date, $start, $end, $type_uuid, $type_other, $title, $engine_uuid, $creator, $noIncidents, $report) {
    global $db;
    
    $uuid = getGUID ();
    
    if($noIncidents){
        
        $statement = $db->prepare("INSERT INTO report (uuid, date, start_time, end_time, type, type_other, title, engine, creator, noIncidents, report)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?)");
        $statement->bind_param('ssssssssss', $uuid, $date, $start, $end, $type_uuid, $type_other, $title, $engine_uuid, $creator, $report);
        
    } else {
        
        $statement = $db->prepare("INSERT INTO report (uuid, date, start_time, end_time, type, type_other, title, engine, creator, noIncidents, report)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE, ?)");
        $statement->bind_param('ssssssssss', $uuid, $date, $start, $end, $type_uuid, $type_other, $title, $engine_uuid, $creator, $report);
        
    }
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "New event record created successfully";
        return $uuid;
    } else {
        echo "Error: " . "<br>" . $db->error;
        return false;
    }
}

function get_reports() {
    global $db;
    $data = array ();
    
    $statement = $db->prepare("SELECT * FROM report");
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            while ( $date = $result->fetch_object () ) {
                $data [] = $date;
            }
            $result->free ();
        }
    }
    return $data;
}

function get_report($report_uuid) {
    global $db;
    
    $statement = $db->prepare("SELECT * FROM report WHERE uuid = ?");
    $statement->bind_param('s', $report_uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        return $statement->get_result()->fetch_object ();
    } else {
        // echo "UUID not found";
    }
}

function create_table_report() {
    global $db;
    
    $statement = $db->prepare("CREATE TABLE report (
                          uuid CHARACTER(36) NOT NULL,
						  date DATE NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME NOT NULL,
                          type CHARACTER(36) NOT NULL,
                          type_other VARCHAR(96),
						  title VARCHAR(96),
                          engine CHARACTER(36) NOT NULL,
						  creator VARCHAR(128) NOT NULL,
                          noIncidents BOOLEAN NOT NULL,
                          report TEXT,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (type) REFERENCES eventtype(uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "Table created<br>";
        return true;
    } else {
        // echo "Error: " . $db->error . "<br><br>";
        return false;
    }
}