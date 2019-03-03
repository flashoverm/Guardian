<p><?php
echo $report->report;

foreach($units as $unit){
	echo $unit->toHTML();
}

?>
</p>
<p><a href='<?=$config["urls"]["html"] ?>/reports' class='btn btn-outline-primary'>Zurück</a></p>
