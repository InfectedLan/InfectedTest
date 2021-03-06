<?php
require_once 'testApi/TestSuite.php';
require_once 'testApi/TestResult.php';

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
		$this->assert_equals(count(ClanHandler::getClanMembers($clan)), 1);
		InviteHandler::createInvite($clan, $randomNonAdmin);
		//InviteHandler::getInvitesByClan
		$this->assert_equals(count(InviteHandler::getInvitesByClan($clan)), 1);
		$this->assert_equals(count(ClanHandler::getClanMembers($clan)), 1);

		//InviteHandler::getInvites
		$this->assert_equals(count(InviteHandler::getInvites()), 1);

		//InviteHandler::getInvitesByUser
		if ($this->assert_equals(count(InviteHandler::getInvitesByUser($randomNonAdmin)), 1)) {
			$invite = InviteHandler::getInvitesByUser($randomNonAdmin)[0];


			//InviteHandler::getInvite
			$getInviteTest = InviteHandler::getInvite($invite->getId());

			$this->assert_equals($getInviteTest->getId(), $invite->getId());

			//Invite->getUser()
			$this->assert_equals($invite->getUser()->getId(), $randomNonAdmin->getId());
			//Invite->getClan()
			$this->assert_equals($invite->getClan()->getId(), $clan->getId());
			//Invite->decline()
			$invite->decline();

			$this->assert_equals(count(InviteHandler::getInvitesByClan($clan)), 0);
			$this->assert_equals(count(InviteHandler::getInvites()), 0);
			$this->assert_equals(count(ClanHandler::getClanMembers($clan)), 1);

			//InviteHandler::createInvite
			InviteHandler::createInvite($clan, $randomNonAdmin);
			$invite = InviteHandler::getInvitesByUser($randomNonAdmin)[0];
			//Invite->accept()
			$invite->accept();
			
			if ($this->assert_equals(count(ClanHandler::getClanMembers($clan)), 2)) {
				$members = ClanHandler::getClanMembers($clan);
				$this->assert_equals($members[1]->getId(), $randomNonAdmin->getId());
			}

			$this->assert_equals(count(InviteHandler::getInvitesByClan($clan)), 0);
			$this->assert_equals(count(InviteHandler::getInvites()), 0);
		}
	}	
}
?>