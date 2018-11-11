<?php
	/**
	 * Class Database
	 * The Database Class
	 */

	class Database {
		/**
		 * @var $mysql_connect mysqli
		 */
		protected $mysql_connect = null;
		/**
		 * @var $query String
		 */
		private $query;
		/**
		 * @var $result mysqli_result
		 */
		private $result;
		public $numRows = 0;
		public function __construct()
		{
			$this->setMysqlConnection();
		}
		public function __destruct()
		{
			if($this->result && $this->result != false)
			{
				//$this->freeResults();
				$this->result = null;
			}
			$this->close();
		}

		/**
		 * @throws Exception
		 * returns mysqli
		 */
		protected function setMysqlConnection() {
			//$this->mysql_connect = mysqli_connect('35.132.252.110', 'pstcc_project', '1SsgpBIsvmU0SZeY', 'pstcc_project', '3306');
			$this->mysql_connect = mysqli_connect('localhost', 'c2375a19', 'c2375aU!', 'c2375a19proj', '3306');
			if(mysqli_connect_errno()) {
				throw new Exception("Cannot connect: " . mysqli_connect_error());
			}
			return $this->mysql_connect;
		}

		public function changeDatabase($db) {
			$this->mysql_connect->select_db($db);
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
				{
					$this->debug("MySQL ERROR: " . mysqli_error($this->mysql_connect) . "\r\n" . $this->query);
					throw new Exception(mysqli_error($this->mysql_connect), mysqli_errno($this->mysql_connect));
				}
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
			if($this->result != false)
				$this->result->free();
		}
		public function close() {
			$this->mysql_connect->close();
			$this->mysql_connect = null;
		}

		public function getInsertID() {
			return $this->mysql_connect->insert_id;
		}

		private function debug($txt)
		{
			$f    = fopen(__DIR__."/../../debugSQL.txt", 'a');
			$text = '[' . date('m/d/Y H:i:s') . '] ' . $txt . "\n";
			fwrite($f, $text);
			fclose($f);
		}
	}