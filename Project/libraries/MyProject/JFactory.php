<?php
	/**
	 * Class JFactory
	 * Class that will get major classes
	 */
	defined('PROJECT_ROOT') || define('PROJECT_ROOT', __DIR__ . '/../..');
	ini_set("log_errors", 1);
	ini_set("error_log", PROJECT_ROOT ."tmp/php-error.log");
	date_default_timezone_set("America/Kentucky/Louisville");
	include_once PROJECT_ROOT . '/defines.php';

	/**
	 * Auto Load Classes
	 * @param $class
	 *
	 * @return bool|mixed
	 */
	function controller($class)
	{
		$include_file = PROJECT_ROOT . "controller/" . $class . ".php";

		return (file_exists($include_file)) ? require_once $include_file : false;
	}
	spl_autoload_register("controller");


	class JFactory
	{
		/**
		 * Return the Input Instance
		 * @return Input
		 */
		public static function getInput() {
			require_once 'input.php';
			return new Input();
		}

		/**
		 * Get the Database Instance
		 * @return Database
		 */
		public static function getDB() {
			require_once 'database.php';
			return new Database();
		}

		/**
		 * Get the PHP Mailer Instance
		 * @return \PHPMailer\PHPMailer\PHPMailer
		 */
		public static function getMailer() {
			require_once '../PHPMailer/src/PHPMailer.php';
			require_once '../PHPMailer/src/SMTP.php';
			return new PHPMailer\PHPMailer\PHPMailer();
		}

		/**
		 * Get the FPDF Class
		 * @return FPDF
		 */
		public static function getFPDF() {
			require_once '../fpdf/fpdf.php';
			return new FPDF('P', 'in', array(8.5, 11));
		}

		/**
		 * Start a session and return instance
		 * @return ProjectSession
		 */
		public static function getSession() {
			require_once 'Session.php';
			return new ProjectSession();
		}

		/**
		 * Main Config Settings
		 * @return ProjectConfig
		 */
		public static function getConfig() {
			require_once 'Config.php';
			return new ProjectConfig();
		}

		/**
		 * All User Info
		 * @return ProjectUser
		 */
		public static function getUser() {
			require_once 'User.php';
			return new ProjectUser();
		}

		/**
		 * Takes care of the Security for the site
		 * @return ProjectSecurity
		 */
		public static function getSecurity() {
			require_once 'Security.php';
			return new ProjectSecurity();
		}


	}