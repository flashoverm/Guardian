<?php
require_once realpath(dirname(__FILE__) . "/../resources/config.php");

class EventReport {
	
	public $date;
	public $beginn;
	public $end;
	
	public $type;
	public $title;
	public $engine;
	
	public $noIncidents;
	public $report;
	public $creator;
	public $ilsEntry;
	
	public $units = array();
	
	function __construct($date, $beginn, $end, $type, $title, $engine, 
	    $noIncidents, $report, $creator, $ilsEntry) {
		
		$this->date = $date;
		$this->beginn = $beginn;
		$this->end = $end;
		
		$this->type = $type;
		$this->title = $title;
		$this->engine = $engine;
		
		$this->noIncidents = $noIncidents;
		$this->report = $report;
		$this->creator = $creator;
		$this->ilsEntry = $ilsEntry;
	}
	
	function addUnit($unit){
		if(get_class ($unit) == "ReportUnit"){
			array_push($this->units, $unit);
			return true;
		}
		return false;
	}
	
	function toHTML(){
		$string = "Date: " . $this->date . " - Beginn: " . $this->beginn . " - End: " . $this->end . "<br>"
				. "Type: " . $this->type . " - Title: " . $this->title . " - Engine: " . $this->engine . "<br>"
						. "NoIncidents: " . $this->noIncidents . " - Reporttext: ". $this->report . " - Creator: " . $this->creator
						. "<br><br>";
						
						foreach ($this->units as $value) {
							$string = $string . $value->toString();
						}
						
						return $string . "<br>";
	}
	
	function toMail(){
		$string = "----------------------- Wachbericht -----------------------"
				. "\n\n" .$this->type;
		
		if($this->title != null){
			$string = $string . "\nTitel: \t\t" . $this->title;
		}
		$string = $string
		. "\n\nDatum: \t" . date($config ["formats"] ["date"], strtotime($this->date))
				. "\nWachbeginn: \t" . $this->beginn 
				. "\nEnde: \t\t" . $this->end . "\n\n";
		
		if($this->ilsEntry){
		    $string = $string . "Wache durch ILS angelegt!\n\n";
		}				
		if($this->noIncidents){
			$string = $string . "Keine Vorkomnisse";
		} else {
			$string = $string . "Vorkomnisse gemeldet - siehe Bericht";
		}
				
		$string = $string . "\n\nBericht: \n". $this->report . "\n\n";
		
		foreach ($this->units as $value) {
			$string = $string . $value->toMail();
		}
		
		return $string
				. "-----------------------------------------------------------"
				. "\n\nZuständiger Zug: \t" . $this->engine
				. "\n\nErsteller: \t\t" . $this->creator;
	}
}
?>