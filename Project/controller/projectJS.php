<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 3:53 PM
	 */
	require_once "controller.php";

	class projectJS extends controller
	{
		private $return;
		public function __construct()
		{
			$this->return = array('error' => false, 'error_msg' => '', 'data' => null);
		}

		public function getStyles() {

			$model = $this->getModel("Project");



			$result = $model->getStyles();

			if($result['error']) {
				$this->return['error'] = true;
				$this->return['error_msg'] = $result['error_msg'];
				$this->debug("Error: " . $result['error_msg']);
				echo json_encode($this->return);
				exit();
			}

			$this->return['data'] = $result['data'];
			echo json_encode($this->return);
			exit();
		}

	}