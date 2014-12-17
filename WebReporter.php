<?php
require_once 'TestReporter.php';

class WebReporter extends TestReporter {
	public function report($testResults) {
		echo "<h1>Test results for ";
		foreach ($testResults as $suiteResult) {
			$this->reportSuite($suiteResult);
		}
	}
	private function reportSuite($suiteResult) {
		echo '<h3>Test report for suite "' . $suiteResult->getName() . '"</h3>';
		$testResults = $suiteResult->getResults();
		foreach ($testResults as $testResult) {
			echo '<div class="';
			switch ($testResult->getResult()) {
				case TestResult::TEST_PASSED:
					echo 'passed';
					break;
				case TestResult::TEST_FAILED:
					echo 'failed';
					break;
				case TestResult::TEST_PASSED_WITH_WARNING:
					echo 'warn';
					break;
				case TestResult::TEST_NOT_RAN:
					echo 'not_ran';
					break;
				
				default:
					echo 'css_error';
					break;
			}
			echo '">';
				echo '<b>';
					echo $testResult->getName();
					echo ' - ';
					switch ($testResult->getResult()) {
					case TestResult::TEST_PASSED:
						echo 'Passed';
						break;
					case TestResult::TEST_FAILED:
						echo 'Failed';
						break;
					case TestResult::TEST_PASSED_WITH_WARNING:
						echo 'Passed with warnings';
						break;
					case TestResult::TEST_NOT_RAN:
						echo 'Not ran';
						break;
					
					default:
						echo 'INTERNAL ERROR';
						break;
				}
				echo '</b>';
				echo ' ';
				echo '(<i>';
					echo $testResult->getMessage();
				echo '</i>)';
			echo '</div>';
		}
	}
}
?>