<?php
	require_once 'TestSuite.php';
	require_once 'TestSuiteResult.php';
	require_once 'TestResult.php';
	require_once 'TestReporter.php';
	require_once 'WebReporter.php';
	require_once 'testApi/settings.php';
	require_once 'testApi/secret.php';

	class InfectedTestbed {
		private $testSuites;

		public function __construct() {
			//Initialize test here
			$this->testSuites = array();
			$this->testSuites[] = new TestSuite("Test suite");
		}

		private function initDatabase() {
			$databases = array();
			$databases[] = array("fs_location" => "deploymentData/test_infected_no.sql", "sql_db" => Settings::db_name_infected);
			$databases[] = array("fs_location" => "deploymentData/test_infected_no_compo.sql", "sql_db" => Settings::db_name_infected_compo);
			$databases[] = array("fs_location" => "deploymentData/test_infected_no_crew.sql", "sql_db" => Settings::db_name_infected_crew);
			$databases[] = array("fs_location" => "deploymentData/test_infected_no_info.sql", "sql_db" => Settings::db_name_infected_info);
			$databases[] = array("fs_location" => "deploymentData/test_infected_no_main.sql", "sql_db" => Settings::db_name_infected_main);
			$databases[] = array("fs_location" => "deploymentData/test_infected_no_tickets.sql", "sql_db" => Settings::db_name_infected_tickets);
			
			foreach($databases as $database) {
				$mysqli = new mysqli(Settings::db_host, 
							   Secret::db_username, 
							   Secret::db_password);

				if($mysqli->select_db($database["sql_db"])) {
					//Database exists
				} else {
					$mysqli->query("CREATE DATABASE " . $database["sql_db"]);
				}

				$command = "mysql -h " . Settings::db_host . " -u '" . Secret::db_username . "' -p'" . Secret::db_password . "' '" . $database["sql_db"] . "' < '" . $database["fs_location"] . "'";
				//echo "<p>" . $command . ", " . shell_exec($command) . "</p>";
			}
		}

		public function runTests() {
			$results = array();

			$this->initDatabase();

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