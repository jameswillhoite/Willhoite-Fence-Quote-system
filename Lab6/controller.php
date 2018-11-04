<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/4/18
	 * Time: 8:13 AM
	 */

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	class SaveGameController
	{
		/**
		 * @var $return ArrayObject
		 */
		private $return;
		/**
		 * @var ArrayObject
		 */
		private $input;
		public function __construct()
		{
			$this->return = (object)array("error" => false, "error_msg" => '', "data" => null);


			if(isset($_POST))
			{
				$array = array();
				foreach ($_POST as $key => $get)
				{
					$array[$key] = $get;
				}
				$this->input = new ArrayObject($array, 2);
			}

			if(isset($_GET['task']))
			{
				$task = $_GET['task'];
				unset($_GET['task']);

				$this->$task();
			}
		}

		private function getVar($key) {
			if(isset($this->input->{$key}))
				return $this->input->{$key};
			else
				return null;
		}

		public function saveGame() {
			$name = $this->getVar("name");
			$bubblesPopped = $this->getVar("bubblesPopped");
			$bubblesLost = $this->getVar("bubblesLost");
			$score = $this->getVar("score");

			if(!$name) {
				$this->return->error = true;
				$this->return->error_msg = "No Name Given";
				echo json_encode($this->return);
				exit();
			}
			if($bubblesLost == null || $bubblesPopped == null ||  $score == null) {
				$this->return->error = true;
				$this->return->error_msg = "No Stats Given";
				echo json_encode($this->return);
				exit();
			}

			require_once "game.php";
			$model = new SaveGame();

			if(!class_exists("SaveGame")) {
				$this->return->error = true;
				$this->return->error_msg = "Cannot load class SaveGame";
				echo json_encode($this->return);
				exit();
			}

			$result = $model->addStat($name, $bubblesPopped, $bubblesLost, $score);

			if($result->error) {
				$this->return->error = true;
				$this->return->error_msg = $result->error_msg;
				echo json_encode($this->return);
				exit();
			}

			echo json_encode($this->return);
			exit();
		}

	}

	$temp = new SaveGameController();
	$temp->__construct();