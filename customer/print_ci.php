<?php
    include "../includes/config.php";
    include "../includes/session.php";
    $id=$_GET['id'];
    $ci_query = "SELECT * FROM form_ci_1 WHERE id=$id";
    $ci_record = mysql_query($ci_query) or die('Query failed: ' . mysql_error());
    $row = mysql_fetch_assoc($ci_record);
    $ci_id = $row['ci_id'];
    $ci_ma = $row['ci_ma'];
    $ci_application_date = $row['ci_application_date'];
    $ci_unit_applied = $row['ci_unit_applied'];
    $ci_pn_amount = $row['ci_pn_amount'];
    $ci_term = $row['ci_term'];
    $ci_address = $row['ci_address'];
    $ci_payment_record = $row['ci_payment_record'];
    $ci_residence_placeis = $row['ci_residence_placeis'];
    $ci_residence_location = $row['ci_residence_location'];
    $ci_residence_area = $row['ci_residence_area'];
    $ci_residence_made = $row['ci_residence_made'];
    $ci_reputation = $row['ci_reputation'];
    $ci_religion = $row['ci_religion'];
    $ci_violation = json_decode($row['ci_violation']);
    $ci_contactfinding = json_decode($row['ci_contactfinding']);
    $ci_residence_ownership = $row['ci_residence_ownership'];
    $ci_residence_length_year = round(($row['ci_residence_length'] / 12));
    $ci_residence_length_month = fmod($row['ci_residence_length'],12);
    if($ci_residence_length_month=='0') { $ci_residence_length = $ci_residence_length_year . " year/s"; }
    else {  $ci_residence_length = $ci_residence_length_year." year/s & ".$ci_residence_length_month." month/s"; }
    $ci_residence_rent = "₱ ".number_format($row['ci_residence_rent'],2);
    $ci_dependents = json_decode($row['ci_dependents']);
    $ci_communityrepuation = $row['ci_communityrepuation'];
    $ci_col_visit = $row['ci_col_visit'];
    $ci_col_number = $row['ci_col_number'];
    $ci_col_visitfrequently = $row['ci_col_visitfrequently'];
    $ci_residence_is = $row['ci_residence_is'];
    $ci_residence_furnituretype = $row['ci_residence_furnituretype'];
    $ci_appliance = json_decode($row['ci_appliance']);
    $ci_appliancepurchasetype = $row['ci_appliancepurchasetype'];
    $ci_installment_financer = $row['ci_installment_financer'];
    $ci_paymentpunctuality = $row['ci_paymentpunctuality'];
    $ci_residence_resource = json_decode($row['ci_residence_resource']);
    $ci_business_name = $row['ci_business_name'];
    $ci_business_nature = $row['ci_business_nature'];
    $ci_business_address = $row['ci_business_address'];
    $ci_position = $row['ci_position'];
    $ci_positionduration = $row['ci_positionduration'];
    $ci_positionduration_year = round(($row['ci_positionduration'] / 12));
    $ci_positionduration_month = fmod($row['ci_positionduration'],12);
    if($ci_positionduration_month=='0') { $ci_positionduration = $ci_positionduration_year . " year/s"; }
    else {  $ci_positionduration = $ci_positionduration_year." year/s & ".$ci_positionduration_month." month/s"; }
    $ci_income = "₱ ".number_format($row['ci_income'],2);
    $ci_business_resource = json_decode($row['ci_business_resource']);
    $ci_bank_status = $row['ci_bank_status'];
    $ci_bank_duration = $row['ci_bank_duration'];
    $ci_bank_resource = json_decode($row['ci_bank_resource']);
    $ci_bank_name = $row['ci_bank_name'];
    $ci_bank_address = $row['ci_bank_address'];
    $ci_loan_purpose = $row['ci_loan_purpose'];
    $a_monthly_income = "₱ ".number_format($row['a_monthly_income'],2);
    $spouse_monthly_income = "₱ ".number_format($row['spouse_monthly_income'],2);
    $less_deductions = "₱ ".number_format($row['less_deductions'],2);
    $expense_food = "₱ ".number_format($row['expense_food'],2);
    $expense_rent = "₱ ".number_format($row['expense_rent'],2);
    $expense_transport = "₱ ".number_format($row['expense_transport'],2);
    $expense_utility = "₱ ".number_format($row['expense_utility'],2);
    $expense_education = "₱ ".number_format($row['expense_education'],2);
    $expense_ma = "₱ ".number_format($row['expense_ma']);
    $expense_others = "₱ ".number_format($row['expense_others'],2);
    $recommendation_character = $row['recommendation_character'];
    $recommendation_capacity = $row['recommendation_capacity'];
    $recommendation_capital = $row['recommendation_capital'];
    $recommendation_condition = $row['recommendation_condition'];
    $recommendation_reference_name = $row['recommendation_reference_name'];
    $recommendation_reference_address = $row['recommendation_reference_address'];
    $recommendation_reference_contactnumber = $row['recommendation_reference_contactnumber'];
    $application_status = $row['application_status'];
    $recommenders = $row['recommender'];
    $recommender_recommended = $row['recommender_recommended'];
    $approvers = $row['approver'];
    $approver_approved = $row['approver_approved'];
    $noters = $row['noter'];
    $noter_noted = $row['noter_noted'];

    $name = "SELECT CONCAT(c_lastname, ', ', c_firstname, ' ', c_middlename) AS name FROM customers WHERE c_id=$ci_id";
    $recordset = mysql_query($name) or die('Query failed: ' . mysql_error());
    $customerName = mysql_fetch_assoc($recordset)['name'];

    $recommenderQuery = "SELECT CONCAT(e_lastname, ', ', e_firstname, ' ', e_middlename) AS name FROM employees WHERE e_id=$recommenders";
    $recommenderRecord = mysql_query($recommenderQuery) or die('Query failed: ' . mysql_error());
    $recommender = mysql_fetch_assoc($recommenderRecord)['name'];

    $approverQuery = "SELECT CONCAT(e_lastname, ', ', e_firstname, ' ', e_middlename) AS name FROM employees WHERE e_id=$approvers";
    $approverRecord = mysql_query($approverQuery) or die('Query failed: ' . mysql_error());
    $approver = mysql_fetch_assoc($approverRecord)['name'];

    $noterQuery = "SELECT CONCAT(e_lastname, ', ', e_firstname, ' ', e_middlename) AS name FROM employees WHERE e_id=$noters";
    $noterRecord = mysql_query($noterQuery) or die('Query failed: ' . mysql_error());
    $noter = mysql_fetch_assoc($noterRecord)['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?=$customerName;?>'s Reports</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <style type="text/css">
        .form-horizontal .form-group { margin-right: 0px; margin-left: 0px; }
        h4 { margin-bottom: -10px; }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th { border-top: 0px; }
        .table>thead>tr>th { border-bottom: 1px solid #eee; }   
        input, select { font-family: sans-serif; height: 20px !important; } 
        .table { margin: 0px; padding: 0px; }
        textarea { height: auto; }
    </style>
</head>
<body onload="autoprint()">
    <div class="container-fluid">
        <div class="text-center" style="margin-bottom: 20px">
            <img src="../images/logo.gif" height="50" style="margin-bottom: 0px; margin-top: 10px;">
            <h2 style="margin-bottom: 0px; margin-top: 0px">Credit Investigation Report</h2>
            <small>VMT - Village Motor Traders | Pastrana St, Kalibo, Aklan | (036) 268 2121 </small>
        </div>
        <form class="form-horizontal">
            <!--basic information-->
            <h4><strong>Basic Information</strong></h4><hr>
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Applicant</label>
                        <input disabled="disabled" type="text" class="form-control" value="<?=$customerName;?>">
                    </div>
                </div>
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <label>MA</label>
                        <input type="text" class="form-control" value="<?=$ci_ma?>" disabled>
                    </div>
                </div>
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" class="form-control" value="<?=$ci_application_date?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Units Applied</label>
                        <input type="text" class="form-control" value="<?=$ci_unit_applied?>" disabled>
                    </div>
                </div>
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <label>PN Amount</label>
                        <input type="number" class="form-control" value="<?=$ci_pn_amount?>" disabled>
                    </div>
                </div>
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <div class="form-group">
                        <label>Term</label>
                        <input type="text" class="form-control" value="<?=$ci_term?>" disabled>
                    </div>
                </div>
                <div class="col-xs-8 col-md-8 col-lg-8">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" value="<?=$ci_address?>" disabled>
                    </div>
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label>Payment Record</label>
                        <input type="text" class="form-control" value="<?=$ci_payment_record?>" disabled>
                    </div>
                </div>
            </div>

            <!--neighborhood checking-->
            <h4><strong>Neighborhood Checking</strong></h4><hr>
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Applicant's Place of Residence</label>
                        <input type="text" class="form-control" value="<?=$ci_residence_placeis?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Applicant's Residence Location</label>
                        <input type="text" class="form-control" value="<?=$ci_residence_location?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Characteristics for the Area of Residence</label>
                        <input type="text" class="form-control" value="<?=$ci_residence_area?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Residence is Made Of</label>
                        <input type="text" class="form-control" value="<?=$ci_residence_made?>" disabled>
                    </div>
                </div>
            </div>

            <!--background checking-->
            <h4><strong>Background Checking</strong></h4><hr>
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Applicant is known in the area as</label>
                        <input type="text" class="form-control" value="<?=$ci_reputation?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Religion</label>
                        <input type="text" class="form-control" value="<?=$ci_religion?>" disabled>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label>Applicant has been known to have engaged in</label>
                        <div class="checkbox" style="padding-top: 0px">
                            <div class="row">
                                <?php 
                                    foreach($ci_violation as $violation){
                                        echo '<div class="col-xs-6 col-md-6 col-lg-6">';
                                        echo '<label>'.$violation.'</label><br />';
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <table class="table">
                        <thead>
                            <tr>
                                <th><span class="mesmer-form-label label-block">Resource Person</span></th>
                                <th><span class="mesmer-form-label label-block">Other Findings</span></th>
                            </tr>
                        </thead>
                        <tbody class="ci-contactfinding-tbody">
                            <?php 
                                foreach($ci_contactfinding as $contactfinding){
                                    echo '<tr>';
                                    echo '<td><input type="text" class="form-control" value="' . $contactfinding->person . '" disabled></td>';
                                    echo '<td><input type="text" class="form-control" value="' . $contactfinding->finding . '" disabled></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Residence is</label>
                        <input type="text" class="form-control" value="<?=$ci_residence_ownership?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" class="form-control" value="<?=$ci_residence_length?>" disabled>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label>If rented how much is the monthly rent</label>
                        <input type="text" class="form-control" value="<?=$ci_residence_rent?>" disabled>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <table class="table">
                        <thead>
                            <tr>
                                <th><span class="mesmer-form-label label-block">Name of Dependents</span></th>
                                <th><span class="mesmer-form-label label-block">Relation</span></th>
                            </tr>
                        </thead>
                        <tbody class="ci-contactfinding-tbody">
                            <?php 
                                foreach($ci_dependents as $dependents){
                                    echo '<tr>';
                                    echo '<td><input type="text" class="form-control" value="' . $dependents->name . '" disabled></td>';
                                    echo '<td><input type="text" class="form-control" value="' . $dependents->relation . '" disabled></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Applicant is know in the community as</label>
                        <input type="text" class="form-control" value="<?=$ci_communityrepuation?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Does collectors visits residence</label>
                        <input type="text" class="form-control" value="<?=$ci_col_visit?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>If yes, how many?</label>
                        <input type="text" class="form-control" value="<?=$ci_col_number?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>How often?</label>
                        <input type="text" class="form-control" value="<?=$ci_col_visitfrequently?>" disabled>
                    </div>
                </div>
            </div>

            <!--background checking-->
            <h4><strong>Residence Checking - Observe Furnishings and Contents of Residence</strong></h4><hr>
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Residence of applicant is</label>
                        <input type="text" class="form-control" value="<?=$ci_residence_is?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Furnitures observed are mostly</label>
                        <input type="text" class="form-control" value="<?=$ci_residence_furnituretype?>" disabled>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label>Applicances</label>
                        <div class="checkbox" style="padding-top: 0px">
                            <div class="row">
                                <?php 
                                    foreach($ci_appliance as $appliance){
                                        echo '<div class="col-xs-6 col-md-6 col-lg-6">';
                                        echo '<label>'.$appliance.'</label><br />';
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Applicances purchased</label>
                        <input type="text" class="form-control" value="<?=$ci_appliancepurchasetype?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>If on installment: Financed PN Amount</label>
                        <input type="text" class="form-control" value="<?=$ci_installment_financer?>" disabled>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <em>If financed, ask for the last two(2) months official receipts.</em>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-xs-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Applicant pays rent/utilities/other obligations</label>
                                <input type="text" class="form-control" value="<?=$ci_paymentpunctuality?>" disabled>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <table class="table">
                                    <tr>
                                        <th><span class="mesmer-form-label label-block">Resource Person</span></th>
                                        <th><span class="mesmer-form-label label-block">Resource Address</span></th>
                                    </tr>
                                    <?php 
                                        foreach($ci_residence_resource as $residence_resource){
                                            echo '<tr>';
                                            echo '<td><input type="text" class="form-control" value="' . $residence_resource->name . '" disabled></td>';
                                            echo '<td><input type="text" class="form-control" value="' . $residence_resource->address . '" disabled></td>';
                                            echo '</tr>';
                                        }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <label>Applicant holds business/employed where?</label><hr style="margin-top: 5px">
                    <div class="row">
                        <div class="col-xs-8 col-md-8 col-lg-8">
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="form-control" value="<?=$ci_business_name?>" disabled>
                            </div>       
                        </div>
                        <div class="col-xs-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label>Nature of the Business</label>
                                <input type="text" class="form-control" value="<?=$ci_business_nature?>" disabled>
                            </div>       
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label>Company Address</label>
                        <input type="text" class="form-control" value="<?=$ci_business_address?>" disabled>
                    </div>   
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" class="form-control" value="<?=$ci_position?>" disabled>
                    </div>   
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" class="form-control" value="<?=$ci_positionduration?>" disabled>
                    </div>   
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label>Monthly Income</label>
                        <input type="text" class="form-control" value="<?=$ci_income?>" disabled>
                    </div>   
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <table class="table">
                            <tr>
                                <th><span class="mesmer-form-label label-block">Resource Person</span></th>
                                <th><span class="mesmer-form-label label-block">Resource Address</span></th>
                            </tr>
                            <?php 
                                foreach($ci_business_resource as $business_resource){
                                    echo '<tr>';
                                    echo '<td><input type="text" class="form-control" value="' . $business_resource->name . '" disabled></td>';
                                    echo '<td><input type="text" class="form-control" value="' . $business_resource->address . '" disabled></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </table>
                    </div>
                </div>
            </div>

            <!--bank checking-->
            <h4><strong>Bank Checkings Reveal that the Applicant has</strong></h4><hr>
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Account Status</label>
                        <input type="text" class="form-control" value="<?=$ci_bank_status?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>How Long</label>
                        <input type="text" class="form-control" value="<?=$ci_bank_duration?>" disabled>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <table class="table">
                            <tr>
                                <th><span class="mesmer-form-label label-block">Resource Person</span></th>
                                <th><span class="mesmer-form-label label-block">Resource Address</span></th>
                            </tr>
                            <?php 
                                foreach($ci_bank_resource as $bank_resource){
                                    echo '<tr>';
                                    echo '<td><input type="text" class="form-control" value="' . $bank_resource->name . '" disabled></td>';
                                    echo '<td><input type="text" class="form-control" value="' . $bank_resource->address . '" disabled></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </table>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Name of Bank</label>
                        <input type="text" class="form-control" value="<?=$ci_bank_name?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Branch of Bank</label>
                        <input type="text" class="form-control" value="<?=$ci_bank_address?>" disabled>
                    </div>
                </div>
            </div>

            <!--loan purpose-->
            <h4><strong>Loan Purpose</strong></h4><hr>
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label>Unit is for</label>
                        <input type="text" class="form-control" value="<?=$ci_loan_purpose?>" disabled>
                    </div>
                </div>
            </div>

            <!--loan purpose-->
            <h4 style="margin-bottom: 5px"><strong>Computation of Disposable Income</strong></h4>
            <em>Note: Co-maker's income should NOT be added to Maker's income except if spouse</em><hr style="margin-top: 10px">
            <div class="row">
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <h5 style="margin: 0px"><strong>Monthly Expense</strong></h5><hr style="margin-top: 10px">
                    <div class="form-group">
                        <label>Applicant</label>
                        <input type="text" class="form-control" value="<?=$a_monthly_income?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Spouse</label>
                        <input type="text" class="form-control" value="<?=$spouse_monthly_income?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Less: Deductions</label>
                        <input type="text" class="form-control" value="<?=$less_deductions?>" disabled>
                    </div>
                </div>
                <div class="col-xs-8 col-md-8 col-lg-8">
                    <h5 style="margin: 0px"><strong>Itemize Living Expense</strong></h5><hr style="margin-top: 10px">
                    <div class="row">
                        <div class="col-xs-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label>Food</label>
                                <input type="text" class="form-control" value="<?=$expense_food?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Rent</label>
                                <input type="text" class="form-control" value="<?=$expense_rent?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Transport</label>
                                <input type="text" class="form-control" value="<?=$expense_transport?>" disabled>
                            </div>      
                        </div>
                        <div class="col-xs-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label>Education</label>
                                <input type="text" class="form-control" value="<?=$expense_education?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>MA</label>
                                <input type="text" class="form-control" value="<?=$expense_ma?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Others</label>
                                <input type="text" class="form-control" value="<?=$expense_others?>" disabled>
                            </div>
                        </div>
                        <div class="col-xs-4 col-md-4 col-lg-4">                            
                            <div class="form-group">
                                <label>Utilities</label>
                                <input type="text" class="form-control" value="<?=$expense_utility?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--recommendation-->
            <h4 style="margin-bottom: 5px"><strong>Recommendation</strong></h4>
            <em>Justify basis for your recommendation</em><hr style="margin-top: 10px">
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Character: 40%</label>
                        <textarea class="form-control" disabled rows="8"><?=$recommendation_character?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Capital: 20%</label>
                        <textarea class="form-control" disabled rows="8"><?=$recommendation_capital?></textarea>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Capacity: 20%</label>
                        <textarea class="form-control" disabled rows="8"><?=$recommendation_capacity?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Condition: 20%</label>
                        <textarea class="form-control" disabled rows="8"><?=$recommendation_condition?></textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12" style="margin-bottom: 10px">
                    <em>Please note one reference very close to the applicant in the province or permanent address)</em>
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="<?=$recommendation_reference_name?>" disabled>
                    </div>
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label>Present Address</label>
                        <input type="text" class="form-control" value="<?=$recommendation_reference_address?>" disabled>
                    </div>
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" class="form-control" value="<?=$recommendation_reference_contactnumber?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Recommended By</label>
                        <input type="text" class="form-control" value="<?=$recommender?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Approved By</label>
                        <input type="text" class="form-control" value="<?=$approver?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Noted By</label>
                        <input type="text" class="form-control" value="<?=$noter?>" disabled>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" value="<?=$recommender_recommended?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" value="<?=$approver_approved?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" value="<?=$noter_noted?>" disabled>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        function autoprint(){ window.print() }
    </script>
</body>
</html>

