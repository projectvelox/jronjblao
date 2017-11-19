<?php
//===================================================//
//=====  REPORT : SCHEDULE OF PAYMENT           =====//
//===================================================//

require_once('../includes/tcppdf/tcpdf.php');
require_once("../includes/config.php");
require_once("class-numwords.php");
require_once("rpt-definitions.php");

ini_set('display_errors', 'Off');
function handleReportError() { if(error_get_last() != NULL)  { echo "Error generating report.  Please try again later.";  } }
register_shutdown_function('handleReportError');

$logoW		 = 26;
$logoH  	 = 16;

session_start();
$salesID	= 0;
if(isset($_SESSION['salesID'])) {  $salesID  = $_SESSION['salesID'];  unset($_SESSION['salesID']); }
if($salesID == 0) { $salesID == NULL; }


class waisReport extends TCPDF 
{
	public $companyName;
	public $reportTitle;
	public $reportName;
	public $logoWidth;
	public $logoFile;
	public $logoW;
	public $logoH;

	public function setReportTitle($S = '') { $this->reportTitle = $S; }  public function getReportTitle() { return $this->reportTitle; }
	public function setReportName ($s = '') { $this->reportName	 = $s; }  public function getReportName()  { return $this->reportName;	}
	public function setLogoFile		($s = '') { $this->logoFile 	= $s; }	public function getLogoFile()	{ return $this->logoFile;		}
	public function setLogoWidth	($i =  8) { $this->logoW		= $i; }	public function getLogoWidth()	{ return $this->logoW;			}
	public function setLogoHeight	($i =  4) { $this->logoH		= $i; }	public function getLogoHeight()	{ return $this->logoH;			}


    // report header
	public function Header() 
	{
		/*   THIS IS FORM (NO NEED FOR HEADER)   */
	}
	
	// report footer
	public function Footer() 
	{
		/*    THIS IS FORM (NO NEED FOR FOOTER)   */
	}
	
