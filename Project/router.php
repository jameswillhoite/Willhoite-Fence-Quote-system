<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 4:22 PM
	 */

	class router
	{
		public function __construct()
		{

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
			require_once $filename;
			$class = new $controller();
			if(!class_exists($controller)) {
				die("Error: Class 404");
			}

			$class->$function();
			exit();
		}
	}
	new router();

