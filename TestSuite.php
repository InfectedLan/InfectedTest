<?php
require_once 'TestResult.php';
require_once 'TestSuiteResult.php';

class TestSuite {
	private $name;
	private $testList;
	public function __construct($name) {
		$this->name = $name;
		$this->testList = array();
	}
	public function getName() {
		return $this->name;
	}

	//Override this
	public function test() {
		$this->assert(false, "Remember to override test!");
	}

	//if $data is a boolean, that is what we want $val to be
	private function assert($val, $data) {
		//Some metadata
		$comment = "";
		$expectedVal = true;
		if(is_string($data)) {
			$comment = $data;
		}
		if(is_bool($data)) {
			$expectedVal = $data;
		}

		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1];

		$this->testList[] = new TestResult($caller["function"] . "() line " . $caller["line"], $val == $expectedVal ? TestResult::TEST_PASSED : TestResult::TEST_FAILED, $comment);
	}

	private function report($result, $comment) {
		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1];

		$this->testList[] = new TestResult($caller["function"] . " line " . $caller["line"], $result, $comment);
	}

	public function runTests() {
		$this->testList = array();

		$this->test();

		return new TestSuiteResult($this->name, $this->testList);
	}
}
?>