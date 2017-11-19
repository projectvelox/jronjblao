<?php

//===================================================//
//=====  REPORT : CHATTEL WITH MORTGAGE         =====//
//===================================================//

require_once('../includes/tcppdf/tcpdf.php');
require_once("../includes/config.php");
require_once("class-numwords.php");
require_once("rpt-definitions.php");

ini_set('display_errors', 'Off');
function handleReportError() { if(error_get_last() != NULL)  { echo "Error generating report.  Please try again later.";  } }
register_shutdown_function('handleReportError');

// constants
$logoW		 = 26;
$logoH  	 = 16;

/*session_start();
$salesID	= 0;
if(isset($_SESSION['salesID'])) {  	$salesID  = $_SESSION['salesID']; 	unset($_SESSION['salesID']); }
if($salesID == 0) { $salesID == NULL; }
*/
$salesID = $_GET['id'];


class waisReport extends TCPDF 
{

	public $reportTitle;

	public function setCompanyName	($s = '') { $this->companyName	= $s; }	public function getCompanyName(){ return $this->companyName;	}
	public function setReportTitle	($s = '') { $this->reportTitle	= $s; }	public function getReportTitle(){ return $this->reportTitle;	}
	public function setReportName	($s = '') { $this->reportName	= $s; }	public function getReportName()	{ return $this->reportName;		}
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
	
