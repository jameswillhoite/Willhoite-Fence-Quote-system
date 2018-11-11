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

		public function getJobNumber(int $customerID, int $addressID) {
			if(!$customerID || !$addressID) {
				return $this->returnError("Cannot generate Job Number. No Customer or Address Given");
			}

			$query = "INSERT INTO quoteHeader (customerID, addressID, dateSold) VALUES ($customerID, $addressID, '" . date("Y-m-d H:i:s") . "')";
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

		public function updateHeaderCustomer(int $jobID, int $customerID, int $addressID) {
			if(!$jobID)
				return $this->returnError("No Job Number Given");
			elseif (!$customerID)
				return $this->returnError("No Customer ID Given");
			elseif (!$addressID)
				return $this->returnError("No Address ID given");

			$query = "UPDATE quoteHeader SET customerID = $customerID, addressID = $addressID WHERE id = $jobID";
			try {
				$this->queryMysql($query);
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
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