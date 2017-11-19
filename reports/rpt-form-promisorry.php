<?php
//===================================================//
//=====  REPORT : PROMISORRY NOTE               =====//
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

	public function getPromissoryNote($salesID = NULL)
	{
		$prom = array();
		if(!$salesID == NULL)
		{
			$sql  = " SELECT tabPROM.*, tabSALES.*, ";
			$sql .= "(SELECT s_customer_id FROM sales_main AS tabSALES WHERE tabSALES.s_id  = tabPROM.sales_id ) as customerID, ";
			$sql .= "(SELECT c_address_country FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = customerID ) AS countryID, ";
			$sql .= "(SELECT CONCAT(c_firstname,' ', c_middlename,' ', c_lastname) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = customerID ) AS customerName, ";
			$sql .= "(SELECT CONCAT(c_address_street,', ', c_address_town,', ', c_address_city, ' ', c_address_zipcode) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = customerID ) AS customerAddress, ";
			$sql .= "(SELECT value FROM options_country AS tabCOUNTRY WHERE tabCOUNTRY.id = countryID ) AS customerCountry, ";
			$sql .= "(SELECT SUM(s_sold_price * s_qty) FROM sales_sub AS tabSUB WHERE tabSUB.s_sales_id = tabPROM.sales_id ) AS salesTotal, ";
			$sql .= "(SELECT p_name FROM products AS tabPROD WHERE tabPROD.p_id  = tabSUB.s_product_id ) AS productName, ";
			$sql .= "(SELECT p_property_1 FROM products AS tabPROD WHERE tabPROD.p_id  = tabSUB.s_product_id ) AS engineNo, ";
			$sql .= "(SELECT p_property_2 FROM products AS tabPROD WHERE tabPROD.p_id  = tabSUB.s_product_id ) AS chassisNo ";
			$sql .= "FROM form_promnote AS tabPROM ";
			$sql .= "JOIN (sales_main AS tabSALES) ON tabSALES.s_id = tabPROM.sales_id ";
			$sql .= "INNER JOIN (sales_sub AS tabSUB ) ON tabSUB.s_sales_id = tabPROM.sales_id ";
			$sql .= "WHERE tabPROM.sales_id = $salesID ";	
			$rec = mysql_query($sql);
		    while ($w = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
			    $prom = array(
			        'promiseDate'            => $w['noteDate'],
			        'promiseAmtNum'          => $w['salesTotal'] + $w['s_othercharges'] + $w['s_tax'] - $w['s_discount'],
			        'monthlyDue'             => $w['s_monthlyamortization'],
			        'monthsToPay'            => $w['s_payment_term'],
			        'monthStartToPay'        => $w['s_firstmonthlydue_date'],
			        'dueEveryNthDay'         => date("jS", strtotime($w['s_firstmonthlydue_date'])),
			        'promiseMakerName'       => $w['customerName'],
			        'promiseMakerAddress'    => $w['customerAddress'].' '. $w['customerCountry'],
			        'promiseCoMakerName'     => $w['coMakerName'],
			        'promiseCoMakerAddress'  => $w['coMakerAddress'],
			        'promiseWitness1'        => $w['witnessName1'],
			        'promiseWitness2'        => $w['witnessName1']
			        );
		    	break;
		    }
		    unset($rec);
		}
		return $prom;
	}
	
	public function reportLayout($data) 
	{
		$lineStyle = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->setXY(0,0);
		if(is_array($data))
		{
			$promiseDateMD = date("F j", strtotime($data['promiseDate']));
			$promiseDateYY = date("y"  , strtotime($data['promiseDate']));
			$w = new NumWords( $data['promiseAmtNum'] );
			$amtNumber   = $w->number;
			$amtWordsLn1 = trim($w->words);
			$amtWordsLn2 = '';
			if(strlen(trim($w->words)) >= 52)
			{
				$amtWordsLn1 = substr(trim($w->words), 0, 52);
				$amtWordsLn2 = substr(trim($w->words),52, strlen(trim($w->words)));
			}
			unset($w);
			
			$w = new NumWords( $data['monthlyDue'] ); $monthlyDue = $w->number; unset($w);
			$this->setXY(152 ,  22);  $this->Cell(0,0, $promiseDateMD );
			$this->setXY(190 ,  22);  $this->Cell(0,0, $promiseDateYY );
			
			$this->setXY(105 ,  33);  $this->Cell(0,0, $amtWordsLn1 );
			
			$this->setXY( 18 ,  37);  $this->Cell(0,0, $amtWordsLn2 );
			$this->setXY(124 ,  37);  $this->Cell(0,0, $amtNumber );

			$startDateMD = date("F j", strtotime($data['monthStartToPay']));
			$startDateYY = date("y"  , strtotime($data['monthStartToPay']));
			
			$this->setXY( 36 ,  45);  $this->Cell(0,0, $monthlyDue );
			$this->setXY( 86 ,  45);  $this->Cell(0,0, $data['monthsToPay'] );
			$this->setXY(158 ,  45);  $this->Cell(0,0, $startDateMD );

			$this->setXY( 38 ,  49);  $this->Cell(0,0, $startDateYY );
			$this->setXY(105 ,  49);  $this->Cell(0,0, $amtNumber );

			$this->setXY( 45 ,  53);  $this->Cell(0,0, $data['dueEveryNthDay'] );

			$this->setXY( 20 , 212);  $this->Cell(70,0, $data['promiseMakerName'], 0, 0, 'C' );
//			$this->setXY( 20 , 221);  $this->Cell(70,0, $data['promiseMakerDate'], 0, 0, 'C' );
			$this->setXY( 20 , 230);  $this->Cell(18,0, $data['promiseMakerAddress'] );

			$this->setXY(115 , 212);  $this->Cell(70,0, $data['promiseCoMakerName'], 0, 0, 'C' );
//			$this->setXY(115 , 221);  $this->Cell(70,0, $data['promiseCoMakerDate'], 0, 0, 'C' );
			$this->setXY(115 , 230);  $this->Cell(18,0, $data['promiseCoMakerAddress'] );

			$this->setXY( 20 , 253);  $this->Cell(70,0, $data['promiseWitness1'], 0, 0, 'C' );
			$this->setXY(115 , 253);  $this->Cell(70,0, $data['promiseWitness2'], 0, 0, 'C' );
		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords("WAIS, ".rptFORMPromNoteTitle." Report");
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setReportTitle(rptFORMPromNoteTitle);
$pdf->setReportName(rptFORMPromNoteName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size
$pdf->SetAutoPageBreak(TRUE, 2);
$pdf->setFooterMargin(2);
$pdf->AddPage('P', 'LETTER');
$d = $pdf->getPromissoryNote($salesID);
$pdf->reportLayout($d);
$pdf->Output($pdf->getReportName().'.pdf', 'I');
?>
