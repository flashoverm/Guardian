<?php 
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";

define("DELIMITER", ";");

function import_user($file, $engine) {

	$handle = fopen($file,"r");
	
	if ($handle) {
		$errorString = false;
		
		while (($line = fgets($handle)) !== false) {
			
			$columns = explode(DELIMITER, $line);
			
			if(sizeof($columns) == 3){
				if(email_in_use($columns[2])){
					$errorString .=  "Benutzer bereits vorhanden:\t" . col_to_string($columns, $engine). "<br>";
				} else {
					$email = trim(strToLower($columns[2]));
					$firstname = trim($columns[0]);
					$lastname = trim($columns[1]);
					insert_user($firstname, $lastname, $email, $engine);
				}
			} else {
					$errorString .=  "Falsches Datenformat:\t\t\t" . col_to_string($columns, $engine). "<br>";
			}
		}
		
		fclose($handle);
	} else {
		$errorString .= "Datei " . $file . " kann nicht gelesen werden ";
	}
	return $errorString;
}

function col_to_string($columns, $engine){
	return $columns[0] . " " . $columns[1] . " - " . $columns[2] . " - " . $engine;
}