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

		public function getJobNumber(int $customerID, int $addressID, DateTime $dateSold, int $sellerID) {
			if(!$customerID || !$addressID || !$dateSold) {
				return $this->returnError("Cannot generate Job Number. No Customer or Address Given");
			}

			$query = "INSERT INTO quoteHeader (customerID, addressID, dateSold, soldBy) VALUES ($customerID, $addressID, '" . $dateSold->format("Y-m-d H:i:s") . "', $sellerID)";
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

		public function addAddress(String $address, String $city, String $state, String $zip) {
			if(!$address || !$city || !$state || !$zip)
				return $this->returnError("Please provide all Required Fields");

			$query = "INSERT INTO customerAddress (Address, City, State, Zip) VALUES ('" . addslashes($address) ."', '$city', '$state', '$zip')";
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
			if(!$blob) {
				return new ArrayObject(array(
					"FrontLeft" => 0,
					"Left" => 0,
					"Back" => 0,
					"Right" => 0,
					"FrontRight" => 0,
					"Extra1" => 0,
					"Extra2" => 0,
					"Extra3" => 0
				), 2);
			}
			$exp = explode('|', $blob);
			return new ArrayObject(array(
				"FrontLeft" => $exp[0],
				"Left" => $exp[1],
				"Back" => $exp[2],
				"Right" => $exp[3],
				"FrontRight" => $exp[4],
				"Extra1" => $exp[5],
				"Extra2" => $exp[6],
				"Extra3" => $exp[7]
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

		public function getAllJobInfo(int $jobNumber) {
			if(!$jobNumber)
				return $this->returnError("No Job Number Given");

			$query = "SELECT h.customerID AS CustomerID, h.addressID AS AddressID, h.dateSold AS DateSold, 
				d.id AS DetailID, d.styleNumber AS StyleNumber, d.styleID AS StyleID, d.heightID AS HeightID, 
				d.measurements AS Measurements, d.totalFeet AS TotalFeet, d.postTopID AS PostTopID, d.pricePerPostTop AS PostTopPrice, d.qtyPostTop AS PostTopQty, d.pricePerFoot AS PricePerFoot, 
				d.qtyGate4Foot AS Gate4FootQty, d.priceGate4Foot AS Gate4FootPrice, d.qtyGate5Foot AS Gate5FootQty, d.priceGate5Foot AS Gate5FootPrice, d.qtyGate8Foot AS Gate8FootQty, 
				d.priceGate8Foot AS Gate8FootPrice, d.qtyGate10Foot AS Gate10FootQty, d.priceGate10Foot AS Gate10FootPrice, d.qtyEndPost AS EndPostQty, d.priceEndPost AS EndPostPrice, d.qtyCornerPost AS CornerPostQty, d.priceCornerPost AS CornerPostPrice, 
				d.qtyGatePosts AS GatePostQty, d.priceGatePosts AS GatePostPrice, d.qtyTempFence AS TempFenceQty, d.priceTempFence AS TempFencePrice, d.qtyRemovalFence AS RemoveFenceQty, d.priceRemovalFence AS RemoveFencePrice, 
				d.qtyPermit AS PermitQty, d.pricePermit AS PermitPrice, d.qtyRemovableSection AS RemovableSectionQty, d.priceRemovableSection AS RemovableSectionPrice, d.qtyHaulAwayDirt AS HaulAwayDirtQty, d.priceHaulAwayDirt AS HaulAwayDirtPrice, 
				d.qtyUpgradedLatch AS UpgradedLatchQty, d.priceUpgradedLatch AS UpgradedLatchPrice, d.qtyUpgradedHinge AS UpgradedHingeQty, d.priceUpgradedHinge AS UpgradedHingePrice,  
				c.CustomerName, c.CustomerPhoneType, c.CustomerPhone, c.CustomerEmail,
				ca.Address, ca.City, ca.State, ca.Zip,
				s.styleFence AS StyleFence,
				t.type AS TypeFence, t.postSpacing AS PostSpacing, 
				fh.height AS FenceHeight, 
				pt.description AS PostTop, 
				photo.notes AS PhotoNotes, photo.photoLocation AS PhotoLocation, photo.id AS PhotoID,
				u.name AS SellerName, u.email AS SellerEmail, u.phone AS SellerPhone
				FROM quoteHeader AS h 
				LEFT JOIN quoteDetail AS d ON h.id = d.jobID 
				LEFT JOIN customer AS c ON h.customerID = c.CustomerID 
				LEFT JOIN customerAddress AS ca ON h.addressID = ca.AddressID 
				LEFT JOIN styles AS s ON d.styleID = s.id 
				LEFT JOIN types AS t ON s.typeFence = t.id 
				LEFT JOIN fenceHeights AS fh ON d.heightID = fh.id 
				LEFT JOIN post_tops AS pt ON d.postTopID = pt.id 
				LEFT JOIN photos AS photo ON h.id = photo.jobID
				LEFT JOIN users AS u ON h.soldBy = u.userID  
				WHERE h.id = $jobNumber";

			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError("Could not get Job Info for ID: " . $jobNumber . " " . $ex->getMessage());
			}

			$detailIDUsed = array();
			$photoIDUsed = array();
			$row = (object)$result['data'][0];
			$newArray = new ArrayObject(array(
				"CustomerID" => $row->CustomerID,
				"AddressID" => $row->AddressID,
				"DateSold" => new DateTime($row->DateSold),
				"CustomerName" => $row->CustomerName,
				"CustomerPhoneType" => $row->CustomerPhoneType,
				"CustomerPhone" => $row->CustomerPhone,
				"CustomerEmail" => $row->CustomerEmail,
				"Address" => $row->Address,
				"City" => $row->City,
				"State" => $row->State,
				"Zip" => $row->Zip,
				"SellerName" => $row->SellerName,
				"SellerEmail" => $row->SellerEmail,
				"SellerPhone" => $row->SellerPhone,
				"Photos" => new ArrayObject(array(), 2),
				"Styles" => new ArrayObject(array(), 2)
			), 2);
			foreach ($result['data'] as $row) {
				$row = (object)$row;
				/**
				 * @var ArrayObject $Style
				 */
				$Style = $newArray->Styles;
				/**
				 * @var ArrayObject $Photos
				 */
				$Photos = $newArray->Photos;
				$dID = $row->DetailID;
				$pID = $row->PhotoID;
				if(!isset($detailIDUsed[$dID])) {
					$detailIDUsed[$dID] = true;
					$measurements = $this->explodeMeasurements($row->Measurements);
					$temp = new ArrayObject(array(
						"StyleNumber"       => $row->StyleNumber,
						"StyleName"         => $row->StyleFence,
						"StyleID"           => $row->StyleID,
						"HeightID"          => $row->HeightID,
						"Height"            => $row->FenceHeight,
						"Measurements"      => $measurements,
						"TotalFeet"         => (int)$row->TotalFeet,
						"PricePerFoot"      => (double)$row->PricePerFoot,
						"PostTopID"         => $row->PostTopID,
						"PostTop"           => $row->PostTop,
						"PostTopPrice"      => (double)$row->PostTopPrice,
						"PostTopQty"        => (int)$row->PostTopQty,
						"Gate4FootQty"      => (int)$row->Gate4FootQty,
						"Gate4FootPrice"    => (double)$row->Gate4FootPrice,
						"Gate5FootQty"      => (int)$row->Gate5FootQty,
						"Gate5FootPrice"    => (double)$row->Gate5FootPrice,
						"Gate8FootQty"      => (int)$row->Gate8FootQty,
						"Gate8FootPrice"    => (double)$row->Gate8FootPrice,
						"Gate10FootQty"     => (int)$row->Gate10FootQty,
						"Gate10FootPrice"   => (double)$row->Gate10FootPrice,
						"EndPostQty"        => (int)$row->EndPostQty,
						"EndPostPrice"      => (double)$row->EndPostPrice,
						"CornerPostQty"     => (int)$row->CornerPostQty,
						"CornerPostPrice"   => (double)$row->CornerPostPrice,
						"GatePostQty"       => (int)$row->GatePostQty,
						"GatePostPrice"     => (double)$row->GatePostPrice,
						"TempFenceQty"      => (int)$row->TempFenceQty,
						"TempFencePrice"    => (double)$row->TempFencePrice,
						"RemoveFenceQty"    => (int)$row->RemoveFenceQty,
						"RemoveFencePrice"  => (double)$row->RemoveFencePrice,
						"PermitQty"         => (int)$row->PermitQty,
						"PermitPrice"       => (double)$row->PermitPrice,
						"RemovableSectionQty" => (int)$row->RemovableSectionQty,
						"RemovableSectionPrice" => (double)$row->RemovableSectionPrice,
						"HaulAwayDirtQty"       => (int)$row->HaulAwayDirtQty,
						"HaulAwayDirtPrice"     => (double)$row->HaulAwayDirtPrice,
						"UpgradedLatchQty"      => (int)$row->UpgradedLatchQty,
						"UpgradedLatchPrice"    => (double)$row->UpgradedLatchPrice,
						"UpgradedHingeQty"      => (int)$row->UpgradedHingeQty,
						"UpgradedHingePrice"    => (double)$row->UpgradedHingePrice
					), 2);

					$Style->append($temp);

				}

				if(!isset($photoIDUsed[$pID])) {
					$photoIDUsed[$pID] = true;
					$temp = new ArrayObject(array(
						"PhotoID"   => $pID,
						"Notes"     => $row->PhotoNotes,
						"Location"  => $row->PhotoLocation
					), 2);

					$Photos->append($temp);
				}
			}

			return $this->returnData($newArray);
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