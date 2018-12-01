<?php
	/**
	 * Generate Contract / Quote
	 */
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	defined("PROJECT_ROOT") || define('PROJECT_ROOT', __DIR__ . '/../');
	require_once PROJECT_ROOT . 'libraries/MyProject/jamesPDFHelpers.php';

	//TODO: Add authentication to this file

	class CustomerAndJob extends jamesPDFHelpers
	{
		/*
		 * Customer Info
		 */
		public $customerName, $customerAddress, $customerCity, $customerState, $customerZip, $customerPhoneType, $customerPhone, $customerEmail;
		/*
		 * Job Number
		 */
		public $jobNumber;
		/*
		 * MISC Job Settings
		 */
		public $spacing = 'N/A', $finishSide = 'Out', $raiseOffGround = '1 inch', $placeDirt = '';
		/*
		 * MISC Customer Will
		 */
		public $cusSurvey = 'No', $cusRemoveFence = 'No', $cusGetPermit = 'No', $cusClearLines = 'No', $cusDisponseDirt = 'No', $cusProvideMarkers = 'No',
			$cusAdviseUndergroundObstacles = 'No';

		/**
		 * @var $contractDate   DateTime
		 */
		public $contractDate;

		public function __construct($orientation = 'P', $unit = 'in', $size = array(8.5,11))
		{
			parent::__construct($orientation, $unit, $size);
			//Add the Open Sans Font
			$this->AddFont("OpenSans", '');
			$this->AddFont("OpenSans", 'I');
			$this->AddFont("OpenSans", 'B');
			$this->SetMargins(0.2, 0.2, 0.2);
			$this->SetFont("OpenSans", '', 12);

			//If no Date, then add today
			$this->contractDate = (!$this->contractDate) ? new DateTime() : $this->contractDate;
		}

		public function setCustomerInfo($name, $address, $city, $state, $zip, $phoneType, $phone, $email) {
			$Helper = JFactory::getHelpers();
			$this->customerName      = ($name) ? $name : '';
			$this->customerAddress   = ($address) ? $address : '';
			$this->customerCity      = ($city) ? $city : '';
			$this->customerState     = ($state) ? $state : '';
			$this->customerZip       = ($zip) ? $zip : '';
			$this->customerPhoneType = ($phoneType) ? $phoneType : 'Phone';
			$this->customerPhone     = ($phone) ? $Helper->formattedPhone($phone) : '';
			$this->customerEmail     = ($email) ? $email : '';
			$this->SetTitle($this->customerName);
		}

		public function setJobNumber(int $jobNumber) {
			$this->jobNumber = $jobNumber;
		}

		public function setContractDate(DateTime $date) {
			$this->contractDate = $date;
		}

		public function setBoardSpacing(double $spacing) {
			$this->spacing = $spacing;
		}

		public function setFinishSide(String $finishSide = "Out") {
			$this->finishSide = $finishSide;
		}

		public function raiseOffGround(String $raise = '1') {
			$this->raiseOffGround = ((double)$raise > 1.0) ? $raise . " inches" : $raise . " inch";
		}

		public function placeDirt(String $dirt) {
			$this->placeDirt = $dirt;
		}

		public function customerWill(bool $survey = false, bool $removeFence = false, bool $permit = false, bool $clearLines = false, bool $disposeDirt = false,
			bool $provideMarkers = false, bool $adviseUndergroundMarkers = false)
		{
			$this->cusSurvey = ($survey) ? 'Yes' : 'No';
			$this->cusRemoveFence = ($removeFence) ? 'Yes' : 'No';
			$this->cusGetPermit = ($permit) ? 'Yes' : 'No';
			$this->cusDisponseDirt = ($disposeDirt) ? 'Yes' : 'No';
			$this->cusClearLines = ($clearLines) ? 'Yes' : 'No';
			$this->cusProvideMarkers = ($provideMarkers) ? 'Yes' : 'No';
			$this->cusAdviseUndergroundObstacles = ($adviseUndergroundMarkers) ? 'Yes' : 'No';
		}

	}

	class GenerateQuote extends CustomerAndJob
	{

		private $sellerName, $sellerEmail, $sellerPhone;

		private $lineHeight = 0.2;

		private $totalInstalledPrice = 0.00;
		public function __construct($orientation = 'P', $unit = 'in', array $size = array(8.5, 11))
		{
			parent::__construct($orientation, $unit, $size);
			$this->SetMargins(0.2, 0.2, 0.2);
		}

		public function Header() {
			$img = PROJECT_ROOT . 'media/images/logo_3.png';
			$this->Image($img, 0.2, 0.2, 4, 1);
			$this->SetFontSize(12);
			$this->SetLineWidth(0.01);


			//Job Number
			$x = 6.2;
			$iw = 0.75;
			$w = 2;
			$h = $this->lineHeight;
			$this->SetXY($x, 0.2);
			$this->SetBold(true);
			$this->SetFontSize(12);
			$this->Cell($iw, $h, 'Job No.', 0, 0, 'R');
			$this->Cell($w-$iw, $h, $this->jobNumber, 'B', 0, 'C');
			$this->SetBold(false);
			$this->Ln();
			$this->SetXY($x,1.2);

		}

		private function __UTILITIES() {
			//Utilities
			$x = 4.2;
			$w = 2;
			$iw = 0.75;
			$h = $this->lineHeight;


			$this->SetFontSize(12);
			$this->SetXY($x, 0.2);
			$this->SetBold(true);
			$this->Cell($w, $h, "UTILITIES", 'LTR', $h, 'C');
			$this->SetBold(false);
			$this->SetFontSize(8);
			$this->SetX($x);

			$this->Cell($iw, $h, "Ref #", "L", 0, 'R');
			$this->Cell($w-$iw, $h, '', 'BR', $h);
			$this->SetX($x);
			$this->Cell($iw, $h, "County", 'L', 0, 'R');
			$this->Cell($w-$iw, $h, '', 'BR', $h);
			$this->SetX($x);
			$this->FitCell($iw, $h, 'Sub Division', 'L', 0, 'R');
			$this->Cell($w-$iw, $h, '', 'BR', $h);
			$this->SetX($x);
			$this->FitCell($iw, $h/2, 'Nearest', 'L', 0, 'R');
			$this->Cell($w-$iw, $h/2, '', 'R', $h);
			$this->SetX($x);
			$this->FitCell($iw, $h/1.5, 'Intersection', 'L', 0, 'R');
			$this->Cell($w-$iw, $h/1.5, '', 'R', $h);

			$x = $x + $w;
			$this->SetFontSize(8);
			$this->SetXY($x, 0.2+$h);
			$this->FitCell($iw, $h, 'OUPS Operator', 0, 0, 'R');
			$this->Cell($w-$iw, $h, '', 'B', $h);
			$this->SetX($x);
			$this->FitCell($iw, $h, 'Call Spots On', 0, 0, 'R');
			$this->Cell($w-$iw, $h, '', 'B', $h);
			$this->SetX($x);
			$this->FitCell($iw, $h, 'Gas/Electric', 0, 0, 'R');
			$this->FitCell($w-$iw, $h, 'Phone/Cable', 0, $h, 'C');
			$this->SetX($x);
			$this->FitCell($iw, $h/1.5, 'Date / Time', 0 , 0, 'R');
			$this->Cell($w-$iw, $h/1.5, '', 0, $h);
			$this->SetX($x);
			$this->FitCell($iw, $h/2, 'Marked', 0, 0, 'R');
			$this->Cell($w-$iw, $h/2, '', 0, $h);

		}

		private function __SOLD_TO() {
			$this->SetXY(0.2, 1.25);
			$h = $this->lineHeight;
			$this->SetFontSize(12);

			//Contract Date
			$this->SetBold(true);
			$this->Cell(2, $h, 'Date', 0, 0, 'R');
			$this->SetBold(false);
			$this->Cell(2, $h, $this->contractDate->format("F d, Y"), 0, 0, 'L');
			$this->Ln($h);

			//Sold To
			$w = 4;
			$l = 0.75;
			$this->SetBold(true);
			$this->Cell($l, $h, 'To', 0, 0, 'R');
			$this->SetBold(false);
			$this->FitCell($w-$l, $h, $this->customerName, 0, 0, 'L');
			$this->Ln();
			//Address
			$this->SetBold(true);
			$this->Cell($l, $h, "Address", 0, 0, 'R');
			$this->SetBold(false);
			$this->FitCell($w-$l, $h, $this->customerAddress, 0, 0, 'L');
			$this->Ln();
			$this->SetX($l + 0.2);
			//City State Zip
			$this->FitCell($w-$l, $h, $this->customerCity . ', ' . $this->customerState . " " . $this->customerZip);
			$this->Ln();
			//Phone
			$this->SetBold(true);
			$this->Cell($l, $h, $this->customerPhoneType, 0, 0, 'R');
			$this->SetBold(false);
			$this->FitCell($w-$l, $h, $this->customerPhone);
			$this->Ln();
			//Email
			$this->SetBold(true);
			$this->Cell($l, $h, "E-mail", 0, 0, 'R');
			$this->SetBold(false);
			$this->FitCell($w-$l, $h, $this->customerEmail);
		}

		private function __INSTRUCTIONS() {
			$h = $this->lineHeight;

			//Instructions
			$x = 4.2;
			$w = 4;
			$iw = 0.75;
			$iiw = $iw/1.285;
			$this->SetXY($x, 1.25);
			$this->SetFontSize(12);
			$this->SetBold(true);
			$this->Cell($w, $h, "INSTRUCTIONS", "LTR", $h, 'C');
			$this->SetBold(false);
			$this->SetFontSize(8);

			$this->SetX($x);
			$this->Cell($iw, $h, "Spacing inch", 'L', 0, 'R');
			$this->SetBold(true);
			$this->Cell($iiw, $h, $this->spacing, 'B', 0, 'C');
			$this->SetBold(false);

			$this->FitCell($iw, $h, 'Finish Side', 0, 0, 'R');
			$this->SetBold(true);
			$this->Cell($iiw, $h, $this->finishSide, 'B', 0, 'C');
			$this->SetBold(false);

			$this->FitCell($iw, $h, 'Raise Fence', 0, 0, 'R');
			$this->SetBold(true);
			$this->FitCell($iiw, $h, $this->raiseOffGround, 'BR', $h, 'C');
			$this->SetBold(false);

			$this->SetX($x);
			$this->FitCell($iw, $h, 'Dirt', 'LB', 0, 'R');
			$this->SetBold(true);
			$this->FitCell($w-$iw, $h, $this->placeDirt, 'BR', 0);
			$this->SetBold(false);

		}

		private function __CUSTOMER_WILL() {
			$h = $this->lineHeight;
			$x = 4.2;
			$w = 4;
			$iw = 0.75;
			$iiw = $iw/1.285;
			$this->SetXY($x, 1.25 + $h*3);
			$this->SetFontSize(12);
			$this->SetBold(true);
			$this->FitCell($w, $h, 'CUSTOMER WILL', 'LR', $h, 'C');
			$this->SetBold(false);

			$this->SetX($x);
			$this->SetFontSize(7.5);
			$this->FitCell($iw, $h, 'Get Permit', 'L', 0, 'R');
			$this->SetBold(true);
			$this->Cell($iiw, $h, $this->cusGetPermit, 'B', 0, 'C');
			$this->SetBold(false);

			$this->FitCell($iw, $h, 'Get Survey', 0, 0, 'R');
			$this->SetBold(true);
			$this->Cell($iiw, $h, $this->cusSurvey, 'B', 0, 'C');
			$this->SetBold(false);

			$this->FitCell($iw, $h, 'Dispose Dirt', 0, 0, 'R');
			$this->SetBold(true);
			$this->Cell($iiw, $h, $this->cusDisponseDirt, 'BR', $h, 'C');
			$this->SetBold(false);

			$this->SetX($x);
			$this->FitCell($iw, $h, 'Remove Fence', 'LB', 0, 'R');
			$this->SetBold(true);
			$this->Cell($iiw, $h, $this->cusRemoveFence, 'B', 0, 'C');
			$this->SetBold(false);

			$this->FitCell($iw, $h, 'Clear Lines', 'B', 0, 'R');
			$this->SetBold(true);
			$this->Cell($iiw, $h, $this->cusClearLines, 'B', 0, 'C');
			$this->SetBold(false);

			$this->FitCell($iw, $h, 'Provide Markers', 'B', 0, 'R');
			$this->SetBold(true);
			$this->Cell($iiw, $h, $this->cusProvideMarkers, 'BR', $h, 'C');
			$this->SetBold(false);


		}

		private function __DISCLAIMER() {
			$this->SetFontSize(10);
			$temp = "1) The Customer agrees to pay for any additional materials and/or labor over and above the stated quantities needed to complete this installation.\r\n"
				. "2) The Customer gives Willhoite Fence and their employees permission to be on their property and to remove the fence for non-payment after written notification.\r\n"
				. "3) The customer assumes full responsibilities for all plat restrictions concerning fences.\r\n"
				. "4) We will assume no responsibility for any damage to underground sprinkler systems, wiring, or obstacles not properly located.\r\n"
				. "5) An 21% interest charge (compounded daily) will be added each month to all balances not paid in full over 30 day's. Willhoite Fence may do a credit check.";
			$this->WordWrapCell(8.1, $this->lineHeight, $temp);
		}

		private function __TOTAL_INSTALLED_PRICE() {
			$h = $this->lineHeight;
			$this->Ln($h*2);
			$text = "The <b>TOTAL INSTALLED PRICE</b> of the Fence is based on the <b>actual footage</b> of the completed job. <b>__________</b>";
			$this->WriteHTML($text);
			$this->Ln($h*2);
			$this->SetBold(true);
			$this->Cell(8.1, $h, "Total Installed Price: $" . number_format($this->totalInstalledPrice, 2));
			$this->SetBold(false);
			$this->Ln($h*1.5);
			$this->Cell(0.75, $h, "Payment:");
			$this->Cell(1.25, $h, "1/2 at Signature");
			$this->Cell(0.25, $h, "/");
			$this->Cell(1.5, $h, 'Balance on Completion');
			$this->Ln();
			$this->SetX(0.75);
			$this->Cell(1.25, $h, "$". number_format($this->totalInstalledPrice/2, 2), 0, 0, 'C');
			$this->Cell(0.52, $h, '');
			$this->Cell(1.5, $h, "$".number_format($this->totalInstalledPrice/2, 2), 0, 0, 'C');
			$this->Ln();
		}

		private function __SIGNATURE_LINE() {
			$h = $this->lineHeight;
			$this->Ln($h*2);
			$this->SetBold(true);
			$this->Cell(0.6, $h, "By:", 0, 0, 'R');
			$this->Cell(4, $h, $this->sellerName . "    " . $this->sellerPhone, 'B', 0, 'C');
			$this->Ln($h*1.25);
			$this->Cell(0.6, $h, "Signed:", 0, 0, 'R');
			$this->Cell(4, $h, '', 'B');
			$this->Ln($h*1.25);
			$this->Cell(4.6, $h, "Scheduling Manager:  Kyle Gosnell  (937) 671-7792");
		}

		private function __ADD_STYLES($styles = array()) {
			//Add the Styles
			$h = $this->lineHeight;
			$x = 0.2;
			$w = 4; //Each Style will be 4 inches wide
			$q = 0.75; //Qty Column
			$d = 1.75; //Description Column
			$u = 0.75; //Unit Price Column
			$t = 0.75; //Total Price Column
			$side = true; //True = Left     False = Right
			$y = 0; //Keep track of the y
			$i = 1; //Keep Track of how many styles there are
			$this->SetFontSize(10);
			$this->SetLineWidth(0.01);
			foreach ($styles as $style) {
				switch($side) {
					case true:
						$x = 0.2;
						$y = 0;
						if($i%3 == 0) {
							$this->SetY($this->GetY() + 0.1);
						}
						break;
					case false:
						$x = $w + 0.2;
						$this->SetY($this->GetY() - $y);
						$y = 0;
						break;
				}

				$subtotal = 0;

				$this->SetX($x);

				//Display Style Name
				$this->SetBold(true);
				$this->FitCell($w, $h, $style->StyleName . " " . $style->Height . " Tall", 1, $h, 'C');
				$this->SetBold(False);
				$y += $h;

				//Place Column Headers
				$this->SetX($x);
				$this->Cell($q, $h, 'Quantity', 'LBR', 0, 'C');
				$this->Cell($d, $h, 'Description', 'BR', 0, 'C');
				$this->Cell($u, $h, 'Unit Price', 'BR', 0, 'C');
				$this->Cell($t, $h, 'Amount', 'BR', $h, 'C');
				$y += $h;

				//Start Listing out the Elements
				$this->SetX($x);
				//Total Feet Fence
				$desc = "Total Feet Fence";
				$qty = ($style->TotalFeet > 0) ? $style->TotalFeet : '';
				$unit = ($qty > 0) ? $style->PricePerFoot : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->FitCell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2) :''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//4 Foot Wide Gate
				$desc = "Gate 4 Foot Wide";
				$qty = ($style->Gate4FootQty > 0) ? $style->Gate4FootQty : '';
				$unit = ($qty > 0) ? $style->Gate4FootPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->FitCell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//5 Foot Wide Gate
				$desc = "Gate 5 Foot Wide";
				$qty = ($style->Gate5FootQty > 0) ? $style->Gate5FootQty : '';
				$unit = ($qty > 0) ? $style->Gate5FootPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->FitCell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//8 Foot Wide Gate
				$desc = "Double Drive Gate 8 Foot";
				$qty = ($style->Gate8FootQty > 0) ? $style->Gate8FootQty : '';
				$unit = ($qty > 0) ? $style->Gate8FootPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->FitCell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//10 Foot Wide Gate
				$desc = "Double Drive Gate 10 Foot";
				$qty = ($style->Gate10FootQty > 0) ? $style->Gate10FootQty : '';
				$unit = ($qty > 0) ? $style->Gate10FootPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->FitCell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//End Posts
				$desc = "End Posts";
				$qty = ($style->EndPostQty > 0) ? $style->EndPostQty : '';
				$unit = ($qty > 0) ? $style->EndPostPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Corner Posts
				$desc = "Corner Posts";
				$qty = ($style->CornerPostQty > 0) ? $style->CornerPostQty : '';
				$unit = ($qty > 0) ? $style->CornerPostPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Gate Posts
				$desc = "Gate Posts";
				$qty = ($style->GatePostQty > 0) ? $style->GatePostQty : '';
				$unit = ($qty > 0) ? $style->GatePostPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Post Tops
				$desc = "Post Tops " . $style->PostTop;
				$qty = ($style->PostTopQty > 0) ? $style->PostTopQty : '';
				$unit = ($qty > 0) ? $style->PostTopPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Temporary Fence
				$desc = "Temporary Fence";
				$qty = ($style->TempFenceQty > 0) ? $style->TempFenceQty : '';
				$unit = ($qty > 0) ? $style->TempFencePrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Removal Of Old Fence
				$desc = "Removal of Old Fence";
				$qty = ($style->RemoveFenceQty > 0) ? $style->RemoveFenceQty : '';
				$unit = ($qty > 0) ? $style->RemoveFencePrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Permit
				$desc = "Permit";
				$qty = ($style->PermitQty > 0) ? $style->PermitQty : '';
				$unit = ($qty > 0) ? $style->PermitPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Removable Section
				$desc = "Removable Section";
				$qty = ($style->RemovableSectionQty > 0) ? $style->RemovableSectionQty : '';
				$unit = ($qty > 0) ? $style->RemovableSectionPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Gate Posts
				$desc = "Haul Away Dirt";
				$qty = ($style->HaulAwayDirtQty > 0) ? $style->HaulAwayDirtQty : '';
				$unit = ($qty > 0) ? $style->HaulAwayDirtPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Upgraded Latch
				$desc = "Upgraded Latch";
				$qty = ($style->UpgradedLatchQty > 0) ? $style->UpgradedLatchQty : '';
				$unit = ($qty > 0) ? $style->UpgradedLatchPrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				$this->SetX($x);
				//Upgraded Hinge
				$desc = "Upgraded Hinge";
				$qty = ($style->UpgradedHingeQty > 0) ? $style->UpgradedHingeQty : '';
				$unit = ($qty > 0) ? $style->UpgradedHingePrice : '';
				$total = ($qty > 0) ? $qty * $unit : '';
				$subtotal += ($qty > 0) ? $total : 0;
				$this->Cell($q, $h, $qty, 'LBR', 0, 'C');
				$this->Cell($d, $h, $desc, 'BR', 0, 'L');
				$this->FitCell($u, $h, (($qty>0)?"$" . number_format($unit, 2) :''), 'BR', 0, 'C');
				$this->FitCell($t, $h, (($qty>0)?"$" . number_format($total, 2):''), 'BR', $h, 'C');
				$y += $h;

				//Display Subtotal
				$this->SetX($x);
				$this->SetBold(true);
				$this->Cell($q+$d+$u, $h, 'Subtotal', 'LBR', 0, 'R');
				$this->FitCell($t, $h, "$" . number_format($subtotal, 2), 'BR', $h, 'C');
				$this->SetBold(false);
				$y += $h;


				//Add Subtotal to Grand total
				$this->totalInstalledPrice += $subtotal;

				//Change Sides
				$side = !$side;

				//Add one to the style counter
				$i++;
			}
		}

		public function Generate($styles = array()) {
			$h = $this->lineHeight;
			//Add the Utilities to the first page
			$this->__UTILITIES();

			//Add the Sold To
			$this->__SOLD_TO();

			//Add Instructions
			$this->__INSTRUCTIONS();

			//Add what the customer will take care of
			$this->__CUSTOMER_WILL();

			//Reset the X Y position to start placing the Styles
			$this->SetXY(0.2, $this->GetY());

			//Add the styles
			$this->__ADD_STYLES($styles);

			//Reset The X
			$this->SetX(0.2);

			//Add the Disclaimer
			$this->__DISCLAIMER();

			//Display the Total Installed Price
			$this->__TOTAL_INSTALLED_PRICE();

			//Place the Signature Line
			$this->__SIGNATURE_LINE();



		}

		public function setSeller($name, $email, $phone) {
			$Helper = JFactory::getHelpers();
			$this->sellerName = trim($name);
			$this->sellerEmail = trim($email);
			$this->sellerPhone = ($phone) ? $Helper->formattedPhone($phone) : '';
		}

	}

	if(isset($_GET['view']) && $_GET['view'] == "browser")
	{
		require_once PROJECT_ROOT . 'libraries/MyProject/JFactory.php';
		//Make sure user is logged in and can access this page
		$security = JFactory::getSecurity(false);
		if(!$security->allow(2)) {
			echo "Not Authorized to Access this Resource!";
			exit();
		}

		require_once PROJECT_ROOT . 'model/project.php';
		$model = new ProjectModelProject();

		$jobID = $_GET['jobID'];
		$job   = $model->getAllJobInfo($jobID);
		if ($job->error)
		{
			echo $job->error_msg;
			exit();
		}

		$Job = $job->data;

		$pdf = new GenerateQuote();
		$pdf->setSeller($Job->SellerName, $Job->SellerEmail, $Job->SellerPhone);
		$pdf->setJobNumber($jobID);
		$pdf->setContractDate($Job->DateSold);
		$pdf->setCustomerInfo($Job->CustomerName, $Job->Address, $Job->City, $Job->State, $Job->Zip,
			$Job->CustomerPhoneType, $Job->CustomerPhone, $Job->CustomerEmail);
		$pdf->AddPage();
		$pdf->Generate($job->data->Styles);
		$pdf->Output("I", $pdf->customerName . ".pdf");
	}
