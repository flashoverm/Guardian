<?php
class ReportUnit {
	
	public $unit;
	public $km;
	
	public $date;
	public $beginn;
	public $end;
	
	public $staffList = array();
	
	function __construct($unit, $date, $beginn, $end) {
		$this->unit = $unit;
		$this->date = $date;
		$this->beginn = $beginn;
		$this->end = $end;
	}
	
	function addStaff($staff){
		if(get_class ($staff) == "ReportUnitStaff"){
			array_push($this->staffList, $staff);
			return true;
		}
		return false;
	}
	
	function setKM($km){
		$this->km = $km;
	}
	
	function toHTML(){
		$string = "Unitname: " . $this->unit . " - KM: ". $this->km . "<br>"
				. "Date: " . $this->date . " - Beginn: " . $this->beginn . " - End: " . $this->end . "<br>" 
				. "";

		foreach ($this->staffList as $value) {
			$string = $string . $value->toString();
		}
		
		return $string . "<br";
	}
	
	function toMail(){
		$string = "------------------------- Einheit --------------------------"
				. "\n\n" . $this->unit 
				. " (km: ". $this->km . ")"
				. "\nDatum: \t" . $this->date 
				. "\nWachbeginn: \t" . $this->beginn 
				. "\nEnde: \t\t" . $this->end
				. "\n\n";
						
		foreach ($this->staffList as $value) {
			$string = $string . $value->toMail();
		}
		
		return $string . "\n";
	}
	
}