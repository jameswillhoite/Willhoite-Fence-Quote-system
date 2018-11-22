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
			$this->customerName      = ($name) ? $name : '';
			$this->customerAddress   = ($address) ? $address : '';
			$this->customerCity      = ($city) ? $city : '';
			$this->customerState     = ($state) ? $state : '';
			$this->customerZip       = ($zip) ? $zip : '';
			$this->customerPhoneType = ($phoneType) ? $phoneType : 'Phone';
			$this->customerPhone     = ($phone) ? $phone : '';
			$this->customerEmail     = ($email) ? $email : '';
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

		private $lineHeight = 0.2;
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
			$this->Cell($w-$iw, $h, $this->jobNumber, 'B', $h, 'C');
			$this->SetBold(false);




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

		public function Generate() {
			$h = $this->lineHeight;
			//Add the Utilities to the first page
			$this->__UTILITIES();

			//Add the Sold To
			$this->__SOLD_TO();

			//Add Instructions
			$this->__INSTRUCTIONS();

			//Add what the customer will take care of
			$this->__CUSTOMER_WILL();





		}

	}

	$pdf = new GenerateQuote();
	$pdf->setJobNumber(12);
	$pdf->setContractDate(new DateTime());
	$pdf->setCustomerInfo("James Willhoite", "105 Grange Way", "Maryville", "TN", "37805",
		"Cell", "937-689-7772", "jameswillhoite@gmail.com");
	$pdf->AddPage();
	$pdf->Generate();
	$pdf->Output("I");

