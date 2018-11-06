<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 3:56 PM
	 */

	defined('PROJECT_ROOT') || define('PROJECT_ROOT', __DIR__ . '/..');
	//connect to database
	require_once PROJECT_ROOT . '/libraries/MyProject/JFactory.php';

	class ProjectModelMaster extends JFactory
	{


		protected function queryMysql($query, $return = null) {
			$returnArray = array("data" => null, "numRows" => 0);
			try
			{
				$mysql = JFactory::getDB();
				//set the query
				$mysql->setQuery($query);

				//check to see if we are to run a query or just execute
				if($return)
					$returnArray['data'] = $mysql->$return();
				else
					$mysql->execute();

				$returnArray['numRows'] = $mysql->numRows;


			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
			return $returnArray;
		}

		protected function debug($txt)
		{
			$f    = fopen("../debugModel.txt", 'a');
			$text = '[' . date('m/d/Y H:i:s') . '] ' . $txt . "\n";
			fwrite($f, $text);
			fclose($f);
		}
	}
