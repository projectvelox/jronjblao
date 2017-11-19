<?php

//===================================================//
//=====  REPORT : CREDIT INVESTIGATION          =====//
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
$customerID	= 0;
//if(isset($_SESSION['customerID'])) {  $customerID  = $_SESSION['customerID'];  unset($_SESSION['customerID']); }
if(isset($_SESSION['customerID'])) {  $customerID  = $_SESSION['customerID'];  }
if($customerID == 0) { $customerID == NULL; }


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
	
	public function getCustomerCreditInv($id = NULL)
	{
		$ci  = array();
		$sql = '';
		if(!$id == NULL)
		{
			$sql   = " SELECT tabCI.*, ";
			$sql  .= "		(SELECT CONCAT(c_firstname,' ', c_middlename,' ', c_lastname) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = customer_ID ) AS customerName, ";
			$sql  .= "		(SELECT CONCAT(c_address_street,', ', c_address_town,', ', c_address_city) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = customer_ID ) AS customerAddress, ";
			$sql  .= "		(SELECT c_address_zipcode FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = customer_ID ) AS customerZip, ";
			$sql  .= "		(SELECT c_address_country FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = customer_ID ) AS countryID, ";
			$sql  .= "		(SELECT value FROM options_country AS tabCOUNTRY WHERE tabCOUNTRY.id = countryID ) AS customerCountry,  ";
			$sql  .= "		(SELECT p_name FROM products AS tabPROD WHERE tabPROD.p_id  = tabCI.ciProdID ) AS productName, ";
			$sql  .= "		(SELECT CONCAT(e_lastname,', ', e_firstname, ' ', e_middlename) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabCI.ciRecommendBy) AS recommendedBy,  ";
			$sql  .= "		(SELECT CONCAT(e_lastname,', ', e_firstname, ' ', e_middlename) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabCI.ciApprovedBy ) AS approvedBy,   ";
			$sql  .= "		(SELECT CONCAT(e_lastname,', ', e_firstname, ' ', e_middlename) FROM employees AS tabEMPL WHERE tabEMPL.e_id = tabCI.ciNotedBy	  ) AS notedBy ";
			$sql  .= " FROM form_ci AS tabCI ";
			$sql  .= " WHERE tabCI.customer_ID = $id ";
			$rec = mysql_query($sql) ;
		    while ($w = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		    	$ci = array(
		    			'customerName'		=> $w['customerName'],
		    			'customerAddress'	=> $w['customerAddress'].' '.$w['customerCountry'],
		    			'prodName'			=> $w['productName'],
		    			'ciDate'			=> date("m/d/Y", strtotime($w['ciDate'])),
		    			'ciMonthlyAmort'	=> $w['ciMonthlyAmort'] + 0,
		    			'ciAmount'			=> $w['ciAmount'] + 0,
		    			'ciTerm'			=> $w['ciTerm'] + 0,
		    			'ciIsCustStatusNew' 	=> ($w['ciIsCustStatus']== 'N'? 'X' : ''),
		    			'ciIsCustStatusRepeat'	=> ($w['ciIsCustStatus']== 'R'? 'X' : ''),
		    			'ciIsPmtRecordGood' 	=> ($w['ciIsPmtRecord']	== 'G'? 'X' : ''),
		    			'ciIsPmtRecordFair' 	=> ($w['ciIsPmtRecord']	== 'F'? 'X' : ''),
		    			'ciIsPmtRecordPoor' 	=> ($w['ciIsPmtRecord']	== 'P'? 'X' : ''),
		    			'ciNCPlaceResMVAccess'	=> ($w['ciNCPlaceRes']	== 'A'? 'X' : ''),
		    			'ciNCPlaceResMVNoAccess'=> ($w['ciNCPlaceRes']	== 'N'? 'X' : ''),
		    			'ciNCResLocStreet'		=> ($w['ciNCResLocStreet']		== 'Y'? 'X' : ''),
		    			'ciNCResLocStAlley' 	=> ($w['ciNCResLocAlley']		== 'Y'? 'X' : ''),
		    			'ciNCResLocRailroad'	=> ($w['ciNCResLocRailroad']	== 'Y'? 'X' : ''),
		    			'ciNCResLocInterior'	=> ($w['ciNCResLocInterior']	== 'Y'? 'X' : ''),
		    			'ciNCAreaResBusy'		=> ($w['ciNCAreaResBusy']		== 'Y'? 'X' : ''),
		    			'ciNCAreaResPeaceful'	=> ($w['ciNCAreaResPeaceful']	== 'Y'? 'X' : ''),
		    			'ciNCAreaResCritical'	=> ($w['ciNCAreaResCritical']	== 'Y'? 'X' : ''),
		    			'ciNCAreaResThreatened' => ($w['ciNCAreaResThreatened'] == 'Y'? 'X' : ''),
		    			'ciNCResMadeConcrete'	=> ($w['ciNCResMadeConcrete']	== 'Y'? 'X' : ''),
		    			'ciNCResMadeSemi'		=> ($w['ciNCResMadeSemi']		== 'Y'? 'X' : ''),
		    			'ciNCResMadeWood'		=> ($w['ciNCResMadeWood']		== 'Y'? 'X' : ''),
		    			'ciNCResMadeLight'		=> ($w['ciNCResMadeLight']		== 'Y'? 'X' : ''),
		    			'ciBCKnownAreaFriendly' => ($w['ciBCKnownAreaFriendly']	== 'Y'? 'X' : ''),
		    			'ciBCKnownAreaReclusive'=> ($w['ciBCKnownAreaReclusive']== 'Y'? 'X' : ''),
		    			'ciBCKnownAreaNotorious'=> ($w['ciBCKnownAreaNotorious']== 'Y'? 'X' : ''),
		    			'ciBCKnownAreaGood' 	=> ($w['ciBCKnownAreaGood']		== 'Y'? 'X' : ''),
		    			'ciReligion'			=> $w['ciReligion'],
		    			'ciBCEngageDrink'		=> ($w['ciBCEngageDrink']	  == 'Y'? 'X' : ''),
		    			'ciBCEngageInfidelity'	=> ($w['ciBCEngageInfidelity']== 'Y'? 'X' : ''),
		    			'ciBCEngageLiveIn'		=> ($w['ciBCEngageLiveIn']	  == 'Y'? 'X' : ''),
		    			'ciBCEngageGambling'	=> ($w['ciBCEngageGambling']  == 'Y'? 'X' : ''),
		    			'ciBCEngageIllegal' 	=> ($w['ciBCEngageIllegal']	  == 'Y'? 'X' : ''),
		    			'ciBCEngageNoVice'		=> ($w['ciBCEngageNoVice']	  == 'Y'? 'X' : ''),
		    			'ciBCResIsOwned'		=> ($w['ciBCResidenceIs']		== 'O'? 'X' : ''),
		    			'ciBCResIsRented'		=> ($w['ciBCResidenceIs']		== 'E'? 'X' : ''),
		    			'ciBCResIsRelative' 	=> ($w['ciBCResidenceIs']		== 'R'? 'X' : ''),
		    			'ciBCResIsBoarder'		=> ($w['ciBCResidenceIs']		== 'B'? 'X' : ''),
		    			'ciBCResideHowLongNum'  => $w['ciBCResideHowLongNum'],
		    			'ciBCResideHowLongMonth'=> ($w['ciBCResideHowLongMoYr'] == 'M'? 'X' : ''),
		    			'ciBCResideHowLongYear' => ($w['ciBCResideHowLongMoYr'] == 'Y'? 'X' : ''),
		    			'ciBCResourceOthers'	=> $w['ciBCResourceOthers'],
		    			'ciBCMonthlyRent'		=> $w['ciBCMonthlyRent'],
		    			'ciBCDependentName1'	=> $w['ciBCDependentName1'],
		    			'ciBCDependentRelation1'=> $w['ciBCDependentRelation1'],
		    			'ciBCDependentName2'	=> $w['ciBCDependentName2'],
		    			'ciBCDependentRelation2'=> $w['ciBCDependentRelation2'],
		    			'ciBCDependentName3'	=> $w['ciBCDependentName3'],
		    			'ciBCDependentRelation3'=> $w['ciBCDependentRelation3'],
		    			'ciBCKnownComGood'		=> ($w['ciBCKnownComGood']		== 'Y'? 'X' : ''),
		    			'ciBCKnownComSickly'	=> ($w['ciBCKnownComSickly'] 	== 'Y'? 'X' : ''),
		    			'ciBCKnownComDisabled'	=> ($w['ciBCKnownComDisabled']	== 'Y'? 'X' : ''),
		    			'ciBCKnownComIncurable'	=> ($w['ciBCKnownComIncurable']	== 'Y'? 'X' : ''),
		    			'ciBCKnownComHealthProb'=> ($w['ciBCKnownComHealthProb']== 'Y'? 'X' : ''),
		    			'ciBCKnownComDID'		=> $w['ciBCKnownComDID'],
		    			'ciBCKnownComWHP'		=> $w['ciBCKnownComWHP'],
		    			'ciBCOtherColY' 		=> ($w['ciBCOtherCollector'] 	== 'Y'? 'X' : ''),
		    			'ciBCOtherColN' 		=> ($w['ciBCOtherCollector'] 	== 'N'? 'X' : ''),
		    			'ciBCOtherCollectorCnt' => $w['ciBCOtherCollectorCnt'],
		    			'ciBCOtherCollectorOften' => $w['ciBCOtherCollectorOften'],
		    			'ciRCFurnishFurnish'	=> ($w['ciRCFurnish'] 	== 'F'? 'X' : ''),
		    			'ciRCFurnishSemi'		=> ($w['ciRCFurnish'] 	== 'S'? 'X' : ''),
		    			'ciRCFurnishNotFur' 	=> ($w['ciRCFurnish']	== 'N'? 'X' : ''),
		    			'ciRCFurnUpholster'		=> ($w['ciRCFurnUpholster'] == 'Y'? 'X' : ''),
		    			'ciRCFurnWood'			=> ($w['ciRCFurnWood']		== 'Y'? 'X' : ''),
		    			'ciRCFurnMonobloc'		=> ($w['ciRCFurnMonobloc'] 	== 'Y'? 'X' : ''),
		    			'ciRCFurnNone'			=> ($w['ciRCFurnNone'] 		== 'Y'? 'X' : ''),
		    			'ciRCAppRef'			=> ($w['ciRCAppRef']		== 'Y'? 'X' : ''),
		    			'ciRCAppTV'				=> ($w['ciRCAppTV']			== 'Y'? 'X' : ''),
		    			'ciRCAppDVD'			=> ($w['ciRCAppDVD']		== 'Y'? 'X' : ''),
		    			'ciRCAppAC' 			=> ($w['ciRCAppAC'] 		== 'Y'? 'X' : ''),
		    			'ciRCAppWM' 			=> ($w['ciRCAppWM'] 		== 'Y'? 'X' : ''),
		    			'ciRCAppGR' 			=> ($w['ciRCAppGR'] 		== 'Y'? 'X' : ''),
		    			'ciRCAppOth'			=> ($w['ciRCAppOth']		== 'Y'? 'X' : ''),
		    			'ciRCAppTxt'			=> $w['ciRCAppTxt'],
		    			'ciRCPurchasedCash' 	=> ($w['ciRCPurchased'] 	== 'C'? 'X' : ''),
		    			'ciRCPurchasedInstall'	=> ($w['ciRCPurchased'] 	== 'I'? 'X' : ''),
		    			'ciRCPurchasedTerm' 	=> $w['ciRCPurchasedTerm'],
		    			'ciRCPurchasedFin'		=> $w['ciRCPurchasedFin'],
		    			'ciRCPurchasedAmt'		=> $w['ciRCPurchasedAmt'],
		    			'ciRCPurchasedMA'		=> $w['ciRCPurchasedMA'],
		    			'ciRCPurchasedP'		=> $w['ciRCPurchasedP'],
		    			'ciRCPayUtilsOnTime'	=> ($w['ciRCPayUtils'] 	== 'O'? 'X' : ''),
		    			'ciRCPayUtilsLate'		=> ($w['ciRCPayUtils'] 	== 'L'? 'X' : ''),
		    			'ciRCPayUtilsNever' 	=> ($w['ciRCPayUtils'] 	== 'N'? 'X' : ''),
		    			'ciRCPersonName1'		=> $w['ciRCPersonName1'],
		    			'ciRCPersonAddress1'	=> $w['ciRCPersonAddress1'],
		    			'ciRCPersonName2'		=> $w['ciRCPersonName2'],
		    			'ciRCPersonAddress2'	=> $w['ciRCPersonAddress2'],
		    			'ciBUSName'				=> $w['ciBUSName'],
		    			'ciBUSNature'			=> $w['ciBUSNature'],
		    			'ciBUSAddress'			=> $w['ciBUSAddress'],
		    			'ciBUSPosition'			=> $w['ciBUSPosition'],
		    			'ciBUSYrs'				=> $w['ciBUSYrs'],
		    			'ciBUSMos'				=> $w['ciBUSMos'],
		    			'ciBUSIncome'			=> $w['ciBUSIncome'],
		    			'ciBUSPersonName1'		=> $w['ciBUSPersonName1'],
		    			'ciBUSPersonAddress1'	=> $w['ciBUSPersonAddress1'],
		    			'ciBUSPersonName2'		=> $w['ciBUSPersonName2'],
		    			'ciBUSPersonAddress2'	=> $w['ciBUSPersonAddress2'],
		    			'ciBCBankPoor'			=> ($w['ciBCBankPoor']   == 'Y'? 'X' : ''),
		    			'ciBCBankSaving'		=> ($w['ciBCBankSaving'] == 'Y'? 'X' : ''),
		    			'ciBCBankProper'		=> ($w['ciBCBankProper'] == 'Y'? 'X' : ''),
		    			'ciBCBankValued'		=> ($w['ciBCBankValued'] == 'Y'? 'X' : ''),
		    			'ciBCBankNoBank'		=> ($w['ciBCBankNoBank'] == 'Y'? 'X' : ''),
		    			'ciBCBankLength'		=> $w['ciBCBankLength'],
		    			'ciBCResourceName'		=> $w['ciBCResourceName'],
		    			'ciBCResourceAddress'	=> $w['ciBCResourceAddress'],
		    			'ciBCBankNameAddress'	=> $w['ciBCBankNameAddress'],
		    			'ciPurposeUtils'		=> ($w['ciPurposeUtils']	== 'Y'? 'X' : ''),
		    			'ciPurposePersonal' 	=> ($w['ciPurposePersonal'] == 'Y'? 'X' : ''),
		    			'ciPurposeBusiness' 	=> ($w['ciPurposeBusiness'] == 'Y'? 'X' : ''),
		    			'ciPurposeHabal'		=> ($w['ciPurposeHabal']	== 'Y'? 'X' : ''),
		    			'ciPurposeOther'		=> ($w['ciPurposeOther']	== 'Y'? 'X' : ''),
		    			'ciLoanPurposeTxt'		=> $w['ciLoanPurposeTxt'],
		    			'ciDIApplicant' 		=> $w['ciDIApplicant'],
		    			'ciDISpouse'			=> $w['ciDISpouse'],
		    			'ciDIDeductions'		=> $w['ciDIDeductions'],
		    			'ciDIMonthlyIncome' 	=> $w['ciDIMonthlyIncome'],
		    			'ciDILivingExp' 		=> $w['ciDILivingExp'],
		    			'ciDINetDispInc'		=> $w['ciDINetDispInc'],
		    			'ciDIFood'				=> $w['ciDIFood'],
		    			'ciDIRent'				=> $w['ciDIRent'],
		    			'ciDITransport' 		=> $w['ciDITransport'],
		    			'ciDIUtils' 			=> $w['ciDIUtils'],
		    			'ciDIEdu'				=> $w['ciDIEdu'],
		    			'ciDIMA'				=> $w['ciDIMA'],
		    			'ciDIOthers'			=> $w['ciDIOthers'],
		    			'ciRecCharacter'		=> $w['ciRecCharacter'],
		    			'ciRecCapacity' 		=> $w['ciRecCapacity'],
		    			'ciRecCapital'			=> $w['ciRecCapital'],
		    			'ciRecCondition'		=> $w['ciRecCondition'],
		    			'ciRefName' 			=> $w['ciRefName'],
		    			'ciRefAddress'			=> $w['ciRefAddress'],
		    			'ciRefPhoneNo'			=> $w['ciRefPhoneNo'],
		    			'ciIsApprovedApproved'  => ($w['ciIsApproved']	== 'A'? 'X' : ''),
		    			'ciIsApprovedDenied'	=> ($w['ciIsApproved']	== 'D'? 'X' : ''),
		    			'recommendBy'			=> $w['recommendedBy'],
		    			'approvedBy'			=> $w['approvedBy'],
		    			'notedBy'				=> $w['notedBy'],
		    			'recommendDate' 		=> date("m/d/Y", strtotime($w['ciRecommendDate'])),
		    			'approvedDate'			=> date("m/d/Y", strtotime($w['ciApprovedDate'])),
		    			'notedDate' 			=> date("m/d/Y", strtotime($w['ciNotedDate']))
		    		  );
		    }
		}
		return $ci;
	}

	public function reportLayout($d) 
	{
		$this->setXY(0,0);
		if(is_array($d))
		{
			// page 1
			$this->setXY( 34 ,  35);  $this->Cell(0,0, $d['customerName'] );
			if(is_numeric( $d['ciMonthlyAmort'])) {  $w = new NumWords( $d['ciMonthlyAmort'] ); $v = 'P ' . $w->number; unset($w); } else { $v = ''; }
			$this->setXY(120 ,  35);  $this->Cell(0,0, $v );
			if(($d['ciDate'])) { $v = date("m/d/Y", strtotime($d['ciDate'])); } else { $v = ''; }
			$this->setXY(174 , 35);  $this->Cell(0,0, $v );
			
			$this->setXY( 40 ,  40);  $this->Cell(0,0, $d['prodName'] );
			if(is_numeric( $d['ciAmount'])) {  $w = new NumWords( $d['ciAmount'] ); $v = 'P ' . $w->number; unset($w); } else { $v = ''; }
			$this->setXY(132 ,  40);  $this->Cell(0,0, $v );
			$this->setXY(174 ,  40);  $this->Cell(0,0, $d['ciTerm'] );
			
			$this->setXY( 34 ,  45);  $this->Cell(0,0, $d['customerAddress'] );

			$this->setXY( 22 ,  51);  $this->Cell(0,0, $d['ciIsCustStatusNew'] );
			$this->setXY( 77 ,  51);  $this->Cell(0,0, $d['ciIsCustStatusRepeat'] );
			
			$this->setXY(140 ,  51);  $this->Cell(0,0, $d['ciIsPmtRecordGood'] );
			$this->setXY(155 ,  51);  $this->Cell(0,0, $d['ciIsPmtRecordFair'] );
			$this->setXY(168 ,  51);  $this->Cell(0,0, $d['ciIsPmtRecordPoor'] );

			$this->setXY( 22 ,  71);  $this->Cell(0,0, $d['ciNCPlaceResMVAccess'] );
			$this->setXY( 84 ,  71);  $this->Cell(0,0, $d['ciNCPlaceResMVNoAccess'] );

			$this->setXY( 22 ,  71);  $this->Cell(0,0, $d['ciNCPlaceResMVAccess'] );
			$this->setXY( 84 ,  71);  $this->Cell(0,0, $d['ciNCPlaceResMVNoAccess'] );

			$this->setXY( 22 ,  81);  $this->Cell(0,0, $d['ciNCResLocStreet'] );
			$this->setXY( 64 ,  81);  $this->Cell(0,0, $d['ciNCResLocStAlley'] );
			$this->setXY( 98 ,  81);  $this->Cell(0,0, $d['ciNCResLocRailroad'] );
			$this->setXY(140 ,  81);  $this->Cell(0,0, $d['ciNCResLocInterior'] );

			$this->setXY( 22 ,  91);  $this->Cell(0,0, $d['ciNCAreaResBusy'] );
			$this->setXY( 50 ,  91);  $this->Cell(0,0, $d['ciNCAreaResPeaceful'] );
			$this->setXY( 78 ,  91);  $this->Cell(0,0, $d['ciNCAreaResCritical'] );
			$this->setXY(105 ,  91);  $this->Cell(0,0, $d['ciNCAreaResThreatened'] );

			$this->setXY( 22 , 101);  $this->Cell(0,0, $d['ciNCResMadeConcrete'] );
			$this->setXY( 57 , 101);  $this->Cell(0,0, $d['ciNCResMadeSemi'] );
			$this->setXY( 99 , 101);  $this->Cell(0,0, $d['ciNCResMadeWood'] );
			$this->setXY(126 , 101);  $this->Cell(0,0, $d['ciNCResMadeLight'] );

			$this->setXY( 22 , 121);  $this->Cell(0,0, $d['ciBCKnownAreaFriendly'] );
			$this->setXY( 44 , 121);  $this->Cell(0,0, $d['ciBCKnownAreaReclusive'] );
			$this->setXY( 70 , 121);  $this->Cell(0,0, $d['ciBCKnownAreaNotorious'] );
			$this->setXY( 98 , 121);  $this->Cell(0,0, $d['ciBCKnownAreaGood'] );

			$this->setXY( 32 , 126);  $this->Cell(0,0, $d['ciReligion'] );

			$this->setXY( 22 , 136);  $this->Cell(0,0, $d['ciBCEngageDrink'] );
			$this->setXY( 22 , 141);  $this->Cell(0,0, $d['ciBCEngageInfidelity'] );
			$this->setXY( 22 , 146);  $this->Cell(0,0, $d['ciBCEngageLiveIn'] );
			$this->setXY( 78 , 136);  $this->Cell(0,0, $d['ciBCEngageGambling'] );
			$this->setXY( 78 , 141);  $this->Cell(0,0, $d['ciBCEngageIllegal'] );
			$this->setXY( 78 , 146);  $this->Cell(0,0, $d['ciBCEngageNoVice'] );
			
			$this->setXY(146 , 121);  $this->MultiCell(60, 5, $d['ciBCResourceOthers'], 0, 'L');

			$this->setXY( 50 , 152);  $this->Cell(0,0, $d['ciBCResIsOwned'] );
			$this->setXY( 50 , 158);  $this->Cell(0,0, $d['ciBCResIsRented'] );
			$this->setXY( 99 , 152);  $this->Cell(0,0, $d['ciBCResIsRelative'] );
			$this->setXY( 99 , 158);  $this->Cell(0,0, $d['ciBCResIsBoarder'] );

			$this->setXY(174 , 157);  $this->Cell(0,0, $d['ciBCResideHowLongYear'] );
			$this->setXY(186 , 157);  $this->Cell(0,0, $d['ciBCResideHowLongMonth'] );
			$this->setXY(186 , 161);  $this->Cell(0,0, $d['ciBCResideHowLongNum'] );

			if(is_numeric( $d['ciBCMonthlyRent'])) {  $w = new NumWords( $d['ciBCMonthlyRent'] ); $v = $w->number; unset($w); } else { $v = ''; }
			$this->setXY(104 , 166);  $this->Cell(0,0, $v );

			$this->setXY( 58 , 176);  $this->Cell(0,0, $d['ciBCDependentName1'] );
			$this->setXY(162 , 176);  $this->Cell(0,0, $d['ciBCDependentRelation1'] );

			$this->setXY( 58 , 180);  $this->Cell(0,0, $d['ciBCDependentName2'] );
			$this->setXY(162 , 180);  $this->Cell(0,0, $d['ciBCDependentRelation2'] );

			$this->setXY( 58 , 185);  $this->Cell(0,0, $d['ciBCDependentName3'] );
			$this->setXY(162 , 185);  $this->Cell(0,0, $d['ciBCDependentRelation3'] );

			$this->setXY( 22 , 198);  $this->Cell(0,0, $d['ciBCKnownComGood'] );
			$this->setXY( 78 , 198);  $this->Cell(0,0, $d['ciBCKnownComSickly'] );
			$this->setXY(140 , 198);  $this->Cell(0,0, $d['ciBCKnownComDisabled'] );
			
			$this->setXY( 22 , 203);  $this->Cell(0,0, $d['ciBCKnownComIncurable'] );
			$this->setXY(126 , 203);  $this->Cell(0,0, $d['ciBCKnownComDID'] );
			$this->setXY( 22 , 208);  $this->Cell(0,0, $d['ciBCKnownComHealthProb'] );
			$this->setXY(126 , 208);  $this->Cell(0,0, $d['ciBCKnownComWHP'] );

			$this->setXY(140 , 216);  $this->Cell(0,0, $d['ciBCOtherColY'] );
			$this->setXY(168 , 216);  $this->Cell(0,0, $d['ciBCOtherColN'] );

			$this->setXY( 58 , 221);  $this->Cell(0,0, $d['ciBCOtherCollectorCnt'] );
			$this->setXY(116 , 221);  $this->Cell(0,0, $d['ciBCOtherCollectorOften'] );

			$this->setXY( 64 , 241);  $this->Cell(0,0, $d['ciRCFurnishFurnish'] );
			$this->setXY( 99 , 241);  $this->Cell(0,0, $d['ciRCFurnishSemi'] );
			$this->setXY(140 , 241);  $this->Cell(0,0, $d['ciRCFurnishNotFur'] );

			$this->setXY( 78 , 246);  $this->Cell(0,0, $d['ciRCFurnUpholster'] );
			$this->setXY(113 , 246);  $this->Cell(0,0, $d['ciRCFurnWood'] );
			$this->setXY(154 , 246);  $this->Cell(0,0, $d['ciRCFurnMonobloc'] );
			$this->setXY(184 , 246);  $this->Cell(0,0, $d['ciRCFurnNone'] );

			$this->setXY( 40 , 251);  $this->Cell(0,0, $d['ciRCAppRef'] );
			$this->setXY( 52 , 251);  $this->Cell(0,0, $d['ciRCAppTV'] );
			$this->setXY( 64 , 251);  $this->Cell(0,0, $d['ciRCAppDVD'] );
			$this->setXY(109 , 251);  $this->Cell(0,0, $d['ciRCAppAC'] );
			$this->setXY(133 , 251);  $this->Cell(0,0, $d['ciRCAppWM'] );
			$this->setXY(174 , 251);  $this->Cell(0,0, $d['ciRCAppGR'] );
			
			$this->setXY( 40 , 256);  $this->Cell(0,0, $d['ciRCAppOth'] );
			$this->setXY( 60 , 256);  $this->Cell(0,0, $d['ciRCAppTxt'] );

			$this->setXY( 72 , 261);  $this->Cell(0,0, $d['ciRCPurchasedCash'] );
			$this->setXY( 92 , 261);  $this->Cell(0,0, $d['ciRCPurchasedInstall'] );
			$this->setXY(164 , 261);  $this->Cell(0,0, $d['ciRCPurchasedTerm'] );

			$this->setXY( 66 , 266);  $this->Cell(0,0, $d['ciRCPurchasedFin'] );
			if(is_numeric( $d['ciRCPurchasedAmt'])) {  $w = new NumWords( $d['ciRCPurchasedAmt'] ); $v = 'P '.$w->number; unset($w); } else { $v = ''; }
			$this->setXY(140 , 266);  $this->Cell(0,0, $v );
			if(is_numeric( $d['ciRCPurchasedMA'])) {  $w = new NumWords( $d['ciRCPurchasedMA'] ); $v = $w->number; unset($w); } else { $v = ''; }
			$this->setXY(180 , 266);  $this->Cell(0,0, $v );

			$this->setXY( 23 , 282);  $this->Cell(0,0, $d['ciRCPayUtilsOnTime'] );
			$this->setXY( 58 , 282);  $this->Cell(0,0, $d['ciRCPayUtilsLate'] );
			$this->setXY( 92 , 282);  $this->Cell(0,0, $d['ciRCPayUtilsNever'] );

			$this->setXY( 86 , 287);  $this->Cell(0,0, $d['ciRCPersonName1'].', '.$d['ciRCPersonAddress1'] );
			$this->setXY( 86 , 291);  $this->Cell(0,0, $d['ciRCPersonName2'].', '.$d['ciRCPersonAddress2'] );

			$this->setXY( 38 , 300);  $this->Cell(0,0, $d['ciBUSName'] );
			$this->setXY(154 , 300);  $this->Cell(0,0, $d['ciBUSNature'] );
			
			$this->setXY( 38 , 305);  $this->Cell(0,0, $d['ciBUSAddress'] );
			
			$this->setXY( 38 , 310);  $this->Cell(0,0, $d['ciBUSPosition'] );
			$this->setXY( 98 , 310);  $this->Cell(0,0, $d['ciBUSYrs'] );
			$this->setXY(114 , 310);  $this->Cell(0,0, $d['ciBUSMos'] );
			if(is_numeric( $d['ciBUSIncome'])) {  $w = new NumWords( $d['ciBUSIncome'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(172 , 310);  $this->Cell(0,0, $v );

			$this->setXY( 80 , 316);  $this->Cell(0,0, $d['ciBUSPersonName1'].', '.$d['ciBUSPersonAddress1'] );
			$this->setXY( 80 , 320);  $this->Cell(0,0, $d['ciBUSPersonName2'].', '.$d['ciBUSPersonAddress2'] );

			// page 2
			$this->AddPage('P', 'LEGAL');
			$this->setXY(0,0);
			
			$this->setXY(174 ,  34);  $this->Cell(0,0, $d['ciBCBankLength'] );
			
			$this->setXY( 18 ,  40);  $this->Cell(0,0, $d['ciBCBankPoor'] );
			$this->setXY( 94 ,  40);  $this->Cell(0,0, $d['ciBCBankProper'] );

			$this->setXY( 18 ,  45);  $this->Cell(0,0, $d['ciBCBankSaving'] );
			$this->setXY( 88 ,  45);  $this->Cell(0,0, $d['ciBCBankValued'] );
			$this->setXY(136 ,  45);  $this->Cell(0,0, $d['ciBCBankNoBank'] );

			$this->setXY( 82 , 49);  $this->Cell(0,0, $d['ciBCResourceName'].', '.$d['ciBCResourceAddress'] );
			$this->setXY( 70 , 59);  $this->Cell(0,0, $d['ciBCBankNameAddress']);

			$this->setXY( 53 , 76);  $this->Cell(0,0, $d['ciPurposeUtils']);
			$this->setXY( 95 , 76);  $this->Cell(0,0, $d['ciPurposePersonal']);
			$this->setXY(122 , 76);  $this->Cell(0,0, $d['ciPurposeBusiness']);
			$this->setXY(170 , 76);  $this->Cell(0,0, $d['ciPurposeHabal']);

			$this->setXY( 53 , 81);  $this->Cell(0,0, $d['ciPurposeOther']);
			$this->setXY( 72 , 81);  $this->Cell(0,0, $d['ciLoanPurposeTxt']);
			
			$exp = $d['ciDIFood'] + $d['ciDIRent'] + $d['ciDITransport'] + $d['ciDIUtils'] + $d['ciDIEdu'] + $d['ciDIMA'] + $d['ciDIOthers'];
			$mi  = $d['ciDIApplicant'] + $d['ciDISpouse'] - $d['ciDIDeductions'];
			$di  = $mi - $exp;
			
			if(is_numeric( $d['ciDIApplicant'])) {  $w = new NumWords( $d['ciDIApplicant'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY( 80 , 106);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $d['ciDISpouse'])) {  $w = new NumWords( $d['ciDISpouse'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY( 80 , 112);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $d['ciDIDeductions'])) {  $w = new NumWords( $d['ciDIDeductions'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY( 80 , 122);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $mi )) {  $w = new NumWords( $mi ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY( 80 , 132);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $exp )) {  $w = new NumWords( $exp ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY( 80 , 142);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $di )) {  $w = new NumWords( $di ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY( 80 , 152);  $this->Cell(24,0, $v, 0, 0, 'R' );

			if(is_numeric( $d['ciDIFood'])) {  $w = new NumWords( $d['ciDIFood'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(166 , 112);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $d['ciDIRent'])) {  $w = new NumWords( $d['ciDIRent'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(166 , 116);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $d['ciDITransport'])) {  $w = new NumWords( $d['ciDITransport'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(162 , 120);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $d['ciDIUtils'])) {  $w = new NumWords( $d['ciDIUtils'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(166 , 124);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $d['ciDIUtils'])) {  $w = new NumWords( $d['ciDIUtils'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(166 , 128);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $d['ciDIEdu'])) {  $w = new NumWords( $d['ciDIEdu'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(166 , 132);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $d['ciDIMA'])) {  $w = new NumWords( $d['ciDIMA'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(166 , 136);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $d['ciDIOthers'])) {  $w = new NumWords( $d['ciDIOthers'] ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(166 , 140);  $this->Cell(24,0, $v, 0, 0, 'R' );
			if(is_numeric( $exp )) {  $w = new NumWords( $exp ); $v = $w->number; unset($w); } else { $v = ''; }			
			$this->setXY(166 , 145);  $this->Cell(24,0, $v, 0, 0, 'R' );

			$this->setXY( 14 , 169);  $this->MultiCell(185, 3, $d['ciRecCharacter'], 0, 'L');
			$this->setXY( 14 , 189);  $this->MultiCell(185, 3, $d['ciRecCapacity'], 0, 'L');
			$this->setXY( 14 , 209);  $this->MultiCell(185, 3, $d['ciRecCapital'], 0, 'L');
			$this->setXY( 14 , 229);  $this->MultiCell(185, 3, $d['ciRecCondition'], 0, 'L');
			
			$this->setXY( 26 , 249);  $this->Cell(0, 0, $d['ciRefName']);
			$this->setXY( 40 , 253);  $this->Cell(0, 0, $d['ciRefAddress']);
			$this->setXY( 34 , 258);  $this->Cell(0, 0, $d['ciRefPhoneNo']);

			$this->setXY( 22 , 273);  $this->Cell(0, 0, $d['ciIsApprovedApproved']);
			$this->setXY( 22 , 278);  $this->Cell(0, 0, $d['ciIsApprovedDenied']);
			
			$this->setXY(100 , 273);  $this->MultiCell(60, 1, $d['recommendBy'], 0, 'C'); 
			$this->setXY(164 , 273);  $this->MultiCell(36, 1, date("m/d/Y",strtotime($d['recommendDate'])), 0, 'C');

			$this->setXY(100 , 287);  $this->MultiCell(60, 1, $d['approvedBy'], 0, 'C'); 
			$this->setXY(164 , 287);  $this->MultiCell(36, 1, date("m/d/Y",strtotime($d['approvedDate'])), 0, 'C');

			$this->setXY(100 , 302);  $this->MultiCell(60, 1, $d['notedBy'], 0, 'C'); 
			$this->setXY(164 , 302);  $this->MultiCell(36, 1, date("m/d/Y",strtotime($d['notedDate'])), 0, 'C');
		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords('WAIS, '.rptFORMCreditInvTitle.' Report');
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setReportTitle(rptFORMCreditInvTitle);
$pdf->setReportName(rptFORMCreditInvName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size
$pdf->SetLineWidth(0.1);
$pdf->AddPage('P', 'LEGAL');
$d = $pdf->getCustomerCreditInv($customerID);
$pdf->reportLayout($d);
$pdf->Output($pdf->getReportName().'.pdf', 'I');
?>
