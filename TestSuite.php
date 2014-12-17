<?php
require_once 'TestResult.php';
require_once 'TestSuiteResult.php';

class TestSuite {
	private $name;
	public function __construct($name) {
		$this->name = $name;
	}
	public function getName() {
		return $this->name;
	}
	public function runTests() {
		$testList = array();

		$testList[] = new TestResult("Standard test", TestResult::TEST_FAILED, "runTests is not overridden!");

		return new TestSuiteResult($this->name, $testList);
	}
}
?>