	public function getChattel($salesID = NULL)
	{
		$chattel		= array();
		$item			= array();
		$isChattelFound = FALSE;
		if(!$salesID == NULL)
		{
			// get sales_main, customer
			$sql    =	" SELECT tabCHATTEL.*, tabSALES.*, ";
			$sql   .=	"	(SELECT CONCAT(c_firstname,' ', c_middlename,' ', c_lastname) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = tabSALES.s_customer_id ) AS customerName, ";
			$sql   .=	"	(SELECT value FROM options_country AS tabCOUNTRY WHERE tabCUSTOMER.c_address_country  = tabCOUNTRY.id ) AS customerCountry, ";
			$sql  .=	"   (SELECT SUM(s_sold_price * s_qty) FROM sales_sub       AS tabSUB     WHERE tabSUB.s_sales_id = tabSALES.s_id ) AS total_sales  ";
			$sql   .=	" FROM form_chattel AS tabCHATTEL ";
			$sql   .=	"	INNER JOIN (sales_main AS tabSALES ) ON tabSALES.s_id = tabCHATTEL.sales_id ";
			$sql   .=	"	INNER JOIN (customers AS tabCUSTOMER ) ON tabCUSTOMER.c_id = tabSALES.s_customer_id ";
			$sql  .=	" WHERE tabSALES.s_id =  $salesID ";
//			$rec = mysql_query($sql) or die('Query failed: ' . mysql_error());
			$rec = mysql_query($sql);
		    while ($sales = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		    	$isChattelFound = TRUE;
		    	// form_chattel table
		    	$chattel['cmDate']					= date("m/d/Y", strtotime($sales['chattelDate']));
		    	$chattel['cmDateExecuted']			= date("m/d/Y", strtotime($sales['chattelDate']));
		    	$chattel['cmDateSigned']			= date("m/d/Y", strtotime($sales['dateDocSign']));
		    	$chattel['cmSignedAddress'] 		= $sales['docSignAddress'];
		    	$chattel['cmBuyer'] 				= $sales['customerName'];
		    	$chattel['cmSeller']				= $sales['nameSeller'];
		    	$chattel['cmSpouse']				= $sales['nameSpouse'];
		    	$chattel['cmCoBuyer']				= $sales['nameCoBuyer'];
		    	$chattel['cmWitness1']				= $sales['nameWitness1'];
		    	$chattel['cmWitness2']				= $sales['nameWitness1'];
		    	$chattel['cmAffidavitDate'] 		= date("m/d/Y", strtotime($sales['ackDate']));
		    	$chattel['cmAffidavitAddress']		= $sales['ackPlace'];
		    	$chattel['cmAffidavitName1']		= $sales['ackResCertName1'];
		    	$chattel['cmAffidavitRCNo1']		= $sales['ackResCertNo1'];
		    	$chattel['cmAffidavitDateIssued1']	= date("m/d/Y", strtotime($sales['ackResCertDate1']));
		    	$chattel['cmAffidavitPlaceIssued1']	= $sales['ackResCertPlace1'];
		    	$chattel['cmAffidavitName2']		= $sales['ackResCertName2'];
		    	$chattel['cmAffidavitRCNo2']		= $sales['ackResCertNo2'];
		    	$chattel['cmAffidavitDateIssued2']	= date("m/d/Y", strtotime($sales['ackResCertDate2']));
		    	$chattel['cmAffidavitPlaceIssued2'] = $sales['ackResCertPlace2'];
		    	
		    	// sales table
		    	$chattel['cmDueBeginning']		= date("m/d/Y"	, strtotime($sales['s_firstmonthlydue_date']));   	// first monthly due
		    	$chattel['cmDueDay']			= date("d/"		, strtotime($sales['s_firstmonthlydue_date']));		// day of fist beginning monthly due
		    	$chattel['cmTerms'] 			= $sales['s_payment_term'] + 0;
		    	
		    	$chattel['salesTax'] 			= $sales['s_tax'] + 0;
		    	$chattel['salesOtherCharges']	= $sales['s_othercharges'] + 0;
		    	$chattel['salesDiscount']		= $sales['s_discount'] + 0;
		    	
		    	$chattel['cmMonthlyInstallment']= $sales['s_monthlyamortization'] + 0;
		    	$chattel['cmPromptPmtDiscount'] = $sales['s_discount'] + 0;
		    	$chattel['cmPurchaseCost']		= $sales['total_sales'] + $chattel['salesOtherCharges'] + $chattel['salesTax'] - $chattel['salesDiscount'] + 0;		// totals sales + tax + charges - discount
		    	$chattel['cmDownPayment']		= 0;								// total down payment from payments tables
		    	$chattel['cmBalance']			= 0;								// totals sales - downpayment
		    	$chattel['cmPromptPmtDiscount'] = $sales['s_rebate'];				// rebate for paying on-time
		    	$chattel['cmAmount']			=  0;								// balance
		    	break;
		    }
		    unset($rec);
		    if($isChattelFound == TRUE)
		    {
			    // downpayments
			    $sql = " SELECT SUM(p_amount) AS downPayment FROM payments WHERE p_sales_id = $salesID AND (p_is_downpayment = 1 OR p_is_downpayment = 2) ";
				$rec = mysql_query($sql);
			    while ($pmt = mysql_fetch_array($rec, MYSQL_ASSOC)) 
			    {
			    	$chattel['cmDownPayment'] = $pmt['downPayment'] + 0;
			    	break;
			    }
		    	$chattel['cmBalance'] = $chattel['cmPurchaseCost'] - $chattel['cmDownPayment'] + 0;
		    	$chattel['cmAmount']  = $chattel['cmBalance']+0;
		    	unset($rec);

			    // items and products
			    $sql  = " SELECT tabSUB.*, tabPROD.*,   ";
			    $sql .= "	(SELECT b_name FROM brands AS tabBRAND WHERE tabBRAND.b_id = tabPROD.p_brand ) AS prodBrand ";
			    $sql .= " FROM sales_sub AS tabSUB  ";
			    $sql .= "	JOIN (products AS tabPROD) ON tabPROD.p_id = tabSUB.s_product_id ";
			    $sql .= " WHERE tabSUB.s_sales_id = $salesID ";
				$rec = mysql_query($sql);
			    while ($prod = mysql_fetch_array($rec, MYSQL_ASSOC)) 
			    {
			    	$item[] = array(
			    				'itemBrand' 	=> $prod['prodBrand'],
			    				'itemName'		=> $prod['p_name'],
			    				'itemModel' 	=> $prod['p_code'],
			    				'itemChassisNo' => $prod['p_property_1'],
			    				'itemEngineNo'	=> $prod['p_property_2'],
			    				'itemQty'		=> $prod['s_qty'] + 0
			    				);
			    }
		    	$chattel['cmItem'] = $item;
		    	unset($rec);
			}
		}
		return $chattel;
	}


