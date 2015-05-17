<?php
require_once 'TestSuite.php';
require_once 'TestResult.php';

require_once 'handlers/eventhandler.php';
require_once 'handlers/grouphandler.php';
require_once 'objects/group.php';

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
		// Some variables needed in order to run this.
		$event = EventHandler::getCurrentEvent();
		$user = UserHandler::getUser(1);

		/* 
		public static function getGroup($id)
		*/
		// Check that the user does not exist. This is done to test that the function does not return bogus data, and is a recommended thing to test. Test everything, basically.
		$group = GroupHandler::getGroup(1);
		$this->assert_not_equals($group, null);
		
		/*
		public static function getGroupByEventAndUser(Event $event, User $user)
		*/
		$groupList = GroupHandler::getGroupsByEventAndUser($event, $user);
		$this->assert_equals(count($groupList), 8);

		/*
		public static function getGroupByUser(User $user)
		*/
		$groupList = GroupHandler::getGroupsByUser($user);
		$this->assert_equals(count($groupList), 8);

		/*
		public static function getGroupsByEvent(Event $event)
		*/
		$groupList = GroupHandler::getGroupsByEvent($event);
		$this->assert_equals(count($groupList), 8);

		/*
		public static function getGroups()
		*/
		// Get all groups and checks if count equals 8.
		$groupList = GroupHandler::getGroups();
		$this->assert_equals(count($groupList), 8);

		/*
		public static function createGroup(Event $event, $name, $title, $description, User $leaderUser = null, User $coleaderUser = null)
		*/
		$createdGroup = GroupHandler::createGroup($event, 'assertGroup', 'assertGroup', 'assertGroup');

		if ($this->assert_not_equals($createdGroup, null)) {
			/*
			public static function updateGroup(Group $group, $name, $title, $description, User $leaderUser = null, User $coleaderUser = null)
			*/

			/*
			public static function removeGroup(Group $group)
			*/
		}

		/*
		public static function getMembersByEvent(Event $event, Group $group)

		public static function getMembers(Group $group)

		public static function isGroupMemberByEvent(Event $event, User $user)

		public static function isGroupMember(User $user) 

		public static function isGroupLeaderByEvent(Event $event, User $user) 

		public static function isGroupLeader(User $user) 

		public static function isGroupCoLeaderByEvent(Event $event, User $user) 

		public static function isGroupCoLeader(User $user) 

		public static function changeGroupForUser(User $user, Group $group)

		public static function removeUserFromGroup(User $user) 

		public static function removeUsersFromGroup(Group $group) 
		*/

		/*
		//Check that the user does not exist. This is done to test that the function does not return bogus data, and is a reccomended thing to test. Test everything, basically.
		$user = UserHandler::getUserByIdentifier("assertUser");
		$this->assert_equals($user, null);
		//Check that we can get the user by email
		$user = UserHandler::getUserByIdentifier("assertUser@infected.no");
		$this->assert_equals($user, null);

		//Let's create another user
		$createdUser = UserHandler::createUser("assertionFirstname", 
											 	"assertionLastname", "assertUser", "32cdb619196200050ab0af581a10fb83cfc63b1a20f58d4bafb6313d55a3f0e9", "assertUser@infected.no", "1998-03-27 00:00:00", 0, "12345678", "Test address", 1337, "AssertNick");
		
		if ($this->assert_not_equals($createdUser, null)) {
			//Check that we can get the user by username
			$user = UserHandler::getUserByIdentifier("assertUser");
			$this->assert_not_equals($user, null);
			//Check that we can get the user by email
			$email_user = UserHandler::getUserByIdentifier("assertUser@infected.no");
			$this->assert_not_equals($email_user, null);

			//Check if the two accounts are the same account
			$this->assert_compare($user->getId(), $email_user->getId());
			//Check if this is the user we inserted
			$this->assert_compare($user->getId(), $createdUser->getId());

			//Check that the fields we inserted are intact
			$this->assert_compare($user->getFirstname(), "assertionFirstname");
			$this->assert_compare($user->getLastname(), "assertionLastname");
			$this->assert_compare($user->getUsername(), "assertUser");
			$this->assert_compare($user->getPassword(), "32cdb619196200050ab0af581a10fb83cfc63b1a20f58d4bafb6313d55a3f0e9");
			$this->assert_compare($user->getEmail(), "assertUser@infected.no");
			$this->assert_compare($user->getBirthdate(), 890953200);
			$this->assert_compare($user->getGenderAsString(), "Gutt");
			$this->assert_compare($user->getPhoneAsString(), "12 34 56 78");
			$this->assert_compare($user->getPhone(), 12345678);
			$this->assert_compare($user->getAddress(), "Test address");
			$this->assert_compare($user->getPostalCode(), "1337");
			$this->assert_compare($user->getNickname(), "AssertNick");
		}

		//One last thing, check if girl string also works
		$createdUser = UserHandler::createUser("assertionGirlFirst", "assertionGirlLast", "assertGirl", "32cdb619196200050ab0af581a10fb83cfc63b1a20f58d4bafb6313d55a3f0e9", "assertGirl@infected.no", "1998-03-27 00:00:00", 1, "12345678", "Test address", 1337, "AssertGirl");
		
		if ($this->assert_not_equals($createdUser, null)) {
			$user = UserHandler::getUserByIdentifier("assertGirl");

			$this->assert_not_equals($user, null);

			$this->assert_compare($user->getGenderAsString(), "Jente");
		}
		*/
	}
}
?>