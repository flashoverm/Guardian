<?php

session_start();
if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="login.php">einloggen</a>');
}

function user_is_admin(){
	if(isset($_SESSION['userid']) and is_admin($_SESSION['userid'])){
		return TRUE;
	}
	return FALSE;
}


?>