<?php
require_once 'inc/page_head.php';
require_once 'inc/secured_page.php';
require_once 'inc/db_user.php';
?>
<div class="jumbotron text-center">
	<h1>Passwort ändern</h1>
</div>
<div class="container">
<?php
if(isset($_POST['password_old']) && isset($_POST['password']) 
	&& isset($_POST['password2']) && isset ($_SESSION['userid'])) {
	
	$uuid = $_SESSION['userid'];
    $password_old = trim($_POST['password_old']);
    $password = trim($_POST['password']);
	$password2 = trim($_POST['password2']);
	
	$error = false;
    if(strlen($password) == 0) {
        showAlert('Bitte Passwort eingeben');
        $error = true;
    }
    if($password != $password2) {
        showAlert('Die Passwörter müssen übereinstimmen');
        $error = true;
    }
	
	if(!$error) {
		$uuid = change_password($uuid, $password_old, $password);
		showSuccess("Password erfolgreich geändert");
	}
}

?>

	<form action="" method="post">
	Altes Passwort:<br>
	<input type="password" size="40" maxlength="250" name="password_old"><br><br>

	Neues Passwort:<br>
	<input type="password" size="40" maxlength="250" name="password"><br><br>
	 
	Passwort wiederholen:<br>
	<input type="password" size="40"  maxlength="250" name="password2"><br>
	<br>
	<input type="submit" value="Passwort ändern" class="btn btn-primary">
	</form> 
</div>
<footer>
	<div class="container">
		<a href='event_overview.php' class="btn btn-outline-primary">Zurück zur Wachenübersicht</a>
		<a href='manager_overview.php' class="btn btn-outline-primary">Zurück zur Beauftragten-Übersicht</a>
	</div>
<?php require_once 'inc/page_end.php'; ?>