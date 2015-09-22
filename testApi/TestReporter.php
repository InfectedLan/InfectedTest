<?php
class TestReporter {
	private $suites;

	public function __construct() {
		$this->suites = [];
	}

	public function beginSuite($suiteName) {
		$this->suites[] = array("name" => $suiteName, "results" => array());
	}
	public function report($result) {
		$this->suites[sizeof($this->suites)-1][] = $result;
	}

	public function doneTesting() {

	}

	public function fatal_error($code, $message, $file, $line) {
		echo "There was a fatal error, but the reporter has no way of reporting it!";
	}
	public function error($code, $message, $file, $line) {
		echo "There was an error, but the reporter has no way of reporting it!";
	}
}
?>