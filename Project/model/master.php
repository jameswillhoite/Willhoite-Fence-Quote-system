<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 3:56 PM
	 */

	class ProjectModelMaster
	{


		protected function queryMysql($query, $return = null) {
			$returnArray = array("data" => null, "numRows" => 0);
			try
			{
				//connect to database
				$mysql = new database();
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

	class database {
		protected $mysql_connect = null;
		private $query;
		private $result;
		public $numRows = 0;
		public function __construct()
		{
			$this->setMysqlConnection();
		}
		public function __destruct()
		{
			$this->freeResults();
			$this->close();
		}

		protected function setMysqlConnection() {
			$this->mysql_connect = mysqli_connect('35.132.252.110', 'pstcc_project', '1SsgpBIsvmU0SZeY', 'pstcc_project', '3306');
			if(mysqli_connect_errno()) {
				throw new Exception("Cannot connect: " . mysqli_connect_error());
			}
		}

		public function setQuery($query) {
			$this->query = $query;
		}


		public function execute() {
			if(!$this->mysql_connect)
				$this->setMysqlConnection();
			try
			{
				$this->result  = $this->mysql_connect->query($this->query);
				if(mysqli_errno($this->mysql_connect))
					$this->debug("MySQL ERROR: " . mysqli_error($this->mysql_connect));
				$this->numRows = $this->result->num_rows;
				$this->query   = null;
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}

		public function loadAssocList() {
			try
			{
				$return = array();
				$this->execute();
				while ($row = $this->result->fetch_assoc())
				{
					$temp = array();
					foreach ($row as $key => $value)
					{
						$temp[$key] = $value;
					}
					$return[] = $temp;
				}
				$this->query = null;
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
			return $return;
		}

		public function freeResults() {
			$this->result->free();
		}
		public function close() {
			$this->mysql_connect->close();
			$this->mysql_connect = null;
		}

		private function debug($txt)
		{
			$f    = fopen(__DIR__."/../debugSQL.txt", 'a');
			$text = '[' . date('m/d/Y H:i:s') . '] ' . $txt . "\n";
			fwrite($f, $text);
			fclose($f);
		}
	}