<?php
require_once 'testApi/TestSuite.php';
require_once 'testApi/TestResult.php';

require_once 'database.php';
require_once 'settings.php';

require_once 'handlers/userhandler.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/compohandler.php';
require_once 'handlers/clanhandler.php';
require_once 'objects/user.php';

/*
 * ClanTestSuite
 *
 * Responsible for testing ClanHandler and the Clan object
 *
 */
class ClanTestSuite extends TestSuite {
	public function test() {
		$this->clanCreationTest();
		$this->clanInviteMemberTest();
	}

	/*
	 * Tests the functionality of ClanHandler::invite. Not a test for InviteHandler.
	 */
	private function clanInviteMemberTest() {
		// Expects clanCreationTest to be ran
		$user = UserHandler::getUserByIdentifier("petterroea"); //Any user we know is in the db
		$testUser = UserHandler::getUserByIdentifier("test1"); //Another user. This one shouldnt be admin, to test permission stuff

		//ClanHandler::getClansByUser()
		if($this->assert_equals(count(ClanHandler::getClansByUser($user)), 1)) {
			$clan = ClanHandler::getClansByUser($user)[0];
			$members = $clan->getMembers();
			$this->assert_equals(count($members), 1);

			//ClanHandler::getClanMembers
			$members = ClanHandler::getClanMembers($clan);
			$this->assert_equals(count($members), 1);
			
			// Invite a new member. We have to go through the entire registration process because api.
			$clan->invite($testUser);
			$invites = InviteHandler::getInvitesByUser($testUser);
			$this->assert_equals(count($invites), 1);
		}
	}

	private function clanCreationTest() {
		$sql = Database::open(Settings::db_name_infected_compo);

		$user = UserHandler::getUserByIdentifier("petterroea"); // Any user we know is in the db

		$event = EventHandler::getCurrentEvent();
		$startTime = time()+10;
		$registerTime = time()+5;

		$sql->query('INSERT INTO `' . Settings::db_table_infected_compo_compos . '`(`eventId`, `name`, `tag`, `desciption`, `mode`, `price`, `startTime`, `registrationDeadline`, `teamSize`) '.
																					'VALUES (\'' . $event->getId() . '\', \'Test compo\', \'COMPO\', \'This is a test compo\', \'1v1\', 1337, \'' . date( 'Y-m-d H:i:s', $startTime ) . '\', \'' . date( 'Y-m-d H:i:s', $registerTime ) . '\', 5);');
		
		$compo = CompoHandler::getCompo($sql->insert_id);

		$this->assert_equals(count(ClanHandler::getClansByCompo($compo)), 0);

		if ($this->assert_not_equals($event, null)) {
			$clan = ClanHandler::createClan($event, "Test clan", "CLAN", $compo, $user);
			
			if ($this->assert_not_equals($clan, null)) {
				//ClanHandler::getClansByCompo()
				if($this->assert_equals(count(ClanHandler::getClansByCompo($compo)), 1)) {
					$this->assert_equals(ClanHandler::getClansByCompo($compo)[0]->getId(), $clan->getId());
				}

				$this->assert_equals($clan->getName(), "Test clan");
				$this->assert_equals($clan->getTag(), "CLAN");
				$this->assert_equals($clan->getChief()->getId(), $user->getId());
				$this->assert_equals($clan->getCompo()->getId(), $compo->getId());
				$members = $clan->getMembers();
				
				if ($this->assert_equals(count($members), 1)) {
					$this->assert_equals($members[0]->getId(), $user->getId());
				}

				$members = ClanHandler::getClanMembers($clan);

				if ($this->assert_equals(count($members), 1)) {
					$this->assert_equals($members[0]->getId(), $user->getId());
				}

				//ClanHandler::getClan()
				$fetchClanTest = ClanHandler::getClan($clan->getId());
				$this->assert_equals($fetchClanTest->getId(), $clan->getId());

				$this->assert_equals($fetchClanTest->getName(), "Test clan");
				$this->assert_equals($fetchClanTest->getTag(), "CLAN");
				$this->assert_equals($fetchClanTest->getChief()->getId(), $user->getId());
				$this->assert_equals($fetchClanTest->getCompo()->getId(), $compo->getId());
				$members = $fetchClanTest->getMembers();
				
				if ($this->assert_equals(count($members), 1)) {
					$this->assert_equals($members[0]->getId(), $user->getId());
				}
			}
		}
	}
}
?>