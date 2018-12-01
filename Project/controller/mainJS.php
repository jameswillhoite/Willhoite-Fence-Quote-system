<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/23/18
	 * Time: 7:44 PM
	 */

	defined("PROJECT_ROOT") || define('PROJECT_ROOT', __DIR__ . '/../');
	require_once "controller.php";

	class mainJS extends controller
	{

		private $return;
		public function __construct()
		{
			$this->return = new ArrayObject(array('error' => false, 'error_msg' => '', 'data' => null), 2);
			parent::__construct();
		}

		/**
		 * @param string $name
		 * @param string $prefix
		 *
		 * @return ProjectModelMain
		 */
		public function &getModel($name = "Main", $prefix = "ProjectModel")
		{
			$temp = parent::getModel($name, $prefix);
			return $temp;
		}

		public function login() {
			$model = $this->getModel();
			$input = $this->input;
			$config = JFactory::getConfig();

			$user = $input->getString('email');
			$pass = $input->getString('password');

			$pass = sha1($pass);

			$result = $model->login($user, $pass, $config->getExpire());
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}
			
			$session = JFactory::getSession();
			$session->setVar("sid", $result->data->Sid);
			$session->setVar('user', $user);
			$session->setVar('userID', $result->data->UserID);

			echo json_encode($this->return);
			exit();
		}

		public function logout() {
			$model= $this->getModel();
			$session = JFactory::getSession();
			$model->logout($session->getVar('sid'));

			$session->destroy();

			JFactory::getSecurity();
		}

		private function message($txt) {
			$this->return->error = true;
			$this->return->error_msg = $txt;
			return json_encode($this->return);
		}

	}