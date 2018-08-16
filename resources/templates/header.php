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

<title>Guardian - <?= $title ?></title>

</head>
<body>
	<header>
	<div class="jumbotron text-center">
		<h1><?= $title ?></h1>
	</div>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <!-- <a class="navbar-brand" href="#">Navigation</a> -->
	<button class="navbar-toggler" type="button" data-toggle="collapse"
		data-target="#navbarMainContent" aria-controls="navbarMainContent"
		aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
<?php
if ($loggedIn) {
	echo "<div class='collapse navbar-collapse' id='navbarMainContent'>
		<ul class='navbar-nav mr-auto'>
			<li class='nav-item'>
        		<a class='nav-link' href='event_overview.php'>Wachübersicht</a>
			</li>	
			<li class='nav-item'>
        		<a class='nav-link' href='event_create.php'>Wache anlegen</a>
			</li>		
		</ul>
	</div>";
}
?>
    <div class="collapse navbar-collapse  order-2 dual-collapse2"
		id="navbarMainContent">
		<ul class="navbar-nav ml-auto">
<?php
if ($loggedIn) {
	if ($isAdmin) {
		echo " 	<li class='nav-item'>
	        	<a class='nav-link' href='manager_overview.php'>Wachbeauftragte</a>
				</li>";
	}
	echo "	<li class='nav-item'>
                <a class='nav-link' href='change_password.php'>Passwort ändern</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='logout.php'>Abmelden</a>
            </li>";
} else {
	echo " 	<li class='nav-item'>
                <a class='nav-link' href='login.php'>Anmelden</a>
            </li>";
	if ($config ["settings"] ["selfregistration"]) {
		echo " 	<li class='nav-item'>
                <a class='nav-link' href='register.php'>Registrierung</a>
            </li>";
	}
}
?>
        </ul>
	</div>
	</nav> </header>