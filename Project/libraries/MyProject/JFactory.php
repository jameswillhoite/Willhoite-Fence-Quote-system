<?php
	/**
	 * Class JFactory
	 * Class that will get major classes
	 */
	defined('PROJECT_ROOT') || define('PROJECT_ROOT', __DIR__ . '/../..');
	include_once PROJECT_ROOT . '/defines.php';

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

	}