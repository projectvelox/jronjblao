<?php
//========================================================================//
//=====  REPORT : ACCOUNTS RECEIVABLES - CUSTOMER PAYMENTS           =====//
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

//==  get sales id, sub_id from session and reset it when done
session_start();
$salesID = $_GET['id'];

/*if(isset($_SESSION['salesID'])) 
{  
	$salesID  = $_SESSION['salesID']; 
	unset($_SESSION['salesID']);
}
if($salesID == 0) { $salesID == NULL; }
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
	public $area;

	public function setCompanyName	($s = '') { $this->companyName	= $s; }	public function getCompanyName(){ return $this->companyName;	}
	public function setReportTitle	($s = '') { $this->reportTitle	= $s; }	public function getReportTitle(){ return $this->reportTitle;	}
	public function setReportName	($s = '') { $this->reportName	= $s; }	public function getReportName()	{ return $this->reportName;		}
	public function setLogoFile		($s = '') { $this->logoFile 	= $s; }	public function getLogoFile()	{ return $this->logoFile;		}
	public function setLogoWidth	($i =  8) { $this->logoW		= $i; }	public function getLogoWidth()	{ return $this->logoW;			}
	public function setLogoHeight	($i =  4) { $this->logoH		= $i; }	public function getLogoHeight()	{ return $this->logoH;			}
	public function setBranch		($s = '') { $this->branch		= $s; }	public function getBranch()		{ return $this->branch;			}
	public function setArea			($s = '') { $this->area 		= $s; }	public function getArea()		{ return $this->area;			}

	public function getCustomerPayments($salesID = NULL)
	{
		$sql	 = '';
		$sales   = array();;
		$payment = array();
		$custName		= ''; $custAddr 	= ''; $custContact	= ''; $coBuyerName 	= '';
		$coBuyerAddr	= ''; $salesInvNo	=  0; $salesCPrice	=  0; $salesCOD		=  0;
		$salesTerm		=  0; $salesAmortize=  0; $salesRebate 	=  0; $salesFirstMI	= '';
		$salesPurDate	= ''; $salesFirstDP	=  0; $salesSecondDP=  0; $salesDateSecondDP= '';
		$salesCOD		=  0; $salesCostPrice= 0; $salesUnit	= ''; $salesModel	= '';
		$salesEngine	= ''; $salesChassis	= ''; 
		if(!$salesID == NULL)
		{
			// get sales_main, sales_sub, customer, product
			$sql   =	" SELECT tabSALES.*, tabCUSTOMER.*, tabSUB.*, tabPROD.*, ";
			$sql  .=	"    (SELECT value                     FROM options_country AS tabCOUNTRY WHERE tabCOUNTRY.id     = tabCUSTOMER.c_address_country ) AS customerCountry,  ";
			$sql  .=	"    (SELECT SUM(s_sold_price * s_qty) FROM sales_sub       AS tabSUB     WHERE tabSUB.s_sales_id = tabSALES.s_id                 ) AS total_sales,  ";
			$sql  .=	"    (SELECT p_sellingprice            FROM products        AS tabPRODUCT WHERE tabPRODUCT.p_id   = tabSUB.s_product_id           ) AS salesSellingPrice, ";    // COD  --- could be wrong column
			$sql  .=	"    (SELECT b_name                    FROM branches        AS tabBRANCH  WHERE tabBRANCH.b_id    = tabSALES.s_branch_id          ) AS salesBranch, ";
			$sql  .=	"    (SELECT a_name                    FROM areas           AS tabAREA    WHERE tabAREA.a_ID      = tabCUSTOMER.c_area            ) AS salesArea ";
			$sql  .=	" FROM sales_main AS tabSALES   ";
			$sql  .=	"    JOIN (customers AS tabCUSTOMER) ON tabCUSTOMER.c_id  = tabSALES.s_customer_id   ";
			$sql  .=	"    JOIN (sales_sub AS tabSUB     ) ON tabSUB.s_sales_id = tabSALES.s_id   ";
			$sql  .=	"    JOIN (products  AS tabPROD    ) ON tabPROD.p_id	  = tabSUB.s_product_id   ";
			$sql  .=	" WHERE tabSALES.s_id =  $salesID ";
			$rec = mysql_query($sql) ;
		    while ($sales = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		    	// customer table
		    	$custName = $sales['c_lastname'].', '.$sales['c_firstname'].' '.$sales['c_middlename'];
		    	if(trim($sales['c_address_street']	<> "")) { $custAddr  = $sales['c_address_street'].', '; }
		    	if(trim($sales['c_address_town']	<> "")) { $custAddr .= $sales['c_address_street'].', '; }
		    	if(trim($sales['c_address_city']	<> "")) { $custAddr .= $sales['c_address_city']	.', '; }
		    	if(trim($sales['customerCountry']	<> "")) { $custAddr .= $sales['customerCountry'].', '; }
		    	if(trim($sales['c_address_zipcode'] <> ""))	{ $custAddr .= ' '.$sales['c_address_zipcode']; }
		    	if(    trim($sales['c_phone_home'])   <> '') { $custContact = $sales['c_phone_home'];   }
		    	elseif(trim($sales['c_phone_mobile']) <> '') { $custContact = $sales['c_phone_mobile']; }
		    	elseif(trim($sales['c_phone_office']) <> '') { $custContact = $sales['c_phone_office']; }
		    	// sales table
		    	$coBuyerName 	= $sales['s_cobuyer_name1'];															//--------------------> added in sales_main
		    	if(trim($sales['s_cobuyer_name2']) <> '') { $coBuyerName .= '/'.$sales['s_cobuyer_name2']; }			//--------------------> added in sales_main
		    	$coBuyerAddr	= $sales['s_cobuyer_address'];															//--------------------> added in sales_main
		    	$salesInvNo		= $sales['s_id'] + 0;
		    	$salesCPrice	= $sales['total_sales'] + $sales['s_othercharges'] + $sales['s_tax'] - $sales['s_discount'];
		    	$salesTerm		= $sales['s_payment_term'] + 0;
		    	$salesAmortize	= $sales['s_monthlyamortization'] + 0;
		    	$salesRebate 	= $sales['s_rebate'] + 0;																//--------------------> added in sales_main
		    	$salesFirstMI	= $sales['s_firstmonthlydue_date'];
		    	$salesCOD			= $sales['salesSellingPrice'] + 0;													// products.p_sellingprice  ----> could be wrong
		    	$salesCostPrice		= $sales['salesSellingPrice'] + 0;													// products.p_sellingprice  ----> wrong
				// products table
				$salesUnit		= $sales['p_name'];
				$salesModel		= $sales['p_code'];
				$salesEngine	= $sales['p_property_1'];
				$salesChassis	= $sales['p_property_2'];
		    	$this->setBranch($sales['salesBranch']);
		    	$this->setArea($sales['salesArea']);
		    	break;
		    }
		    unset($rec);
		    // get all payments
		    $sql  = " SELECT tabPMT.*, tabPROD.* FROM payments AS tabPMT ";
		    $sql .= "   JOIN (sales_sub AS tabSUB) ON tabSUB.s_sales_id = tabPMT.p_sales_id ";
		    $sql .= "   JOIN (products AS tabPROD) ON tabPROD.p_id = tabSUB.s_product_id ";
		    $sql .= " WHERE tabPMT.p_sales_id = $salesID  order by tabPMT.p_is_downpayment ";
			$rec = mysql_query($sql) ;
		    while ($pmt = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		    	$pmtOR		= $pmt['p_or'];
		    	$pmtDate	= $pmt['p_date'];
		    	$pmtAmt		= $pmt['p_amount'] + 0;
		    	$pmtRebate	= $pmt['p_rebate'] + 0;
		    	$pmtIsDP	= $pmt['p_is_downpayment'] + 0;
		    	$payment[] = array('pmtOR' => $pmtOR, 'pmtDate' => $pmtDate, 'pmtAmount' => $pmtAmt, 'pmtRebate' => $pmtRebate, 'pmtIsDP' => $pmtIsDP);
		    }
		    unset($rec);
		    $sales =array
		    		(	'customerName'		=> $custName	,	'customerAddress'		=> $custAddr,			'customerContact'		=> $custContact	,  
		    			'coBuyerName'		=> $coBuyerName	,	'coBuyerAddress'		=> $coBuyerAddr	,		'salesInvoiceNo'		=> $salesInvNo	,  
		    			'salesContractPrice'=> $salesCPrice	,	'salesTerm'				=> $salesTerm,			'salesMonthlyInstallment'=> $salesAmortize,  
		    			'salesRebate'		=> $salesRebate	,	'salesFirstInstallment'	=> $salesFirstMI,
		    			'salesCOD'			=> $salesCOD	,	'salesCostPrice'		=>	$salesCostPrice,	'salesUnit'				=> $salesUnit,
		    			'salesModel'		=> $salesModel	,	'salesEngineSerial'		=>	$salesEngine,		'salesChassis'			=> $salesChassis,
		    			'payments'			=> $payment
		    		);
		}
		return $sales;
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
		$this->SetFont('helvetica', '', 12);
		$this->setXY(PDF_MARGIN_LEFT+$this->getLogoWidth() - 4, $this->getY() + 6);
		$this->Cell(0,0, $this->getReportTitle() );
		// branch
		$s = "BRANCH : " . $this->getBranch();
		$this->SetFont('helvetica', '', 10);
		$this->setXY(PDF_MARGIN_LEFT+$this->getLogoWidth() - 4, $this->getY() + 6);
		$this->Cell(0,0, $s );
		// area
		$s = "AREA : " . $this->getArea();
		$this->SetFont('helvetica', '', 12);
		$this->setX(PDF_MARGIN_LEFT+$this->getLogoWidth() + 102);
		$this->Cell(0,0, $s );
		// line separator
		$this->setXY(PDF_MARGIN_LEFT-5, $this->getY() + 6); 
		$this->Line($this->getX()-1, $this->getY(), $pageWidth, $this->getY());
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
	
	public function reportLayout($data = NULL)
	{
		$v = 0;
		if(is_array($data))
		{
			// 2 cols
			$col_11_Label = 31;	$col_11_Value =100;
			$col_12_Label = 34;	$col_12_Value = 29;

			// 3 cols
			$col_21_Label = 25; $col_21_Value = 50;
			$col_22_Label = 22; $col_22_Value = 42;
			$col_23_Label = 16; $col_23_Value = 30;
			
			$fontHAdd  = 4;
			$minLeft   = PDF_MARGIN_LEFT - 6;
			$minTop    = $this->getY() + 18;
			$maxHeight = 0;
			$pageWidth = $this->getPageWidth() - PDF_MARGIN_LEFT + 5;

			// build payments array
			$pmt		= $data['payments'];
			$term		= $data['salesTerm'] + 3;
			$balanceEnd = $data['salesContractPrice']+0;
			$tempBalance= $balanceEnd;
			$totalPayment = 0;
			$totalRebates = 0;
			$dpFirstDate  = ''; $dpFirstAmt   = 0;
			$dpSecondDate = ''; $dpSecondAmt  = 0;
			$r = 1;

			$tempDP  = array();
			$tempPmt = array();
			$tmpTotal= array();
			
			for($i = 0; $i <= $term-1; $i++)
			{
				$tmpORNo  = '';
				$tmpDate  = '';
				$tmpAmt   = 0;
				$tmpRebate= 0;
				$tmpRemark= '';
				if($i <= count($pmt)-1)
				{
					$tmpORNo	= $pmt[$i]['pmtOR'];
					if(trim($pmt[$i]['pmtDate']) <> '') { $tmpDate = date("m/d/Y", strtotime($pmt[$i]['pmtDate'])); } else { $tmpDate = ''; }
					$tmpAmt 	= $pmt[$i]['pmtAmount'] + 0;
					$tmpRebate	= $pmt[$i]['pmtRebate'] + 0;
					$tmpIsDP	= $pmt[$i]['pmtIsDP'] + 0;
					$tmpRemark	= '';
					$totalPayment	+= $tmpAmt;
					$totalRebates	+= $tmpRebate;
					switch($tmpIsDP)
					{
						case 0:		// payment
							$tempPmt[$r] = array('pmtRow' =>$r, 'pmtOR' => $tmpORNo,'pmtDate' => $tmpDate, 'pmtAmount' => $tmpAmt,	'pmtRebate' => $tmpRebate, 'pmtBalance'=> 0, 'pmtRemark' => $tmpRemark);
							$r++;
							break;
						case 1:		// first dp
							$dpFirstDate = $tmpDate;
							$dpFirstAmt  = $tmpAmt;
							$tempDP[] = array('pmtRow' =>'1st DP', 'pmtOR' => $tmpORNo,'pmtDate' => $tmpDate, 'pmtAmount' => $tmpAmt,	'pmtRebate' => $tmpRebate, 'pmtBalance'=> 0, 'pmtRemark' => $tmpRemark);
							break;
						case 2:		// second dp
							$dpSecondDate = $tmpDate;
							$dpSecondAmt  = $tmpAmt;
							$tempDP[] = array('pmtRow' =>'2nd DP', 'pmtOR' => $tmpORNo,'pmtDate' => $tmpDate, 'pmtAmount' => $tmpAmt,	'pmtRebate' => $tmpRebate, 'pmtBalance'=> 0, 'pmtRemark' => $tmpRemark);
							break;
					}
				}
				else
				{ $tempPmt[$r] = array('pmtRow' => $r, 'pmtOR' => '','pmtDate' => '', 'pmtAmount' => 0,	'pmtRebate' => 0, 'pmtBalance'=> 0, 'pmtRemark' => ''); $r++; }
			}
			$tempBalance-= ($totalPayment + $totalRebates);
			$tmpTotal[]  = array('pmtRow' => ' ', 'pmtOR' => ' ', 'pmtDate' => ' ', 'pmtAmount' => $totalPayment, 'pmtRebate' => $totalRebates, 'pmtBalance' => $tempBalance, 'pmtRemark' => ' ');
			$pmt		 = array_merge($tmpTotal, $tempDP, $tempPmt);
			unset($term);
			unset($tempDP);
			unset($tempPmt);
			unset($tmpTotal);
			unset($r);
			unset($totalRebates);
			unset($totalPayment);
			unset($tmpRemark);
			unset($tmpDate);
			unset($tmpAmt);
			unset($tmpORNo);
			
			// customer information
			// 2 columns
			//customer name / sales invoice no
			$this->setXY($minLeft, $this->getY() + 18); $this->Cell(0,0, 'Name' );  $this->setX($minLeft + $col_11_Label + 1);  $this->Cell(0,0, ':' );		// customer name
			$this->setX($minLeft + $col_11_Label +  4); $this->Cell(0,0, $data['customerName'] );
			
			$v = sprintf("%0". lengthInvoiceNumber ."d", $data['salesInvoiceNo']);
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + 2); $this->Cell(0,0, 'Sales Invoice No' );		// sales invoice no
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label + 2); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label+ 4); $this->Cell($col_12_Value,0, $v.' ', 0, 0, 'R' );

			// customer address/total contract price
			$this->setXY($minLeft, $this->getY() + $fontHAdd);  $this->Cell(0,0, 'Addess' ); $this->setX($minLeft + $col_11_Label + 1);  $this->Cell(0,0, ':' );  // customer address
			$this->setX($minLeft + $col_11_Label  +  4); $this->Cell(0,0, $data['customerAddress'] );

			$w = new NumWords( $data['salesContractPrice'] ); $v = 'P ' . $w->number; unset($w);
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + 2); $this->Cell(0,0, 'Total Contract Price' );		// contract price
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label + 2); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label+ 4); $this->Cell($col_12_Value,0, $v.' ', 0, 0, 'R'  );

			//contact no / term
			$this->setXY($minLeft, $this->getY() + $fontHAdd);  $this->Cell(0,0, 'Contact No' ); $this->setX($minLeft + $col_11_Label + 1);  $this->Cell(0,0, ':' );  // contact no
			$this->setX($minLeft + $col_11_Label  +  4); $this->Cell(0,0, $data['customerContact'] );

			$this->setX($minLeft + $col_11_Label  + $col_11_Value + 2); $this->Cell(0,0, 'Term' );		// term
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label + 2); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label+ 4); $this->Cell($col_12_Value,0, $data['salesTerm'].' ', 0, 0, 'R'  );
			
			//cobuyer / monthly installment
			$this->setXY($minLeft, $this->getY() + $fontHAdd);  $this->Cell(0,0, 'Co-Buyer' ); $this->setX($minLeft + $col_11_Label + 1);  $this->Cell(0,0, ':' );  // contact no
			$this->setX($minLeft + $col_11_Label  +  4); $this->Cell(0,0, $data['coBuyerName'] );

			if(is_numeric($data['salesMonthlyInstallment'])) { $w = new NumWords( $data['salesMonthlyInstallment'] ); $v = 'P ' . $w->number; unset($w); } else { $v = ''; }
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + 2); $this->Cell(0,0, 'Monthly Installment' );		// monthly installment
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label + 2); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label+ 4); $this->Cell($col_12_Value,0, $v.' ', 0, 0, 'R'  );
			
			//cobuyer address / rebate
			$this->setXY($minLeft, $this->getY() + $fontHAdd);  $this->Cell(0,0, 'Address' ); $this->setX($minLeft + $col_11_Label + 1);  $this->Cell(0,0, ':' );  // contact no
			$this->setX($minLeft + $col_11_Label  +  4); $this->Cell(0,0, $data['coBuyerName'] );

			if(is_numeric( $data['salesRebate'])) {  $w = new NumWords( $data['salesRebate'] ); $v = 'P ' . $w->number; unset($w); } else { $v = ''; }
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + 2); $this->Cell(0,0, 'Rebate' );		// rebate
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label + 2); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_11_Label  + $col_11_Value + $col_12_Label+ 4); $this->Cell($col_12_Value,0, $v.' ', 0, 0, 'R'  );
			
			$maxX = $this->getX(); 
			$maxY = $this->getY(); 
			
			// boxes
			$this->Line($minLeft, $minTop-2, $pageWidth   , $minTop-2);
			$this->Line($minLeft, 50, $pageWidth , 50);
			$this->Line($minLeft, 70, $pageWidth , 70);
			
			$this->Line($minLeft, $minTop-2, $minLeft , 70);
			$this->Line($pageWidth, $minTop-2, $pageWidth , 70);
			
			$this->Line($minLeft+132, $minTop-2, $minLeft+132, 50);
			$this->Line($minLeft+75, 50, $minLeft+75 , 70);
			$this->Line($minLeft+140, 50, $minLeft+140 , 70);
			
			// 3 columns
			// unit / 1st mi / cost price
			$this->setXY($maxX, $maxY); 
			$this->setXY($minLeft, $this->getY() + $fontHAdd + $fontHAdd);  $this->Cell(0,0, 'Unit' ); $this->setX($minLeft + $col_21_Label + 1);  $this->Cell(0,0, ':' );  // unit
			$this->setX($minLeft + $col_21_Label  +  4); $this->Cell(0,0, $data['salesUnit'] );
			
			if(($data['salesFirstInstallment'])) { $v = date("m/d/Y", strtotime($data['salesFirstInstallment'])); } else { $v = ''; }
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + 2); $this->Cell(0,0, '1st MI' );		// 1st installment date
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + 2); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + 4); $this->Cell(0,0, $v);

			if(is_numeric( $data['salesCostPrice'])) {  $w = new NumWords( $data['salesCostPrice'] ); $v = 'P ' . $w->number; unset($w); } else { $v = ''; }
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + 2); $this->Cell(0,0, 'Cost' );		// cost price
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + $col_23_Label); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + $col_23_Label + 2); $this->Cell($col_12_Value,0, $v, 0, 0, 'R'  );

			// model / cod / 'downpayment'
			$this->setXY($minLeft, $this->getY() + $fontHAdd );  $this->Cell(0,0, 'Model' ); $this->setX($minLeft + $col_21_Label + 1);  $this->Cell(0,0, ':' );  // model
			$this->setX($minLeft + $col_21_Label  +  4); $this->Cell(0,0, $data['salesModel'] );

			if(is_numeric( $data['salesCOD'])) {  $w = new NumWords( $data['salesCOD'] ); $v = 'P ' . $w->number; unset($w); } else { $v = ''; }
			$this->setX($minLeft + $col_21_Label  + $col_21_Value +  2); $this->Cell(0,0, 'COD' );		// COD
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + 2); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + 4); $this->Cell(0,0, $v);

			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + 8); $this->Cell(0,0, 'Down Payment' );		// 'downpayment''

			// engine / date 1st dp / amt 1st dp
			$this->setXY($minLeft, $this->getY() + $fontHAdd );  $this->Cell(0,0, 'Engine#/Serial#' ); $this->setX($minLeft + $col_21_Label + 1);  $this->Cell(0,0, ':' );  // engine/serial
			$this->setX($minLeft + $col_21_Label  +  4); $this->Cell(0,0, $data['salesEngineSerial'] );

			if($dpFirstDate <> '') { $v = date("m/d/Y", strtotime($dpFirstDate)); } else { $v = ''; }
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + 2); $this->Cell(0,0, '1st DP Date' );		// 1st DP date
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + 2); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + 4); $this->Cell(0,0, $v);

			if(is_numeric( $dpFirstAmt ) ) {  $w = new NumWords( $dpFirstAmt ); $v = 'P ' . $w->number; unset($w); } else { $v = ''; }
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + 2); $this->Cell(0,0, '1st DP' );		// 1st dp amount
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + $col_23_Label ); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + $col_23_Label + 2); $this->Cell($col_12_Value,0, $v, 0, 0, 'R'  );

			// chassis / date 2nd dp / amt 2nd dp
			$this->setXY($minLeft, $this->getY() + $fontHAdd );  $this->Cell(0,0, 'Chassis#' ); $this->setX($minLeft + $col_21_Label + 1);  $this->Cell(0,0, ':' );  // chassis
			$this->setX($minLeft + $col_21_Label  +  4); $this->Cell(0,0, $data['salesChassis'] );

			if($dpSecondDate <> '') { $v = date("m/d/Y", strtotime( $dpSecondDate )); } else { $v = ''; }
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + 2); $this->Cell(0,0, '2nd DP Date' );		// 2nd DP date
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + 2); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + 4); $this->Cell(0,0, $v);

			if(is_numeric( $dpSecondAmt )) {  $w = new NumWords( $dpSecondAmt ); $v = 'P ' . $w->number; unset($w); } else { $v = ''; }
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + 2); $this->Cell(0,0, '2nd DP' );		// 2nd dp amount
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + $col_23_Label ); $this->Cell(0,0, ':' );
			$this->setX($minLeft + $col_21_Label  + $col_21_Value + $col_22_Label + $col_22_Value + $col_23_Label + 2); $this->Cell($col_12_Value,0, $v, 0, 0, 'R'  );
			
			$pg1Y = $this->getY() + 10;
			$pgHeight = $this->getPageHeight() - PDF_MARGIN_BOTTOM + $fontHAdd + 1;
			if($this->PageNo() == 1)
			{
				// draw boxes around the columns
				$this->Line($minLeft, $pg1Y - 1, $pageWidth, $pg1Y - 1);
				$this->Line($minLeft, $pg1Y + ($fontHAdd * 2) - 2, $pageWidth, $pg1Y + ($fontHAdd * 2) - 2);
				$this->Line($minLeft, $pg1Y + ($fontHAdd * 3) + 2, $pageWidth, $pg1Y + ($fontHAdd * 3) + 2);
				$this->Line($minLeft, $pg1Y - 1, $minLeft , $pgHeight);
				$this->Line( 61, $pg1Y - 1,  61, $pgHeight);
				$this->Line( 61, $pg1Y - 1,  61, $pgHeight);
				$this->Line( 85, $pg1Y - 1,  85, $pgHeight);
				$this->Line(111, $pg1Y - 1, 111, $pgHeight);
				$this->Line(137, $pg1Y - 1, 137, $pgHeight);
				$this->Line(163, $pg1Y - 1, 163, $pgHeight);
				$this->Line($pageWidth, $pg1Y - 1, $pageWidth, $pgHeight);
			}

			// column headings
			$pg1Y = $this->getY() + 10;
			$this->setXY($minLeft, $this->getY() + 10); $this->Cell(0,0, ' '); 
			$this->setX($minLeft +  12); $this->Cell(40,0, 'OFFICIAL RECEIPT #', 0, 0, 'C');
			$this->setX($minLeft +  52); $this->Cell(24,0, 'DATE', 0, 0, 'C');
			$this->setX($minLeft +  76); $this->Cell(26,0, 'PAYMENTS', 0, 0, 'C');
			$this->setX($minLeft + 102); $this->Cell(26,0, 'REBATES', 0, 0, 'C');
			$this->setX($minLeft + 128); $this->Cell(26,0, 'BALANCE', 0, 0, 'C');
			$this->setX($minLeft + 154); $this->Cell(42,0, ' Remarks', 0, 0, 'L');
			$isTotalDone = FALSE;
			$isNewPage   = TRUE;
			if(is_array($pmt))
			{
				for($i = 0; $i <= count($pmt)-1; $i++)
				{
					$buff		= 0;
					$tempRow	= $pmt[$i]['pmtRow'];
					$tempOR 	= $pmt[$i]['pmtOR'];
					$tempDate	= date("m/d/Y", strtotime($pmt[$i]['pmtDate']));
					$tempPmt	= $pmt[$i]['pmtAmount'] + 0;
					$tempRebate = $pmt[$i]['pmtRebate'] + 0;
					if($i == 0 ) { $tempBalance = $pmt[$i]['pmtBalance'] + 0; }
					elseif($i > 0 )
					{
						if(!$isTotalDone){ $tempBalance = $balanceEnd; $isTotalDone = TRUE; }
						if($isTotalDone) { $tempBalance -= ( $tempPmt + $tempRebate );  }
					}
					$buff = $tempBalance; 
					$tempRemark = ' ';

					if(trim($pmt[$i]['pmtDate']) <> '') { $tempDate = date("m/d/Y", strtotime($pmt[$i]['pmtDate']));} else { $tempDate	 = ''; }
					if($tempPmt 	<> 0) {  $w = new NumWords( $tempPmt	); $tempPmt    = $w->number; unset($w); } else { $tempPmt	 = ''; }
					if($tempRebate	<> 0) {  $w = new NumWords( $tempRebate ); $tempRebate = $w->number; unset($w); } else { $tempRebate = ''; }
					if($tempBalance	<> 0) {  $w = new NumWords( $tempBalance); $tempBalance= $w->number; unset($w); } else { $tempBalance= ''; }
					if($tempOR == '' && $tempDate === '' && $tempPmt == '' && $tempRebate == '' ) {  $tempBalance= ''; }

					if( (round($this->getPageHeight()) - PDF_MARGIN_BOTTOM - $this->getY()) <= $fontHAdd )
					{
						$pgHeight = $this->getPageHeight() - PDF_MARGIN_BOTTOM + $fontHAdd + 1;
						$this->setY($this->getY());
						$this->AddPage();
						// re-create column headings on succeeding pages
						if($this->PageNo() > 1)
						{
							$this->setXY($minLeft, $this->getY() + 20); $this->Cell(0,0, ' '); 
							$this->setX($minLeft +  12); $this->Cell(40,0, 'OFFICIAL RECEIPT #', 0, 0, 'C');
							$this->setX($minLeft +  52); $this->Cell(24,0, 'DATE', 0, 0, 'C');
							$this->setX($minLeft +  76); $this->Cell(26,0, 'PAYMENTS', 0, 0, 'C');
							$this->setX($minLeft + 102); $this->Cell(26,0, 'REBATES', 0, 0, 'C');
							$this->setX($minLeft + 128); $this->Cell(26,0, 'BALANCE', 0, 0, 'C');
							$this->setX($minLeft + 154); $this->Cell(42,0, ' Remarks', 0, 0, 'L');
							$this->setY($this->getY() + 2);
							$pg1Y = $this->getY() - 3;
							
							// and the boxes
							$this->Line($minLeft, $pg1Y - 1, $pageWidth, $pg1Y - 1);
							$this->Line($minLeft, $pg1Y + ($fontHAdd * 2) - 2, $pageWidth, $pg1Y + ($fontHAdd * 2) - 2);
							$this->Line($minLeft, $pg1Y - 1, $minLeft , $pgHeight);
							$this->Line( 61, $pg1Y - 1, 61, $pgHeight);
							$this->Line( 61, $pg1Y - 1,  61, $pgHeight);
							$this->Line( 85, $pg1Y - 1,  85, $pgHeight);
							$this->Line(111, $pg1Y - 1, 111, $pgHeight);
							$this->Line(137, $pg1Y - 1, 137, $pgHeight);
							$this->Line(163, $pg1Y - 1, 163, $pgHeight);
							$this->Line($pageWidth, $pg1Y - 1, $pageWidth, $pgHeight);
						}
						
					}
					if($i < 2 ) { $this->setXY($minLeft+4, $this->getY() + $fontHAdd); }
					$this->setXY($minLeft+4, $this->getY() + $fontHAdd); $this->Cell(9,0, $tempRow, 0, 0, 'R');
					$this->setX($minLeft +  12); $this->Cell(35,0, $tempOR  		, 0, 0, 'R');
					$this->setX($minLeft +  52); $this->Cell(24,0, $tempDate		, 0, 0, 'C');
					$this->setX($minLeft +  76); $this->Cell(26,0, $tempPmt		.' ', 0, 0, 'R');
					$this->setX($minLeft + 102); $this->Cell(26,0, $tempRebate	.' ', 0, 0, 'R');
					$this->setX($minLeft + 128); $this->Cell(26,0, $tempBalance	.' ', 0, 0, 'R');
					$this->setX($minLeft + 154); $this->Cell(42,0, $tempRemark		, 0, 0, 'L');
					$tempBalance = $buff;
					$isNewPage = FALSE;
				}
				unset($tempRow);
				unset($tempOR);
				unset($tempDate);
				unset($tempPmt);
				unset($tempRebate);
				unset($tempBalance);
				unset($tempRemark);
				
			}
		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords("WAIS, " . rptARCustomerPaymentTitle);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$d = $pdf->getCustomerPayments($salesID);
$pdf->setCompanyName(rptCompanyName);
$pdf->setReportTitle(rptARCustomerPaymentTitle);
$pdf->setReportName(rptARCustomerPaymentName);
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
