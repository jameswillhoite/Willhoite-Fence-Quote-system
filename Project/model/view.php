<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/23/18
	 * Time: 2:18 PM
	 */

	require_once 'master.php';
	class ProjectModelView extends ProjectModelMaster
	{

		private $return;
		public function __construct()
		{
			$this->return = new ArrayObject(array('error' => false, 'error_msg' => '', 'data' => null), 2);
		}

		public function doSearch($searchBy, $searchFor, $zip = null) {
			$sBy = null;
			switch($searchBy) {
				case "jobID":
					$sBy = "qh.id";
					break;
				case "customerName":
					$sBy = "c.CustomerName";
					$searchFor = "%" . preg_replace('/\s/', '%', $searchFor) . '%';
					break;
				case "styleName":
					$sBy = "s.styleFence";
					$searchFor = "%" . preg_replace('/\s/', '%', $searchFor) . '%';
					break;
				case "phone":
					$sBy = "c.CustomerPhone";
					$searchFor = "%" . preg_replace('/[\s\-]/', '%', $searchFor) . '%';
					break;
			}

			$query = "SELECT qh.id AS JobID, c.CustomerID, c.CustomerName, ca.Address AS CustomerAddress, ca.City AS CustomerCity, ca.Zip AS CustomerZip, 
				s.styleFence AS Style, c.CustomerPhone, qd.id AS DetailID
				FROM quoteHeader AS qh 
				LEFT JOIN quoteDetail AS qd ON qh.id = qd.jobID
				LEFT JOIN customer AS c ON qh.customerID = c.CustomerID 
				LEFT JOIN customerAddress AS ca ON qh.addressID = ca.AddressID 
				LEFT JOIN styles AS s ON qd.styleID = s.id 
				WHERE " . $sBy . " LIKE '" . $searchFor . "' ORDER BY qh.id DESC";

			if($zip) {
				$query .= " AND ca.Zip LIKE '%" . $zip . "%'";
			}

			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				error_log("Could not complete query for search. " . $ex->getMessage());
				return $this->returnError("Could not complete search");
			}

			$newReturn = array();
			$jobArray = array();
			foreach ($result['data'] as $r) {
				$jobID = $r['JobID'];
				if(!isset($jobArray[$jobID])) {
					$temp = new ArrayObject(array(
						"JobID" => $jobID,
						"CustomerID" => $r['CustomerID'],
						"CustomerName" => $r['CustomerName'],
						"CustomerAddress" => $r['CustomerAddress'],
						"CustomerCity" => $r['CustomerCity'],
						"CustomerZip" => $r['CustomerZip'],
						"Styles" => new ArrayObject(array(), 2)
					), 2);

					$temp->Styles->append(new ArrayObject(array(
						"Style" => $r['Style']
					),2));

					$newReturn[] = $temp;

					$jobArray[$jobID] = count($newReturn) - 1;
				}
				else {
					$index = $jobArray[$jobID];
					/**
					 * @var ArrayObject $Style
					 */
					$Style = $newReturn[$index]->Styles;
					$Style->append(new ArrayObject(array(
						"Style" => $r['Style']
					), 2));
				}
			}


			return $this->returnData($newReturn);
		}

		private function returnError($txt) {
			$this->return->error = true;
			$this->return->error_msg = $txt;
			return $this->return;
		}
		private function returnData($data = null) {
			if($data) {
				$this->return->data = $data;
			}
			$return = new ArrayObject(array(
				"error" => $this->return->error,
				"error_msg" => $this->return->error_msg,
				"data" => $this->return->data
			), 2);

			$this->return->error = false;
			$this->return->error_msg = '';
			$this->return->data = null;
			return $return;
		}
	}