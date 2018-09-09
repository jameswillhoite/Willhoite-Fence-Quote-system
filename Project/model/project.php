<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 4:15 PM
	 */
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once 'master.php';
	class ProjectModelProject extends ProjectModelMaster
	{
		private $return;
		public function __construct()
		{
			$this->return = array('error' => false, 'error_msg' => '', 'data' => null);
		}

		public function getStyles() {
			$query = "SELECT * FROM styles AS s LEFT JOIN types AS t ON s.typeFence = t.id";
			try {
				$result = $this->queryMysql($query, 'fetch_assoc');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}
			$this->return['data'] = $result['data'];
			return $this->returnData();

		}

		private function returnError($txt) {
			$this->return['error'] = true;
			$this->return['error_msg'] = $txt;
			return $this->return;
		}
		private function returnData() {
			$return = array (
				'error' => $this->return['error'],
				'error_msg' => $this->return['error_msg'],
				'data' => $this->return['data']
			);
			$this->return['error'] = false;
			$this->return['error_msg'] = '';
			$this->return['data'] = null;
			return $return;
		}
	}