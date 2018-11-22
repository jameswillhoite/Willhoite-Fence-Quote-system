<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 9/9/18
	 * Time: 4:15 PM
	 */

	require_once 'master.php';

	class ProjectModelProject extends ProjectModelMaster
	{
		private $return;
		public function __construct()
		{
			$this->return = new ArrayObject(array('error' => false, 'error_msg' => '', 'data' => null), 2);
		}

		public function getStyles() {
			$query = "SELECT * FROM styles AS s LEFT JOIN types AS t ON s.typeFence = t.id";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}
			$this->return->data = $result['data'];
			return $this->returnData();

		}
		public function getHeights() {
			$query = "SELECT * FROM fenceHeights ORDER BY height ASC";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}
			$this->return->data = $result['data'];
			return $this->returnData();
		}
		public function getPostTops() {
			$query = "SELECT * FROM post_tops";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			$this->return->data = $result['data'];
			return $this->returnData();

		}
		public function getMiscPrices() {
			$query = "SELECT * FROM miscPrices";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			return $this->returnData($result['data']);

		}

		public function getStyleAll() {
			$ra = array();
			$query = "SELECT s.styleFence, s.id AS styleID, t.type, t.postSpacing, p.pricePerFoot, fh.height, fh.id AS heightID, g.gateID,  g.price AS gatePrice, g.width AS gateWidth 
					FROM styles AS s "
				. " LEFT JOIN types AS t ON s.typeFence = t.id "
				. " LEFT JOIN prices AS p ON s.id = p.stylesID "
				. " LEFT JOIN fenceHeights AS fh ON p.fenceHeightsID = fh.id "
				. " LEFT JOIN gates AS g ON s.id = g.StyleID AND fh.id = g.heightID ";

			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			foreach ($result['data'] as $v) {
				$index = -1;
				for($i = 0; $i < count($ra); $i++) {
					if($v['styleID'] == $ra[$i]['styleID']) {
						$index = $i;
						break;
					}
				}

				if($index === -1) {
					$ra[] = array(
						"styleID" => $v['styleID'],
						"style" => $v['styleFence'],
						"type" => $v['type'],
						"postSpacing" => $v['postSpacing'],
						"heights" => array(
							0 => array(
								"heightID" => $v['heightID'],
								"height" => $v['height'],
								"pricePerFoot" => $v['pricePerFoot'],
								"gates" => array(
									0 => array(
										"gateID" => $v['gateID'],
										"gatePrice" => $v['gatePrice'],
										"gateWidth" => $v['gateWidth']
									)
								)
							)
						)
					);
				}
				else {
					$hIndex = -1;
					for($i = 0; $i < count($ra[$index]['heights']); $i++) {
						if($ra[$index]['heights'][$i]['heightID'] === $v['heightID']) {
							$hIndex = $i;
							break;
						}
					}

					if($hIndex === -1) {
						$ra[$index]['heights'][] = array(
							"heightID" => $v['heightID'],
							"height" => $v['height'],
							"pricePerFoot" => $v['pricePerFoot'],
							"gates" => array(
								0 => array(
									"gateID" => $v['gateID'],
									"gatePrice" => $v['gatePrice'],
									"gateWidth" => $v['gateWidth']
								)
							)
						);
					}
					else {
						$gIndex = -1;
						for($i = 0; $i < count($ra[$index]['heights'][$hIndex]['gates']); $i++) {
							if($ra[$index]['heights'][$hIndex]['gates'][$i]['gateID'] == $v['gateID']) {
								$gIndex = $i;
								break;
							}
						}
						if($gIndex == -1) {
							$ra[$index]['heights'][$hIndex]['gates'][] = array(
								"gateID" => $v['gateID'],
								"gatePrice" => $v['gatePrice'],
								"gateWidth" => $v['gateWidth']
							);
						}
						else {
							//nothing to do right now
						}
					}
				}

			}

			$this->return->data = $ra;
			return $this->returnData();

		}

		public function getCustomerList(String $customerName) {
			if(!$customerName || empty($customerName))
				return $this->returnError("No name was provided");

			$customerName = explode(' ', $customerName);

			$query = "SELECT c.CustomerID, c.CustomerName, c.CustomerPhoneType, c.CustomerPhone, c.CustomerEmail, 
					ca.AddressID, ca.Address, ca.City, ca.TaxCity, ca.State, ca.Zip
				FROM customer AS c
				LEFT JOIN customerAddress AS ca ON c.CustomerAddressID = ca.AddressID 
				WHERE CustomerName LIKE '%" . implode('%', $customerName) . "%'";

			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			return $this->returnData($result['data']);
		}

		public function getAddressList(String $address) {
			if(!$address || empty($address))
				return $this->returnError("No Address Given");

			$address = explode(' ', $address);

			$query = "SELECT AddressID, Address, City, TaxCity, State, County, Zip FROM customerAddress WHERE Address LIKE '%" . implode('%', $address) . "%'";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			return $this->returnData($result['data']);
		}

		public function getJobNumber(int $customerID, int $addressID, DateTime $dateSold) {
			if(!$customerID || !$addressID || !$dateSold) {
				return $this->returnError("Cannot generate Job Number. No Customer or Address Given");
			}

			$query = "INSERT INTO quoteHeader (customerID, addressID, dateSold) VALUES ($customerID, $addressID, '" . $dateSold->format("Y-m-d H:i:s") . "')";
			try {
				$mysql = JFactory::getDB();
				$mysql->setQuery($query);
				$mysql->execute();
				$jobID = $mysql->getInsertID();
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			return $this->returnData($jobID);


		}

		public function updateHeaderCustomer(int $jobID, int $customerID, int $addressID, DateTime $dateSold) {
			if(!$jobID)
				return $this->returnError("No Job Number Given");
			elseif (!$customerID)
				return $this->returnError("No Customer ID Given");
			elseif (!$addressID)
				return $this->returnError("No Address ID given");
			elseif (!$dateSold)
				return $this->returnError("No Date Sold Given");

			/* Update the Header Table */
			$query = "UPDATE quoteHeader SET customerID = $customerID, addressID = $addressID, dateSold = '" . $dateSold->format("Y-m-d H:i:s"). "'  WHERE id = $jobID";
			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			/* Update the Customer Table to reflect the new Address if it is new */
			$query = "UPDATE customer SET CustomerAddressID = $addressID WHERE CustomerID = $customerID";
			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				//don't do anything if it fails
			}


			return $this->returnData();

		}

		public function addCustomer(String $customerName, String $phoneType, String $phone, String $email = null) {
			if(!$customerName || !$phone) {
				return $this->returnError("No Customer Or Phone Given");
			}

			$query = "INSERT INTO customer (CustomerName, CustomerPhoneType, CustomerPhone, CustomerEmail) 
				VALUES ('" . addslashes($customerName) . "', '" . $phoneType . "', '" . $phone . "', '" . addslashes($email) . "')";
			try {
				$result = $this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			return $this->returnData($result['insertID']);

		}

		public function addAddress(String $address, String $city, String $taxCity, String $state, String $zip) {
			if(!$address || !$city || !$state || !$zip)
				return $this->returnError("Please provide all Required Fields");

			$query = "INSERT INTO customerAddress (Address, City, TaxCity, State, Zip) VALUES ('" . addslashes($address) ."', '$city', '$taxCity', '$state', '$zip')";
			try {
				$addressID = $this->queryMysql($query)->insertID;
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			return $this->returnData($addressID);

		}



		public function saveMeasurements(int $jobID, array $allStyles) {
			$added = array();

			foreach ($allStyles as $style) {
				if(!$style['DatabaseID']) {
					$style['DatabaseID'] = $this->getNewDetailID($jobID)->data;
					$added[] = array("id" => $style['ID'], "databaseID" => $style['DatabaseID']);
				}
				$m = $style['Measurements'];
				$measurementBlob = $this->combineMeasurements($m['FrontLeft'], $m['Left'], $m['Back'], $m['Right'], $m['FrontRight'], $m['Extra1'], $m['Extra2'], $m['Extra3']);
				$query = "UPDATE quoteDetail SET 
					styleNumber = '" . $style['ID'] . "',
					styleID = '" . (int)$style['StyleID'] . "', 
					heightID = '" . (int)$style['HeightID'] . "', 
					measurements = '" . $measurementBlob . "',
					totalFeet = '" . (int)$style['TotalFeetFence'] . "',
					postTopID = '" . (int)$style['PostTopID'] . "',
					pricePerPostTop = '" . (double)$style['PostTopPrice'] ."', 
					qtyPostTop = '" . (int)$style['PostTopQty'] . "',
					pricePerFoot = '" . (double)$style['PricePerFoot'] . "', 
					qtyGate4Foot = '" . (int)$style['Gate4Qty'] . "',
					priceGate4Foot = '" . (double)$style['Gate4Price'] . "',
					qtyGate5Foot = '" . (int)$style['Gate5Qty'] . "', 
					priceGate5Foot = '" . (double)$style['Gate5Price'] . "', 
					qtyGate8Foot = '" . (int)$style['Gate8Qty'] . "', 
					priceGate8Foot = '" . (double)$style['Gate8Price'] . "', 
					qtyGate10Foot = '" . (int)$style['Gate10Qty'] . "', 
					priceGate10Foot = '" . (double)$style['Gate10Price'] . "', 
					qtyEndPost = '" . (int)$style['EndPostQty'] . "', 
					priceEndPost = '" . (double)$style['EndPostPrice'] . "', 
					qtyCornerPost = '" . (int)$style['CornerPostQty'] . "',  
					priceCornerPost = '" . (double)$style['CornerPostPrice'] . "', 
					qtyGatePosts = '" . (int)$style['GatePostQty'] . "', 
					priceGatePosts = '" . (double)$style['GatePostPrice'] . "', 
					qtyTempFence = '" . (int)$style['TempFenceQty'] . "', 
					priceTempFence = '" . (double)$style['TempFencePrice'] . "', 
					qtyRemovalFence = '" . (int)$style['RemoveFenceQty'] . "', 
					priceRemovalFence = '" . (double)$style['RemoveFencePrice'] . "', 
					qtyPermit = '" . (int)$style['PermitQty'] . "', 
					pricePermit = '" . (double)$style['PermitPrice'] . "', 
					qtyRemovableSection = '" . (int)$style['RemoveSectionQty'] . "', 
					priceRemovableSection = '" . (double)$style['RemoveSectionPrice'] . "', 
					qtyHaulAwayDirt = '" . (int)$style['HaulDirtQty'] . "', 
					priceHaulAwayDirt = '" . (double)$style['HaulDirtPrice'] . "', 
					qtyUpgradedLatch = '" . (int)$style['UpgradedLatchQty'] . "', 
					priceUpgradedLatch = '" . (double)$style['UpgradedLatchPrice'] . "', 
					qtyUpgradedHinge = '" . (int)$style['UpgradedHingeQty'] . "', 
					priceUpgradedHinge = '" . (double)$style['UpgradedHingePrice'] . "' 
					WHERE id = '" . $style['DatabaseID'] . "'";
				try {
					$this->queryMysql($query);
				} catch (Exception $ex) {
					return $this->returnError($ex->getMessage());
				}
			}

			return $this->returnData($added);
		}
		private function getNewDetailID(int $jobID) {
			if(!$jobID)
				return false;
			$query = "INSERT INTO quoteDetail (jobID) VALUES ($jobID)";
			try {
				$result = $this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}
			return $this->returnData($result['insertID']);
		}
		public function combineMeasurements($frontLeft = 0, $left = 0, $back = 0, $right = 0, $frontRight = 0, $extra1 = 0, $extra2 = 0, $extra3 = 0) {
			return "$frontLeft|$left|$back|$right|$frontRight|$extra1|$extra2|$extra3";
		}
		public function explodeMeasurements($blob) {
			$exp = explode('|', $blob);
			return new ArrayObject(array(
				"frontLeft" => $exp[0],
				"left" => $exp[1],
				"back" => $exp[2],
				"right" => $exp[3],
				"frontRight" => $exp[4],
				"extra1" => $exp[5],
				"extra2" => $exp[6],
				"extra3" => $exp[7]
			), 2);
		}


		public function uploadPhoto(int $jobID, String $file) {
			if(!$jobID || !$file)
				return $this->returnError("No Photo");

			$query = "INSERT INTO photos (jobID, photoLocation) VALUES ($jobID, '" . addslashes($file) . "')";
			try {
				$result = $this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			return $this->returnData($result['insertID']);

		}

		public function savePictureNotes(int $noteID, String $noteText) {
			if(!$noteText || !$noteID)
				return $this->returnError("No ID or Text Given");

			$query = "UPDATE photos SET notes = '" . addslashes($noteText) . "' WHERE id = '" . $noteID . "'";

			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError("Could not save note. " . $ex->getMessage());
			}

			return $this->returnData();
		}

		public function removePicture(int $noteID) {
			if(!$noteID) {
				return $this->returnError("No ID given");
			}

			//Get the location of the image to remove
			$query = "SELECT photoLocation FROM photos WHERE id = $noteID";
			try {
				$result = $this->queryMysql($query, 'loadAssoc');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			$file = $result['data']['photoLocation'];
			if(file_exists($file)) {
				unlink($file);
			}

			$query = "DELETE FROM photos WHERE id = $noteID";
			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError("Could not remove from database. " . $ex->getMessage());
			}

			return $this->returnData();

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