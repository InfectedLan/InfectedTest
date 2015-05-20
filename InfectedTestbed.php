<?php
	require_once 'TestSuite.php';
	require_once 'TestResult.php';
	require_once 'TestReporter.php';
	require_once 'CmdReporter.php';
	require_once 'WebReporter.php';
	require_once 'api/settings.php';
	require_once 'api/secret.php';

	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__DIR__) . '/test/api');

	function __autoload($class) {
		include 'tests/' . $class . '.php';
	}

	class InfectedTestbed {
		private $testSuites;

		public function __construct($reporter) {
			//Initialize test here
			$this->testSuites = array();
			//$this->testSuites[] = new TestSuite("Test suite", $reporter);
			
			// Infected
			$this->testSuites[] = new UserTestSuite($reporter);
			$this->testSuites[] = new LocationTestSuite($reporter);

			// InfectedCompo
			$this->testSuites[] = new ClanTestSuite($reporter);
			$this->testSuites[] = new InviteTestSuite($reporter);

			// InfectedCrew
			$this->testSuites[] = new GroupTestSuite($reporter);
		}

		//Handles stuff like git pulling
		private function initEnvironment() {
			//echo shell_exec("./updateRepositories");
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

				if ($mysqli->select_db($database["sql_db"])) {
					//Database exists
					$mysqli->query("DROP DATABASE " . $database["sql_db"]);
				}
				//echo "<p>" . $mysqli->error . "</p>";

				$mysqli->query("CREATE DATABASE " . $database["sql_db"]);

				$command = "mysql -h " . Settings::db_host . " -u '" . Secret::db_username . "' -p'" . Secret::db_password . "' '" . $database["sql_db"] . "' < '" . $database["fs_location"] . "'";
				//echo "<p>" . $command . ", " . shell_exec($command) . "</p>";
				shell_exec($command);
			}
		}

		public function runTests($reporter) {
			$results = array();

			$this->initEnvironment();
			$this->initDatabase();


			foreach ($this->testSuites as $suite) {
				//Run in try block so we can catch errors
				try {
					$suite->runTests();
				} catch(Exception $e) {
					$testResult = new TestResult("[CORE]ErrorCatcher", TestResult::TEST_FAILED, "An exception occured while running the test suite: " . $e);
					$reporter->report($testResult);
				}
			}

			return $results;
		}
	}
	//Reporter
	$reporter = new WebReporter();

	//Error handling. We are using a hack to catch all errors, as set_error_handler does not catch all by default
	//See http://insomanic.me.uk/post/229851073/php-trick-catching-fatal-errors-e-error-with-a
	//set_error_handler('myErrorHandler');
	//register_shutdown_function('fatalErrorShutdownHandler');

	function fatalErrorShutdownHandler()
	{
		$last_error = error_get_last();
		if ($last_error['type'] === E_ERROR) {
			// fatal error
		  	$reporter->fatal_error(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
		}
	}

	function myErrorHandler($code, $message, $file, $line) {
		global $reporter;
		if($code === E_ERROR) {
			$reporter->fatal_error($code, $message, $file, $line);
		} else {
			$reporter->error($code, $message, $file, $line);
		}
	}

	//error_reporting(0); //Turns off the automatic error reporting, we are handling it ourselves

	//Run the test	
	$testbed = new InfectedTestbed($reporter);
	$testbed->runTests($reporter);

	//Done testing. This is for reporters that report after testing is done
	$reporter->doneTesting();
?>