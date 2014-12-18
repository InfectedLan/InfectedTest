<?php
require_once 'TestReporter.php';

class WebReporter extends TestReporter {
	public function report($testResults) {
		echo "<html>";
			echo "<head>";
				echo "<style type='text/css'>";
					echo '.passed { background-color: rgb(100, 255, 100); }';
					echo '.failed { background-color: rgb(255, 100, 100); }';
					echo '.warn { background-color: rgb(255, 128, 100); }';
					echo '.not_ran { background-color: rgb(100, 100, 255); }';
					echo '.css_error { background-color: rgb(100, 255, 100); }';
				echo "</style>";
			echo "</head>";
			echo "<body>";
				echo "<h1>Test results for InfectedTest</h1>";
				foreach ($testResults as $suiteResult) {
					$this->reportSuite($suiteResult);
				}
			echo "</body>";
		echo "</html>";
	}
	private function reportSuite($suiteResult) {
		$testResults = $suiteResult->getResults();
		$passed = 0;
		$failed = 0;
		$passed_with_warn = 0;
		$not_ran = 0;
		foreach($testResults as $testResult) {
			switch ($testResult->getResult()) {
				case TestResult::TEST_PASSED:
					$passed++;
					break;
				case TestResult::TEST_FAILED:
					$failed++;
					break;
				case TestResult::TEST_PASSED_WITH_WARNING:
					$passed_with_warn++;
					break;
				case TestResult::TEST_NOT_RAN:
					$not_ran++;
					break;
				
				default:
					break;
			}
		}
		echo '<h3>Test report for suite "' . $suiteResult->getName() . '". ' . $passed . ' passed, ' . $failed . ' failed, ' . $passed_with_warn . ' passed with warning, ' . $not_ran . ' not ran.' . '</h3>';
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