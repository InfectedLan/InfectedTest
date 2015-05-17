<?php
require_once 'TestReporter.php';

class WebReporter extends TestReporter {
	private $suites;
	private $errors;

	public function __construct() {
		$this->suites = array();
	}

	public function beginSuite($suiteName) {
		$this->suites[] = array("name" => $suiteName, "results" => array());
	}
	public function report($result) {
		array_push($this->suites[sizeof($this->suites)-1]["results"], $result);
	}

	public function doneTesting() {
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
				foreach ($this->suites as $suiteResult) {
					$this->reportSuite($suiteResult);
				}
				if(sizeof($this->errors)>0) {
					$this->reportErrors();
				}
			echo "</body>";
		echo "</html>";
	}
	private function reportSuite($suiteResult) {
		//print_r($suiteResult);
		$testResults = $suiteResult["results"];
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
		echo '<h3>Test report for suite "' . $suiteResult["name"] . '". ' . $passed . ' passed, ' . $failed . ' failed, ' . $passed_with_warn . ' passed with warning, ' . $not_ran . ' not ran.' . '</h3>';
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

	public function fatal_error($code, $message, $file, $line) {
			$stacktrace = debug_backtrace();
			echo "<table id=\"error\" border=\"1\">";
			echo "<tr>";
				echo "<td colspan=\"3\"><h1>Fatal error</h1></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td colspan=\"3\"><i>Error info</i></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>Type:</td>";
				echo "<td colspan=\"2\">" . $this->codeToString($code) . "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>Message:</td>";
				echo "<td colspan=\"2\">" . $message . "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>File:</td>";
				echo "<td colspan=\"2\">" . $file . "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>Line:</td>";
				echo "<td colspan=\"2\">" . $line . "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td colspan=\"3\"><i>Stack trace</i></td>";
			echo "</tr>";
			$i = 0;
			foreach ($stacktrace as $stack) {
				echo "<tr>";
					echo "<td>" . $i . "</td>";
					echo "<td>" . ( isset($stack["file"]) ? $stack["file"] : "<i>No file</i>" ) . " line " . ( isset($stack["line"]) ? $stack["line"] : "<i>No line</i>" )  . "</td>";
					echo "<td>" . $stack["function"] . "()</td>";
				echo "</tr>";
					foreach($stack["args"] as $argument) {
						echo "<tr>";
						echo "<td></td>";
						echo "<td colspan=\"2\"><pre>";
						print_r($argument);
						echo "</pre></td>";
						echo "</tr>";
					}				
				$i++;
			}
		echo "</table>";
	}
	public function error($code, $message, $file, $line) {
		$this->errors[] = array("code" => $code, "message" => $message, "file" => $file, "line" => $line);
	}

	private functionreportErrors() {
		echo "<h1>Errors</h1>";
		echo "<table>";
		echo "<tr><td>Type</td><td>Message</td><td>File</td><td>Line</td></tr>";
		foreach($this->errors as $error) {

		}
		echo "</table>";
	}

	private function codeToString($code) {
		if($code==E_ERROR) {
			return "E_ERROR";
		}
		if($code==E_WARNING) {
			return "E_WARNING";
		}
		if($code==E_PARSE) {
			return "E_PARSE";
		}
		if($code==E_NOTICE) {
			return "E_NOTICE";
		}
		if($code==E_CORE_ERROR) {
			return "E_CORE_ERROR";
		}
		if($code==E_CORE_WARNING) {
			return "E_CORE_WARNING";
		}
		if($code==E_COMPILE_ERROR) {
			return "E_COMPILE_ERROR";
		}
		if($code==E_USER_ERROR) {
			return "E_USER_ERROR";
		}
		if($code==E_USER_WARNING) {
			return "E_USER_WARNING";
		}
		if($code==E_USER_NOTICE) {
			return "E_USER_NOTICE";
		}
		if($code==E_STRICT) {
			return "E_STRICT";
		}
		if($code==E_RECOVERABLE_ERROR) {
			return "E_RECOVERABLE_ERROR";
		}
		if($code==E_ALL) {
			return "E_ALL";
		}
		return "?";
	}
}
?>