	public function reportLayout($data) 
	{
		$lineStyle = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$v   = 0;
		$this->setXY(0,0);
		if(is_array($data))
		{
		    // page 1
		    $cmDateDD = date("jS", strtotime($data['cmDate']));
		    $cmDateMM = date("F" , strtotime($data['cmDate']));
		    $cmDateYY = date("y" , strtotime($data['cmDate']));

			$this->setXY( 92 ,  26);  $this->Cell(0,0, $cmDateDD );
			$this->setXY(118 ,  26);  $this->Cell(0,0, $cmDateMM );
			$this->setXY(195 ,  26);  $this->Cell(0,0, $cmDateYY );

			$items = $data['cmItem'];
			$itemRow = 1;
			$row     = 60;
			for($i = 0; $i <= count($items)-1; $i++)
			{
			    $item = $items[$i];
    			$w    = new NumWords( $item['itemQty'] );
    			$itemQty = $w->number;
    			unset($w);
    			$itemQty = substr($itemQty, 0, strlen($itemQty)-3 );
			    $this->setXY( 11 , $row);  $this->Cell(12,0, $itemQty, 0, 0, 'R'  );
			    $this->setXY( 24 , $row);  $this->Cell(27,0, $item['itemBrand'], 0, 0, 'L'  );
			    $this->setXY( 52 , $row);  $this->Cell(37,0, $item['itemName'], 0, 0, 'L'  );
			    $this->setXY( 91 , $row);  $this->Cell(30,0, $item['itemModel'], 0, 0, 'L'  );
			    $this->setXY(122 , $row);  $this->Cell(40,0, $item['itemChassisNo'], 0, 0, 'L'  );
			    $this->setXY(164 , $row);  $this->Cell(40,0, $item['itemEngineNo'], 0, 0, 'L'  );
			    $row += 4;
			    $itemRow++;
			}

			$w = new NumWords( $data['cmPurchaseCost'] ); $v = $w->number; unset($w); 
			$this->setXY(  50 ,  88);  $this->Cell(26,0, $v, 0, 0, 'C'  );
			$w = new NumWords( $data['cmDownPayment'] ); $v = $w->number; unset($w); 
			$this->setXY(  50 , 94);  $this->Cell(26,0, $v, 0, 0, 'C'  );
			$w = new NumWords( $data['cmBalance'] ); $v = $w->number; unset($w); 
			$this->setXY(  50 , 100);  $this->Cell(26,0, $v, 0, 0, 'C'  );
			$this->setXY(150 , 88);  $this->Cell( 0,0, $data['cmTerms']);
			$w = new NumWords( $data['cmMonthlyInstallment'] ); $v = $w->number; unset($w); 
			$this->setXY( 164 , 92);  $this->Cell(26,0, $v, 0, 0, 'C'  );
			$v = date("d", strtotime($data['cmDueBeginning'])); 
			$this->setXY( 170 , 95);  $this->Cell(26,0, $v, 0, 0, 'C'  );
			$v = date("m/d/Y", strtotime($data['cmDueBeginning'])); 
			$this->setXY( 158 ,  98);  $this->Cell(26,0, $v, 0, 0, 'C'  );
			$w = new NumWords( $data['cmPromptPmtDiscount'] ); $v = $w->number; unset($w); 
			$this->setXY( 170 , 101);  $this->Cell(26,0, $v, 0, 0, 'C'  );

			$w = new NumWords( $data['cmAmount'] ); $cmExecAmtNumber = $w->number; $cmExecAmtWord = trim($w->words); unset($w);
			$this->setXY(126 , 125);  $this->Cell(0,0, $data['cmDateExecuted'] );
			$this->setXY( 16 , 130);  $this->Cell(0,0, $cmExecAmtWord );
			$this->setXY(120 , 130);  $this->Cell(0,0, '(P '.$cmExecAmtNumber.')' );
			
		    // page 2
            $this->SetAutoPageBreak(TRUE, 2);
            $this->setFooterMargin(2);
            $this->AddPage('P', 'LETTER');

		    $cmSignDateDD = date("jS", strtotime($data['cmDateSigned']));
		    $cmSignDateMM = date("F" , strtotime($data['cmDateSigned']));
		    $cmSignDateYY = date("y" , strtotime($data['cmDateSigned']));
			$this->setXY(118 , 63);  $this->Cell(0,0, $cmSignDateDD );
			$this->setXY(148 , 63);  $this->Cell(0,0, $cmSignDateMM );
			$this->setXY(198 , 63);  $this->Cell(0,0, $cmSignDateYY );
			$this->setXY( 16 , 66);  $this->Cell(0,0, $data['cmSignedAddress'] );

			$this->setXY( 15 ,  80);  $this->Cell(70,0, $data['cmBuyer'], 0, 0, 'C'  );
			$this->setXY(130 ,  80);  $this->Cell(38,0, $data['cmSeller'], 0, 0, 'C'  );
			$this->setXY( 15 ,  92);  $this->Cell(70,0, $data['cmSpouse'], 0, 0, 'C'  );
			$this->setXY(130 ,  92);  $this->Cell(38,0, $data['cmCoBuyer'], 0, 0, 'C'  );
			$this->setXY( 15 , 108);  $this->Cell(70,0, $data['cmWitness1'], 0, 0, 'C'  );
			$this->setXY(130 , 108);  $this->Cell(38,0, $data['cmWitness2'], 0, 0, 'C'  );

			$this->setXY( 15 , 136);  $this->Cell(70,0, $data['cmBuyer'], 0, 0, 'C'  );
			$this->setXY(130 , 136);  $this->Cell(38,0, $data['cmSeller'], 0, 0, 'C'  );
			$this->setXY( 15 , 152);  $this->Cell(70,0, $data['cmWitness1'], 0, 0, 'C'  );
			$this->setXY(130 , 152);  $this->Cell(38,0, $data['cmWitness2'], 0, 0, 'C'  );

		    $cmAffidavitDateDD = date("jS", strtotime($data['cmAffidavitDate']));
		    $cmAffidavitDateMM = date("F" , strtotime($data['cmAffidavitDate']));
		    $cmAffidavitDateYY = date("y" , strtotime($data['cmAffidavitDate']));
			$this->setXY( 35 , 180);  $this->Cell(0,0, $data['cmAffidavitAddress'] );
			$this->setXY(118 , 180);  $this->Cell(0,0, $cmAffidavitDateDD );
			$this->setXY(148 , 180);  $this->Cell(0,0, $cmAffidavitDateMM );
			$this->setXY(198 , 180);  $this->Cell(0,0, $cmAffidavitDateYY );

			$this->setXY( 12 , 198);  $this->Cell(0,0, $data['cmAffidavitName1'] );
			$this->setXY( 83 , 198);  $this->Cell(0,0, $data['cmAffidavitRCNo1'] );
			$this->setXY(123 , 198);  $this->Cell(0,0, $data['cmAffidavitDateIssued1'] );
			$this->setXY(160 , 198);  $this->Cell(0,0, $data['cmAffidavitPlaceIssued1'] );

			$this->setXY( 12 , 208);  $this->Cell(0,0, $data['cmAffidavitName2'] );
			$this->setXY( 83 , 208);  $this->Cell(0,0, $data['cmAffidavitRCNo2'] );
			$this->setXY(123 , 208);  $this->Cell(0,0, $data['cmAffidavitDateIssued2'] );
			$this->setXY(160 , 208);  $this->Cell(0,0, $data['cmAffidavitPlaceIssued2'] );

		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords('WAIS, Chattel with Mortgage Report');
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->setCompanyName(rptCompanyName);
$pdf->setReportTitle(rptFORMChattelTitle);
$pdf->setReportName(rptFORMChattelName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size

$pdf->SetAutoPageBreak(TRUE, 2);
$pdf->setFooterMargin(2);
  
$pdf->AddPage('P', 'LETTER');

$d = $pdf->getChattel($salesID);
$pdf->reportLayout( $d );
$pdf->Output($pdf->getReportName() . '.pdf', 'I');
?>