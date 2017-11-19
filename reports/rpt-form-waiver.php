<?php
//===================================================//
//=====  REPORT : WAIVER                        =====//
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
	
	public function getCustomerWaiver($salesID = NULL)
	{
		$waive = array();
		$sql   = '';
		if(!$salesID == NULL)
		{
			$sql  = " SELECT tabWAIVE.*, tabSALES.s_ispaid, ";
			$sql .= "		(SELECT CONCAT(c_firstname,' ', c_middlename,' ', c_lastname) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = tabSALES.s_customer_id ) AS customerName, ";
			$sql .= "		(SELECT CONCAT(c_address_street,', ', c_address_town,', ', c_address_city, ' ', c_address_zipcode) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = tabSALES.s_customer_id ) AS customerAddress, ";
			$sql .= "		(SELECT value FROM options_country AS tabCOUNTRY WHERE tabCUSTOMER.c_address_country  = tabCOUNTRY.id ) AS customerCountry, ";
			$sql .= "		tabPROD.p_name, tabPROD.p_property_1, tabPROD.p_property_2 ";
			$sql .= " FROM form_waiver AS tabWAIVE ";
			$sql .= "		INNER JOIN (sales_main AS tabSALES ) ON tabSALES.s_id = tabWAIVE.sales_id  ";
			$sql .= "		INNER JOIN (sales_sub AS tabSUB ) ON tabSUB.s_sales_id = tabSALES.s_id  ";
			$sql .= "		INNER JOIN (products AS tabPROD ) ON tabPROD.p_id = tabSUB.s_product_id  ";
			$sql .= "		INNER JOIN (customers AS tabCUSTOMER ) ON tabCUSTOMER.c_id = tabSALES.s_customer_id ";
			$sql .= " WHERE tabWAIVE.sales_id = $salesID";
			$rec = mysql_query($sql) ;
		    while ($w = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
			    $waive = array(
			        'customerName'       => $w['customerName'],
			        'customerAddress'    => $w['customerAddress'].' '.$w['customerCountry'],
			        'itemName'           => $w['p_name'],
			        'itemMotorNo'        => $w['p_property_1'],
			        'itemMotorChassisNo' => $w['p_property_2'],
			        'isPaidCash'         => $w['s_ispaid'],
			        'affiantSayeth'      => $w['sayethNaught'],
			        'witnessName'       => $w['witnessName'],
			        'witnessAddress'    => $w['witnessAddress']
			        );
			        break;
		    }
		    unset($rec);
		}
		return $waive;
	}
	
	public function reportLayout($data) 
	{
		$this->setXY(0,0);
		if(is_array($data))
		{
			$addrStrLine1   = $data['customerAddress'];
			$addrStrLine2   = '';
			$this->setXY( 84 ,  51);  $this->Cell(0,0, $data['customerName'] );
			if(strlen(trim($data['customerAddress'])) >= 60 )
			{
			    $addrStrLine1 = substr(trim($data['customerAddress']), 0, 60);
			    $addrStrLine2 = substr(trim($data['customerAddress']),60, strlen(trim($data['customerAddress'])));
			}
			$this->setXY(104 ,  56);  $this->cell(0,0, $addrStrLine1);
			if(trim($addrStrLine2)!= '')
			{  $this->setXY(14 ,  62);  $this->cell(0, 0, $addrStrLine2);  }
			$this->setXY( 40 ,  84);  $this->Cell(0,0, $data['itemName'] );
			$this->setXY(144 ,  84);  $this->Cell(0,0, $data['itemMotorNo'] );
			$this->setXY( 70 ,  89);  $this->Cell(0,0, $data['itemMotorChassisNo'] );
			if($data['isPaidCash'] == 'Y')
			{ $this->setXY(182 , 89);  $this->Cell(0,0, 'XXXXXXXXX' ); }           // paid in cash, crash-out 'installment'
			else
			{ $this->setXY(172 , 89);  $this->Cell(0,0, 'XXXX' ); }                // installment, crash-out 'cash'
			$this->setXY( 14 , 155);  
			$this->MultiCell(180, 0, $data['affiantSayeth']);

			$this->setXY(150 , 182);  $this->Cell(0,0, $data['customerName'] );
			
			$this->setXY( 14 , 207);  $this->Cell(0,0, $data['witnessName'] );
			$this->setXY( 14 , 213);  $this->Cell(0,0, $data['witnessAddress'] );
		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords('WAIS, Waiver Report');
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setReportTitle(rptFORMWaiverTitle);
$pdf->setReportName(rptFORMWaiverName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size
$pdf->SetLineWidth(0.1);
$pdf->AddPage('P', 'LETTER');
$d = $pdf->getCustomerWaiver($salesID);
$pdf->reportLayout($d);
$pdf->Output($pdf->getReportName().'.pdf', 'I');
?>
