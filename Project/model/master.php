<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 3:56 PM
	 */

	class ProjectModelMaster
	{
		protected $mysql_connect = null;
		protected function setMysqlConnection() {
			$this->mysql_connect = mysqli_connect('35.132.252.110', 'pstcc_project', '1SsgpBIsvmU0SZeY', 'pstcc_project', '3306');
			if(mysqli_connect_errno()) {
				throw new Exception("Cannot connect: " . mysqli_connect_error());
			}
		}

		protected function queryMysql($query, $return = null) {
			$returnArray = array("data" => null, "numRows" => 0);
			try
			{
				if (!$this->mysql_connect)
					$this->setMysqlConnection();

				$result = $this->mysql_connect->query($query);
				if(!$result)
					throw new Exception("MySQL Error: " . mysqli_connect_error());
				$returnArray['numRows'] = $result->num_rows;
				if($return)
					$returnArray['data'] = $result->$return();
				$result->free_result();
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