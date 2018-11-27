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
			//Check to see if the Session has been started or not. If not, then start one
			if(session_status() < 2)
				session_start();

			//If there is a Session, get all the keys and values and place into the array to be used by getVar
			if(isset($_SESSION)) {
				$this->sessionVars = new ArrayObject($_SESSION, 2);
			}
			else {
				$this->sessionVars = null;
			}
		}


		/**
		 * Get a Session Variable from the Session or return Null
		 * @param String     $key
		 * @param mixed      $default
		 *
		 * @return mixed
		 */
		public function getVar($key, $default = null) {
			if($this->sessionVars && isset($this->sessionVars->{$key}))
				return $this->sessionVars->{$key};
			else
				return $default;
		}

		/**
		 * Set a session Variable
		 * @param String    $key
		 * @param String    $value
		 *
		 * @return null     If there is no session then return
		 */
		public function setVar($key, $value = null) {
			if(!$this->sessionVars)
				return null;
			//The @ sign just quites an error that happens when I assign a variable dynamically like I am doing here
			@$this->sessionVars->{$key} = $value;
			$_SESSION[$key] = $value;
		}

		/**
		 * Remove a Variable from the Session
		 * @param String $key
		 *
		 * @return null     If there is no Session then return
		 */
		public function unsetVar($key) {
			if(!$this->sessionVars)
				return null;
			unset($this->sessionVars->{$key});
			unset($_SESSION[$key]);
		}

		/**
		 * Function to check and see if a Session is active, if not, and there is no SID then redirect to the login page
		 * NOTE: The variable 'sid' was set by me in the login script, not from the session
		 */
		public function check() {
			require_once PROJECT_ROOT . '/model/main.php';
			require_once 'JFactory.php';
			//IF there is not sid set, then the user has not logged in, direct them to the login
			$sid = $this->sessionVars->{'sid'};
			if(!$sid) {
				header('Location: ' . JFactory::getConfig()::BASE_URL . '/login.php');
				return;
			}

			//The user is logged in, check to make sure they have not exceeded the max inactivity, if so, then direct them to the login page and display a message
			$model = new ProjectModelMain();
			$result = $model->updateSession($sid, JFactory::getConfig()->getExpire());
			if($result->error) {
				$this->destroy();
				header('Location: ' . JFactory::getConfig()::BASE_URL . '/login.php?msg=' . $result->error_msg, true);
				return;
			}
		}

		/**
		 * This will destroy the Session, this is done when the User 'Logs out' of the application.
		 * NOTE: This also logs the User out of my Database
		 */
		public function destroy() {
			require_once PROJECT_ROOT . '/model/main.php';
			$model = new ProjectModelMain();
			$sid = $this->getVar('sid');
			$model->logout($sid);
			session_destroy();
			$this->sessionVars = null;
		}

	}