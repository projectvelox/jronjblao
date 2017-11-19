<?php

include "../includes/config.php";

if($_GET['action']=="create") {
    $c_firstname = addslashes($_POST['c_firstname']);
    $c_middlename = addslashes($_POST['c_middlename']);
    $c_lastname = addslashes($_POST['c_lastname']);
    $c_branch_id = addslashes($_POST['c_branch_id']);
    $c_phone_home = addslashes($_POST['c_phone_home']);
    $c_phone_office = addslashes($_POST['c_phone_office']);
    $c_phone_mobile =  addslashes($_POST['c_phone_mobile']);
    $c_email = addslashes($_POST['c_email']);
    $c_gender = addslashes($_POST['c_gender']);
    $c_civil_status = addslashes($_POST['c_civil_status']);
    $c_birthdate = $_POST['c_birthdate'];
    $c_createdate = date('Y-m-d');
    $c_address_street = addslashes($_POST['c_address_street']);
    $c_address_town = addslashes($_POST['c_address_town']);
    $c_address_city = addslashes($_POST['c_address_city']);
    $c_address_zipcode = addslashes($_POST['c_address_zipcode']);
    $c_address_country = addslashes($_POST['c_address_country']);
    $c_citizenship = addslashes($_POST['c_citizenship']);
    $c_area = addslashes($_POST['c_area']);
    $c_active = "Y";
    $sql = "INSERT INTO customers (
              c_firstname,
              c_middlename,
              c_lastname,
              c_branch_id,
              c_phone_office,
              c_phone_home,
              c_phone_mobile,
              c_email,
              c_gender,
              c_civil_status,
              c_birthdate,
              c_createdate,
              c_address_street,
              c_address_town,
              c_address_city,
              c_address_zipcode,
              c_address_country,
              c_citizenship,
              c_area,
              c_active
          ) VALUES ( 
              '$c_firstname',
              '$c_middlename',
              '$c_lastname',  
              '$c_branch_id',
              '$c_phone_office',
              '$c_phone_home',
              '$c_phone_mobile',
              '$c_email',
              '$c_gender',
              '$c_civil_status',
              '$c_birthdate',
              '$c_createdate',
              '$c_address_street',
              '$c_address_town',
              '$c_address_city',
              '$c_address_zipcode',
              '$c_address_country',
              '$c_citizenship',
              '$c_area',
              '$c_active'
          )";
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
} else if ($_GET['action']=="update") {
    $c_id = addslashes($_POST['c_id']);
    $c_firstname = addslashes($_POST['c_firstname']);
    $c_middlename = addslashes($_POST['c_middlename']);
    $c_lastname = addslashes($_POST['c_lastname']);
    $c_branch_id = addslashes($_POST['c_branch_id']);
    $c_phone_home = addslashes($_POST['c_phone_home']);
    $c_phone_office = addslashes($_POST['c_phone_office']);
    $c_phone_mobile =  addslashes($_POST['c_phone_mobile']);
    $c_email = addslashes($_POST['c_email']);
    $c_gender = addslashes($_POST['c_gender']);
    $c_civil_status = addslashes($_POST['c_civil_status']);
    $c_birthdate = $_POST['c_birthdate'];
    $c_address_street = addslashes($_POST['c_address_street']);
    $c_address_town = addslashes($_POST['c_address_town']);
    $c_address_city = addslashes($_POST['c_address_city']);
    $c_address_zipcode = addslashes($_POST['c_address_zipcode']);
    $c_address_country = addslashes($_POST['c_address_country']);
    $c_citizenship = addslashes($_POST['c_citizenship']);
    $c_area = addslashes($_POST['c_area']);
    $c_active = addslashes($_POST['c_active']);
    $sql = "UPDATE customers SET 
              c_firstname='$c_firstname',
              c_middlename='$c_middlename',
              c_lastname='$c_lastname',
              c_branch_id='$c_branch_id',
              c_phone_office='$c_phone_office',
              c_phone_home='$c_phone_home',
              c_phone_mobile='$c_phone_mobile',
              c_gender='$c_gender',
              c_gender='$c_gender',
              c_civil_status='$c_civil_status',
              c_birthdate='$c_birthdate',
              c_address_street='$c_address_street',
              c_address_town='$c_address_town',
              c_address_city='$c_address_city',
              c_address_zipcode='$c_address_zipcode',
              c_address_country='$c_address_country',
              c_citizenship='$c_citizenship',
              c_area='$c_area',
              c_active='$c_active'
          WHERE c_id=".$c_id;
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
} else if ($_GET['action'] == "delete") { 
    $c_id = addslashes($_GET['id']);   
    $sql = "UPDATE customers SET c_is_deleted='Y' WHERE c_id=".$c_id;
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
} else if ($_GET['action'] == "create_ci") {

    //methods
    function emptyStringIfPostNotSet($index) {
      return isset($_POST[$index])? addslashes((string)$_POST[$index]):"";
    }

    function zeroIfPostNotSet($index) {
      return isset($_POST[$index])? (double)$_POST[$index]:0;
    }

    function postToDbDateString($index) {
      $time = strtotime($_POST[$index]);
      return addslashes(date("Y-m-d", $time));
    }

    //variables
    $ci_id = zeroIfPostNotSet("ci_id");   
    $ci_ma = emptyStringIfPostNotSet("ci_ma");
    $ci_application_date = postToDbDateString("ci_application_date");
    $ci_unit_applied = emptyStringIfPostNotSet("ci_unit_applied");
    $ci_pn_amount = zeroIfPostNotSet("ci_pn_amount");
    $ci_term = emptyStringIfPostNotSet("ci_term");
    $ci_address = emptyStringIfPostNotSet("ci_address");
    $ci_payment_record = emptyStringIfPostNotSet("ci_payment_record");
    $ci_residence_placeis = emptyStringIfPostNotSet("ci_residence_placeis");
    $ci_residence_location = emptyStringIfPostNotSet("ci_residence_location");
    $ci_residence_area = emptyStringIfPostNotSet("ci_residence_area");
    $ci_residence_made = emptyStringIfPostNotSet("ci_residence_made");
    $ci_reputation = emptyStringIfPostNotSet("ci_reputation");
    $ci_religion = emptyStringIfPostNotSet("ci_religion");
    $ci_violation = isset($_POST["ci_violation"])? addslashes(json_encode($_POST["ci_violation"])):"[]";
    $ci_contactfinding = isset($_POST["ci_contactfinding"])? addslashes(json_encode($_POST["ci_contactfinding"])):"[]";
    $ci_residence_ownership = emptyStringIfPostNotSet("ci_residence_ownership");
    $ci_residence_length = 0;
    $ci_residence_rent = zeroIfPostNotSet("ci_residence_rent");
    $ci_dependents = isset($_POST["ci_dependents"])? addslashes(json_encode($_POST["ci_dependents"])):"[]";
    $ci_communityrepuation = emptyStringIfPostNotSet("ci_communityrepuation");
    $ci_col_visit = emptyStringIfPostNotSet("ci_col_visit");
    $ci_col_number = zeroIfPostNotSet("ci_col_number");
    $ci_col_visitfrequently = emptyStringIfPostNotSet("ci_col_visitfrequently");
    $ci_residence_is = emptyStringIfPostNotSet("ci_residence_is");
    $ci_residence_furnituretype = emptyStringIfPostNotSet("ci_residence_furnituretype");
    $ci_appliance = isset($_POST["ci_appliance"])? addslashes(json_encode($_POST["ci_appliance"])):"[]";
    $ci_appliancepurchasetype = emptyStringIfPostNotSet("ci_appliancepurchasetype");
    $ci_installment_financer = emptyStringIfPostNotSet("ci_installment_financer");
    $ci_paymentpunctuality = emptyStringIfPostNotSet("ci_paymentpunctuality");
    $ci_residence_resource = isset($_POST["ci_residence_resource"])? addslashes(json_encode($_POST["ci_residence_resource"])):"[]";
    $ci_business_name = emptyStringIfPostNotSet("ci_business_name");
    $ci_business_nature = emptyStringIfPostNotSet("ci_business_nature");
    $ci_business_address = emptyStringIfPostNotSet("ci_business_address");
    $ci_position = emptyStringIfPostNotSet("ci_position");
    $ci_positionduration = zeroIfPostNotSet("ci_positionduration_month") + (12 * zeroIfPostNotSet("ci_positionduration_year"));
    $ci_income = zeroIfPostNotSet("ci_income");
    $ci_business_resource = isset($_POST["ci_business_resource"])? addslashes(json_encode($_POST["ci_business_resource"])):"[]";
    $ci_bank_status = emptyStringIfPostNotSet("ci_bank_status");
    $ci_bank_duration = zeroIfPostNotSet("ci_bank_duration");
    $ci_bank_resource = isset($_POST["ci_bank_resource"])? addslashes(json_encode($_POST["ci_bank_resource"])):"[]";
    $ci_bank_name = emptyStringIfPostNotSet("ci_bank_name");
    $ci_bank_address = emptyStringIfPostNotSet("ci_bank_address");
    $ci_loan_purpose = emptyStringIfPostNotSet("ci_loan_purpose");
    $a_monthly_income = zeroIfPostNotSet("a_monthly_income");
    $spouse_monthly_income = zeroIfPostNotSet("spouse_monthly_income");
    $less_deductions = zeroIfPostNotSet("less_deductions");
    $expense_food = zeroIfPostNotSet("expense_food");
    $expense_rent = zeroIfPostNotSet("expense_rent");
    $expense_transport = zeroIfPostNotSet("expense_transport");
    $expense_utility = zeroIfPostNotSet("expense_utility");
    $expense_education = zeroIfPostNotSet("expense_education");
    $expense_ma = zeroIfPostNotSet("expense_ma");
    $expense_others = zeroIfPostNotSet("expense_others");
    $recommendation_character = emptyStringIfPostNotSet("recommendation_character");
    $recommendation_capacity = emptyStringIfPostNotSet("recommendation_capacity");
    $recommendation_capital = emptyStringIfPostNotSet("recommendation_capital");
    $recommendation_condition = emptyStringIfPostNotSet("recommendation_condition");
    $recommendation_reference_name = emptyStringIfPostNotSet("recommendation_reference_name");
    $recommendation_reference_address = emptyStringIfPostNotSet("recommendation_reference_address");
    $recommendation_reference_contactnumber = emptyStringIfPostNotSet("recommendation_reference_contactnumber");
    $application_status = emptyStringIfPostNotSet("application_status");
    $recommender = zeroIfPostNotSet("recommender");
    $recommender_recommended = postToDbDateString("recommender_recommended");
    $approver = zeroIfPostNotSet("approver");
    $approver_approved = postToDbDateString("approver_approved");
    $noter = zeroIfPostNotSet("noter");
    $noter_noted = postToDbDateString("noter_noted");

    //calculate ci_residence_length (month count)
    if(isset($_POST["ci_residence_length_type"])){
      switch ($_POST["ci_residence_length_type"]) {
        case "Months": $ci_residence_length += (int)$_POST["ci_residence_length"]; break;
        case "Years": $ci_residence_length += (int)($_POST["ci_residence_length"] * 12); break;
      }
    }

    //sql
    $sql = "INSERT INTO form_ci_1(
      ci_id, 
      ci_ma, 
      ci_application_date, 
      ci_unit_applied, 
      ci_pn_amount, 
      ci_term, 
      ci_address, 
      ci_payment_record, 
      ci_residence_placeis, 
      ci_residence_location, 
      ci_residence_area, 
      ci_residence_made, 
      ci_reputation, 
      ci_religion, 
      ci_violation, 
      ci_contactfinding, 
      ci_residence_ownership, 
      ci_residence_length, 
      ci_residence_rent, 
      ci_dependents, 
      ci_communityrepuation, 
      ci_col_visit, 
      ci_col_number, 
      ci_col_visitfrequently, 
      ci_residence_is, 
      ci_residence_furnituretype, 
      ci_appliance, 
      ci_appliancepurchasetype, 
      ci_installment_financer, 
      ci_paymentpunctuality, 
      ci_residence_resource, 
      ci_business_name, 
      ci_business_nature, 
      ci_business_address, 
      ci_position, 
      ci_positionduration, 
      ci_income, 
      ci_business_resource,
      ci_bank_status, 
      ci_bank_duration, 
      ci_bank_resource,
      ci_bank_name, 
      ci_bank_address, 
      ci_loan_purpose, 
      a_monthly_income, 
      spouse_monthly_income, 
      less_deductions, 
      expense_food, 
      expense_rent, 
      expense_transport, 
      expense_utility, 
      expense_education, 
      expense_ma, 
      expense_others, 
      recommendation_character, 
      recommendation_capacity, 
      recommendation_capital, 
      recommendation_condition, 
      recommendation_reference_name, 
      recommendation_reference_address, 
      recommendation_reference_contactnumber, 
      application_status, 
      recommender, 
      recommender_recommended, 
      approver, 
      approver_approved, 
      noter, 
      noter_noted
    ) VALUES (
      $ci_id,
      '$ci_ma',
      '$ci_application_date',
      '$ci_unit_applied',
      $ci_pn_amount,
      '$ci_term',
      '$ci_address',
      '$ci_payment_record',
      '$ci_residence_placeis',
      '$ci_residence_location',
      '$ci_residence_area',
      '$ci_residence_made',
      '$ci_reputation',
      '$ci_religion',
      '$ci_violation',
      '$ci_contactfinding',
      '$ci_residence_ownership',
      $ci_residence_length,
      $ci_residence_rent,
      '$ci_dependents',
      '$ci_communityrepuation',
      '$ci_col_visit',
      $ci_col_number,
      '$ci_col_visitfrequently',
      '$ci_residence_is',
      '$ci_residence_furnituretype',
      '$ci_appliance',
      '$ci_appliancepurchasetype',
      '$ci_installment_financer',
      '$ci_paymentpunctuality',
      '$ci_residence_resource',
      '$ci_business_name',
      '$ci_business_nature',
      '$ci_business_address',
      '$ci_position',
      $ci_positionduration,
      $ci_income,
      '$ci_business_resource',
      '$ci_bank_status',
      $ci_bank_duration,
      '$ci_bank_resource',
      '$ci_bank_name',
      '$ci_bank_address',
      '$ci_loan_purpose',
      $a_monthly_income,
      $spouse_monthly_income,
      $less_deductions,
      $expense_food,
      $expense_rent,
      $expense_transport,
      $expense_utility,
      $expense_education,
      $expense_ma,
      $expense_others,
      '$recommendation_character',
      '$recommendation_capacity',
      '$recommendation_capital',
      '$recommendation_condition',
      '$recommendation_reference_name',
      '$recommendation_reference_address',
      '$recommendation_reference_contactnumber',
      '$application_status',
      $recommender,
      '$recommender_recommended',
      $approver,
      '$approver_approved',
      $noter,
      '$noter_noted'
    )";

    //exec
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');

    
    //check post values
    //echo json_encode($_POST);
    
    //check sql query
    //echo $sql;
}
?>  