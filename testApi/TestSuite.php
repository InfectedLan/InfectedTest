<?php
require_once 'testApi/TestResult.php';

class TestSuite {
	private $name;
	private $testList;
	private $testReporter;

	public function __construct($reporter) {
		$this->name = get_called_class();
		$this->testReporter = $reporter;
		$this->testList = [];
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
		
		return $val === true;
	}

	//Asserts if val is equal(type specific) to value passed
	protected function assert_equals($val, $expected) {
		$comment = "Test passed";
		
		if ($val !== $expected) {
			$comment = "Expected " . self::createHumanString($expected) . ", got " . self::createHumanString($val);
		}

		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1]["function"];
		$line = $stackTrace[0]["line"];

		$this->testReporter->report(new TestResult($caller . "() line " . $line, $val === $expected ? TestResult::TEST_PASSED : TestResult::TEST_FAILED, $comment));
		
		return $val === $expected;
	}

	//Asserts if val is not equal(type specific) to value passed
	protected function assert_not_equals($val, $expected) {
		$comment = "Test passed";
		
		if ($val === $expected) {
			$comment = "Expected " . self::createHumanString($val) . " to not be equal to " . self::createHumanString($expected);
		}

		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1]["function"];
		$line = $stackTrace[0]["line"];

		$this->testReporter->report(new TestResult($caller . "() line " . $line, $val !== $expected ? TestResult::TEST_PASSED : TestResult::TEST_FAILED, $comment));
		
		return $val !== $expected;
	}

	protected function assert_greater_than($val, $expected) {
		$comment = "Test passed";
		
		if ($val < $expected) {
			$comment = "Expected " . self::createHumanString($expected) . ", got " . self::createHumanString($val);
		}

		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1]["function"];
		$line = $stackTrace[0]["line"];

		$this->testReporter->report(new TestResult($caller . "() line " . $line, $val > $expected ? TestResult::TEST_PASSED : TestResult::TEST_FAILED, $comment));
		
		return $val > $expected;
	}

	protected function assert_less_than($val, $expected) {
		$comment = "Test passed";
		
		if ($val > $expected) {
			$comment = "Expected " . self::createHumanString($expected) . ", got " . self::createHumanString($val);
		}

		$stackTrace = debug_backtrace();
		$caller = $stackTrace[1]["function"];
		$line = $stackTrace[0]["line"];

		$this->testReporter->report(new TestResult($caller . "() line " . $line, $val < $expected ? TestResult::TEST_PASSED : TestResult::TEST_FAILED, $comment));
		
		return $val < $expected;
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

	private function createHumanString($val) {
		if ($val == null) {
			return "null";
		}

		if (is_string($val)) {
			return '"' . $val . '"';
		}
		
		if(is_numeric($val)) {
			return strval($val);
		}
	}
}
?>