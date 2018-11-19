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
		/**
		 * Number of rows affected by query
		 * @var int
		 */
		public $numRows = 0;

		public function __construct()
		{
			$this->setMysqlConnection();
		}
		public function __destruct()
		{
			if($this->result && $this->result != false)
			{
				$this->result = null;
			}
			$this->close();
		}

		/**
		 * Connect to the Database
		 * @throws Exception
		 * returns mysqli
		 */
		protected function setMysqlConnection() {
			$this->mysql_connect = mysqli_connect('localhost', 'c2375a19', 'c2375aU!', 'c2375a19proj', '3306');
			if(mysqli_connect_errno()) {
				throw new Exception("Cannot connect: " . mysqli_connect_error());
			}
			return $this->mysql_connect;
		}

		/**
		 * Change to a different database then what was connected with setMysqlConnection()
		 * @param String $db    Database to connect to
		 */
		public function changeDatabase(String $db) {
			$this->mysql_connect->select_db($db);
		}

		/**
		 * Set the query to execute
		 * @param String $query
		 */
		public function setQuery(String $query) {
			$this->query = $query;
		}

		/**
		 * Execute the set Query
		 * @throws Exception
		 */
		public function execute() {
			//Make sure there is a connection
			if(!$this->mysql_connect)
				$this->setMysqlConnection();
			try
			{
				//Query the databse
				$this->result  = $this->mysql_connect->query($this->query);
				//If error, then log that error and throw Exception
				if(mysqli_errno($this->mysql_connect))
				{
					$this->debug("MySQL ERROR: " . mysqli_error($this->mysql_connect) . "\r\n" . $this->query);
					throw new Exception(mysqli_error($this->mysql_connect), mysqli_errno($this->mysql_connect));
				}
				// Clear the stored query
				$this->query   = null;
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
		}

		/**
		 * Execute the Stored Query and return an Assoc Array back to the user
		 * @return array
		 * @throws Exception
		 */
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

		public function loadAssoc() {
			try{
				$this->execute();
				$return = $this->result->fetch_assoc();
				$this->query = null;
			} catch (Exception $ex) {
				throw new Exception($ex->getMessage());
			}
			return $return;
		}

		/**
		 * Free the Result Set
		 */
		public function freeResults() {
			if($this->result != false)
				$this->result->free();
		}

		/**
		 * Close the connection
		 */
		public function close() {
			$this->mysql_connect->close();
			$this->mysql_connect = null;
		}

		/**
		 * Get the Last Insert ID from the Database
		 * @return int
		 */
		public function getInsertID() {
			return $this->mysql_connect->insert_id;
		}

		/**
		 * Just a debug tool
		 * @param $txt
		 */
		private function debug($txt)
		{
			$f    = fopen(__DIR__."/../../debugSQL.txt", 'a');
			$text = '[' . date('m/d/Y H:i:s') . '] ' . $txt . "\n";
			fwrite($f, $text);
			fclose($f);
		}
	}