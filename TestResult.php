<?php
class TestResult {

	const TEST_PASSED = 0;
	const TEST_FAILED = 1;
	const TEST_PASSED_WITH_WARNING = 2;
	const TEST_NOT_RAN = 3;

	private $testName;
	private $testResult;
	private $message;

	public function __construct($testName, $testResult, $message) {
		$this->testName = $testName;
		$this->testResult = $testResult;
		$this->message = $message;
	}

	public function getName() {
		return $this->testName;
	}

	public function getResult() {
		return $this->testResult;
	}

	public function getMessage() {
		return $this->message;
	}
}
?>