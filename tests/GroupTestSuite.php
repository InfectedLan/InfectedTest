<?php
require_once 'TestSuite.php';
require_once 'TestResult.php';

require_once 'handlers/eventhandler.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/grouphandler.php';

/*
 * GroupTestSuite
 *
 * Responsible for testing GroupHandler and the Group object.
 *
 */
class GroupTestSuite extends TestSuite {
	public function test() {
		$this->groupCreationTest();
	}

	private function groupCreationTest() {
		$event = EventHandler::getCurrentEvent();
		$user = UserHandler::getUser(1);

		// public static function getGroup($id)
		$group = GroupHandler::getGroup(1); // Check that the user does not exist. This is done to test that the function does not return bogus data, and is a recommended thing to test. Test everything, basically.
		$this->assert_not_equals($group, null);
		
		// public static function getGroupByEventAndUser(Event $event, User $user)
		$group = GroupHandler::getGroupByEventAndUser($event, $user);
		$this->assert_not_equals(count($group), null);

		// public static function getGroupByUser(User $user)
		$group = GroupHandler::getGroupByUser($user);
		$this->assert_not_equals(count($group), null);

		// public static function getGroupsByEvent(Event $event)
		$groupList = GroupHandler::getGroupsByEvent($event);
		$this->assert_greater_than(count($groupList), 1);

		// public static function getGroups()
		$groupList = GroupHandler::getGroups();
		$this->assert_greater_than(count($groupList), 1);

		// public static function createGroup(Event $event, $name, $title, $description, User $leaderUser = null, User $coleaderUser = null)
		$createdGroup = GroupHandler::createGroup($event, 'assertGroup', 'assertGroup', 'assertGroup');

		if ($this->assert_not_equals($createdGroup, null)) {
			// public static function updateGroup(Group $group, $name, $title, $description, User $leaderUser = null, User $coleaderUser = null)

			// public static function removeGroup(Group $group)
			GroupHandler::removeGroup($createdGroup);
			$group = GroupHandler::getGroup($createdGroup->getId());
			$this->assert_equals($group, null);
		}

		// public static function getMembersByEvent(Event $event, Group $group)
		$group = GroupHandler::getGroup(1);
		$memberList = GroupHandler::getMembersByEvent($event, $group);
		$this->assert_greater_than(count($memberList), 1);

		// public static function getMembers(Group $group)
		$group = GroupHandler::getGroup(1);
		$memberList = GroupHandler::getMembers($group);
		$this->assert_greater_than(count($memberList), 1);

		// public static function isGroupMemberByEvent(Event $event, User $user)

		// public static function isGroupMember(User $user) 

		// public static function isGroupLeaderByEvent(Event $event, User $user) 

		// public static function isGroupLeader(User $user) 

		// public static function isGroupCoLeaderByEvent(Event $event, User $user) 

		// public static function isGroupCoLeader(User $user) 

		// public static function changeGroupForUser(User $user, Group $group)

		// public static function removeUserFromGroup(User $user) 

		// public static function removeUsersFromGroup(Group $group) 
	}
}
?>