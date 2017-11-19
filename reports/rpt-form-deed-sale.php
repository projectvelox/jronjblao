<?php
//===================================================//
//=====  REPORT : DEED OF ABSOLUTE SALE         =====//
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
	public $reportTitle;
	public $reportName;
	public $logoFile;
	public $logoH;
	public $logoW;

	public function setReportTitle  ($S = '') { $this->reportTitle  = $S; }  public function getReportTitle() { return $this->reportTitle; }
	public function setReportName   ($s = '') { $this->reportName	= $s; }  public function getReportName()  { return $this->reportName;	}
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

	public function getDeedOfSale($salesID = NULL)
	{
		$deed = array();
		if(!$salesID == NULL)
		{
			$sql  = " SELECT tabDEED.*, tabSALES.s_ispaid, ";
			$sql .= "		(SELECT CONCAT(c_firstname,' ', c_middlename,' ', c_lastname) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = tabSALES.s_customer_id ) AS customerName, ";
			$sql .= "		(SELECT CONCAT(c_address_street,', ', c_address_town,', ', c_address_city, ' ', c_address_zipcode) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = tabSALES.s_customer_id ) AS customerAddress, ";
			$sql .= "		(SELECT value FROM options_country AS tabCOUNTRY WHERE tabCUSTOMER.c_address_country  = tabCOUNTRY.id ) AS customerCountry, ";
			$sql .= "		tabPROD.p_name, tabPROD.p_property_1, tabPROD.p_property_2, ";
			$sql .= "		(SELECT SUM(s_sold_price * s_qty) FROM sales_sub AS tabSUB WHERE tabSUB.s_sales_id = tabSALES.s_id ) AS salesTotal ";
			$sql .= " FROM form_deed AS tabDEED ";
			$sql .= "		INNER JOIN (sales_main AS tabSALES ) ON tabSALES.s_id = tabDEED.sales_id  ";
			$sql .= "		INNER JOIN (sales_sub AS tabSUB ) ON tabSUB.s_sales_id = tabSALES.s_id  ";
			$sql .= "		INNER JOIN (products AS tabPROD ) ON tabPROD.p_id = tabSUB.s_product_id  ";
			$sql .= "		INNER JOIN (customers AS tabCUSTOMER ) ON tabCUSTOMER.c_id = tabSALES.s_customer_id ";
			$sql .= " WHERE tabDEED.sales_id = $salesID";
			$rec = mysql_query($sql) ;
		    while ($w = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
			    $deed = array(
			        'customerName'      => $w['customerName'],
			        'customerAddress'   => $w['customerAddress'].' '. $w['customerCountry'],
			        'considerAmt'       => $w['salesTotal'],
			        'paidByName'        => $w['customerName'],
			        'paidByAddress'     => $w['customerAddress'].' '. $w['customerCountry'],
			        'itemDescription'   => $w['p_name'],
			        'motorMake'         => $w['p_name'],
			        'motorNo'           => $w['p_property_1'],
			        'motorChassis'      => $w['p_property_2'],
			        'motorFileNo'       => $w['deedFileNo'],
			        'motorPlateNo'      => $w['deedPlateNo'],
			        'motorCertReg'      => $w['deedCertReg'],
			        'motorCertDateIssued'=>$w['deedRegDateIssued'],
			        'motorORNo'         => $w['deedORNo'],
			        'motorORDateIssued' => $w['deedORDateIssued'],
			        'setHandDate'       => $w['deedDate'],
			        'vendorName'        => rptCompanyName, 
			        'nameWitness1'      => $w['nameWitness1'],
			        'nameWitness2'      => $w['nameWitness2'],
			        'appearDate'        => $w['dateDeedSign'],
			        'cedulaNO'          => $w['ackResCertNo'],
			        'cedulaIssuedAt'    => $w['ackResCertPlace'],
			        'cedulaIssuedDate'  => $w['ackResCertDate']
			        );
			        break;
		    }
		}
		return $deed;
	}
	
	public function reportLayout($data) 
	{
		$lineStyle = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->setXY(0,0);
		if(is_array($data))
		{
			$w = new NumWords( $data['considerAmt'] );
			$amtNumber = $w->number;
			$amtWords  = trim($w->words);
			unset($w);
			
			$setHandDD = date("jS", strtotime($data['setHandDate']));
			$setHandMM = date("F" , strtotime($data['setHandDate']));
			$setHandYY = date("y" , strtotime($data['setHandDate']));

			$appearDD = date("jS", strtotime($data['appearDate']));
			$appearMM = date("F" , strtotime($data['appearDate']));
			$appearYY = date("y" , strtotime($data['appearDate']));

			$cedulaMM = date("F j,", strtotime($data['appearDate']));
			$cedulaYY = date("y"   , strtotime($data['appearDate']));

			$itemDescLn1 = '';
			$itemDescLn2 = '';
			if(strlen(trim($data['itemDescription'])) >= 37)
			{
				$itemDescLn1 = substr(trim($data['itemDescription']), 0, 37);
				$itemDescLn2 = substr(trim($data['itemDescription']),37, strlen(trim($data['itemDescription'])));
			}
			else { $itemDescLn1 = ''; $itemDescLn2 = $data['itemDescription']; }


			$this->setXY( 38,  26);  $this->Cell(0,0, $data['customerName'] );
			$this->setXY( 40,  32);  $this->Cell(0,0, $data['customerAddress'] );

			$this->setXY( 32,  38);  $this->Cell(0,0, $amtWords );
			$this->setXY(140,  38);  $this->Cell(0,0, $amtNumber );

			$this->setXY( 80,  43);  $this->Cell(0,0, $data['paidByName'] );
			$this->setXY( 40,  49);  $this->Cell(0,0, $data['paidByAddress'] );

			$this->setXY(138,  54);  $this->Cell(0,0, $itemDescLn1 );
			$this->setXY( 18,  59);  $this->Cell(0,0, $itemDescLn2 );
		    
			$this->setXY( 94,  77);  $this->Cell(0,0, $data['motorMake'] );
			$this->setXY( 94,  82);  $this->Cell(0,0, $data['motorNo'] );
			$this->setXY( 94,  88);  $this->Cell(0,0, $data['motorChassis'] );
			$this->setXY( 94,  94);  $this->Cell(0,0, $data['motorFileNo'] );
			$this->setXY( 94,  99);  $this->Cell(0,0, $data['motorPlateNo'] );
			$this->setXY( 94, 104);  $this->Cell(0,0, $data['motorCertReg'] );
			$this->setXY( 94, 110);  $this->Cell(0,0, date("m/d/Y",strtotime($data['motorCertDateIssued'] )));
			$this->setXY( 94, 116);  $this->Cell(0,0, $data['motorORNo'] );
			$this->setXY( 94, 121);  $this->Cell(0,0, date("m/d/Y",strtotime($data['motorORDateIssued'] )));

			$this->setXY( 18, 140);  $this->Cell(0,0, $data['itemDescription'] );

			$this->setXY(150, 158);  $this->Cell(0,0, $setHandDD );
			$this->setXY( 24, 163);  $this->Cell(0,0, $setHandMM );
			$this->setXY( 80, 163);  $this->Cell(0,0, $setHandYY );

			$this->setXY(132, 180);  $this->Cell(60,0, $data['vendorName'], 0, 0, 'C' );

			$this->setXY( 35, 207);  $this->Cell(60,0, $data['nameWitness1'], 0, 0, 'C' );
			$this->setXY(125, 207);  $this->Cell(60,0, $data['nameWitness2'], 0, 0, 'C' );
			
			$this->setXY( 75, 246);  $this->Cell(0,0, $appearDD );
			$this->setXY(125, 246);  $this->Cell(0,0, $appearMM );
			$this->setXY(191, 246);  $this->Cell(0,0, $appearYY );

			$this->setXY(118, 252);  $this->Cell(0,0, $data['customerName'] );
			
			$this->setXY( 28, 256);  $this->Cell(0,0, $data['cedulaNO'] );
			$this->setXY(115, 256);  $this->Cell(0,0, $data['cedulaIssuedAt'] );

			$this->setXY( 25, 262);  $this->Cell(0,0, $cedulaMM );
			$this->setXY( 90, 262);  $this->Cell(0,0, $cedulaYY );

		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords("WAIS, ".rptFORMDeedSaleTitle." Report");
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setReportTitle(rptFORMDeedSaleTitle);
$pdf->setReportName(rptFORMDeedSaleName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size
$pdf->SetAutoPageBreak(TRUE, 2);
$pdf->setFooterMargin(2);
$pdf->AddPage('P', 'LETTER');
$d = $pdf->getDeedOfSale($salesID);
$pdf->reportLayout($d);
$pdf->Output($pdf->getReportName().'.pdf', 'I');
?>
