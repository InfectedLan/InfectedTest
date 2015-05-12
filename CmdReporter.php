<?php
class CmdReporter extends TestReporter{
	private $suites;

	public function __construct() {
		$this->suites = array();
	}

	public function beginSuite($suiteName) {
		$this->suites[] = array("name" => $suiteName, "results" => array());
		echo "===BEGIN SUITE " . $suiteName . "===\n";
	}
	public function report($result) {
		$this->suites[sizeof($this->suites)-1][] = $result;
		echo $result->getResultString() . " - " . $result->getName() . " - " . $result->getMessage() . "\n";
	}

	public function doneTesting() {
		//print_r($this->suites);
	}
}
?>