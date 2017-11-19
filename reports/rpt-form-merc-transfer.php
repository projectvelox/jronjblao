<?php
//===================================================//
//=====  REPORT : MERCHANDISE TRANSFER          =====//
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

/*session_start();
$transferID	= 0;
if(isset($_SESSION['transferID'])) {  $transferID  = $_SESSION['transferID'];  unset($_SESSION['transferID']); }
if($transferID == 0) { $transferID == NULL; }
*/

$transferID = $_GET['id'];

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
	
	public function getMerchandiseTransfer($transferID = NULL)
	{
		$xfer = array();
		$items= array();
		$sql = '';
		$hasXfer = FALSE;
		$concatOrderLFM = "e_lastname,', ', e_firstname, ' ', e_middlename";
		$concatOrderFML = "e_firstame,' ', e_middlename, ' ', e_lastname";
		if(!$transferID == NULL)
		{
			$sql  = " SELECT tabXFER.*, tabMTF.*, ";
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabXFER.t_requestby_id	 ) AS requestedBy, ";
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabXFER.t_verifiedby_id	 ) AS verifiedBy,  ";
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabXFER.t_approvedby_id	 ) AS approvedBy,  ";
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabXFER.t_intransitby_id	 ) AS inTransitBy, ";
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabXFER.t_receivedby_id	 ) AS receivedBy,  ";
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabXFER.t_cancelby_id	 ) AS cancelBy,    ";
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabMTF.xferPreparedBy_ID	 ) AS preparedBy,   ";
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabMTF.xferDeliveredBy_ID ) AS deliveredBy,  ";
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabMTF.xferCheckedBy_ID	 ) AS checkedBy,    "; 
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabMTF.rcvdCheckedBy_ID	 ) AS rcvdCheckedBy,";   
			$sql .= "	(SELECT CONCAT($concatOrderLFM) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabMTF.rcvdNotedBy_ID	 ) AS rcvdNotedBy,  "; 
			$sql .= "	(SELECT b_name FROM branches AS tabBRANCH WHERE tabBRANCH.b_id = tabXFER.t_branch_origin_id		)  AS branchOrigin, 	";
			$sql .= "	(SELECT b_name FROM branches AS tabBRANCH WHERE tabBRANCH.b_id = tabXFER.t_branch_destination_id)  AS branchDestination ";
			$sql .= " FROM stocktransfer_main AS tabXFER  ";
			$sql .= "	JOIN (form_mtf as tabMTF) ON tabMTF.t_id = tabXFER.t_id ";
			$sql .= " WHERE tabXFER.t_id = $transferID ";
			$rec = mysql_query($sql) ;
		    while ($w = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		    	$order = ''; $display = ''; $other = ''; $txt = '';
		    	switch($w['xferPurpose'])
		    	 {
		    	 	case 'C':
		    	 		$order = 'Y';
		    	 		break;
		    	 	case 'D':
		    	 		$display = 'Y';
		    	 		break;
		    	 	case 'O':
		    	 		$other = 'Y';
		    	 		$txt   = $w['xferPurpose_other'];
		    	 		break;
		    	 }
				$v = sprintf("%0". lengthInvoiceNumber ."d", $w['t_id']);
			    $xfer = array(
			        'mtfNo'             => $v,
			        'mtfDate'           => date("m/d/Y",strtotime($w['t_request_date'])),
			        'mtfOrigin'         => $w['branchOrigin'],
			        'mtfDestination'    => $w['branchDestination'],
			        
			        // sender
			        'mtfPreparedBy'     => $w['preparedBy'],
			        'mtfDatePrepared'   => date("m/d/Y",strtotime($w['xferPrepared_Date'])),	
			        'mtfRequestedBy'    => $w['requestedBy'],
			        'mtfDateRequested'  => date("m/d/Y",strtotime($w['t_request_date'])),
			        'mtfDeliveredBy'    => $w['deliveredBy'],
			        'mtfDateDelivered'  => date("m/d/Y",strtotime($w['xferDelivered_Date'])),
			        'mtfCheckedBy'      => $w['checkedBy'],
			        'mtfDateChecked'    => date("m/d/Y",strtotime($w['xferChecked_Date'])),
			        'mtfApprovedBy'     => $w['approvedBy'],
			        'mtfDateApproved'   => date("m/d/Y",strtotime($w['t_approved_date'])),
			        'mtfRequestedPurposeOrder'  => $order,
			        'mtfRequestedPurposeDisplay'=> $display,
			        'mtfRequestedPurposeOther'  => $txt,

			        // receiver
			        'mtfRcvCheckedBy'   => $w['rcvdCheckedBy'],
			        'mtfRcvDateChecked' => date("m/d/Y",strtotime($w['rcvdChecked_Date'])),
			        'mtfRcvReceiveBy'   => $w['receivedBy'],
			        'mtfRcvDateReceived'=> date("m/d/Y",strtotime($w['t_received_date'])),
			        'mtfRcvNotedBy'     => $w['rcvdNotedBy'],
			        'mtfRcvDateNoted'   => date("m/d/Y",strtotime($w['rcvdNoted_Date'])),
			        'mtfMerchandise'    => NULL
			        );
		    	$hasXfer = TRUE;
		    	break;
		    }
			unset($rec);
			if($hasXfer == TRUE)
			{
				$sql  = " SELECT tabXFER.*, ";
				$sql .= "	(SELECT p_product_id	FROM inventory		AS tabINV	WHERE tabINV.p_id  = tabXFER.p_id) AS productID,	";
				$sql .= "	(SELECT p_purchase_cost FROM inventory		AS tabINV	WHERE tabINV.p_id  = tabXFER.p_id) AS purchaseCost, ";
				$sql .= "	(SELECT p_code			FROM products		AS tabPROD	WHERE tabPROD.p_id = productID	 ) AS prodCode, 	";
				$sql .= "	(SELECT p_property_1	FROM products		AS tabPROD	WHERE tabPROD.p_id = productID	 ) AS engineNo, 	";
				$sql .= "	(SELECT p_property_2	FROM products		AS tabPROD	WHERE tabPROD.p_id = productID	 ) AS chassisNo,	";
				$sql .= "	(SELECT p_property_7	FROM products		AS tabPROD	WHERE tabPROD.p_id = productID	 ) AS unitMeasure,	";
				$sql .= "	(SELECT p_color 		FROM products		AS tabPROD	WHERE tabPROD.p_id = productID	 ) AS colorID,		";
				$sql .= "	(SELECT color_name		FROM options_colors AS tabCOLOR WHERE tabCOLOR.color_id = colorID) AS colorName 	";
				$sql .= "	FROM stocktransfer_sub	AS tabXFER ";
				$sql .= "	WHERE tabXFER.t_id = $transferID ";
				$rec = mysql_query($sql) ;
			    while ($w = mysql_fetch_array($rec, MYSQL_ASSOC)) 
			    {
    	    		$items[]   = array( 
	    	    						'mtfCost'		=> $w['purchaseCost'],
	    	    						'mtfQty'		=> $w['p_qty'],
	    	    						'mtfUnitMeasure'=> $w['unitMeasure'], 
	    	    						'mtfModel'		=> $w['prodCode'],
	    	    						'mtfMCEngineNo' => $w['engineNo'],
	    	    						'mtfChassisNo'  => $w['chassisNo'],
	    	    						'mtfColor'		=> $w['colorName'],
	    	    						'mtfRemark' 	=> ''
    	    						);
			    }
			    $xfer['mtfMerchandise'] = $items;
				unset($rec);
			}
		}
		return $xfer;
	}
	
	public function reportLayout($data) 
	{
		$lineStyle = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->setXY(0,0);
		if(is_array($data))
		{
			$this->setXY( 32 ,  32);  $this->Cell(0,0, $data['mtfDate'] );
			$this->setXY(165 ,  32);  $this->Cell(0,0, $data['mtfNo'] );
			$this->setXY( 34 ,  53);  $this->Cell(0,0, $data['mtfOrigin'] );
			$this->setXY(162 ,  53);  $this->Cell(0,0, $data['mtfDestination'] );
			$mercRow = 1;
			$row     = 84;
			$mercXfer = $data['mtfMerchandise'];
			for($i = 0; $i <= count($mercXfer)-1; $i++)
			{
			    $merc = $mercXfer[$i];
				if(is_numeric($merc['mtfCost'])) { $w = new NumWords( $merc['mtfCost'] ); $v = 'P ' . $w->number; unset($w); } else { $v = ''; }
			    $this->setXY( 17 , $row);  $this->Cell(15,0, $v, 0, 0, 'R'  );
			    $this->setXY( 34 , $row);  $this->Cell(12,0, $merc['mtfQty'], 0, 0, 'R'  );
			    $this->setXY( 48 , $row);  $this->Cell(18,0, $merc['mtfUnitMeasure'], 0, 0, 'L'  );
			    $this->setXY( 66 , $row);  $this->Cell(19,0, $merc['mtfModel'], 0, 0, 'L'  );
			    $this->setXY( 86 , $row);  $this->Cell(34,0, $merc['mtfMCEngineNo'], 0, 0, 'L'  );
			    $this->setXY(122 , $row);  $this->Cell(30,0, $merc['mtfChassisNo'], 0, 0, 'L'  );
			    $this->setXY(158 , $row);  $this->Cell(14,0, $merc['mtfColor'], 0, 0, 'L'  );
			    $this->setXY(175 , $row);  $this->Cell(30,0, $merc['mtfRemark'], 0, 0, 'L'  );
			    $row += 4;
			    $mercRow++;
			}
			$this->setXY( 20 , 177);  $this->Cell(35,0,  $data['mtfPreparedBy'], 0, 0, 'C' );
			$this->setXY( 58 , 177);  $this->Cell(35,0,  $data['mtfRequestedBy'], 0, 0, 'C' );
			$this->setXY(114 , 177);  $this->Cell(35,0,  $data['mtfDeliveredBy'], 0, 0, 'C' );
			$this->setXY(158 , 177);  $this->Cell(35,0,  $data['mtfCheckedBy'], 0, 0, 'C' );
			
			$this->setXY( 33 , 186);  $this->Cell(0,0, $data['mtfDatePrepared'] );
			$this->setXY( 72 , 186);  $this->Cell(0,0, $data['mtfDateRequested'] );
			$this->setXY(128 , 186);  $this->Cell(0,0, $data['mtfDateDelivered'] );
			$this->setXY(172 , 186);  $this->Cell(0,0, $data['mtfDateChecked'] );
			if($data['mtfRequestedPurposeOrder'] == 'Y')
			{  $this->setXY(106 , 198);  $this->Cell(0,0, 'XXX' );  }
			if($data['mtfRequestedPurposeDisplay'] == 'Y')
			{  $this->setXY(106 , 202);  $this->Cell(0,0, 'XXX' );  }
			if(!(trim($data['mtfRequestedPurposeOther'])) == '')
			{  $this->setXY(76 , 207);  $this->Cell(0,0, $data['mtfRequestedPurposeOther'] );  }

			$this->setXY( 48 , 213);  $this->Cell(0,0, $data['mtfApprovedBy'] );
			$this->setXY(137 , 213);  $this->Cell(0,0, $data['mtfDateApproved'] );
			
			$this->setXY( 22 , 252);  $this->Cell(0,0, $data['mtfRcvCheckedBy'] );
			$this->setXY( 86 , 252);  $this->Cell(0,0, $data['mtfRcvReceiveBy'] );
			$this->setXY(148 , 252);  $this->Cell(0,0, $data['mtfRcvNotedBy'] );

			$this->setXY( 32 , 258);  $this->Cell(0,0, $data['mtfRcvDateChecked'] );
			$this->setXY( 96 , 258);  $this->Cell(0,0, $data['mtfRcvDateReceived'] );
			$this->setXY(158 , 258);  $this->Cell(0,0, $data['mtfRcvDateNoted'] );
		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords('WAIS, '.rptFORMMercXFerName.' Report');
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setReportTitle(rptFORMMercXFerTitle);
$pdf->setReportName(rptFORMMercXFerName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size
$pdf->SetAutoPageBreak(TRUE, 2);
$pdf->setFooterMargin(2);
$pdf->AddPage('P', 'LETTER');
$d = $pdf->getMerchandiseTransfer($transferID);
$pdf->reportLayout($d);
$pdf->Output($pdf->getReportName().'.pdf', 'D');
?>
