<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/23/18
	 * Time: 2:17 PM
	 */

	defined("PROJECT_ROOT") || define('PROJECT_ROOT', __DIR__ . '/../');
	require_once "controller.php";
	class viewJS extends controller
	{
		private $return;
		public function __construct()
		{
			$this->return = new ArrayObject(array('error' => false, 'error_msg' => '', 'data' => null), 2);
			parent::__construct();

			//Check to see if the user is in the allowed group to access this resource
			if(!JFactory::getUser()->id) {
				echo $this->message("Not Authorized!");
				exit();
			}
		}

		/**
		 * @param string $name
		 * @param string $prefix
		 *
		 * @return ProjectModelView
		 */
		public function &getModel($name = "View", $prefix = "ProjectModel")
		{
			$temp = parent::getModel($name, $prefix);
			return $temp;
		}

		public function doSearch() {
			$model = $this->getModel();
			$input = $this->input;

			$searchBy = $input->getString('searchBy');
			$searchFor = $input->getString('searchFor');
			$zip = $input->getString('zip');

			if(!$searchBy) {
				echo $this->message("Please select a value to search by");
				exit();
			}
			if(!$searchFor) {
				echo $this->message("Please enter something to lookup");
				exit();
			}

			$result = $model->doSearch($searchBy, $searchFor, $zip);
			if($result->error) {
				echo $this->message("Error: " . $result->error_msg);
				exit();
			}

			$this->return->data = $result->data;
			echo json_encode($this->return);
			exit();
		}

		public function getJobInfo() {
			require_once PROJECT_ROOT . 'model/project.php';
			$model = new ProjectModelProject();
			$input = $this->input;
			$security = JFactory::getSecurity(false);

			$jobID = $input->getInt('jobID');

			if(!$jobID) {
				echo $this->message("No Job Number Given");
				exit();
			}

			$result = $model->getAllJobInfo($jobID);
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}

			//Check for permission to see price
			$perms = ($security->allow(2)) ? true : false;

			//Go thorough the result and black out things that are not supposed to be seen by those who don't have perms
			$c = $result->data;
			$c->DateSold = $c->DateSold->format("F d, Y");
			$S = $c->Styles;
			foreach ($S as $s)
			{
				$s->CornerPostPrice = ($perms) ? "$" . number_format($s->CornerPostPrice, 2) : "0.00";
				$s->EndPostPrice    = ($perms) ? "$" . number_format($s->EndPostPrice, 2) : "0.00";
				$s->PricePerFoot    = ($perms) ? "$" . number_format($s->PricePerFoot, 2) : "0.00";
				$s->Gate4FootPrice  = ($perms) ? "$" . number_format($s->Gate4FootPrice, 2) : "0.00";
				$s->Gate5FootPrice  = ($perms) ? "$" . number_format($s->Gate5FootPrice, 2) : "0.00";
				$s->Gate8FootPrice  = ($perms) ? "$" . number_format($s->Gate8FootPrice, 2) : "0.00";
				$s->Gate10FootPrice = ($perms) ? "$" . number_format($s->Gate10FootPrice, 2) : "0.00";
				$s->GatePostPrice   = ($perms) ? "$" . number_format($s->GatePostPrice, 2) : "0.00";
				$s->HaulAwayDirtPrice = ($perms) ? "$" . number_format($s->HaulAwayDirtPrice, 2) : "0.00";
				$s->PermitPrice     = ($perms) ? "$" . number_format($s->PermitPrice, 2) : "0.00";
				$s->PostTopPrice    = ($perms) ? "$" . number_format($s->PostTopPrice, 2) : "0.00";
				$s->RemovableSectionPrice = ($perms) ? "$" . number_format($s->RemovableSectionPrice, 2) : "0.00";
				$s->RemoveFencePrice = ($perms) ? "$" . number_format($s->RemoveFencePrice, 2) : "0.00";
				$s->TempFencePrice  = ($perms) ? "$" . number_format($s->TempFencePrice, 2) : "0.00";
				$s->UpgradedHingePrice = ($perms) ? "$" . number_format($s->UpgradedHingePrice, 2) : "0.00";
				$s->UpgradedLatchPrice  = ($perms) ? "$" . number_format($s->UpgradedLatchPrice, 2) : "0.00";
			}
			$this->return->data = $c;
			echo json_encode($this->return);
			exit();
		}


		private function message($txt) {
			$this->return->error = true;
			$this->return->error_msg = $txt;
			return json_encode($this->return);
		}

	}