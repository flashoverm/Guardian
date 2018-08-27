<?php
require_once realpath(dirname(__FILE__) . "/../resources/config.php");
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_user.php';

// Pass variables (as an array) to template
$variables = array(
    'title' => "Passwort ändern",
    'secured' => true
);

if (isset($_POST['password_old']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_SESSION['userid'])) {

    $uuid = $_SESSION['userid'];
    $password_old = trim($_POST['password_old']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    $error = false;
    if ($password != $password2) {
        $variables['alertMessage'] = "Die Passwörter müssen übereinstimmen";
        $error = true;
    }

    if (! $error) {
        $uuid = change_password($uuid, $password_old, $password);
        $variables['successMessage'] = "Password erfolgreich geändert";
    }
}

renderLayoutWithContentFile("changePassword_template.php", $variables);

?>