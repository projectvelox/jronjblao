<?php
//========================================================================//
//=====  REPORT : COLLECTIONS                                        =====//
//========================================================================//

require_once('../includes/tcppdf/tcpdf.php');
require_once("../includes/config.php");
require_once("class-numwords.php");
require_once("rpt-definitions.php");

ini_set('display_errors', 'Off');
function handleReportError() { if(error_get_last() != NULL)  { echo "Error generating report.  Please try again later.";  } }
register_shutdown_function('handleReportError');

$logoW		 = 26;
$logoH  	 = 16;

//==  get report requirements from session and reset it when done
session_start();
$dateStart  = '';
$dateEnd    = '';
$branchID   = 0;

if(isset($_SESSION['dateStart']))   { $dateStart= $_SESSION['dateStart']; 	unset($_SESSION['dateStart']);   } else { $dateStart = NULL; }
if(isset($_SESSION['dateEnd']))     { $dateEnd  = $_SESSION['dateEnd']; 	unset($_SESSION['dateEnd']);     } else { $dateEnd   = NULL; }
if(isset($_SESSION['branchID']))    { $branchID = $_SESSION['branchID'] ;   unset($_SESSION['branchID']);    } else { $branchID  = NULL; }


if($dateStart == NULL || $dateStart == '' || date("Y-m-d", strtotime($dateStart)) == '1970-07-01') { $dateStart = date("Y-m-d"); } else { $dateStart = date("Y-m-d", strtotime($dateStart)); } 
if($dateEnd   == NULL || $dateEnd   == '' || date("Y-m-d", strtotime($dateEnd)  ) == '1970-07-01') { $dateEnd   = date("Y-m-d"); } else { $dateEnd   = date("Y-m-d", strtotime($dateEnd)); } 

/*
// switch to monthly report if dates are not the same
if($dateStart <> $dateEnd)
{
    $temp = '';
    $temp = date("Y-m-d", strtotime(date("Y", strtotime($dateStart)).'-'.date("m", strtotime($dateStart)).'-01') );
    $dateStart= $temp;
    $temp   = date("Y-m-d", strtotime(date("Y-m-d", strtotime($dateStart) ) . " +1 month"));
    $temp   = date("Y-m-d", strtotime(date("Y-m-d", strtotime($temp) ) . " -1 day"));
    $dateEnd  = $temp;
    unset($temp);
} else
{ 
	// starting and ending date are equal report should be daily 
}
*/

class waisReport extends TCPDF 
{
	public $companyName;
	public $reportTitle;
	public $reportName;
	public $logoFile;
	public $logoH;
	public $logoW;
	public $branch;
	public $startRptDate;
	public $endRptDate;
	public $fontHeight;
	
	public function setCompanyName	($s = '') { $this->companyName	= $s; }	public function getCompanyName(){ return $this->companyName;	}
	public function setReportTitle	($s = '') { $this->reportTitle	= $s; }	public function getReportTitle(){ return $this->reportTitle;	}
	public function setReportName	($s = '') { $this->reportName	= $s; }	public function getReportName()	{ return $this->reportName;		}
	public function setLogoFile		($s = '') { $this->logoFile 	= $s; }	public function getLogoFile()	{ return $this->logoFile;		}
	public function setLogoWidth	($i =  8) { $this->logoW		= $i; }	public function getLogoWidth()	{ return $this->logoW;			}
	public function setLogoHeight	($i =  4) { $this->logoH		= $i; }	public function getLogoHeight()	{ return $this->logoH;			}
	public function setFontHeight	($i =  4) { $this->fontHeight	= $i; }	public function getFontHeight()	{ return $this->fontHeight;		}
	public function setBranch		($s = '') { $this->branch		= $s; }	public function getBranch()		{ return $this->getBranchName($this->branch);       }
	public function setStartingDate	($s = '') { $this->startRptDate	= $s; }	public function getStartingDate() { return strtoupper(date("M j, Y", strtotime($this->startRptDate))); }
	public function setEndingDate	($s = '') { $this->endRptDate	= $s; }	public function getEndingDate()	  { return strtoupper(date("M j, Y", strtotime($this->endRptDate))); }
	
	private function getBranchName($id = NULL)
	{
	    $r   = '';
	    $sql = '';
	    if(!$id == NULL)
	    {
	        $sql = " select b_name FROM branches WHERE b_id = $id ";
			$rec = mysql_query($sql) or die('Query failed: ' . mysql_error());
		    while ($branch = mysql_fetch_array($rec, MYSQL_ASSOC)) { $r = $branch['b_name']; break; }
		    unset($rec);	        
	    }
	    unset($sql);
	    return $r;
	}

