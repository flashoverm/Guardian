<?php
require_once 'page_head.php';
require_once 'inc/db_event.php';
require_once 'inc/db_engines.php';
require_once 'inc/db_user.php';
require_once 'inc/mail_controller.php';

$showFormular = true;		
?>
<div class="jumbotron text-center">
	<h1>In Wache eintragen</h1>
</div>
<div class="container">
<?php
if(isset($_GET['staffid']) and isset($_GET['id'])){
	
	$staff_uuid = trim($_GET['staffid']);
	$engines = get_engines();
	$event_uuid = trim($_GET['id']);
	
	if (isset($_POST['firstname']) and isset($_POST['lastname']) 
		&& isset($_POST['email']) && isset($_POST['engine']) ) {

		$firstname = trim($_POST['firstname']);
		$lastname = trim($_POST['lastname']);
		$email = trim($_POST['email']);
		$engine_uuid = trim($_POST['engine']);

		$error = false;
		if(strlen($firstname) == 0) {
			showAlert('Bitte Vorname angeben');
			$error = true;
		}
		if(strlen($lastname) == 0) {
			showAlert('Bitte Nachname angeben');
			$error = true;
		}	
		if(strlen($email) == 0) {
			showAlert('Bitte E-Mail angeben');
			$error = true;
		}
		if(!$error){
			$user_uuid = insert_user($firstname, $lastname, $email, $engine_uuid);
			add_staff_user($staff_uuid, $user_uuid);
			mail_subscribe_staff_user($event_uuid, $email, $engine_uuid);
			//TODO if ok
			showSuccess("Als Wachteilnehmer eintragen");
			$showFormular = false;
		}
	}
} else {
	$showFormular = false;
	showAlert("Fehlende Paramter");
}

if($showFormular) {
	$results = get_engines();

?>
	<form action="<?="event_subscribe.php?id=".$event_uuid."&staffid=".$staff_uuid?>" method="post">
		<legend>Für Wache eintragen</legend>
		<div class="form-group">
			<label>Vorname:</label>
			<input type="text" class="form-control" required="required" name="firstname"id="firstname" placeholder="Vorname eingeben">
		</div>
		<div class="form-group">
			<label >Nachname:</label>
			<input type="text" class="form-control" required="required" name="lastname" id="lastname" placeholder="Nachname eingeben">
		</div>
		<div class="form-group">
			<label >E-Mail:</label>
			<input type="email" class="form-control" required="required" name="email"id="email" placeholder="E-Mail eingeben">
		</div>
		<div class="form-group">
			<label >Löschzug:</label>
			<select class="form-control" name="engine">
				<?php foreach ( $results as $option ) : ?>
					<option value="<?php echo $option->uuid; ?>"><?php echo $option->name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<input type="submit" value="Eintragen" class="btn btn-primary"> 
	</form>
</div>
<?php 
}
?>
<footer>
	<div class="container">
		<?php 
		if(isset($event_uuid)){
			echo "<p><a href='event_details.php?id=".$event_uuid."' class=\"btn btn-outline-primary\">Zurück</a></p>"; 
		}
		?>
	</div>
<?php require_once 'inc/page_end.php'; ?>