	public function getCustomerSales($salesID = NULL)
	{
		$sched      = array();
		$schedLiist = array();
		$sql      = '';
		$custAddr = '';
		$term     = 0;
		$pmtPrincipal = 0;
		$pmtInterest  = 0;
		$salesTotal   = 0;
		$schedBalance = 0;
		$schedDueAmt  = 0;
		$schedDueDate = '';
		$dateBegin    = '';
		if(!$salesID == NULL)
		{
			
	        $sql   = " SELECT tabSCHED.*, tabSALES.*, tabCUSTOMER.*, ";
	        $sql  .= "		(SELECT CONCAT(c_firstname,' ', c_middlename,' ', c_lastname) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = tabSALES.s_customer_id ) AS customerName, ";
			$sql  .= "		(SELECT value FROM options_country AS tabCOUNTRY WHERE tabCOUNTRY.id = tabCUSTOMER.c_address_country ) AS customerCountry,  ";
	        $sql  .= "		(SELECT SUM(s_sold_price * s_qty) FROM sales_sub AS tabSUB WHERE tabSUB.s_sales_id = tabSALES.s_id ) AS totalSales ";
	        $sql  .= " FROM form_pmtsched AS tabSCHED ";
	        $sql  .= "		INNER JOIN (sales_main AS tabSALES ) ON tabSALES.s_id = tabSCHED.sales_id ";
			$sql  .= "		JOIN (customers AS tabCUSTOMER) ON tabCUSTOMER.c_id  = tabSALES.s_customer_id   ";
	        $sql  .= " WHERE tabSCHED.sales_id = $salesID ";
			$rec = mysql_query($sql);
		    while ($pmt = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		    	if(trim($pmt['c_address_street']<> "")) { $custAddr  = $pmt['c_address_street'].', '; }
		    	if(trim($pmt['c_address_town']	<> "")) { $custAddr .= $pmt['c_address_street'].', '; }
		    	if(trim($pmt['c_address_city']	<> "")) { $custAddr .= $pmt['c_address_city']	.', '; }
		    	if(trim($pmt['customerCountry']	<> "")) { $custAddr .= $pmt['customerCountry'].', '; }
		    	if(trim($pmt['c_address_zipcode'] <> "")){ $custAddr .= ' '.$pmt['c_address_zipcode']; }
		    	$term		 = $pmt['s_payment_term'] + 0;
				$pmtPrincipal= $pmt['pmtPrincipal'] + 0;
				$pmtInterest = $pmt['pmtInterest']  + 0;
				$salesTotal  = $pmt['totalSales'] + $pmt['s_othercharges'] + $pmt['s_tax'] - $pmt['s_discount'] + 0;
				$dateBegin   = $pmt['s_firstmonthlydue_date'];
		        $sched = array
		                 (  
		                    'reportDate' 	  => $pmt['formDate'],
		                    'customerName' 	  => $pmt['customerName'],
		                    'customerAddress' => $custAddr,
		                    'saleAmount'	  => $salesTotal,
		                    'saleTerms'    	  => $pmt['s_payment_term'],
		                    'salesPmtBegin'   => $pmt['s_firstmonthlydue_date'],
		                    'itemName'   => '',
		                    'itemMotorChassisNo'   => '', 
		                    'itemMotorEngineNo'    => ''
		                );
		    }
		    unset($rec);
	        $sql   = " SELECT ";
	        $sql  .= "      tabSALES.*, tabSUB.*, tabPROD.p_name, tabPROD.p_property_1, tabPROD.p_property_2 ";
	        $sql  .= " FROM sales_main AS tabSALES ";
  	        $sql  .= "		JOIN (sales_sub AS tabSUB) ON tabSUB.s_sales_id = tabSALES.s_id ";
  	        $sql  .= "		JOIN (products AS tabPROD) ON tabPROD.p_id = tabSUB.s_product_id ";
	        $sql  .= " WHERE tabSALES.s_id = $salesID ";
			$rec = mysql_query($sql);
		    while ($p = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		        $sched['itemName']			= $p['p_name'];
		        $sched['itemMotorChassisNo']= $p['p_property_1'];
		        $sched['itemMotorEngineNo'] = $p['p_property_2'];
		        break;
		    }
		    unset($rec);
		    $schedBalance = $salesTotal;
		    for($i = 1; $i <= $term; $i++)
		    {
				$schedDueAmt   = $pmtPrincipal + $pmtInterest + 0;
				$schedBalance -= $schedDueAmt + 0;
				$schedDueDate  = date("Y-m-d",strtotime(date("Y-m-d", strtotime($schedDueDate)) . ' +1 month')); 
				if($i == 1) { $schedDueDate = $dateBegin; }
				$schedLiist[] = array(
									'schedPmtRow'		=> $i, 
									'schedPmtDate'		=> $schedDueDate, 
									'schedPmtPrincipal' => $pmtPrincipal, 
									'schPmtInterest'	=> $pmtInterest, 
									'schedPmtDue'		=> $schedDueAmt, 
									'schedPmtBalance'	=> $schedBalance
								);
		    }
		    $sched['paymentSchedule'] = $schedLiist;
		}
		return $sched;
	}


