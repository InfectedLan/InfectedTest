<?php
require_once 'TestResult.php';
require_once 'objects/user.php';
require_once 'handlers/userhandler.php';

/*
 * HEY!
 * 
 * I know this is overly commented, but that is the meaning. This is a "tutorial" on how we assert stuff, and so i explain a lot of stuff
 * If you are learning test writing from this, try to learn the way of thinking from reading the code :)
 *
 * - Liam
 */
class ClanTestSuite extends TestSuite {
	//Override this
	public function test() {
		$this->clanCreationTest();
	}
	private function clanCreationTest() {
		
	}
}
?>