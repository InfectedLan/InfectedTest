<?php
	require_once 'TestSuite.php';
	require_once 'TestSuiteResult.php';
	require_once 'TestResult.php';
	require_once 'TestReporter.php';
	require_once 'WebReporter.php';

	class InfectedTestbed {
		private $testSuites;

		public function __construct() {
			//Initialize test here
			$this->testSuites = array();
			$this->testSuites[] = new TestSuite("Test suite");
		}

		public function runTests() {
			$results = array();

			foreach ($this->testSuites as $suite) {
				//Run in try block so we can catch errors
				try {
					$suiteResults = $suite->runTests();
					$results[] = $suiteResults;
				} catch(Exception $e) {
					$testResult = new TestResult("[CORE]ErrorCatcher", TestResult::TEST_FAILED, "An exception occured while running the test suite: ");
					$results[] = new TestSuiteResult($suite->getName(), array($testResult));
				}
			}

			return $results;
		}
	}

	//Run the test
	$testbed = new InfectedTestbed();
	$testResults = $testbed->runTests();

	//Only support web for now, but we could do this CLI on a CRON job or something connected to git?
	$reporter = new WebReporter();
	$reporter->report($testResults);
?>