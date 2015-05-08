<?php
require_once 'TestResult.php';
require_once 'TestSuiteResult.php';
require_once 'objects/user.php';
require_once 'handlers/userhandler.php';

class UserTestSuite extends TestSuite {

	//Override this
	public function test() {
		$this->assert(false, "User test suite not implemented!");
	}
}
?>