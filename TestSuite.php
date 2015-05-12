<?php
require_once 'TestResult.php';

class TestSuite {
	private $name;
	private $testList;
	private $testReporter;
	public function __construct($reporter) {
		$this->name = get_called_class();
		$this->testReporter = $reporter;
		$this->testList = array();
	}
	public function getName() {
		return $this->name;
	}

	//Override this
	public function test() {
		$this->assert(false, "Remember to override test!");
	}

	//Asserts if val is true
	protected function assert($val) {

		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1]["function"];
		$line = $stackTrace[0]["line"];

		$this->testReporter->report(new TestResult($caller . "() line " . $line, $val === true ? TestResult::TEST_PASSED : TestResult::TEST_FAILED, ""));
	}

	//Asserts if val is equal(type specific) to value passed
	protected function assert_equals($val, $expected) 
	{
		$comment = "Test passed";
		if($val !== $expected) {
			$comment = "Expected " . $expected . ", got " . $val;
		}

		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1]["function"];
		$line = $stackTrace[0]["line"];

		$this->testReporter->report(new TestResult($caller . "() line " . $line, $val === $expected ? TestResult::TEST_PASSED : TestResult::TEST_FAILED, $comment));
	}

	//Asserts if val is not equal(type specific) to value passed
	protected function assert_not_equals($val, $expected) 
	{
		$comment = "Test passed";
		if($val === $expected) {
			$comment = "Expected " . $val . ", to not be equal to " . $expected;
		}

		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1]["function"];
		$line = $stackTrace[0]["line"];

		$this->testReporter->report(new TestResult($caller . "() line " . $line, $val !== $expected ? TestResult::TEST_PASSED : TestResult::TEST_FAILED, $comment));
	}

	//Same as assert_equals, but with a different text on a failure(for troubleshooting)
	protected function assert_compare($val1, $val2) 
	{
		$comment = "Test passed";
		if($val1 !== $val2) {
			$comment = $val1 . " is not the same as " . $val2;
		}

		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1]["function"];
		$line = $stackTrace[0]["line"];

		$this->testReporter->report(new TestResult($caller . "() line " . $line, $val1 === $val2 ? TestResult::TEST_PASSED : TestResult::TEST_FAILED, $comment));
	}

	//An assertion with custom rersult and comment. Basically ignoring all the things in the functions above
	protected function report($result, $comment) {
		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1]["function"];
		$line = $stackTrace[0]["line"];

		$this->testReporter->report(new TestResult($caller . " line " . $line, $result, $comment));
	}

	public function runTests() {
		$this->testReporter->beginSuite($this->name);
		$this->test();
	}
}
?>