	public function reportLayout($d) 
	{
		$lineStyle = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->setXY(0,0);
		if(is_array($d))
		{
			$rptDate = date("F j, Y", strtotime($d['reportDate']));
			$this->setXY(159 ,  49);  $this->Cell(0,0, $rptDate );
			$this->setXY( 34 ,  62);  $this->Cell(0,0, $d['customerName'] );
			$this->setXY(122 ,  62);  $this->Cell(0,0, $d['customerAddress'] );

			$this->setXY( 43 ,  70);  $this->Cell(0,0, $d['itemName'] );
			$this->setXY(126 ,  70);  $this->Cell(0,0, $d['itemMotorChassisNo'] );
			$this->setXY(166 ,  70);  $this->Cell(0,0, $d['itemMotorEngineNo'] );

			$this->setXY( 32 ,  77);  $this->Cell(0,0, $d['saleTerms'].' Months' );
		    if(is_numeric( $d['saleAmount'] )) {  $w = new NumWords( $d['saleAmount'] ); $v = $w->number; unset($w); } else { $v = ''; }
			$this->setXY(128 ,  77);  $this->Cell(0,0, $v );
			
			// payment heading
			$this->setXY( 20 , 100);  $this->Cell(15,0, 'Payment', 0, 0, 'C' );
			$this->setXY( 42 , 100);  $this->Cell(30,0, 'Date of Payment', 0, 0, 'L'  );
			$this->setXY( 72 , 100);  $this->Cell(30,0, 'Principal Due', 0, 0, 'R'  );
			$this->setXY(102 , 100);  $this->Cell(30,0, 'Interest Due', 0, 0, 'R'  );
			$this->setXY(137 , 100);  $this->Cell(30,0, 'Payment Due', 0, 0, 'R'  );
			$this->setXY(168 , 100);  $this->Cell(30,0, 'Balance Due', 0, 0, 'R'  );
			
			$this->SetLineStyle($lineStyle);
            $this->Line(20 , 104,  35, 104);  // payment row
            $this->Line(43 , 104,  70, 104);  // date
            $this->Line(79 , 104, 101, 104);  // principal
            $this->Line(111, 104, 131, 104);  // interest
            $this->Line(144, 104, 166, 104);  // payment
            $this->Line(176, 104, 198, 104);  // balance
            
            $this->setXY(20 , 120); 

			// payment schedule rows
			$pmtRow = 1;
			$row    = 104;
			$pmtSchedule = $d['paymentSchedule'];
			for($i = 0; $i <= count($pmtSchedule)-1; $i++)
			{
			    $p = $pmtSchedule[$i];
			    $schedPmtDate   = date("m/d/Y", strtotime($p['schedPmtDate']));
			    $schedPrincipal = $p['schedPmtPrincipal']+0;
			    $schedInterest  = $p['schPmtInterest']+0;
			    $schedAmtDue    = $p['schedPmtDue']+0;
			    $schedAmtBalance= $p['schedPmtBalance']+0;
			    $this->setXY( 20 , $row);  $this->Cell(12,0, $p['schedPmtRow'].'.', 0, 0, 'R'  );
			    $this->setXY( 44 , $row);  $this->Cell(30,0, $schedPmtDate, 0, 0, 'L'  );
			    if(is_numeric( $schedPrincipal )) {  $w = new NumWords( $schedPrincipal ); $v = $w->number; unset($w); } else { $v = ''; }
			    $this->setXY( 70 , $row);  $this->Cell(30,0, $v, 0, 0, 'R'  );
			    if(is_numeric( $schedInterest )) {  $w = new NumWords( $schedInterest ); $v = $w->number; unset($w); } else { $v = ''; }
			    $this->setXY(100 , $row);  $this->Cell(30,0, $v, 0, 0, 'R'  );
			    if(is_numeric( $schedAmtDue )) {  $w = new NumWords( $schedAmtDue ); $v = $w->number; unset($w); } else { $v = ''; }
			    $this->setXY(135 , $row);  $this->Cell(30,0, $v, 0, 0, 'R'  );
			    if(is_numeric( $schedAmtBalance )) {  $w = new NumWords( $schedAmtBalance ); $v = $w->number; unset($w); } else { $v = ''; }
			    $this->setXY(166 , $row);  $this->Cell(30,0, $v, 0, 0, 'R'  );
			    $row += 4;
			    $pmtRow++;
			}
			$this->setXY( 140, 252);  $this->Cell(60,0, $d['customerName'], 0, 0, 'C' );
		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords('WAIS, '.rptFORMSchedPmtTitle.' Report');
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setReportTitle(rptFORMSchedPmtTitle);
$pdf->setReportName(rptFORMSchedPmtName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size
$pdf->SetAutoPageBreak(TRUE, 2);
$pdf->setFooterMargin(2);
$pdf->AddPage('P', 'LETTER');
$d = $pdf->getCustomerSales($salesID);
$pdf->reportLayout($d);
$pdf->Output($pdf->getReportName().'.pdf', 'I');
?>
