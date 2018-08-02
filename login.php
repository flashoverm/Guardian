<?php
require_once 'inc/page_head.php';
require_once 'inc/db_user.php';
require_once 'inc/config.php';

session_start();
?>
<div class="jumbotron text-center">
	<h1>Guardian</h1>
	<h5>Wachverwaltung der Freiwilligen Feuerwehr der Stadt Landshut</h5>
</div>
<div class="container">
<?php
if(isset($_POST['email']) && isset($_POST['password'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
	
	if(login_enabled($email)){
		$uuid = check_password($email, $password);
		if($uuid){
			$_SESSION['userid'] = $uuid;
			die('Login erfolgreich. Weiter zur <a href="event_overview.php">Wachenübersicht</a>');
		}
	}
	showAlert("E-Mail oder Passwort ungültig");
}

?>

	<form action="" method="post">
		<div class="form-group">
			<label >Email:</label>
			<input type="email" class="form-control" name="email"id="email" placeholder="E-Mail eingeben">
		</div>
		<div class="form-group">
			<label >Passwort:</label>
			<input type="password" class="form-control" name="password" id="password" placeholder="Passwort eingeben">
		</div>
		<input type="submit" value="Einloggen" class="btn btn-primary">
	</form> 
</div>
<footer>
	<div class="container">
		<?php
		if($enable_self_registration){
			echo "<p><a href='register.php' class=\"btn btn-outline-primary\">Selbstregistrierung Wachbeauftragter</a></p>";
		}
		?>
	</div>
<?php require_once 'inc/page_end.php'; ?>