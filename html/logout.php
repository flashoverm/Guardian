<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );

session_start ();
session_destroy ();

header("Location: login.php"); // redirects
?>