    // report header
	public function Header() 
	{
		// logo
		$pageWidth   = $this->getPageWidth() - PDF_MARGIN_LEFT + 5;
		$this->Image( $this->getLogoFile(), $this->getX(), $this->getY() - 4, $this->getLogoWidth(), $this->getLogoHeight(), '', '', '', true);
		//company name
		$this->SetFont('helvetica', 'B', 14);
		$this->setXY(PDF_MARGIN_LEFT+$this->getLogoWidth() - 4, $this->getY() - 4);
		$this->Cell(0,0, $this->getCompanyName() );
		// report title
		
		$t = $this->getReportTitle();
		if($this->getStartingDate() == $this->getEndingDate()) 
		{ $t = 'DAILY '  .$t; $s = "     AS OF " . $this->getStartingDate();   }
		else
		{ $t = 'MONTHLY '.$t; $s = "     AS OF " . $this->getStartingDate() . " TO ". $this->getEndingDate(); }

		$this->SetFont('helvetica', '', 12);
		$this->setXY(PDF_MARGIN_LEFT+$this->getLogoWidth() - 4, $this->getY() + 6);
		$this->Cell(0,0, $t . $s );
		
		// branch
		$s = "BRANCH : " . $this->getBranch();
		$this->SetFont('helvetica', '', 10);
		$this->setXY(PDF_MARGIN_LEFT+$this->getLogoWidth() - 4, $this->getY() + 6);
		$this->Cell(0,0, $s );

		// line separator
		$this->setXY(PDF_MARGIN_LEFT-5, $this->getY() + 6); 
		$this->Line($this->getX()-1, $this->getY(), $pageWidth, $this->getY());
		
		$this->drawColumnHeading();
	}
	
