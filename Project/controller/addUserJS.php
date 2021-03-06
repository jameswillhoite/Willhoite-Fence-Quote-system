<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/23/18
	 * Time: 2:17 PM
	 */

	defined("PROJECT_ROOT") || define('PROJECT_ROOT', __DIR__ . '/../');
	require_once "controller.php";
	class addUserJS extends controller
	{
		private $return;
		public function __construct()
		{
			$this->return = new ArrayObject(array('error' => false, 'error_msg' => '', 'data' => null), 2);
			parent::__construct();

			//Check to see if the user is in the allowed group to access this resource
			if(!JFactory::getSecurity()->allow(array(4))) {
				echo $this->message("Not Authorized!");
				exit();
			}
		}

		/**
		 * @param string $name
		 * @param string $prefix
		 *
		 * @return ProjectModelAddUser
		 */
		public function &getModel($name = "AddUser", $prefix = "ProjectModel")
		{
			$temp = parent::getModel($name, $prefix);
			return $temp;
		}

		public function addUser() {
			$model = $this->getModel();
			$input = $this->input;

			$name = $input->getString('name');
			$email = $input->getString('email');
			$password = $input->getString('password');

			$encryptedPassword = sha1($password);

			$result= $model->checkForDuplicateEmail($email);
			if(count($result->data) > 0) {
				echo $this->message("This email address is already in use");
				exit();
			}


			$result = $model->addUser($name, $email, $encryptedPassword);
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}

			echo json_encode($this->return);
			exit();

		}

		public function updateGroup() {
			$model = $this->getModel();
			$input = $this->input;

			$uid = $input->getInt('uid');
			$gid = $input->getInt('gid');
			$addRemove = $input->getString('addRemove');

			if(!$uid && !$gid && !$addRemove) {
				echo $this->message("Need all fields to update group for user.");
				exit();
			}

			$result = $model->updateGroup($uid, $gid, $addRemove);
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}

			$this->return->data = $result->data;

			echo json_encode($this->return);
			exit();
		}

		public function updateUser() {
			$model = $this->getModel();
			$input = $this->input;

			$uid = $input->getInt('uid');
			$name = $input->getVar('name');
			$email = $input->getVar('email');
			$password = $input->getVar('password');
			$password2 = $input->getVar('password2');
			$encryptedPassword = null;

			if($password) {
				if($password != $password2) {
					echo $this->message("Passwords do not match");
					exit();
				}
				$encryptedPassword = sha1($password);
			}

			$result = $model->checkForDuplicateEmail($email, $uid);
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}
			if($result->data && count($result->data) > 0) {
				echo $this->message("Email Address is already being used");
				exit();
			}

			//update the user
			$result = $model->updateUser($uid, $name, $email, $encryptedPassword);
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}

			echo json_encode($this->return);
			exit();

		}

		private function message($txt) {
			$this->return->error = true;
			$this->return->error_msg = $txt;
			return json_encode($this->return);
		}

	}