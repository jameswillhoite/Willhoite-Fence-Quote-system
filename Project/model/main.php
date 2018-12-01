<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/23/18
	 * Time: 3:59 PM
	 */
	require_once 'master.php';

	class ProjectModelMain extends ProjectModelMaster
	{

		private $return;
		public function __construct()
		{
			$this->return = new ArrayObject(array('error' => false, 'error_msg' => '', 'data' => null), 2);
		}

		public function login($user, $password, DateTime $expire) {
			if(!$user || !$password) {
				return $this->returnError("required Fields not given");
			}
			$query = "SELECT userID, password FROM users WHERE email = '" . trim($user) . "'";
			try {
				$result = $this->queryMysql($query, 'loadAssoc');
			} catch (Exception $ex) {
				return $this->returnError("Cannot validate login");
			}

			$passwordDB = $result['data']['password'];

			if($password != $passwordDB)
				return $this->returnError("Incorrect Username Or Password");

			$temp = new ArrayObject(array(
				"UserID" => $result['data']['userID'],
				"Sid" => sha1("Project" . trim($user) . "Project")
			), 2);

			//Remove Old Session
			$query = "DELETE FROM sessions WHERE userID = '" . $temp->UserID . "'";
			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError("Error.... Could not log in");
			}


			//Create the Session
			$query = "INSERT INTO sessions (sid, userID, expire) VALUES ('" . $temp->Sid . "', '" . $temp->UserID . "', '" . $expire->format("Y-m-d H:i:s") . "')";
			try {
				$result = $this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError("Error.... Could not log in");
			}


			return $this->returnData($temp);
		}

		public function updateSession($sid, DateTime $expire) {
			$query = "SELECT expire FROM sessions WHERE sid = '" . $sid . "'";
			try {
				$result = $this->queryMysql($query, 'loadAssoc');
			} catch (Exception $ex) {
				return $this->returnError("Cannot update Session");
			}

			if(!$result['data']) {
				return $this->returnError("Not Authorized");
			}

			$willExpire = new DateTime($result['data']['expire']);
			if($willExpire->format("YmdHis") < (new DateTime())->format("YmdHis")) {
				return $this->returnError("Session Expired");
			}

			$query = "UPDATE sessions SET expire = '" . $expire->format("Y-m-d H:i:s") . "' WHERE sid = '" . $sid . "'";
			try {
				$result = $this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError("Could not Update Session.");
			}

			return $this->returnData();

		}

		public function logout($sid) {
			if(!$sid)
				return;
			$query = "DELETE FROM sessions WHERE sid = '" . $sid . "'";
			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError("Could not log out");
			}

			return $this->returnData();
		}

		public function getUserInfo($sid) {
			if(!$sid)
				return $this->returnData("Session Expired");
			$query = "SELECT u.name, u.userID, u.email, gm.groupID, u.phone 
				FROM sessions AS s 
				LEFT JOIN users AS u ON s.userID = u.userID 
				LEFT JOIN group_map AS gm ON u.userID = gm.userID
				WHERE s.sid = '" . $sid . "'";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError("Could not get user info");
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