	// report footer
	public function Footer() 
	{
		$pageWidth   = $this->getPageWidth() - PDF_MARGIN_LEFT + 5;
		// line
		$this->SetY(-15);
		$this->Line($this->getX()-1, $this->getY(), $pageWidth, $this->getY() );
		// footer info
		$this->SetFont('dejavusans', '', 8);
		$page = trim('Page: '.$this->getAliasNumPage().' of '.$this->getAliasNbPages());
		$date = 'Date: '.date('n/j/Y h:ia');        
		$this->SetFont('dejavusans', '', 8);
		$page = trim('Page: '.$this->getAliasNumPage().' of '.$this->getAliasNbPages());
		$date = 'Date: '.date('n/j/Y h:ia');        
		$footer = '<table width="100%" border="0">';
		$footer .= '<thead>';
		$footer .= '<tr>';
		$footer .= '	<td width="50%" align="left">'.$page.'</td>';
		$footer .= '	<td width="50%" align="right">Report Code: '.$this->getReportName().'</td>';
		$footer .= '</tr>';
		$footer .= '<tr>';
		$footer .= '	<td width="50%" align="left">'.$date.'</td>';
		$footer .= '	<td width="50%" align="right">'.$this->getCompanyName().'</td>';
		$footer .= '</tr>';
		$footer .= '</thead>';
		$footer .= '</table>';
		$this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $footer, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);		
		
	}

	public function getCollections($dateStart = NULL, $dateEnd = NULL, $branchID = NULL)
	{
		$sql	     = '';
		$collection  = array();
		$totalAmt    = 0;
		$totalRebate = 0;
		$rowCount    = 0;

		// get payments
        $sql   = " SELECT ";
        $sql  .= "      tabPMT.*, ";
        $sql  .= "      (  SELECT CONCAT(UPPER(tabEMPL.e_lastname), ', ', tabEMPL.e_firstname, ' ', tabEMPL.e_middlename) ";
        $sql  .= "         FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabPMT.p_collector_id ";
        $sql  .= "      ) AS collectorName ";
        $sql  .= "FROM payments AS tabPMT ";
        $sql  .= "WHERE tabPMT.p_date >= '$dateStart' AND tabPMT.p_date <= '$dateEnd' AND tabPMT.p_branch_id = $branchID ";
        $sql  .= "ORDER BY tabPMT.p_or, collectorName ";

		$rec = mysql_query($sql);
	    while ($pmt = mysql_fetch_array($rec, MYSQL_ASSOC)) 
	    {
	        $collection[] = array
	                 (  
	                    'pmtID' 	      => $pmt['p_id'],
	                    'salesID' 	      => $pmt['p_sales_id'],
	                    'branchID' 	      => $pmt['p_branch_id'],
	                    'pmtCollectorID'  => $pmt['p_collector_id'], 
	                    'pmtCollectorName'=> $pmt['collectorName'],
	                    'pmtDate'    	  => $pmt['p_date'],  
	                    'pmtORNO'         => $pmt['p_or'], 
	                    'pmtAmt'    	  => $pmt['p_amount'],
	                    'pmtRebate'  	  => $pmt['p_rebate']
	                );
	                $totalAmt    += $pmt['p_amount'];
	                $totalRebate += $pmt['p_rebate'];
	                $rowCount    += 1;
	    }
        $collection[] = array
                 (  
                    'pmtID' 	      => 0,
                    'salesID' 	      => 0,
                    'branchID' 	      => 0,
                    'pmtCollectorID'  => 0, 
                    'pmtCollectorName'=> $rowCount . ' - OR numbers',
                    'pmtDate'    	  => '',
                    'pmtORNO'         => 'Totals', 
                    'pmtAmt'    	  => $totalAmt,
                    'pmtRebate'  	  => $totalRebate
                );
	    unset($rec);
        unset( $sql );
		return $collection;
	}
	
	private function drawColumnHeading($isPgFull = FALSE, $lastY = 0)
	{
		$fontHAdd  = $this->getFontHeight();
		$minLeft   = PDF_MARGIN_LEFT - 6;
        $this->setY(10);
		$minTop    = $this->getY() + 24;
		$pageWidth = $this->getPageWidth() - PDF_MARGIN_LEFT + 5;

		$this->setXY($minLeft, $this->getY() + 18)  ; $this->Cell(28 , 0, 'OR Number ',  0, 0, 'R' );
		$this->setXY($minLeft +  28, $this->getY() ); $this->Cell(20 , 0, 'Date '     ,  0, 0, 'R' );
		$this->setXY($minLeft +  48, $this->getY() ); $this->Cell(24 , 0, 'Amount '   ,  0, 0, 'R' );
		$this->setXY($minLeft +  72, $this->getY() ); $this->Cell(24 , 0, 'Rebates '  ,  0, 0, 'R' );
		$this->setXY($minLeft +  96, $this->getY() ); $this->Cell(60 , 0, ' Collector',  0, 0, 'L' );
		$this->setXY($minLeft + 156, $this->getY() ); $this->Cell(40 , 0, ' Remarks'  ,  0, 0, 'L' );

		// boxes
		$this->Line($minLeft	, $minTop  , $pageWidth , $minTop );
		$this->Line($minLeft	, $minTop - 8 , $minLeft   , $minTop );
		$this->Line($pageWidth	, $minTop - 8 , $pageWidth , $minTop );
		$this->Line( 37 , $minTop - 8 , 37 , $minTop);
		$this->Line( 57 , $minTop - 8 , 57 , $minTop);
		$this->Line( 81 , $minTop - 8 , 81 , $minTop);
		$this->Line(105 , $minTop - 8 ,105 , $minTop);
		$this->Line(164 , $minTop - 8 ,164 , $minTop);
	}
	
	private function drawColumnBorders($y = 0)
	{
		$fontHAdd  = $this->getFontHeight();
		$minLeft   = PDF_MARGIN_LEFT - 6;
        $this->setY(10);
		$minTop    = $this->getY() + 24;
		$pageWidth = $this->getPageWidth() - PDF_MARGIN_LEFT + 5;
		$pgHeight  = $y + 6;
//		$pgHeight  = $this->getPageHeight() - PDF_MARGIN_BOTTOM + $fontHAdd + 1;

		$this->Line($minLeft  , $minTop - 8, $minLeft, $pgHeight);
		$this->Line($pageWidth, $minTop - 8, $pageWidth, $pgHeight);
		$this->Line( 37 , $minTop - 8 , 37 , $pgHeight);
		$this->Line( 57 , $minTop - 8 , 57 , $pgHeight);
		$this->Line( 81 , $minTop - 8 , 81 , $pgHeight);
		$this->Line(105 , $minTop - 8 ,105 , $pgHeight);
		$this->Line(164 , $minTop - 8 ,164 , $pgHeight);
	    
	}
	
	private function drawCollectionTotals($t = NULL, $y = 0)
	{
		$fontHAdd  = $this->getFontHeight();
		$minTop    = $y;
		$pageWidth = $this->getPageWidth() - PDF_MARGIN_LEFT + 5;
		$minLeft   = PDF_MARGIN_LEFT - 6;
		
		if( (round($this->getPageHeight()) - PDF_MARGIN_BOTTOM - $this->getY()) <= $fontHAdd) { $this->AddPage(); }
		$this->setY($minTop);
		
		$this->Line($minLeft	, $minTop +  2 , $pageWidth , $minTop + 2 );
		$this->Line($minLeft	, $minTop + 10 , $pageWidth , $minTop + 10);
		$this->Line($minLeft	, $minTop +  2 , $minLeft   , $minTop + 10);
		$this->Line($pageWidth	, $minTop +  2 , $pageWidth , $minTop + 10);

		$this->Line( 57 , $minTop + 2 , 57 ,  $minTop + 10);
		$this->Line( 81 , $minTop + 2 , 81 ,  $minTop + 10);
		$this->Line(105 , $minTop + 2 ,105 ,  $minTop + 10);

		$this->setXY($minLeft, $this->getY() + 4)  ; $this->Cell(48 , 0, 'Totals ',  0, 0, 'R' );
		if(is_numeric( $t['pmtAmt'] )) {  $w = new NumWords( $t['pmtAmt'] ); $v = $w->number; unset($w); } else { $v = ''; } if($v == '0.00') { $v = ''; }
		$this->setXY($minLeft +  48, $this->getY() ); $this->Cell(24 , 0, $v.' ',  0, 0, 'R' );
    	if(is_numeric( $t['pmtRebate'] )) {  $w = new NumWords( $t['pmtRebate'] ); $v = $w->number; unset($w); } else { $v = ''; } if($v == '0.00') { $v = ''; }
		$this->setXY($minLeft +  72, $this->getY() ); $this->Cell(24 , 0, $v.' '  ,  0, 0, 'R' );
		$this->setXY($minLeft +  96, $this->getY() ); $this->Cell(60 , 0, $t['pmtCollectorName'],  0, 0, 'L' );
	}

	public function reportLayout($d = NULL)
	{
		if(is_array($d))
		{
		    $isPageFull = FALSE;
		    $r  = count($d)-1;
		    $t  = $d[$r];
		    $fontHAdd  = $this->getFontHeight();
			$minLeft   = PDF_MARGIN_LEFT - 6;
		    $this->setY($this->getY() + 20);
		    $lastY = $this->getY();
			for($i = 0; $i <= count($d)-2; $i++)
			{
				$this->setXY($minLeft    , $this->getY() + $fontHAdd); $this->Cell(28 , 0, $d[$i]['pmtORNO'].' ', 0, 0, 'R' );
				$v = date("m/d/Y", strtotime($d[$i]['pmtDate']));
				$this->setXY($minLeft + 28, $this->getY() ); $this->Cell(20 , 0, $v.' ', 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['pmtAmt'] )) {  $w = new NumWords( $d[$i]['pmtAmt'] ); $v = $w->number; unset($w); } else { $v = ''; } if($v == '0.00') { $v = ''; }
				$this->setXY($minLeft + 48, $this->getY() ); $this->Cell(24 , 0, $v.' ', 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['pmtRebate'] )) {  $w = new NumWords( $d[$i]['pmtRebate'] ); $v = $w->number; unset($w); } else { $v = ''; } if($v == '0.00') { $v = ''; }
				$this->setXY($minLeft + 72, $this->getY() ); $this->Cell(24 , 0, $v.' ', 0, 0, 'R' );

				$this->setXY($minLeft + 96, $this->getY() ); $this->Cell(60 , 0, $d[$i]['pmtCollectorName'].' ', 0, 0, 'L' );

				$this->setXY($minLeft + 156, $this->getY() ); $this->Cell(40 , 0, ' ', 0, 0, 'L' );
				if( (round($this->getPageHeight()) - PDF_MARGIN_BOTTOM - $this->getY()) <= $fontHAdd )
				{
		            $this->drawColumnBorders($this->getY());  // on page 1
					$this->AddPage();
		            $this->setY($this->getY() + 20);
					$lastY = $this->getY() + 20;
		            $this->drawColumnBorders($this->getY());
		            $this->setY($lastY - 20);
					$isPageFull = TRUE;
				}
				else{ $isPageFull = FALSE; }
				$lastY += $fontHAdd;
			}
            $this->drawColumnBorders($this->getY());
            if($this->PageNo() == 1)
            { $this->drawCollectionTotals($t, $lastY + $fontHAdd); }
            else
            { $this->drawCollectionTotals($t, $lastY - 20); }
		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords("WAIS, " . rptARCollectionName);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontHeight(fontSizeHeight);
$pdf->setBranch($branchID);
$pdf->setStartingDate($dateStart);
$pdf->setEndingDate($dateEnd);
$d = $pdf->getCollections($dateStart, $dateEnd, $branchID);
$pdf->setCompanyName(rptCompanyName);
$pdf->setReportTitle(rptARCollectionTitle);
$pdf->setReportName(rptARCollectionName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size
$pdf->SetAutoPageBreak(TRUE, 2);
$pdf->setFooterMargin(12);
$pdf->AddPage('P', 'LETTER');
$pdf->reportLayout( $d );
$pdf->Output($pdf->getReportName().'.pdf', 'I');
?>
