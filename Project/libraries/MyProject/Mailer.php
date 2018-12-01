<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/30/18
	 * Time: 9:10 PM
	 */

	use PHPMailer\PHPMailer\PHPMailer;

	defined("PROJECT_ROOT") || define('PROJECT_ROOT', __DIR__ . '/../..');
	require_once 'JFactory.php';
	require_once PROJECT_ROOT . '/libraries/PHPMailer/src/PHPMailer.php';
	require_once PROJECT_ROOT . '/libraries/PHPMailer/src/SMTP.php';
	require_once PROJECT_ROOT . '/libraries/PHPMailer/src/Exception.php';

	class ProjectMailer extends PHPMailer
	{
		public function __construct($exceptions = null)
		{
			parent::__construct($exceptions);
			$this->setFrom("jameswillhoite@gmail.com", "James Willhoite");
			$this->Username = "jameswillhoite@gmail.com";
			$this->Password = "bffffzncsuhkujmc";
			$this->Host = "smtp.gmail.com";
			$this->SMTPSecure = "tls";
			$this->SMTPAuth = true;
			$this->isSMTP();

		}
	}