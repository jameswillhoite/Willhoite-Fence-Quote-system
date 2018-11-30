<?php

	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 3:53 PM
	 */
	defined("PROJECT_ROOT") || define('PROJECT_ROOT', __DIR__ . '/../');
	require_once "controller.php";

	class projectJS extends controller
	{
		private $return;
		public function __construct()
		{
			$this->return = new ArrayObject(array('error' => false, 'error_msg' => '', 'data' => null), 2);
			parent::__construct();

			//Check to make sure the user is in the allowed group
			if(!JFactory::getSecurity()->allow(array(2))) {
				echo $this->message("Not Authorized!");
				exit();
			}
		}


		public function getStyles() {

			$model = $this->getModel("Project");
			$result = $model->getStyles();

			if($result['error']) {
				$this->return->error = true;
				$this->return->error_msg = $result['error_msg'];
				$this->debug("Error: " . $result['error_msg']);
				echo json_encode($this->return);
				exit();
			}

			$this->return->data = $result['data'];
			echo json_encode($this->return);
			exit();
		}

		/**
		 * @param string $name
		 * @param string $prefix
		 *
		 * @return ProjectModelProject
		 */
		public function &getModel($name = 'Project', $prefix = "ProjectModel")
		{
			$model = parent::getModel($name, $prefix);
			return $model;
		}

		public function getCustomerList() {
			$model = $this->getModel();

			$name = $this->input->getString("name");

			if(!$name) {
				$this->return->error = true;
				$this->return->error_msg = "No Name Given";
				echo json_encode($this->return);
				exit();
			}

			$result = $model->getCustomerList($name);
			if($result->error) {
				$this->return->error = true;
				$this->return->error_msg = $result->error_msg;
				echo json_encode($this->return);
				exit();
			}
			$this->return->data = $result->data;
			echo json_encode($this->return);
			exit();
		}

		public function getAddressList() {
			$address = $this->input->getString("address");

			if(!$address) {
				$this->return->error = true;
				$this->return->error_msg = "No address Given";
				echo json_encode($this->return);
				exit();
			}

			$model = $this->getModel();
			$result = $model->getAddressList($address);
			if($result->error) {
				$this->return->error = true;
				$this->return->error_msg = $result->error_msg;
				echo json_encode($this->return);
				exit();
			}

			$this->return->data = $result->data;
			echo json_encode($this->return);
			exit();

		}

		public function saveCustomer() {
			$customerID = $this->input->getInt('customerID');
			$addressID = $this->input->getInt('addressID');
			$jobID = $this->input->getInt('jobID');
			$dateSold = $this->input->getString('dateSold');

			if(!$customerID || !$addressID) {
				$this->return->error = true;
				$this->return->error_msg = "No Customer or Address Given";
				echo json_encode($this->return);
				exit();
			}
			if(!$dateSold) {
				echo $this->message("No Date Sold Given");
				exit();
			}

			$dateSold = new DateTime($dateSold);

			$model = $this->getModel();

			if(!$jobID || $jobID == 0) {
				$result = $model->getJobNumber($customerID, $addressID, $dateSold);
				$jobID = $result->data;
			}
			else {
				$result = $model->updateHeaderCustomer($jobID, $customerID, $addressID, $dateSold);
			}

			if($result->error) {
				$this->return->error = true;
				$this->return->error_msg = $result->error_msg;
				echo json_encode($this->return);
				exit();
			}

			@$this->return->data->jobID = (int)$jobID;
			echo json_encode($this->return);
			exit();


		}

		public function addCustomer() {
			$model = $this->getModel();
			$customerName = $this->input->getString("customerName");
			$phoneType = $this->input->getString("phoneType");
			$phone = $this->input->getString("phone");
			$email = $this->input->getString("email");

			if(!$customerName) {
				echo $this->message("No Customer Name Given");
				exit();
			}
			elseif (!$phone) {
				echo $this->message("No Phone Number Given");
				exit();
			}

			$result = $model->addCustomer($customerName, $phoneType, $phone, $email);

			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}

			//Return the new Customer ID
			$this->return->data = $result->data;
			echo json_encode($this->return);
			exit();


		}

		public function addAddress() {
			$model = $this->getModel();
			$address = $this->input->getString('address');
			$city = $this->input->getString('city');
			$state = $this->input->getString('state');
			$zip = $this->input->getString('zip');

			if(!$address || !$city || !$state || !$zip) {
				echo $this->message("Please provide required fields");
				exit();
			}

			$result = $model->addAddress($address, $city, $state, $zip);
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}

			$this->return->data = $result->data;
			echo json_encode($this->return);
			exit();
		}

		public function saveMeasurements() {
			$model = $this->getModel();
			$allStyles = $this->input->getVar('styles', array());
			$jobID = $this->input->getInt('jobID');

			if(!$jobID) {
				echo $this->message("No JobID Given");
				exit();
			}
			if(count($allStyles) == 0) {
				echo $this->message("No Styles Given to Save");
				exit();
			}

			$result = $model->saveMeasurements($jobID, $allStyles);
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}

			$this->return->data = $result->data;
			echo json_encode($this->return);
			exit();


		}

		public function uploadPicture() {
			$model = $this->getModel();
			$file = $this->input->files->get('file');
			$jobID = $this->input->getInt('jobID');
			$baseUpload = PROJECT_ROOT . 'media/photoUpload';


			if(!$file) {
				echo $this->message("No file to upload");
				exit();
			}

			//Check that the folder is writable
			if(!is_writeable($baseUpload)) {
				echo $this->message("Upload Folder is not writable");
				exit();
			}

			//check that the file is in temp location
			if(!file_exists($file['tmp_name'])) {
				echo $this->message("The File was not uploaded");
				exit();
			}

			//Validate that it is an image
			$mimeTest = mime_content_type($file['tmp_name']);
			if(preg_match('/^image\/*/', $mimeTest)) {
				//is valid image
				$path =  $baseUpload . '/' . $jobID . "_" . date("Ymd_His") . "_" . $file['name'];
				move_uploaded_file($file['tmp_name'], $path);
				if(!file_exists($path)) {
					echo $this->message("The Photo did not upload properly");
					exit();
				}
			}else {
				unlink($file['tmp_name']);
				echo $this->message("The Selected file was not an Image. Please upload a JPEG, PNG, TIFF, GIF.");
				exit();
			}


			//Save to the database
			$result = $model->uploadPhoto($jobID, $path);
			if($result->error) {
				if(file_exists($path))
					unlink($path);
				echo $this->message($result->error_msg);
				exit();
			}

			$this->return->data = $result->data;

			echo json_encode($this->return);
			exit();
		}

		public function savePictureNotes() {
			$model = $this->getModel();
			$noteID = $this->input->getInt('noteID');
			$noteText = $this->input->getString('noteText');

			if(!$noteID || !$noteText) {
				echo $this->message("No ID or Note Given. ID: " . $noteID . " Text: " . $noteText);
				exit();
			}

			$result = $model->savePictureNotes($noteID, $noteText);
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}

			echo json_encode($this->return);
			exit();
		}

		public function removePicture() {
			$model = $this->getModel();
			$noteID = $this->input->getInt('noteID');

			if(!$noteID) {
				echo $this->message("No ID Given");
				exit();
			}

			$result = $model->removePicture($noteID);
			if($result->error) {
				echo $this->message($result->error_msg);
				exit();
			}

			echo json_encode($this->return);
			exit();
		}



		private function message($txt) {
			$this->return->error = true;
			$this->return->error_msg = $txt;
			return json_encode($this->return);
		}

	}