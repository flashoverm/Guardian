<form action="event_report.php" method="post">

	<input required="required" class="form-control" name="date" id="date"
		type="date"> 
		
	<input required="required" class="form-control"
		name="start" id="start" type="time"> 
		
	<input required="required"
		class="form-control" name="end" id="end" type="time"> 
		
	<select
		class="form-control" name="type">
	</select> 
	
	<input required="required" class="form-control" name="title"
		id="title" placeholder="Titel eingeben" type="text"> 
		
	<select
		class="form-control" name="engine" required="required">
	</select> 
	
	<input class="form-check-input" id="noIncidents"
		type="checkbox">

	<textarea class="form-control" name="report" id="report"
		placeholder="Bericht"></textarea>

	<input required="required" class="form-control" name="creator"
		id="creator" placeholder="Namen eintragen" type="text">

	<div class="card" id="unit1">

		<input class="form-control border-0 bg-white" id="unit1date"
			name="unit1date" readonly="" type="text"> 
			<input
			class="form-control border-0 bg-white" id="unit1start"
			name="unit1start" readonly="" type="text"> 
			<input
			class="form-control border-0 bg-white" id="unit1end" name="unit1end"
			readonly="" type="text"> 
			<input
			class="form-control border-0 bg-white" id="unit1unit"
			name="unit1unit" readonly="" value="Fahrzeug 1" type="hidden"> 
			<input
			class="form-control border-0 bg-white" id="unit1km" name="unit1km"
			readonly="" value="10" type="hidden"> 
			
			<input
			class="form-control border-0 bg-white" id="unit1function1"
			name="unit1function1" readonly="" type="text"> 
			<input
			class="form-control border-0 bg-white" id="unit1name1"
			name="unit1name1" readonly="" type="text"> 
			<input
			class="form-control border-0 bg-white" id="unit1engine1"
			name="unit1engine1" readonly="" type="text"> 
			<input
			class="form-control border-0 bg-white" id="unit1function2"
			name="unit1function2" readonly="" type="text"> 
			<input
			class="form-control border-0 bg-white" id="unit1name2"
			name="unit1name2" readonly="" type="text"> 
			<input
			class="form-control border-0 bg-white" id="unit1engine2"
			name="unit1engine2" readonly="" type="text">


		<div class="card" id="unit2">

			<input class="form-control border-0 bg-white" id="unit2date"
				name="unit2date" readonly="" type="text"> <input
				class="form-control border-0 bg-white" id="unit2start"
				name="unit2start" readonly="" type="text"> <input
				class="form-control border-0 bg-white" id="unit2end" name="unit2end"
				readonly="" type="text"> <input
				class="form-control border-0 bg-white" id="unit2unit"
				name="unit2unit" readonly="" value="Fahrzeug 2" type="hidden"> <input
				class="form-control border-0 bg-white" id="unit2km" name="unit2km"
				readonly="" value="15" type="hidden"> <input
				class="form-control border-0 bg-white" id="unit2function1"
				name="unit2function1" readonly="" type="text"> <input
				class="form-control border-0 bg-white" id="unit2name1"
				name="unit2name1" readonly="" type="text"> <input
				class="form-control border-0 bg-white" id="unit2engine1"
				name="unit2engine1" readonly="" type="text"> <input
				class="form-control border-0 bg-white" id="unit2function2"
				name="unit2function2" readonly="" type="text"> <input
				class="form-control border-0 bg-white" id="unit2name2"
				name="unit2name2" readonly="" type="text"> <input
				class="form-control border-0 bg-white" id="unit2engine2"
				name="unit2engine2" readonly="" type="text">