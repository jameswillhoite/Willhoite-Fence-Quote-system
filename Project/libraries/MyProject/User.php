<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/24/18
	 * Time: 7:14 AM
	 */
	defined('PROJECT_ROOT') || define('PROJECT_ROOT', __DIR__ . '/../..');
	include_once PROJECT_ROOT . '/defines.php';

	class ProjectUser
	{
		public $name = null, $id = null, $email = null, $groups = array();

		public function __construct()
		{
			$this->getUserInfo();
		}

		private function getUserInfo() {
			require_once PROJECT_MODEL . '/main.php';
			require_once PROJECT_ROOT . '/libraries/MyProject/JFactory.php';
			$sid = JFactory::getSession()->getVar('sid');
			if(!$sid)
				return;
			$model = new ProjectModelMain();
			$result = $model->getUserInfo($sid);
			if($result->error) {
				return;
			}
			$user = $result['data'];
			$this->name = $user[0]['name'];
			$this->id = $user[0]['userID'];
			$this->email = $user[0]['email'];
			foreach ($user as $value) {
				$this->groups[] = (int)$value['groupID'];
			}
		}
	}