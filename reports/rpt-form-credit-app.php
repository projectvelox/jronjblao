<?php
//===================================================//
//=====  REPORT : CREDIT APPLICATION            =====//
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
	
	public function getCreditApplication($salesID = NULL)
	{
		$ca = array();
		$sql = '';
		if(!$salesID == NULL)
		{
			$sql  = " SELECT tabCA.*, tabSALES.s_payment_term, tabSALES.s_monthlyamortization, tabCUST.*, ";
			$sql .= "	(SELECT s_customer_id FROM sales_main AS tabSALES WHERE tabSALES.s_id  = tabCA.sales_id ) as customerID, ";
			$sql .= "	(SELECT value FROM options_civilstatus AS tabCIVIL WHERE tabCIVIL.id  = tabCUST.c_civil_status ) as customerCivilStatus, ";
			$sql .= "	(SELECT c_address_country FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = customerID ) AS countryID, ";
			$sql .= "	(SELECT CONCAT(c_address_street,', ', c_address_town,', ', c_address_city, ' ', c_address_zipcode) FROM customers AS tabCUSTOMER WHERE tabCUSTOMER.c_id  = customerID ) AS customerAddress,  ";
			$sql .= "	(SELECT value FROM options_country AS tabCOUNTRY WHERE tabCOUNTRY.id = countryID ) AS customerCountry,  ";
			$sql .= "	(SELECT p_category_id FROM products AS tabPROD WHERE tabPROD.p_id  = tabSUB.s_product_id ) AS productCatID, ";
			$sql .= "	(SELECT p_name FROM products AS tabPROD WHERE tabPROD.p_id  = tabSUB.s_product_id ) AS productName, ";
			$sql .= "	(SELECT p_color FROM products AS tabPROD WHERE tabPROD.p_id  = tabSUB.s_product_id ) AS colorID, ";
			$sql .= "	(SELECT color_name FROM options_colors AS tabCOLOR WHERE tabCOLOR.color_id = colorID ) AS colorName, "; 
			$sql .= "	(SELECT p_isrepo FROM inventory as tabINV WHERE tabINV.p_id=tabSUB.s_product_id) AS isRepo, ";
			$sql .= "	(SELECT SUM(p_amount) FROM payments AS tabPMT WHERE tabPMT.p_sales_id = tabCA.sales_id and tabPMT.p_is_downpayment = 1 or tabPMT.p_is_downpayment=2) AS downPayment, ";
			$sql .= "	(SELECT i_parent_id FROM inv_category AS tabCAT WHERE tabCAT.i_id  = productCatID ) as parentCatID ";
			$sql .= " FROM form_crapp AS tabCA  ";
			$sql .= "	INNER JOIN (sales_main AS tabSALES) ON tabSALES.s_id = tabCA.sales_id ";
			$sql .= "	INNER JOIN (sales_sub AS tabSUB ) ON tabSUB.s_sales_id = tabCA.sales_id ";
			$sql .= "	INNER JOIN (customers AS tabCUST ) ON tabCUST.c_id = tabSALES.s_customer_id ";
			$sql .= " WHERE tabCA.sales_id = $salesID ";
			$rec = mysql_query($sql) ;
		    while ($w = mysql_fetch_array($rec, MYSQL_ASSOC)) 
		    {
		    	$isNew  = ''; $isRepo = ''; $custSex = ''; $spouseSex = ''; $custAge = 0; $isLoanMC = ''; $isLoanAppliance = '';
		    	if($w['isRepo']    == 'Y') { $isRepo    = 'Y'; $isNew = 'N'; } else { $isRepo = 'N'; $isNew = 'Y'; } 
		    	if($w['spouseSex'] == 'M') { $spouseSex = 'Male'; } else { $spouseSex = 'Female'; }
		    	if($w['c_gender']  == 'M') { $custSex   = 'Male'; } else { $custSex   = 'Female'; }
		    	if($w['parentCatID']  == 2) { $isLoanMC  = 'X'; $isLoanAppliance = ''; } else { $isLoanMC  = ''; $isLoanAppliance = 'X'; }
		    	$custAge = floor((time() - strtotime($w['c_birthdate'])) / 31556926);
			    $ca = array(
			        'applicationNo'     => $w['caAppNo'],
			        'applicationDate'   => date("m/d/Y",strtotime($w['caDate'])),
			        'isLoanMC'          => $isLoanMC,
			        'isLoanAppliance'   => $isLoanAppliance,
			        'model'             => $w['productName'],
			        'color'             => $w['colorName'],
			        'downPayment'       => $w['downPayment'],
			        'loanTerm'          => $w['s_payment_term'],
			        'loanMA'            => $w['s_monthlyamortization'],
			        'isNew'             => $isNew,
			        'isRepo'            => $isRepo,
			        'mcNeed'            => $w['caNeed'],
			        'mcPurpose'         => $w['caPurpose'],
			        // customer info
			        'custSurname'       => $w['c_lastname'],
			        'custFirstname'     => $w['c_firstname'],
			        'custMiddlename'    => $w['c_middlename'],
			        'custAddress'       => $w['customerAddress'].' '.$w['customerCountry'],
			        'custCivilStatus'   => $w['customerCivilStatus'],
			        'custDOB'           => date("m/d/Y",strtotime($w['c_birthdate'])),
			        'custSex'           => $custSex,
			        'custAge'           => $custAge,
			        'custBirthPlace'    => $w['c_birthplace'],
			        'custMobileNo'      => $w['c_phone_mobile'],
			        'custContactPerson' => $w['c_contactperson'],
			        // customers education
			        'custLastSchool'    => $w['custLastSchool'],
			        'custLastSchYr'     => $w['custLastSchoolYr'],
			        'custReligion'      => $w['custReligion'],
			        // customers employment
			        'custEmplName'      => $w['custEmployer'],
			        'custEmplStatus'    => $w['custEmplStatus'],
			        'custEmplPosition'  => $w['custEmplPosition'],
			        'custEmplDateHired' => date("m/d/Y",strtotime($w['custEmplDateHired'])),
			        'custEmplSalary'    => $w['custEmplSalary'],
			        'custEmplAddress'   => $w['custEmplAddress'],
			        'custEmplOfficeNo'  => $w['custEmplOfficeNo'],
			        'custEmplDirectHead'=> $w['custEmplDirectHead'],
			        'custEmplMobileNo'  => $w['custEmplMobileNo'],
			        // customers children
			        'custChildName1'        => $w['caChildName1'],
			        'custChildAge1'         => $w['caChildAge1'],
			        'custChildSchool1'      => $w['caChildSchool1'],
			        'custChildOccupation1'  => $w['caChildOccupation1'],
			        'custChildName2'        => $w['caChildName2'],
			        'custChildAge2'         => $w['caChildAge2'],
			        'custChildSchool2'      => $w['caChildSchool2'],
			        'custChildOccupation2'  => $w['caChildOccupation1'],
			        // customers parent
			        'custFatherName'        => $w['caFathersName'],
			        'custFatherAge'         => $w['caFathersAge'],
			        'custFatherOccupation'  => $w['caFathersOccupation'],
			        'custMotherName'        => $w['caMothersName'],
			        'custMotherAge'         => $w['caMothersAge'],
			        'custMotherOccupation'  => $w['caMothersOccupation'],
			        'custParentAddress'     => $w['caParentsAddress'],
			        'custParentTelNo'       => $w['caParentsPhoneNo'],
			        // customers siblings
			        'custSiblingName1'       => $w['caSiblingName1'],
			        'custSiblingAddress1'    => $w['caSiblingAddress1'],
			        'custSiblingOccupation1' => $w['caSiblingOccupation1'],
			        'custSiblingTelNo1'      => $w['caSiblingPhoneNo1'],
			        'custSiblingName2'       => $w['caSiblingName2'],
			        'custSiblingAddress2'    => $w['caSiblingAddress2'],
			        'custSiblingOccupation2' => $w['caSiblingOccupation2'],
			        'custSiblingTelNo2'      => $w['caSiblingPhoneNo2'],
			        'custSiblingName3'       => $w['caSiblingName3'],
			        'custSiblingAddress3'    => $w['caSiblingAddress3'],
			        'custSiblingOccupation3' => $w['caSiblingOccupation3'],
			        'custSiblingTelNo3'      => $w['caSiblingPhoneNo3'],
			        // spouse info
			        'custSpouseName'         => $w['spouseName'],
			        'custSpouseNickName'     => $w['spouseNickName'],
			        'custSpouseDOB'          => date("m/d/Y",strtotime($w['spouseDOB'])),
			        'custSpouseSex'          => $spouseSex,
			        'custSpouseAge'          => $w['spouseAge'],
			        'custSpouseBirthPlace'   => $w['spouseBirthPlace'],
			        'custSpouseMobileNo'     => $w['spouseMobileNo'],
			        'custSpouseReligion'     => $w['spouseReligion'],
			        // spouse education
			        'custSpouseEducation'    => $w['spouseEduAttainment'],
			        'custSpouseLastSchool'   => $w['spouseLastSchool'],
			        'custSpouseLastSchYR'    => $w['spouseLastSchoolYr'],
			        // spouse parents
			        'custSpouseParentName'  	=> $w['spouseParentName'],
			        'custSpouseParentAddress'   => $w['spouseParentAddress'],
			        'custSpouseParentMobileNo'  => $w['spouseParentMobileNo'],
			        // spouse employment
			        'custSpouseEmplName'       => $w['spouseEmployer'],
			        'custSpouseEmplStatus'     => $w['spouseEmplStatus'],
			        'custSpouseEmplPosition'   => $w['spouseEmplPosition'],
			        'custSpouseEmplDateHired'  => date("m/d/Y",strtotime($w['spouseEmplDateHired'])),
			        'custSpouseEmplSalary'     => $w['spouseEmplSalary'],
			        'custSpouseEmplAddress'    => $w['spouseEmplAddress'],
			        'custSpouseEmplDirectHead' => $w['spouseEmplDirectHead'],
			        'custSpouseEmplOfficeNo'   => $w['spouseEmplOfficeNo'],
			        'custSpouseEmplMobileNo'   => $w['spouseEmplMobileNo'],
			        // other sources of income
			        'custIncomeBusiness'    => $w['caBusinessNature'],
			        'custIncomeYROperation' => $w['caBusinessYrsOpn'],
			        'custIncomeIncome'      => $w['caBusinessIncome'],
			        'custFarmHectare'       => $w['caFarmHectare'],
			        'custFarmKind'          => $w['caFarmKind'],
			        'custFarmYROperation'   => $w['caFarmYrsOpn'],
			        'custFarmIncome'        => $w['caFarmIncome'],
			        // real/personal properties
			        'custRealPropTitle'    => $w['caRealPropertyTitle'],
			        'custRealPropLocation' => $w['caRealPropLocation'],
			        'custRealPropStatus'   => $w['caRealPropStatus'],
			        // credit history
			        'custCreditHistoryInstitution'  => $w['caCreditHistInstitution'],
			        'custCreditHistoryCreditType'   => $w['caCreditHistCreditType'],
			        'custCreditHistoryTerm'         => $w['caCreditHistTerm'],
			        'custCreditHistoryStatus'       => $w['caCreditHistStatus'],
			        // references
			        'custRefName1'      => $w['caRefName1'],
			        'custRefPosition1'  => $w['caRefPosition1'],
			        'custRefCompany1'   => $w['caRefCompany1'],
			        'custRefAddress1'   => $w['caRefAddress1'],
			        'custRefTelNo1'     => $w['caRefPhoneNo1'],
			        'custRefName2'      => $w['caRefName2'],
			        'custRefPosition2'  => $w['caRefPosition2'],
			        'custRefCompany2'   => $w['caRefCompany2'],
			        'custRefAddress2'   => $w['caRefAddress2'],
			        'custRefTelNo2'     => $w['caRefPhoneNo2'],
			        'custRefName3'      => $w['caRefName3'],
			        'custRefPosition3'  => $w['caRefPosition3'],
			        'custRefCompany3'   => $w['caRefCompany3'],
			        'custRefAddress3'   => $w['caRefAddress3'],
			        'custRefTelNo3'     => $w['caRefPhoneNo3']
			        );		    	
		    }
		    unset($rec);
		}
		$xx = 1;
		if($xx ==1)
		{
	    $customerDetail = array(
	        'applicationNo'     => '123456',
	        'applicationDate'   => '07/18/2017',
	        'isLoanMC'          => 'Y',
	        'isLoanAppliance'   => 'Y',
	        'model'             => 'UX31A',
	        'color'             => 'Gray',
	        'downPayment'       => '2,500.99',
	        'loanTerm'          => '12 Mos',
	        'loanMA'            => '12345',
	        'isNew'             => 'Y',
	        'isRepo'            => 'Y',
	        'mcNeed'            => 'Tricycle',
	        'mcPurpose'         => 'Public Transport',
	        'custSurname'       => 'Dela Cruz',
	        'custFirstname'     => 'Juan',
	        'custMiddlename'    => 'T',
	        'custAddress'       => 'Poblacion Kalibo, Aklan',
	        'custCivilStatus'   => 'Married',
	        'custDOB'           => '01/01/1965',
	        'custSex'           => 'Male',
	        'custAge'           => '60',
	        'custBirthPlace'    => 'Makato, Aklan',
	        'custMobileNo'      => '09912355522',
	        'custContactPerson' => 'Gabriela Silang',
	        
	        'custLastSchool'    => 'Kalibo Institute',
	        'custLastSchYr'     => '1980',
	        'custReligion'      => 'Catholic',
	        
	        'custEmplName'      => 'Kalibo Import Export Co',
	        'custEmplStatus'    => 'Permanent',
	        'custEmplPosition'  => 'CEO',
	        'custEmplDateHired' => '12/31/1985',
	        'custEmplSalary'    => '65,000.00',
	        'custEmplAddress'   => 'Crossing Banga, Kalibo, Aklan',
	        'custEmplOfficeNo'  => '3209876',
	        'custEmplDirectHead'=> 'Andres Bonifacio',
	        'custEmplMobileNo'  => '09955511133',
	        
	        'custChildName1'        => 'Child Name 1',
	        'custChildAge1'         => '25',
	        'custChildSchool1'      => 'Kalibo Institute 1',
	        'custChildOccupation1'  => 'Occupation 1',
	        
	        'custChildName2'        => 'Child Name 2',
	        'custChildAge2'         => '25',
	        'custChildSchool2'      => 'Kalibo Institute 2',
	        'custChildOccupation2'  => 'Occupation 2',
	        
	        'custFatherName'        => 'Father Name',
	        'custFatherAge'         => '101',
	        'custFatherOccupation'  => 'Father Occupation',
	        'custMotherName'        => 'Mother Name',
	        'custMotherAge'         => '95',
	        'custMotherOccupation'  => 'Mother Occupation',
	        'custParentAddress'     => 'Banga, Kalibo, Aklan',
	        'custParentTelNo'       => '320-0000',

	        'custSiblingName1'       => 'Sibling Name 1',
	        'custSiblingAddress1'    => 'Address 1',
	        'custSiblingOccupation1' => 'Occupation 1',
	        'custSiblingTelNo1'      => 'Telephone 1',

	        'custSiblingName2'       => 'Sibling Name 2',
	        'custSiblingAddress2'    => 'Address 2',
	        'custSiblingOccupation2' => 'Occupation 2',
	        'custSiblingTelNo2'      => 'Telephone 2',

	        'custSiblingName3'       => 'Sibling Name 3',
	        'custSiblingAddress3'    => 'Address 3',
	        'custSiblingOccupation3' => 'Occupation 3',
	        'custSiblingTelNo3'      => 'Telephone 3',

	        'custSpouseName'         => 'Spouse Name',
	        'custSpouseNickName'     => 'Nick name',
	        'custSpouseDOB'          => '06/01/1960',
	        'custSpouseSex'          => 'Female',
	        'custSpouseAge'          => '80',
	        'custSpouseBirthPlace'   => 'Numancia, Kalibo, Aklan',
	        'custSpouseMobileNo'     => '0995557788',
	        'custSpouseReligion'     => 'Catholic',
	        'custSpouseEducation'    => 'Bachelors Degree',
	        'custSpouseLastSchool'   => 'Aklan State University',
	        'custSpouseLastSchYR'    => '1985',
	        'custSpouseParentName'   => 'Parents Name',
	        'custSpouseParentAddress'   => 'Parents Address',
	        'custSpouseParentMobileNo'  => '09966655544',
	        
	        'custSpouseEmplName'       => 'Spouse Employer',
	        'custSpouseEmplStatus'     => 'Permanent',
	        'custSpouseEmplPosition'   => 'COO',
	        'custSpouseEmplDateHired'  => '04/01/1990',
	        'custSpouseEmplSalary'     => '75,000.00',
	        'custSpouseEmplAddress'    => 'Employee Address',
	        'custSpouseEmplDirectHead' => 'Direct Head',
	        'custSpouseEmplOfficeNo'   => '123-4567',
	        'custSpouseEmplMobileNo'   => '09911122233',
	        
	        'custIncomeBusiness'    => 'Business Name',
	        'custIncomeYROperation' => '15',
	        'custIncomeIncome'      => '500,000.00',
	        
	        'custFarmHectare'       => '5',
	        'custFarmKind'          => 'Sugarcane',
	        'custFarmYROperation'   => '15',
	        'custFarmIncome'        => '850,000.00',

	        'custRealPropTitle'    => 'Real Property',
	        'custRealPropLocation' => 'Property Location',
	        'custRealPropStatus'   => 'Property Status',

	        'custCreditHistoryInstitution'  => 'Credit Institution',
	        'custCreditHistoryCreditType'   => 'Credit Type',
	        'custCreditHistoryTerm'         => '24 Mos',
	        'custCreditHistoryStatus'       => 'Fully Paid',
	        
	        'custRefName1'      => 'Reference Name 1',
	        'custRefPosition1'  => 'Position 1',
	        'custRefCompany1'   => 'Company 1',
	        'custRefAddress1'   => 'Address 1',
	        'custRefTelNo1'     => '320-1111',

	        'custRefName2'      => 'Reference Name 2',
	        'custRefPosition2'  => 'Position 2',
	        'custRefCompany2'   => 'Company 2',
	        'custRefAddress2'   => 'Address 2',
	        'custRefTelNo2'     => '320-2222',
	        
	        'custRefName3'      => 'Reference Name 3',
	        'custRefPosition3'  => 'Position 3',
	        'custRefCompany3'   => 'Company 3',
	        'custRefAddress3'   => 'Address 3',
	        'custRefTelNo3'     => '320-3333'
	        
	        );
		}
		return $ca;
	}

	public function reportLayout($data) 
	{
		$this->setXY(0,0);
		if(is_array($data))
		{
			// page 1
			$this->setXY(174 ,  60);  $this->Cell(0,0, $data['applicationNo'] );
			$this->setXY(150 ,  66);  $this->Cell(0,0, $data['applicationDate'] );
			
			if($data['isLoanMC'] == 'Y')
			{ $this->setXY(49 ,  75);  $this->Cell(0,0, $data['isLoanMC'] ); }
			if($data['isLoanAppliance'] == 'Y')
			{ $this->setXY(60 ,  75);  $this->Cell(0,0, $data['isLoanAppliance'] ); }
			
			$this->setXY(32  ,  79);  $this->Cell(0,0, $data['model'] );
			$this->setXY(69  ,  79);  $this->Cell(0,0, $data['color'] );
			if(is_numeric( $data['downPayment'] )) {  $w = new NumWords( $data['downPayment'] ); $v = $w->number; unset($w); } else { $v = ''; }
			$this->setXY(92  ,  79);  $this->Cell(0,0, $v );
			$this->setXY(118 ,  79);  $this->Cell(0,0, $data['loanTerm'] );
			$this->setXY(136 ,  79);  $this->Cell(0,0, $data['loanMA'] );
			if($data['isNew'] == 'Y')
			{  $this->setXY(159, 79);  $this->Cell(0,0, $data['isNew'] );  }
			if($data['isRepo'] == 'Y')
			{  $this->setXY(181, 79);  $this->Cell(0,0, $data['isRepo'] );  }
			
			$this->setXY(73  ,  89);  $this->Cell(0,0, $data['mcNeed'] );
			$this->setXY(130 ,  89);  $this->Cell(0,0, $data['mcPurpose'] );
			
			$this->setXY(37  , 102);  $this->Cell(0,0, $data['custSurname'] );
			$this->setXY(91  , 102);  $this->Cell(0,0, $data['custFirstname'] );
			$this->setXY(150 , 102);  $this->Cell(0,0, $data['custMiddlename'] );

			$this->setXY(48  , 108);  $this->Cell(0,0, $data['custAddress'] );
			
			$this->setXY(34  , 112);  $this->Cell(0,0, $data['custCivilStatus'] );
			$this->setXY(107 , 112);  $this->Cell(0,0, $data['custDOB'] );
			$this->setXY(143 , 112);  $this->Cell(0,0, $data['custSex'] );
			
			$this->setXY(34  , 117);  $this->Cell(0,0, $data['custAge'] );
			$this->setXY(107 , 117);  $this->Cell(0,0, $data['custBirthPlace'] );
			$this->setXY(163 , 117);  $this->Cell(0,0, $data['custMobileNo'] );
			
			$this->setXY(47 , 122);  $this->Cell(0,0, $data['custContactPerson'] );
			
			$this->setXY(60  , 136);  $this->Cell(0,0, $data['custLastSchool'] );
			$this->setXY(114 , 136);  $this->Cell(0,0, $data['custLastSchYr'] );
			$this->setXY(152 , 136);  $this->Cell(0,0, $data['custReligion'] );
			
			$this->setXY(48 ,145);  $this->Cell(0,0, $data['custEmplName'] );
			$this->setXY(97 ,145);  $this->Cell(0,0, $data['custEmplStatus'] );
			$this->setXY(152,145);  $this->Cell(0,0, $data['custEmplPosition'] );
			
			$this->setXY(48 ,149);  $this->Cell(0,0, $data['custEmplDateHired'] );
			if(is_numeric( $data['custEmplSalary'] )) {  $w = new NumWords( $data['custEmplSalary'] ); $v = $w->number; unset($w); } else { $v = ''; }
			$this->setXY(97 ,149);  $this->Cell(0,0, $v );
			$this->setXY(152,149);  $this->Cell(0,0, $data['custEmplAddress'] );
			
			$this->setXY(46 ,154);  $this->Cell(0,0, $data['custEmplOfficeNo'] );
			$this->setXY(106,154);  $this->Cell(0,0, $data['custEmplDirectHead'] );
			$this->setXY(152,154);  $this->Cell(0,0, $data['custEmplMobileNo'] );

			$this->setXY(36 ,173);  $this->Cell(0,0, $data['custChildName1'] );
			$this->setXY(92 ,173);  $this->Cell(0,0, $data['custChildAge1'] );
			$this->setXY(118,173);  $this->Cell(0,0, $data['custChildSchool1'] );
			$this->setXY(166,173);  $this->Cell(0,0, $data['custChildOccupation1'] );

			$this->setXY(36 ,177);  $this->Cell(0,0, $data['custChildName2'] );
			$this->setXY(92 ,177);  $this->Cell(0,0, $data['custChildAge2'] );
			$this->setXY(118,177);  $this->Cell(0,0, $data['custChildSchool2'] );
			$this->setXY(166,177);  $this->Cell(0,0, $data['custChildOccupation2'] );

			$this->setXY( 48,191);  $this->Cell(0,0, $data['custFatherName'] );
			$this->setXY(120,191);  $this->Cell(0,0, $data['custFatherAge'] );
			$this->setXY(156,191);  $this->Cell(0,0, $data['custFatherOccupation'] );

			$this->setXY( 48,196);  $this->Cell(0,0, $data['custMotherName'] );
			$this->setXY(120,196);  $this->Cell(0,0, $data['custMotherAge'] );
			$this->setXY(156,196);  $this->Cell(0,0, $data['custMotherOccupation'] );

			$this->setXY( 38,201);  $this->Cell(0,0, $data['custParentAddress'] );
			$this->setXY(120,201);  $this->Cell(0,0, $data['custParentTelNo'] );

			$this->setXY( 26,220);  $this->Cell(0,0, $data['custSiblingName1'] );
			$this->setXY( 80,220);  $this->Cell(0,0, $data['custSiblingAddress1'] );
			$this->setXY(132,220);  $this->Cell(0,0, $data['custSiblingOccupation1'] );
			$this->setXY(180,220);  $this->Cell(0,0, $data['custSiblingTelNo1'] );

			$this->setXY( 26,225);  $this->Cell(0,0, $data['custSiblingName2'] );
			$this->setXY( 80,225);  $this->Cell(0,0, $data['custSiblingAddress2'] );
			$this->setXY(132,225);  $this->Cell(0,0, $data['custSiblingOccupation2'] );
			$this->setXY(180,225);  $this->Cell(0,0, $data['custSiblingTelNo2'] );

			$this->setXY( 26,230);  $this->Cell(0,0, $data['custSiblingName3'] );
			$this->setXY( 80,230);  $this->Cell(0,0, $data['custSiblingAddress3'] );
			$this->setXY(132,230);  $this->Cell(0,0, $data['custSiblingOccupation3'] );
			$this->setXY(180,230);  $this->Cell(0,0, $data['custSiblingTelNo3'] );

			$this->setXY( 46,238);  $this->Cell(0,0, $data['custSpouseName'] );

			$this->setXY( 40,243);  $this->Cell(0,0, $data['custSpouseNickName'] );
			$this->setXY(105,243);  $this->Cell(0,0, $data['custSpouseDOB'] );
			$this->setXY(144,243);  $this->Cell(0,0, $data['custSpouseSex'] );
			$this->setXY(168,243);  $this->Cell(0,0, $data['custSpouseAge'] );

			$this->setXY( 45,248);  $this->Cell(0,0, $data['custSpouseBirthPlace'] );
			$this->setXY(104,248);  $this->Cell(0,0, $data['custSpouseMobileNo'] );
			$this->setXY(152,248);  $this->Cell(0,0, $data['custSpouseReligion'] );

			$this->setXY( 62,252);  $this->Cell(0,0, $data['custSpouseEducation'] );
			$this->setXY(120,252);  $this->Cell(0,0, $data['custSpouseLastSchool'] );
			$this->setXY(172,252);  $this->Cell(0,0, $data['custSpouseLastSchYR'] );

			$this->setXY( 45,258);  $this->Cell(0,0, $data['custSpouseParentName'] );
			$this->setXY(100,258);  $this->Cell(0,0, $data['custSpouseParentAddress'] );
			$this->setXY(152,258);  $this->Cell(0,0, $data['custSpouseParentMobileNo'] );

			$this->setXY( 48,267);  $this->Cell(0,0, $data['custSpouseEmplName'] );
			$this->setXY( 98,267);  $this->Cell(0,0, $data['custSpouseEmplStatus'] );
			$this->setXY(152,267);  $this->Cell(0,0, $data['custSpouseEmplPosition'] );

			$this->setXY( 40,271);  $this->Cell(0,0, $data['custSpouseEmplDateHired'] );
			if(is_numeric( $data['custSpouseEmplSalary'] )) {  $w = new NumWords( $data['custSpouseEmplSalary'] ); $v = $w->number; unset($w); } else { $v = ''; }
			$this->setXY( 98,271);  $this->Cell(0,0, $v );
			$this->setXY(152,271);  $this->Cell(0,0, $data['custSpouseEmplAddress'] );

			$this->setXY( 42,276);  $this->Cell(0,0, $data['custSpouseEmplDirectHead'] );
			$this->setXY(100,276);  $this->Cell(0,0, $data['custSpouseEmplOfficeNo'] );
			$this->setXY(152,276);  $this->Cell(0,0, $data['custSpouseEmplMobileNo'] );

			$this->setXY( 53,290);  $this->Cell(0,0, $data['custIncomeBusiness'] );
			$this->setXY(122,290);  $this->Cell(0,0, $data['custIncomeYROperation'] );
			if(is_numeric( $data['custIncomeIncome'] )) {  $w = new NumWords( $data['custIncomeIncome'] ); $v = $w->number; unset($w); } else { $v = ''; }
			$this->setXY(165,290);  $this->Cell(0,0, $v );

			$this->setXY( 40,298);  $this->Cell(0,0, $data['custFarmHectare'] );
			$this->setXY( 75,298);  $this->Cell(0,0, $data['custFarmKind'] );
			$this->setXY(123,298);  $this->Cell(0,0, $data['custFarmYROperation'] );
			if(is_numeric( $data['custFarmIncome'] )) {  $w = new NumWords( $data['custFarmIncome'] ); $v = $w->number; unset($w); } else { $v = ''; }
			$this->setXY(162,298);  $this->Cell(0,0, $v );

			$this->setXY( 22,318);  $this->Cell(0,0, $data['custRealPropTitle'] );
			$this->setXY( 73,318);  $this->Cell(0,0, $data['custRealPropLocation'] );
			$this->setXY(125,318);  $this->Cell(0,0, $data['custRealPropStatus'] );
			
			// page 2
			$this->AddPage('P', 'LEGAL');
			$this->setXY(0,0);

			$this->setXY( 37, 48);  $this->Cell(0,0, $data['custCreditHistoryInstitution'] );
			$this->setXY( 92, 48);  $this->Cell(0,0, $data['custCreditHistoryCreditType'] );
			$this->setXY(150, 48);  $this->Cell(0,0, $data['custCreditHistoryTerm'] );
			$this->setXY(176, 48);  $this->Cell(0,0, $data['custCreditHistoryStatus'] );

			$this->setXY( 32, 67);  $this->Cell(0,0, $data['custRefName1'] );
			$this->setXY( 84, 67);  $this->Cell(0,0, $data['custRefPosition1'] );
			$this->setXY(110, 67);  $this->Cell(0,0, $data['custRefCompany1'] );
			$this->setXY(145, 67);  $this->Cell(0,0, $data['custRefAddress1'] );
			$this->setXY(185, 67);  $this->Cell(0,0, $data['custRefTelNo1'] );

			$this->setXY( 32, 71);  $this->Cell(0,0, $data['custRefName2'] );
			$this->setXY( 84, 71);  $this->Cell(0,0, $data['custRefPosition2'] );
			$this->setXY(110, 71);  $this->Cell(0,0, $data['custRefCompany2'] );
			$this->setXY(145, 71);  $this->Cell(0,0, $data['custRefAddress2'] );
			$this->setXY(185, 71);  $this->Cell(0,0, $data['custRefTelNo2'] );

			$this->setXY( 32, 75);  $this->Cell(0,0, $data['custRefName3'] );
			$this->setXY( 84, 75);  $this->Cell(0,0, $data['custRefPosition3'] );
			$this->setXY(110, 75);  $this->Cell(0,0, $data['custRefCompany3'] );
			$this->setXY(145, 75);  $this->Cell(0,0, $data['custRefAddress3'] );
			$this->setXY(185, 75);  $this->Cell(0,0, $data['custRefTelNo3'] );

		}
	}
	
}  // end of class

$pdf = new waisReport(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject('WAIS Reports');
$pdf->SetKeywords('WAIS,' . rptFORMCreditAppTitle);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setReportTitle(rptFORMCreditAppTitle);
$pdf->setReportName(rptFORMCreditAppName);
$pdf->setLogoFile(rptLogoPath);
$pdf->setLogoWidth($logoW);
$pdf->setLogoHeight($logoH);
$pdf->SetFont('helvetica', '', 10);      // font and size
$pdf->SetLineWidth(0.1);
$pdf->AddPage('P', 'LEGAL');
$d = $pdf->getCreditApplication($salesID);
$pdf->reportLayout($d);
$pdf->Output($pdf->getReportName().'.pdf', 'I');
?>
