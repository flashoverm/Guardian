<?php
require_once 'inc/page_head.php';
require_once 'inc/secured_page.php';
require_once 'inc/db_user.php';
require_once 'inc/mail_controller.php';

$showFormular = true;		
?>
<div class="jumbotron text-center">
	<h1>Wachbeauftragten anlegen</h1>
</div>
<div class="container">
<?php
if(isset($_POST['email']) && isset($_POST['engine']) 
	&& isset($_POST['firstname']) && isset($_POST['lastname'])) {
		
    $error = false;
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
    $email = $_POST['email'];
	$engine = $_POST['engine'];

    if(!$error) { 
        if(email_in_use($email)) {
            showError('Diese E-Mail-Adresse ist bereits vergeben');
            $error = true;
        }    
    }
	
    if(!$error) { 
		$password = random_password();
		$result = insert_manager($firstname, $lastname, $email, $password, $engine);
        
        if($result) { 
			mail_add_manager($email, $password);
            $showFormular = false;		
            showSuccess('Wachbeauftragter erfolgreich angelegt - <a href="manager_overview.php" class="alert-link">Zurück zur Übersicht</a>)';
        } else {
            showError('Beim Abspeichern ist leider ein Fehler aufgetreten');
        }
    } 
}

if($showFormular) {
	$results = get_engines();

?>
	<form action="" method="post">
		<div class="form-group">
			<label>Vorname:</label>
			<input type="text" class="form-control" name="firstname"id="firstname" placeholder="Vorname eingeben">
		</div>
		<div class="form-group">
			<label >Nachname:</label>
			<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Nachname eingeben">
		</div>
		<div class="form-group">
			<label >E-Mail:</label>
			<input type="email" class="form-control" name="email"id="email" placeholder="E-Mail eingeben">
		</div>
		<div class="form-group">
			<label >Löschzug:</label>
			<select class="form-control" name="engine">
				<?php foreach ( $results as $option ) : ?>
					<option value="<?php echo $option->uuid; ?>"><?php echo $option->name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<input type="submit" value="Anlegen" class="btn btn-primary">	 
	</form>
</div>
<?php
}
?>
<footer>
	<div class="container">
		<a href='manager_overview.php' class="btn btn-outline-primary">Zurück</a></p>
	</div>
<?php require_once 'inc/page_end.php'; ?>