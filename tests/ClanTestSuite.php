<?php
require_once 'TestResult.php';
require_once 'objects/user.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/compohandler.php';
require_once 'database.php';
require_once 'settings.php';

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
		$sql = Database::open(Settings::db_name_infected_compo);

		$event = EventHandler::getCurrentEvent();
		$startTime = time()+10;
		$registerTime = time()+5;

		$sql->query('INSERT INTO `' . Settings::db_table_infected_compo_compos . '`(`eventId`, `name`, `tag`, `description`, `mode`, `price`, `startTime`, `registrationDeadline`, `teamSize`) '.
																					'VALUES (\'' . $event->getId() . '\', \'Test compo\', \'This is a test compo\', \'1v1\', 1337, \'' . date( 'Y-m-d H:i:s', $startTime ) . '\', \'' . date( 'Y-m-d H:i:s', $registerTime ) . '\', 5);');
		
		$compo = CompoHandler::getCompo($sql->insert_id);
		if($this->assert_not_equals($event, null)) {

		}
		$this->report(TestResult::TEST_NOT_RAN, "Yoloswag");
	}
}
?>