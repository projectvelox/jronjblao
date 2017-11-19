<?php
//========================================================================//
//=====  REPORT : ACCOUNTS RECEIVABLES - AGING OF ACCOUNTS           =====//
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
$endDate    = '';
$branchID   = 0;
$areaID     = 0;
$collectorID= 0;

if(isset($_SESSION['reportDate']))  { $endDate      = $_SESSION['reportDate']; 	unset($_SESSION['reportDate']);  } else { $endDate   = NULL; }
if(isset($_SESSION['branchID']))    { $branchID     = $_SESSION['branchID'] ;   unset($_SESSION['branchID']);    } else { $branchID    = NULL; }
if(isset($_SESSION['areaID']))      { $areaID       = $_SESSION['areaID'];      unset($_SESSION['areaID']);      } else { $areaID      = NULL; }
if(isset($_SESSION['collectorID'])) { $collectorID  = $_SESSION['collectorID']; unset($_SESSION['collectorID']); } else { $collectorID = NULL; }
if($endDate   == NULL || $endDate   == '' || date("Y-m-d", strtotime($endDate)  ) == '1970-07-01') { $endDate   = date("Y-m-d"); } else { $endDate   = date("Y-m-d", strtotime($endDate)); } 

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
	public $collector;
	public $endRptDate;

	public function setCompanyName	($s = '') { $this->companyName	= $s; }	public function getCompanyName(){ return $this->companyName;	}
	public function setReportTitle	($s = '') { $this->reportTitle	= $s; }	public function getReportTitle(){ return $this->reportTitle;	}
	public function setReportName	($s = '') { $this->reportName	= $s; }	public function getReportName()	{ return $this->reportName;		}
	public function setLogoFile		($s = '') { $this->logoFile 	= $s; }	public function getLogoFile()	{ return $this->logoFile;		}
	public function setLogoWidth	($i =  8) { $this->logoW		= $i; }	public function getLogoWidth()	{ return $this->logoW;			}
	public function setLogoHeight	($i =  4) { $this->logoH		= $i; }	public function getLogoHeight()	{ return $this->logoH;			}
	public function setBranch		($s = '') { $this->branch		= $s; }	public function getBranch()		{ return $this->getBranchName($this->branch);       }
	public function setArea			($s = '') { $this->area 		= $s; }	public function getArea()		{ return $this->getAreaName($this->area);           }
	public function setCollector	($s = '') { $this->collector	= $s; }	public function getCollector()	{ return $this->getCollectorName($this->collector); }
	public function setEndingDate	($s = '') { $this->endRptDate	= $s; }	public function getEndingDate()	{ return strtoupper(date("M j, Y", strtotime($this->endRptDate))); }
	
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

	private function getAreaName($id = NULL)
	{
	    $r   = '';
	    $sql = '';
	    if(!$id == NULL)
	    {
	        $sql = " select a_name FROM areas WHERE a_id = $id ";
			$rec = mysql_query($sql) or die('Query failed: ' . mysql_error());
		    while ($area = mysql_fetch_array($rec, MYSQL_ASSOC)) { $r = $area['a_name']; break; }
		    unset($rec);	        
	    }
	    unset($sql);
	    return $r;
	}

	private function getCollectorName($id = NULL)
	{
	    $r   = '';
	    $sql = '';
	    if(!$id == NULL)
	    {
	        $sql = " SELECT CONCAT(UPPER(e_lastname), ', ', e_firstname, ' ', e_middlename) AS collectorName FROM employees WHERE e_id = $id AND e_is_collector = 'Y' ";
			$rec = mysql_query($sql) or die('Query failed: ' . mysql_error());
		    while ($empl= mysql_fetch_array($rec, MYSQL_ASSOC)) { $r = $empl['collectorName']; break; }
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
		$s = "     AS OF " . $this->getEndingDate();
		$this->SetFont('helvetica', '', 12);
		$this->setXY(PDF_MARGIN_LEFT+$this->getLogoWidth() - 4, $this->getY() + 6);
		$this->Cell(0,0, $this->getReportTitle() . $s );
		// branch
		$s = "BRANCH : " . $this->getBranch();
		$this->SetFont('helvetica', '', 10);
		$this->setXY(PDF_MARGIN_LEFT+$this->getLogoWidth() - 4, $this->getY() + 6);
		$this->Cell(0,0, $s );
		// area
		$s = "AREA : " . $this->getArea();
		$this->SetFont('helvetica', '', 12);
		$this->setX(PDF_MARGIN_LEFT+$this->getLogoWidth() + 60);
		$this->Cell(0,0, $s );
		
		// collector
		$s = "COLLECTOR : " . $this->getCollector();
		$this->SetFont('helvetica', '', 12);
		$this->setX(PDF_MARGIN_LEFT+$this->getLogoWidth() + 180);
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

	public function getAccountsAge($endDate = NULL, $branchID = NULL, $areaID = NULL, $collectorID = NULL)
	{
		$sql	 = '';
		$age     = array();
		$ageSalesList       = array();
		$ageSalesSchedule   = array();
		$agePmtList			= array();
		$hasSales= FALSE;

		// get customer sales
        $sql   = " SELECT ";
        $sql  .= "		tabSALES.s_customer_id, ";
        $sql  .= "		CONCAT(UPPER(tabCUST.c_lastname), ', ', tabCUST.c_firstname, ' ', c_middlename) as customerName,  ";
        $sql  .= "		(SELECT SUM(s_sold_price * s_qty) FROM sales_sub AS tabSUB WHERE tabSUB.s_sales_id = tabSALES.s_id ) AS salesTotal, ";
        $sql  .= "		sum(tabSALES.s_othercharges) AS salesCharges, ";
        $sql  .= "		sum(tabSALES.s_tax) as salesTax,  ";
        $sql  .= "		sum(tabSALES.s_discount) as salesDiscount, ";
        $sql  .= "		tabSALES.s_monthlyamortization ";
        $sql  .= " FROM sales_main AS tabSALES  ";
        $sql  .= "		JOIN (customers AS tabCUST  ) ON tabCUST.c_id = tabSALES.s_customer_id  ";
		$sql  .="   	JOIN (sales_sub AS tabSUB     ) ON tabSUB.s_sales_id = tabSALES.s_id   ";
        $sql  .= " WHERE  ";
        $sql  .= "		tabSALES.s_branch_id   = $branchID  AND  ";
        $sql  .= "		tabSALES.s_sales_date <= '$endDate' AND  ";
        $sql  .= "		tabSALES.s_ispaid      = 'N'  ";
        $sql  .= " GROUP BY tabSALES.s_customer_id  ";
        $sql  .= " ORDER BY customerName  ";
		$rec = mysql_query($sql);
	    while ($sales = mysql_fetch_array($rec, MYSQL_ASSOC)) 
	    {
	        $hasSales = TRUE;
	        $age[] = array
	                 (  'customerID'    	=> $sales['s_customer_id'],  
	                    'customerName'  	=> $sales['customerName'], 
	                    'totalSales'    	=> $sales['salesTotal'] + $sales['salesCharges'] + $sales['salesTax'] - $sales['salesDiscount'],
	                    'monthlyInstallment'=> $sales['s_monthlyamortization'], 
	                    'totalPayments' 	=> 0,   
	                    'totalRebates'  	=> 0,   
	                    'dpSecondAmt'		=> 0, 
	                    'dueThisMonth'  	=> 0, 
	                    'due1Month'  		=> 0, 
	                    'due2Month'  		=> 0, 
	                    'due3Month'  		=> 0, 
	                    'dueTotal'  		=> 0, 
	                    'remarks'			=> ''
	                );
	    }
	    unset($rec);
	    if($hasSales == FALSE) { $age = NULL; }
	    
        if($hasSales)
        {
			// sales_main - select all customer by branch
	        $sql  = " SELECT ";
	        $sql .= "    tabSALES.s_id, tabSALES.s_branch_id, tabSALES.s_sales_date, tabSALES.s_customer_id,  ";
	        $sql .= "    tabSALES.s_firstmonthlydue_date AS firstDueDate, tabSALES.s_payment_term as pmtTerm, tabSALES.s_monthlyamortization, ";
	        $sql .= "    CONCAT(UPPER(tabCUST.c_lastname), ', ', tabCUST.c_firstname, ' ', c_middlename) as customerName, ";
	        $sql .= "    tabBRANCH.b_name, tabAREA.a_id, tabAREA.a_name  ";
//        $sql .= "    tabSUB.s_sub_id, tabSUB.s_product_id ";
	        $sql .= " FROM sales_main AS tabSALES ";
	        $sql .= "    JOIN (customers AS tabCUST  ) ON tabCUST.c_id        = tabSALES.s_customer_id ";
//        $sql .= "    JOIN (sales_sub AS tabSUB   ) ON tabSUB.s_sales_id   = tabSALES.s_id ";
	        $sql .= "    JOIN (branches  AS tabBRANCH) ON tabBRANCH.b_id      = tabSALES.s_branch_id ";
	        $sql .= "    JOIN (areas     AS tabAREA  ) ON tabAREA.a_branch_id = tabSALES.s_branch_id ";
	        $sql .= " WHERE ";
	        $sql .= "    tabSALES.s_branch_id   = $branchID AND ";
	        $sql .= "    tabSALES.s_sales_date <= '$endDate' AND ";
	        $sql .= "    tabSALES.s_ispaid      = 'N' AND ";
	        $sql .= "    tabAREA.a_id           = $areaID ";
	        $sql .= " ORDER BY customerName, firstDueDate ";
//			$rec = mysql_query($sql) or die('Query failed: ' . mysql_error());
			$rec = mysql_query($sql);
		    while ($sales = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		        $ageSalesList[] = array
		                            (  'salesID'        => $sales['s_id'],  
		                                'branchID'      => $sales['s_branch_id'] , 
		                                'branchName'    => $sales['b_name'], 
		                                'areaID'        => $sales['a_id'], 
		                                'areaName'      => $sales['a_name'], 
		                                'customerID'    => $sales['s_customer_id'], 
		                                'customerName'  => $sales['customerName'], 
		                                'salesDate'     => $sales['s_sales_date'],   
//	                                'salesSubID'    => $sales['s_sub_id'],   
//	                                'salesProdID'   => $sales['s_product_id'],   
		                                'pmtTerm'       => $sales['pmtTerm'], 
		                                'pmtFirstDueDate'=>$sales['firstDueDate'], 
		                                'pmtDueAmt'     => $sales['s_monthlyamortization'] 
		                            );
		    }
		    unset($rec);
		    
            // get payments
	        $sql  = " SELECT ";
	        $sql .= "	tabPMT.*, tabSALES.s_customer_id,  ";
	        $sql .= "	CONCAT(UPPER(tabCUST.c_lastname), ', ', tabCUST.c_firstname, ' ', c_middlename) as customerName, ";
	        $sql .= "	tabSALES.s_id, tabBRANCH.b_name, tabAREA.a_id, tabAREA.a_name ";
	        $sql .= " FROM payments AS tabPMT ";
	        $sql .= "	JOIN (sales_main AS tabSALES) ON tabSALES.s_id		= tabPMT.p_sales_id  ";
	        $sql .= "	JOIN (customers AS tabCUST  ) ON tabCUST.c_id		= tabSALES.s_customer_id  ";
	        $sql .= "	JOIN (branches  AS tabBRANCH) ON tabBRANCH.b_id 	= tabPMT.p_branch_id  ";
	        $sql .= "	JOIN (areas     AS tabAREA  ) ON tabAREA.a_branch_id= tabPMT.p_branch_id  ";
	        $sql .= "WHERE  ";
	        $sql .= "	tabPMT.p_branch_id    = $branchID     AND  ";
	        $sql .= "	tabAREA.a_id		  = $areaID       AND ";
	        $sql .= "	tabPMT.p_collector_id = $collectorID  AND ";
	        $sql .= "	tabPMT.p_date		 <= '$endDate' ";
	        $sql .= " ORDER BY tabPMT.p_date ";

			$rec = mysql_query($sql) or die('Query failed: ' . mysql_error());
		    while ($pmt = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		    	$dpSecondAmt = 0;
		    	if($pmt['p_is_downpayment'] == 2) { $dpSecondAmt = $pmt['p_amount']; }
		        $agePmtList[] = array
		                            (   'pmtID'      	=> $pmt['p_id'],  
		                                'salesID'       => $pmt['p_sales_id'], 
		                                'branchID'      => $pmt['p_branch_id'] , 
		                                'branchName'    => $pmt['b_name'], 
		                                'areaID'        => $pmt['a_id'], 
		                                'areaName'      => $pmt['a_name'], 
		                                'collectorID'	=> $pmt['p_collector_id'], 
		                                'customerID'    => $pmt['s_customer_id'], 
		                                'customerName'	=> $pmt['customerName'], 
		                                'paidDate'  	=> $pmt['p_date'],   
		                                'paidOR'		=> $pmt['p_or'], 
		                                'paidAmt'		=> $pmt['p_amount'], 
		                                'paidRebate'	=> $pmt['p_rebate'],
		                                'paidDPSecondAmt'=> $dpSecondAmt
		                            );
		    }
		    unset($rec);
		    
            // for each sales record, create the terms list 
            for($i = 0; $i <= count($ageSalesList)-1; $i++)
            {
                $salesID        = $ageSalesList[$i]['salesID'];
//                $salesSubID     = $ageSalesList[$i]['salesSubID'];
//                $prodID         = $ageSalesList[$i]['salesProdID'];
                $customerID     = $ageSalesList[$i]['customerID'];
	            $branchID       = $ageSalesList[$i]['branchID'];
                $customerName   = $ageSalesList[$i]['customerName'];
                $dueDate        = $ageSalesList[$i]['pmtFirstDueDate'];
                $dueAmt         = $ageSalesList[$i]['pmtDueAmt'];
                $term           = $ageSalesList[$i]['pmtTerm'];
                for($j = 1; $j <= $term; $j++)
                {
                    if($j > 1 ) { $dueDate = strtotime(date("Y-m-d", strtotime($dueDate)) . ' -1 month'); }
                    if($dueDate <= $endDate)
                    {
                        $ageSalesSchedule[] = array(
                                                    'salesID'    => $salesID,    'branchID'     => $branchID,
//                                                    'salesSubID' => $salesSubID, 'productID'    => $prodID,  
                                                    'customerID' => $customerID, 'customerName' => $customerName,  
                                                    'pmtDueDate' => $dueDate,    'pmtDueAmt'    => $dueAmt,
                                                    'paidDate'	 => '',			 'paidAmt'		=> 0,
                                                    'paidRebate' => 0,			 'paidDPSecondAmt'=> 0
                                                    );
                    }
                }
            }
		    // merge schedules and payments
		    for($i = 0; $i <= count($ageSalesSchedule)-1; $i++)
		    {
		    	$schedMonYR   = date("Y-m", strtotime($ageSalesSchedule[$i]['pmtDueDate']));
		    	$schedSalesID = $ageSalesSchedule[$i]['salesID']+0;
			    for($j = 0; $j <= count($agePmtList)-1; $j++)
			    {
			    	$paidMonYR   = date("Y-m", strtotime($agePmtList[$j]['paidDate']));
			    	$paidSalesID = $agePmtList[$j]['salesID']+0;
			    	if($schedMonYR == $paidMonYR && $schedSalesID == $paidSalesID)
			    	{
			    		$ageSalesSchedule[$i]['paidDate']	= $agePmtList[$j]['paidDate'];
			    		$ageSalesSchedule[$i]['paidAmt']	= $agePmtList[$j]['paidAmt']	+ 0;
			    		$ageSalesSchedule[$i]['paidRebate'] = $agePmtList[$j]['paidRebate']	+ 0;
			    		$ageSalesSchedule[$i]['paidDPSecondAmt'] = $agePmtList[$j]['paidDPSecondAmt'] + 0;
			    		$break;
			    	}
			    }
		    }

		    // aggregate list
		    for($i = 0; $i <= count($age) -1; $i++)
		    {
			    for($j = 0; $j <= count($ageSalesSchedule) -1; $j++)
			    {
			    	$thisMonYr = date("Y-m", strtotime($endDate));
					$due1Month = strtotime(date("Y-m-d", strtotime($endDate)) . ' -1 month'); 
					$due2Month = strtotime(date("Y-m-d", strtotime($endDate)) . ' -2 month'); 
					$due3Month = strtotime(date("Y-m-d", strtotime($endDate)) . ' -3 month'); 
			    	
			    	$dueCurrent = 0;
			    	$amt1Month  = 0;
			    	$amt2Month  = 0;
			    	$amt3Month  = 0;
			    	$totalPmt	= 0;
			    	$totalRebate= 0;
			    	$totalDue	= 0;
			    	$dpSecondAmt= 0;
			    	if( $age[$i]['customerID'] ==  $ageSalesSchedule[$j]['customerID']  )
			    	{
			    		$schedMonYR = date("Y-m", strtotime($ageSalesSchedule[$j]['pmtDueDate']));
			    		if( $schedMonYR <=  $thisMonYr ) { $dueCurrent += $ageSalesSchedule[$j]['pmtDueAmt'] + 0; }
			    		if( $schedMonYR ==  $due1Month ) { $amt1Month  += $ageSalesSchedule[$j]['pmtDueAmt'] + 0; }
			    		if( $schedMonYR ==  $due2Month ) { $amt2Month  += $ageSalesSchedule[$j]['pmtDueAmt'] + 0; }
			    		if( $schedMonYR <=  $due3Month ) { $amt3Month  += $ageSalesSchedule[$j]['pmtDueAmt'] + 0; }
			    		$totalPmt	 += $ageSalesSchedule[$j]['pmtDueAmt']  + 0;
			    		$totalRebate += $ageSalesSchedule[$j]['paidRebate'] + 0;
			    		$totalDue	  = $dueCurrent + $amt1Month + $amt2Month + $amt3Month;
			    		$dpSecondAmt  = $ageSalesSchedule[$j]['paidDPSecondAmt'] + 0;
			    	}
			    }
		    	$age[$i]['totalPayments']= $totalPmt;
		    	$age[$i]['totalRebates'] = $totalRebate;
		    	$age[$i]['dpSecondAmt']  = $dpSecondAmt;
		    	$age[$i]['dueThisMonth'] = $dueCurrent;
		    	$age[$i]['due1Month']    = $amt1Month;
		    	$age[$i]['due2Month']    = $amt2Month;
		    	$age[$i]['due3Month']    = $amt3Month;
		    	$age[$i]['dueTotal']     = $totalDue;
		    }
        }
        unset( $ageSalesSchedule );
        unset( $agePmtList );
        unset( $ageSalesList );
        unset( $sql );
		return $age;
	}
	
	private function drawColumnHeading()
	{
			$fontHAdd  = 4;
			$minLeft   = PDF_MARGIN_LEFT - 6;
			$minTop    = $this->getY() + 18;
			$pageWidth = $this->getPageWidth() - PDF_MARGIN_LEFT + 5;
			$pgHeight  = $this->getPageHeight() - PDF_MARGIN_BOTTOM + $fontHAdd + 1;

			// boxes
			$this->Line($minLeft	, $minTop - 2 , $pageWidth , $minTop - 2);
			$this->Line($minLeft	, $minTop + 6, $pageWidth , $minTop + 6);
			$this->Line($minLeft	, $minTop - 2 , $minLeft   , $pgHeight);
			$this->Line($pageWidth	, $minTop - 2 , $pageWidth , $pgHeight);
			$this->Line($pageWidth	, $pgHeight	, $pageWidth , $pgHeight);
			
			$this->Line($minLeft +  50 , $minTop-2	, $minLeft +  50 , $pgHeight);
			$this->Line($minLeft +  76 , $minTop-2	, $minLeft +  76 , $pgHeight);
			$this->Line($minLeft +  96 , $minTop-2	, $minLeft +  96 , $pgHeight);
			
			$this->Line($minLeft + 122 , $minTop-2	, $minLeft + 122 , $pgHeight);
			$this->Line($minLeft + 142 , $minTop-2	, $minLeft + 142 , $pgHeight);
			$this->Line($minLeft + 167 , $minTop-2	, $minLeft + 167 , $pgHeight);

			$this->Line($minLeft + 193 , $minTop-2	, $minLeft + 193 , $pgHeight);
			$this->Line($minLeft + 219 , $minTop-2	, $minLeft + 219 , $pgHeight);
			$this->Line($minLeft + 245 , $minTop-2	, $minLeft + 245 , $pgHeight);

			$this->Line($minLeft + 271 , $minTop-2	, $minLeft + 271 , $pgHeight);
			$this->Line($minLeft + 297 , $minTop-2	, $minLeft + 297 , $pgHeight);

			// headers
			$this->setXY($minLeft, $this->getY() + 18)  ; $this->Cell(50 , 0, ' Customer Name', 0, 0, 'L' );
			$this->setXY($minLeft +  50, $this->getY() ); $this->Cell(26 , 0, 'Payments ', 0, 0, 'R' );
			$this->setXY($minLeft +  76, $this->getY() ); $this->Cell(20 , 0, 'Rebates ', 0, 0, 'R' );
			$this->setXY($minLeft +  96, $this->getY() ); $this->Cell(26 , 0, 'Balance ', 0, 0, 'R' );
			
			$this->setXY($minLeft + 122, $this->getY() ); $this->Cell(20 , 0, 'MI   ', 0, 0, 'R' );
			$this->setXY($minLeft + 142, $this->getY() ); $this->Cell(26 , 0, '2nd DownPmt ', 0, 0, 'R' );
			$this->setXY($minLeft + 167, $this->getY() ); $this->Cell(26 , 0, 'Current Due ', 0, 0, 'R' );
			
			$this->setXY($minLeft + 193, $this->getY() ); $this->Cell(26 , 0, 'Due 1mo ', 0, 0, 'R' );
			$this->setXY($minLeft + 219, $this->getY() ); $this->Cell(26 , 0, 'Due 2mos ', 0, 0, 'R' );
			$this->setXY($minLeft + 245, $this->getY() ); $this->Cell(26 , 0, 'Due 3mos ', 0, 0, 'R' );
			
			$this->setXY($minLeft + 271, $this->getY() ); $this->Cell(26 , 0, 'Total Due ', 0, 0, 'R' );
			$this->setXY($minLeft + 297, $this->getY() ); $this->Cell(40 , 0, ' Remarks', 0, 0, 'L' );
	}

	public function reportLayout($d = NULL)
	{
		if(is_array($d))
		{
			$fontHAdd  = 4;
			$minLeft   = PDF_MARGIN_LEFT - 6;
			if($this->PageNo() == 1) { $this->drawColumnHeading(); }
			$this->setXY($minLeft , $this->getY() + $fontHAdd );

			for($i = 0; $i <= count($d)-1; $i++)
			{
				$this->setXY($minLeft      , $this->getY() + $fontHAdd); $this->Cell(50 , 0, $d[$i]['customerName'], 0, 0, 'L' );

				if(is_numeric( $d[$i]['totalPayments'] )) {  $w = new NumWords( $d[$i]['totalPayments'] ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft +  50, $this->getY() ); $this->Cell(26 , 0, $v, 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['totalRebates'] )) {  $w = new NumWords( $d[$i]['totalRebates'] ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft +  76, $this->getY() ); $this->Cell(20 , 0, $v, 0, 0, 'R' );
				
				$balance = $d[$i]['totalSales'] - $d[$i]['totalPayments'] - $d[$i]['totalRebates'];
				if(is_numeric( $balance )) {  $w = new NumWords( $balance ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft +  96, $this->getY() ); $this->Cell(26 , 0, $v, 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['monthlyInstallment'] )) {  $w = new NumWords( $d[$i]['monthlyInstallment'] ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft + 122, $this->getY() ); $this->Cell(20 , 0, $v, 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['dpSecondAmt'] )) {  $w = new NumWords( $d[$i]['dpSecondAmt'] ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft + 142, $this->getY() ); $this->Cell(26 , 0, $v, 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['dueThisMonth'] )) {  $w = new NumWords( $d[$i]['dueThisMonth'] ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft + 167, $this->getY() ); $this->Cell(26 , 0, $v, 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['due1Month']  )) {  $w = new NumWords( $d[$i]['due1Month'] ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft + 193, $this->getY() ); $this->Cell(26 , 0, $v, 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['due2Month'] )) {  $w = new NumWords( $d[$i]['due2Month'] ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft + 219, $this->getY() ); $this->Cell(26 , 0, $v, 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['due3Month'] )) {  $w = new NumWords( $d[$i]['due3Month'] ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft + 245, $this->getY() ); $this->Cell(26 , 0, $v, 0, 0, 'R' );
				
				if(is_numeric( $d[$i]['dueTotal'] )) {  $w = new NumWords( $d[$i]['dueTotal'] ); $v = $w->number; unset($w); } else { $v = ''; }
				$this->setXY($minLeft + 271, $this->getY() ); $this->Cell(26 , 0, $v, 0, 0, 'R' );
				
				$this->setXY($minLeft + 297, $this->getY() ); $this->Cell(40 , 0, ' ', 0, 0, 'L' );
				if( (round($this->getPageHeight()) - PDF_MARGIN_BOTTOM - $this->getY()) <= $fontHAdd )
				{
					$this->AddPage();
					if($this->PageNo() > 1) { $this->drawColumnHeading(); }
					$this->setXY($minLeft , $this->getY() + $fontHAdd );
				}
			}
		}	
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords("WAIS, " . rptARAgingAcctsTitle);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setBranch($branchID);
$pdf->setArea($areaID);
$pdf->setCollector($collectorID);
$pdf->setEndingDate($endDate);
$d = $pdf->getAccountsAge($endDate, $branchID, $areaID, $collectorID);
$pdf->setCompanyName(rptCompanyName);
$pdf->setReportTitle(rptARAgingAcctsTitle);
$pdf->setReportName(rptARAgingAcctsName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size
$pdf->SetAutoPageBreak(TRUE, 2);
$pdf->setFooterMargin(12);
$pdf->AddPage('L', 'LEGAL');
$pdf->reportLayout( $d );
$pdf->Output($pdf->getReportName().'.pdf', 'I');
?>
