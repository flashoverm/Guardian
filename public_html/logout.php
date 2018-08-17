<?php
require_once '../resources/templates/header.php';

session_destroy ();

header("Location: login.php"); // redirects
?>