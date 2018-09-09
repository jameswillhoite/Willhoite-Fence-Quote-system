<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 3:49 PM
	 */

	class controller
	{
		public function __construct()
		{


		}

		public function getModel($name, $prefix = "ProjectModel") {
			$filename = __DIR__ . strtolower("/../model/".$name.".php");
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