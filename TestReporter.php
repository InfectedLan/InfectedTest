<?php
class TestReporter {
	private $suites;

	public function __construct() {
		$this->suites = array();
	}

	public function beginSuite($suiteName) {
		$this->suites[] = array("name" => $suiteName, "results" => array());
	}
	public function report($result) {
		$this->suites[sizeof($this->suites)-1][] = $result;
	}

	public function doneTesting() {

	}
}
?>