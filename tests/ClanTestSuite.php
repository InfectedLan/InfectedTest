<?php
require_once 'TestResult.php';
require_once 'objects/user.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/eventhandler.php';

/*
 * ClanTestSuite
 *
 * Responsible for testing ClanHandler and the Clan object
 *
 */
class ClanTestSuite extends TestSuite {
	public function test() {
		$this->clanCreationTest();
	}
	private function clanCreationTest() {
		$event = EventHandler::getCurrentEvent();
		if($this->assert_not_equals($event, null)) {
			
		}
	}
}
?>