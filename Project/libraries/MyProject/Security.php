<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/24/18
	 * Time: 10:35 AM
	 */

	require_once 'JFactory.php';

	class ProjectSecurity
	{

		public function __construct($redirect = true)
		{
			if($redirect == true)
			{
				//check and make sure user is logged in
				JFactory::getSession()->check();
			}
		}

		/**
		 * Will Check to make sure the User is allowed to access the page
		 * @param array $allowed    The allowed groups
		 * @param bool  $redirect   Will redirect the User to the Index Page
		 *
		 * @return bool
		 */
		public function allow($allowed = array(), $redirect = false) {
			$user = JFactory::getUser();
			if(!is_array($allowed))
				$allowed = array($allowed);

			if(!in_array(1, $allowed))
				$allowed[] = 1; //always put group 1 (Super User) into the allowed group

			if(!array_intersect($allowed, $user->groups) && $redirect === true)
				$this->redirect("Not Authorized to View that page.");
			elseif(!array_intersect($user->groups, $allowed))
				return false;

			return true;
		}

		public function redirect($msg = null) {
			JFactory::getSession()->setVar('msg', $msg);
			header('Location: ' . JFactory::getConfig()::BASE_URL . '/index.php');
		}
	}