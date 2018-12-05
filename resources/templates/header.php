<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="icon" type="image/png" href="images/layout/Logo.png">

<!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">-->
<link rel="stylesheet" type="text/css" href='css/custom.css'>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
	integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
	crossorigin="anonymous"></script>
<script
	src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
	integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
	crossorigin="anonymous"></script>
<script
	src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
	integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
	crossorigin="anonymous"></script>

<title><?= $title ?></title>

</head>
<body>
	<header>
	<div class="jumbotron text-center">
		<h1><?= $title ?></h1>
		<?php
		if(isset($subtitle)){
			echo "<h5>".$subtitle."</h5>";
		}
		?>
	</div>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <!-- <a class="navbar-brand" href="#">Navigation</a> -->
	<button class="navbar-toggler" type="button" data-toggle="collapse"
		data-target="#navbarMainContent">
		<span class="navbar-toggler-icon"></span>
	</button>

<?php
if ($loggedIn) {
	echo "<div class='collapse navbar-collapse' id='navbarMainContent'>
		  	<ul class='navbar-nav mr-auto'>
	            <li class='nav-item'>
	        		<a class='nav-link text-light' href='event_overview.php'>Wachübersicht</a>
				</li>	
				<li class='nav-item'>
	        		<a class='nav-link text-light' href='event_create.php'>Wache anlegen</a>
				</li>
			</ul>
		</div>";
	
	if ($isAdmin) {
	echo "<div class='collapse navbar-collapse order-2 dual-collapse2' id='navbarMainContent'>
			<ul class='navbar-nav mx-auto'>
				<li class='nav-item'>
	        		<a class='nav-link text-light' href='manager_overview.php'>Wachbeauftragte</a>
				</li>
			</ul>
		</div>";
	}
} else {
	?>
	<div class='collapse navbar-collapse' id='navbarMainContent'>
		<ul class='navbar-nav mr-auto'>
	<?php
	if($config ["settings"] ["publicevents"]){
		echo "	<li class='nav-item'>
	        		<a class='nav-link text-light' href='event_public.php'>Öffentliche Wachen</a>
				</li>";
	}
	if($config ["settings"] ["reportfunction"]){
	    echo "<li class='nav-item'>
		        	<a class='nav-link text-light' href='event_report.php'>Wachbericht erstellen</a>
			  </li>";  
	}
	?>
		</ul>
	</div>
<?php
}
?>
    <div class="collapse navbar-collapse  order-3 dual-collapse3"
		id="navbarMainContent">
		<ul class="navbar-nav ml-auto">
<?php
if ($loggedIn) {
	echo "	
			<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light' data-toggle='dropdown' href='#'>"
				. $_SESSION ['guardian_usermail'] . 
			"	<span class='caret'></span></a>
				<ul class='dropdown-menu dropdown-menu-right bg-dark'>
					<li class='nav-item'>
		                <a class='dropdown-item text-light' href='change_password.php'>Passwort ändern</a>
		            </li>
		            <li class='nav-item'>
    					<div class='dropdown-divider'></div>
		                <a class='dropdown-item text-light' href='logout.php'>Abmelden</a>
		            </li>
				</ul>
			</li>
";
} else {
	echo " 	<li class='nav-item'>
                <a class='nav-link text-light' href='login.php'>Anmelden</a>
            </li>";
	if ($config ["settings"] ["selfregistration"]) {
		echo " 	<li class='nav-item'>
                <a class='nav-link text-light' href='register.php'>Registrierung</a>
            </li>";
	}
}
?>
        </ul>
	</div>
	</nav> </header>
	
