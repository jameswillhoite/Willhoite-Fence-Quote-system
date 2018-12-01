<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/23/18
	 * Time: 2:18 PM
	 */

	require_once 'master.php';
	class ProjectModelAddUser extends ProjectModelMaster
	{

		private $return;
		public function __construct()
		{
			$this->return = new ArrayObject(array('error' => false, 'error_msg' => '', 'data' => null), 2);
		}

		public function addUser($name, $email, $password) {
			if(!$name || !$email || !$password) {
				return $this->returnError("All Fields Required");
			}

			$query = "INSERT INTO users (name, email, password) VALUE ('" . addslashes($name) . "', '" . addslashes($email) . "', '" . addslashes($password) . "')";
			try {
				$result = $this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError("Could not add user. " . $ex->getMessage());
			}

			$uid = $result['insertID'];

			$query = "INSERT INTO group_map (groupID, userID) VALUES (5, $uid)";
			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				error_log("Couldn't add the user to the default registered group. " . $ex->getMessage());
			}

			return $this->returnData();
		}

		public function getListOfUsers() {
			$query = "SELECT u.name, u.userID, u.email, gm.groupID
				FROM users AS u 
				LEFT JOIN group_map AS gm ON u.userID = gm.userID  
				ORDER BY name";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError("Could not get List of Users");
			}
			$usedIDs = array();
			$newArray = array();
			foreach ($result['data'] as $r) {
				if(!isset($usedIDs[$r['userID']])) {
					$newArray[] = array(
						"userID" => $r['userID'],
						"name" => $r['name'],
						"email" => $r['email'],
						"groups" => array($r['groupID'])
					);
					$usedIDs[$r['userID']] = count($newArray) - 1; //Index of this user
				}
				else {
					$index = $usedIDs[$r['userID']];
					$newArray[$index]['groups'][] = $r['groupID'];
				}
			}

			return $this->returnData($newArray);
		}

		public function getListOfGroups() {
			$query = "SELECT groupID, name FROM groups";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				error_log("Could not get a list of groups. Mysql Error: " . $ex->getMessage());
				return $this->returnError("Could not get a list of Groups");
			}

			return $this->returnData($result['data']);
		}

		public function updateGroup($uid, $gid, $addRemove) {
			if(!$uid && !$gid && !$addRemove) {
				return $this->returnError("Need all fields to update group for user.");
			}

			if($addRemove == "add") {
				$query = "INSERT INTO group_map (groupID, userID) VALUES ($gid, $uid)";
			}
			else {
				$query = "DELETE FROM group_map WHERE groupID = $gid AND userID = $uid";
			}

			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError("Could not Add or Remove the User from the group");
			}

			//Get a new list of groups for the user
			$query = "SELECT groupID FROM group_map WHERE userID = $uid";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError("User was Added/Removed from the Group, but could not get a new list");
			}

			return $this->returnData($result['data']);
		}

		public function updateUser($uid, $name = null, $email = null, $password = null) {
			if(!$uid)
				return $this->returnError("No User ID");

			$tuples = array();

			if($name)
				$tuples[] = "name = '" . $name . "'";
			if($email)
				$tuples[] = "email = '" . $email . "'";
			if($password)
				$tuples[] = "password = '" . $password . "'";

			if(count($tuples) == 0)
				return $this->returnError("User was not updated. Nothing was changed.");

			$query = "UPDATE users SET " . implode(',', $tuples) . " WHERE userID = $uid";

			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError("Could not update the user");
			}

			return $this->returnData();

		}

		public function checkForDuplicateEmail($email, $uid = null) {
			$query = "SELECT userID FROM users WHERE email = '" . $email . "'";

			if($uid) {
				$query .= " AND userID <> '$uid'";
			}

			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				error_log("Could not check for duplicate email: " . $ex->getMessage());
				return $this->returnError("Could not check for duplicate email.");
			}

			return $this->returnData($result['data']);
		}

		private function returnError($txt) {
			$this->return->error = true;
			$this->return->error_msg = $txt;
			return $this->return;
		}
		private function returnData($data = null) {
			if($data) {
				$this->return->data = $data;
			}
			$return = new ArrayObject(array(
				"error" => $this->return->error,
				"error_msg" => $this->return->error_msg,
				"data" => $this->return->data
			), 2);

			$this->return->error = false;
			$this->return->error_msg = '';
			$this->return->data = null;
			return $return;
		}
	}