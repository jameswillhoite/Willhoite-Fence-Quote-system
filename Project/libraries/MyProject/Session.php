<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/23/18
	 * Time: 7:52 PM
	 */

	defined('PROJECT_ROOT') || define('PROJECT_ROOT', __DIR__ . '/../..');
	include_once PROJECT_ROOT . '/defines.php';

	class ProjectSession
	{
		private $sessionVars;
		public function __construct()
		{
			if(session_status() < 2)
				session_start();
			if(isset($_SESSION)) {
				$this->sessionVars = new ArrayObject($_SESSION, 2);
			}
			else {
				$this->sessionVars = null;
			}
		}


		public function getVar($key, $default = null) {
			if($this->sessionVars && isset($this->sessionVars->{$key}))
				return $this->sessionVars->{$key};
			else
				return $default;
		}

		public function setVar($key, $value = null) {
			if(!$this->sessionVars)
				return null;
			@$this->sessionVars->{$key} = $value;
			$_SESSION[$key] = $value;
		}

		public function unsetVar($key) {
			if(!$this->sessionVars)
				return null;
			unset($this->sessionVars->{$key});
			unset($_SESSION[$key]);
		}

		public function check() {
			require_once PROJECT_ROOT . '/model/main.php';
			require_once 'JFactory.php';
			$sid = $this->sessionVars->{'sid'};
			if(!$sid) {
				header('Location: ' . JFactory::getConfig()::BASE_URL . '/login.php');
				return;
			}

			$model = new ProjectModelMain();
			$result = $model->updateSession($sid, JFactory::getConfig()->getExpire());
			if($result->error) {
				$this->destroy();
				header('Location: ' . JFactory::getConfig()::BASE_URL . '/login.php?msg=' . $result->error_msg, true);
				return;
			}
		}

		public function destroy() {
			require_once PROJECT_ROOT . '/model/main.php';
			$model = new ProjectModelMain();
			$sid = $this->getVar('sid');
			$model->logout($sid);
			session_destroy();
			$this->sessionVars = null;
		}

	}