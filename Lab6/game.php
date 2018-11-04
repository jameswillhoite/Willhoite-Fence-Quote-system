<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/4/18
	 * Time: 6:38 AM
	 */
	require_once '../Project/libraries/MyProject/database.php';
	class SaveGame extends database
	{
		/**
		 * @var $return ArrayObject
		 */
		private $return;

		public function __construct()
		{
			$this->return = new ArrayObject(array("error" => false, "error_msg" => '', "data" => null), 2);
			$this->setMysqlConnection();
			$this->changeDatabase("game");
			parent::__construct();

		}


		public function getStats() {

			$query = "SELECT * FROM game ORDER BY score DESC LIMIT 5";
			try {
				$this->setQuery($query);
				$result = $this->loadAssocList();
			} catch (Exception $ex) {
				$this->return->error = true;
				$this->return->error_msg = "Could not get Stats: " . $ex->getMessage();
				$this->close();
				return $this->return;
			}

			$this->return->data = $result;
			return $this->return;
		}

		public function addStat($name, $bubblesPopped, $bubblesLost, $score) {

			$query = "INSERT INTO game (name, bubblesPopped, bubblesLost, score) VALUES ('" . addslashes($name) . "', $bubblesPopped, $bubblesLost, $score)";
			try {
				$this->setQuery($query);
				$this->execute();
			} catch (Exception $ex) {
				$this->return->error = true;
				$this->return->error_msg = "Could not add new Score. " . $ex->getMessage();
				//$this->close();
				return $this->return;
			}

			//$this->close();
			return $this->return;
		}

	}