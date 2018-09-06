<?php
class ReportUnitStaff {
	
	public $position;
	public $name;
	public $engine;
	
	function __construct($position, $name, $engine) {
		$this->position = $position;
		$this->name = $name;
		$this->engine = $engine;
	}
	
	function toString(){
		return "Position: " . $this->position
				. " - Name: " . $this->name 
				. " - Engine: " . $this->engine
				. "<br>";
	}
}
?>