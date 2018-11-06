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
			$this->return = array('error' => false, 'error_msg' => '', 'data' => null);
		}

		public function getStyles() {
			$query = "SELECT * FROM styles AS s LEFT JOIN types AS t ON s.typeFence = t.id";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}
			$this->return['data'] = $result['data'];
			return $this->returnData();

		}
		public function getHeights() {
			$query = "SELECT * FROM fenceHeights ORDER BY height ASC";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}
			$this->return['data'] = $result['data'];
			return $this->returnData();
		}
		public function getPostTops() {
			$query = "SELECT * FROM post_tops";
			try {
				$result = $this->queryMysql($query, 'loadAssocList');
			} catch (Exception $ex) {
				return $this->returnError($ex->getMessage());
			}

			$this->return['data'] = $result['data'];
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

			$this->return['data'] = $ra;
			return $this->returnData();

		}

		private function returnError($txt) {
			$this->return['error'] = true;
			$this->return['error_msg'] = $txt;
			return $this->return;
		}
		private function returnData($data = null) {
			if($data) {
				$this->return['data'] = $data;
			}
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