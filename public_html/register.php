<?php
require_once '../resources/templates/header.php';
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';
require_once '../resources/config.php';

session_start ();
?>

<div class="jumbotron text-center">
	<h1>Als Wachbeauftragter registrieren</h1>
</div>
<div class="container">

<?php
if ($config ["settings"] ["selfregistration"]) {
	$showFormular = true;
} else {
	$showFormular = false;
	showAlert ( "Selbstregistrierung deaktiviert - <a href=\"login.php\" class=\"alert-link\">Zum Login</a>" );
}

if (isset ( $_POST ['email'] ) && isset ( $_POST ['password'] ) && isset ( $_POST ['password2'] ) && isset ( $_POST ['engine'] ) && isset ( $_POST ['firstname'] ) && isset ( $_POST ['lastname'] )) {

	$error = false;
	$firstname = $_POST ['firstname'];
	$lastname = $_POST ['lastname'];
	$email = $_POST ['email'];
	$password = $_POST ['password'];
	$password2 = $_POST ['password2'];
	$engine = $_POST ['engine'];

	if (strlen ( $password ) == 0) {
		showAlert ( 'Bitte Passwort angeben' );
		$error = true;
	}
	if ($password != $password2) {
		showAlert ( 'Die Passwörter müssen übereinstimmen' );
		$error = true;
	}
	if (! $error) {
		if (email_in_use ( $email )) {
			showAlert ( 'Diese E-Mail-Adresse ist bereits vergeben' );
			$error = true;
		}
	}
	if (! $error) {
		$result = insert_manager ( $firstname, $lastname, $email, $password, $engine );

		if ($result) {
			$showFormular = false;
			header("Location: login.php"); // redirects
		} else {
			showAlert('Leider ist ein Fehler aufgetreten');
		}
	}
}

if ($showFormular) {
	$results = get_engines ();

?>

	<form action="" method="post">
		<div class="form-group">
			<label>Vorname:</label> <input type="text" class="form-control"
				required="required" name="firstname" id="firstname"
				placeholder="Vorname eingeben">
		</div>
		<div class="form-group">
			<label>Nachname:</label> <input type="text" class="form-control"
				required="required" name="lastname" id="lastname"
				placeholder="Nachname eingeben">
		</div>
		<div class="form-group">
			<label>E-Mail:</label> <input type="email" class="form-control"
				required="required" name="email" id="email"
				placeholder="E-Mail eingeben">
		</div>
		<div class="form-group">
			<label>Passwort:</label> <input type="password" class="form-control"
				required="required" name="password" id="password"
				placeholder="Passwort eingeben">
		</div>
		<div class="form-group">
			<label>Passwort wiederholen:</label> <input type="password"
				class="form-control" required="required" name="password2"
				id="password2" placeholder="Passwort wiederholen">
		</div>
		<div class="form-group">
			<label>LÃ¶schzug:</label> <select class="form-control" name="engine">
				<?php foreach ( $results as $option ) : ?>
					<option value="<?php echo $option->uuid; ?>"><?php echo $option->name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<input type="submit" value="Registrieren" class="btn btn-primary">
	</form>
</div>

<?php
}
?>

<footer>
	<div class="container">
		<a href='login.php' class="btn btn-outline-primary">Zurück</a>
	</div>
	
<?php require_once '../resources/templates/footer.php'; ?>