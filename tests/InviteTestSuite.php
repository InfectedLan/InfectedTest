<?php
require_once 'TestSuite.php';
require_once 'TestResult.php';

require_once 'handlers/userhandler.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/compohandler.php';
require_once 'handlers/clanhandler.php';
require_once 'handlers/invitehandler.php';
require_once 'objects/user.php';

/*
 * InviteTestSuite
 *
 * Responsible for testing InviteHandler and the Invite object
 *
 */
class InviteTestSuite extends TestSuite {
	public function test() {
		$this->inviteCreationTest();
	}

	private function inviteCreationTest() {
		$petterroea = UserHandler::getUserByIdentifier("petterroea");
		$compo = CompoHandler::getCompo(1); //In deployment
		$event = EventHandler::getCurrentEvent();

		$clan = ClanHandler::createClan($event, "the yolos", "yolo", $compo, $petterroea);
		$randomNonAdmin = UserHandler::getUserByIdentifier("test1");

		$this->assert_equals(count(InviteHandler::getInvitesByClan($clan)), 0);
		InviteHandler::createInvite($clan, $randomNonAdmin);
		$this->assert_equals(count(InviteHandler::getInvitesByClan($clan)), 1);

		$this->assert_equals(count(InviteHandler::getInvites()), 1);

		if ($this->assert_equals(count(InviteHandler::getInvitesByUser($randomNonAdmin)), 1)) {
			$invite = InviteHandler::getInvitesByUser($randomNonAdmin)[0];
		}
	}	
}
?>