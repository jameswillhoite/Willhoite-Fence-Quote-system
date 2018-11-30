<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 3:49 PM
	 */

	defined('PROJECT_ROOT') || define('PROJECT_ROOT', __DIR__ . '/');
	ini_set("log_errors", 1);
	ini_set("error_log", PROJECT_ROOT ."tmp/php-error.log");
	require_once PROJECT_ROOT . 'libraries/MyProject/JFactory.php';


	class controller extends JFactory
	{
		/**
		 * @var $input Input
		 */
		protected $input;
		public function __construct()
		{
			$this->input = JFactory::getInput();

		}

		public function getModel($name, $prefix = "ProjectModel") {
			$filename = __DIR__ . "/../model/".lcfirst($name).".php";
			if(is_readable($filename)) {
				require_once($filename);
				$class = $prefix . $name;
				if(!class_exists($prefix.$name))
					die("Class doesn't exist");
				$t = new $class();
				return $t;
			}
			throw new Exception("Could not load Model. Filename " .$filename);
		}

		protected function debug($txt) {
			$f = fopen("debug.txt", 'a');
			$text = '['.date('m/d/Y H:i:s').'] ' .$txt ."\n";
			fwrite($f, $text);
			fclose($f);
		}
	}