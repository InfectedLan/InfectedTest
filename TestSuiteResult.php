<?php
class TestSuiteResult {

	private $suiteName;
	private $testResults;

	public function __construct($suiteName, $testResults) {
		$this->suiteName = $suiteName;
		$this->testResults = $testResults;
	}

	public function getName() {
		return $this->suiteName;
	}

	public function getResults() {
		return $this->testResults;
	}
}
?>