<?php
require_once LIBRARY_PATH . "/db_connect.php";

create_table_staff_template();

function insert_template($eventtype, $staffposition){
    global $db;
    
    $uuid = getGUID();
    
    
    $statement = $db->prepare("INSERT INTO stafftemplate (uuid, eventtype, staffposition)
		VALUES (?,
            (SELECT uuid FROM eventtype WHERE type = ?),
            (SELECT uuid FROM staffposition WHERE position = ?)
        )");
    
    $statement->bind_param('sss', $uuid, $eventtype, $staffposition);
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "New event record created successfully";
        return $uuid;
    } else {
        //echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
    
}

function get_staff_template($eventtype_uuid){
    global $db;
    $data = array ();
    
    $statement = $db->prepare("SELECT staffposition.uuid, staffposition.position 
            FROM eventtype, stafftemplate 
            WHERE eventtype.uuid = stafftype.eventtype AND stafftype.eventtype = ?
            ORDER BY staffposition.list_index");
    $statement->bind_param('s', $eventtype_uuid);
        
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

function create_table_staff_template(){
    global $db;
    
    $statement = $db->prepare("CREATE TABLE stafftemplate (
                          uuid CHARACTER(36) NOT NULL,
                          eventtype CHARACTER(36) NOT NULL,
                          staffposition CHARACTER(36) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (eventtype) REFERENCES eventtype(uuid),
						  FOREIGN KEY (staffposition) REFERENCES staffposition(uuid)
                          )");
    
    $result = $statement->execute();
    
    if ($result) {
        // echo "Table created<br>";
        insert_template("Theaterwache","Dienstgrad (HFM)");
        insert_template("Theaterwache","Wachmann");
        insert_template("Theaterwache","Wachmann");
        
        insert_template("Theaterwache Schüler","Dienstgrad (HFM)");
        insert_template("Theaterwache Schüler","Wachmann");
        insert_template("Theaterwache Schüler","Wachmann");
        
        insert_template("Theaterwache Prantlgarten","Dienstgrad (HFM)");
        insert_template("Theaterwache Prantlgarten","Wachmann");
        insert_template("Theaterwache Prantlgarten","Wachmann");

        insert_template("Dultwache","Dienstgrad (LM)");
        insert_template("Dultwache","Maschinist");
        insert_template("Dultwache","Atemschutzträger");
        insert_template("Dultwache","Atemschutzträger");
        
        return true;
    } else {
        // echo "Error: " . $db->error . "<br><br>";
        return false;
    }
}