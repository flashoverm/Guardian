<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="icon" type="image/png" href="<?= $config["urls"]["html"]?>/images/layout/Logo.png">

<!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">-->
<link rel="stylesheet" type="text/css" href='<?= $config["urls"]["html"]?>/css/custom.css'>

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

<script>
	function isDateSupported() {
		var input = document.createElement('input');
		var value = 'a';
		input.setAttribute('type', 'date');
		input.setAttribute('value', value);
		return (input.value !== value);
	};
</script>
	
<title><?= $title ?></title>

</head>
<body>
	<header>
	<div class="jumbotron py-3">
		<div class="row">
			<div class="col">
				<a href="https://intranet.feuerwehr-landshut.de">
					<img class="img-fluid d-block"
						src="<?= $config["urls"]["html"] ?>/images/layout/shortheader_new-1.png">
				</a>
					 
			</div>
			<div class="col d-flex align-items-center justify-content-center">
				<span class="">
    				<h1 class="text-center"><?= $title ?></h1>
    				<?php
    		          if(isset($subtitle)){
    			         echo "<h5 class='text-center'>".$subtitle."</h5>";
    		          }
    		        ?>
		        </span>
			</div>
			<div class="col">
			</div>
		</div>
	</div>
	

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-toggle="collapse"
		data-target="#navbarMainContent">
		<span class="navbar-toggler-icon"></span>
	</button>
		
	<?php if(!isset($noNav) || $noNav == false) {?>
	
	<div class='collapse navbar-collapse w-100' id='navbarMainContent'>
		<ul class='navbar-nav'>
<?php
if ($loggedIn) {
	echo "      <li class='nav-item dropdown'>
        			<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>
						Wachen
					</a>
        			<div class='dropdown-menu bg-dark'>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["html"]. "/events'>Wachübersicht</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["html"]. "/events/new'>Wache anlegen</a>
					</div>
				</li>
				<li class='nav-item dropdown'>
        			<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>
						Wachberichte
					</a>
        			<div class='dropdown-menu bg-dark'>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["html"]. "/reports'>Berichtsübersicht</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["html"]. "/reports/new'>Bericht anlegen</a>
					</div>
				</li>
			</ul>
		</div>";
} else {
	if($config ["settings"] ["publicevents"]){
		echo "	<li class='nav-item mx-1'>
	        		<a class='nav-link text-light' href='" . $config["urls"]["html"]. "/events/public'>Öffentliche Wachen</a>
				</li>";
	}
	if($config ["settings"] ["reportfunction"]){
	    echo "<li class='nav-item mx-1'>
		        	<a class='nav-link text-light' href='" . $config["urls"]["html"]. "/reports/new'>Wachbericht erstellen</a>
			  </li>";  
	}
	?>
		</ul>
	</div>
<?php
}
?>
    <div class="collapse navbar-collapse w-100"
		id="navbarMainContent">
		<ul class="navbar-nav ml-auto">
<?php
if ($loggedIn) {
	
	if ($isAdmin) {
		echo "<li class='nav-item dropdown'>
        			<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>
						Administration
					</a>
        			<div class='dropdown-menu bg-dark'>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["html"]. "/manager'>Wachbeauftragte</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["html"]. "/events/admin'>Alle Wachen</a>
	        			<a class='dropdown-item text-light' href='" . $config["urls"]["html"]. "/templates'>Personalvorlagen</a>
					</div>
				</li>";
	}
	echo "	<li class='dropdown'>
				<a class='nav-link dropdown-toggle text-light mx-1' data-toggle='dropdown' href='#'>"
				. $_SESSION ['guardian_usermail'] . 
				"</a>
	        	<div class='dropdown-menu dropdown-menu-right bg-dark'>
					<a class='dropdown-item disabled text-secondary'>" . $_SESSION ['guardian_engine'] . "</a>
					<div class='dropdown-divider'></div>
					<a class='dropdown-item text-light' href='" . $config["urls"]["html"]. "/change_password'>Passwort ändern</a>
					<a class='dropdown-item text-light' href='" . $config["urls"]["html"]. "/logout'>Abmelden</a>
				</div>
			</li>
";
} else {
	echo " 	<li class='nav-item'>
                <a class='nav-link text-light' href='" . $config["urls"]["html"]. "/login'>Anmelden</a>
            </li>";
	if ($config ["settings"] ["selfregistration"]) {
		echo " 	<li class='nav-item'>
                <a class='nav-link text-light' href='" . $config["urls"]["html"]. "/register'>Registrierung</a>
            </li>";
	}
}
?>
			<li class='nav-item'>
				<a class='nav-link text-light' href="<?= $config["urls"]["html"]?>/manual" data-toggle="tooltip" title="Anleitung">&#9432;</a>
            </li>
        </ul>
	</div>
	</nav> 
	<?php }?>
	</header>
	
