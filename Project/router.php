<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 4:22 PM
	 */

	defined('PROJECT_ROOT') || define('PROJECT_ROOT', __DIR__ . '/');
	ini_set("log_errors", 1);
	ini_set("error_log", PROJECT_ROOT ."tmp/php-error.log");
	require_once PROJECT_ROOT . "libraries/MyProject/JFactory.php";


	class router extends JFactory
	{
		public function __construct()
		{
			$this->getTask();

		}

		private function getTask() {
			if(!isset($_GET['task']))
				return;
			$parse = $_GET['task'];
			$temp = explode('.', $parse);
			$controller = $temp[0];
			$function = $temp[1];
			if(!$controller || !$function)
			{
				echo json_encode('Error: Cannot process');
				exit();
			}
			$filename = "controller/".$controller.'.php';
			//require_once $filename;
			$class = new $controller();
			if(!class_exists($controller)) {
				die("Error: Class 404");
			}

			$class->$function();
		}
	}
	new router();

