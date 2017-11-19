<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";

    $c_id = $_GET['id'];
    $query = "SELECT CONCAT(c_lastname, ', ', c_firstname, ' ', c_middlename) AS name FROM customers WHERE c_id=$c_id";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $customer_name = mysql_fetch_assoc($recordset)['name'];


    $employees = [];
    $query = "SELECT e_id as id, CONCAT(e_lastname, ', ', e_firstname, ' ', e_middlename) AS name FROM employees";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) $employees[] = $row;
?>
<style type="text/css">
    .row { margin: 0px 0px 15px 0px; }
    button { margin-right: 0px; }
    input[disabled] {background-color: rgb(240,240,240) !important;}
</style>
<div class="form-container">
    <form id="form_create_ci">
    <input style="display: none;" name="ci_id" type="text" class="form-control input-md field_input" value="<?php echo $_GET['id']; ?>">
        <div class="container-fluid">
            <h3 class="ci-form-sub-label"><strong>Basic Information</strong></h3>
            <hr style="margin-bottom: 5px; margin-top: 5px;">
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Applicant</span>
                    <input disabled="disabled" type="text" class="form-control input-md field_input" value=" <?=$customer_name;?>">
                </div>
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <span class="mesmer-form-label label-block">MA</span>
                    <input type="text" name="ci_ma" placeholder="Enter the MA" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <span class="mesmer-form-label label-block">Date</span>
                    <input type="text" name="ci_application_date" placeholder="Enter the date of the application" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Units Applied</span>
                    <input type="text" name="ci_unit_applied" placeholder="Enter the Unit/s Applied" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <span class="mesmer-form-label label-block">PN Amount</span>
                    <input type="number" name="ci_pn_amount" placeholder="Enter the PN Amount" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-3 col-md-3 col-lg-3">
                    <span class="mesmer-form-label label-block">Term</span>
                    <input type="text" name="ci_term" placeholder="Enter the term" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-8 col-md-8 col-lg-8">
                    <span class="mesmer-form-label label-block">Address</span>
                    <input type="text" name="ci_address" placeholder="Enter the applicant's address" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <span class="mesmer-form-label label-block">Payment Record</span>
                    <select name="ci_payment_record" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Good</option>
                        <option>Fair</option>
                        <option>Poor</option>
                    </select>
                </div>
            </div>

            <h3 class="ci-form-sub-label"><strong>Neighborhood Checking</strong></h3>
            <hr style="margin-bottom: 5px; margin-top: 5px;">
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Applicant's Place of Residence</span>
                    <select name="ci_residence_placeis" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Accessible to motor vehicles</option>
                        <option>Not accessible to motor vehicles</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Applicant's Residence Location</span>
                    <select name="ci_residence_location" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Along the street/road</option>
                        <option>Along the alley</option>
                        <option>Along the railroad</option>
                        <option>In an interior lot</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Characteristics for the Area of Residence </span>
                    <select name="ci_residence_area" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Busy</option>
                        <option>Peaceful</option>
                        <option>Critical</option>
                        <option>Flood/disaster-threatened</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Residence is made of</span>
                    <select name="ci_residence_made" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Concrete</option>
                        <option>Semi-concrete</option>
                        <option>Wood</option>
                        <option value="Light materials">Light materials (nipa/bamboo)</option>
                    </select>
                </div>
            </div>

            <h3 class="ci-form-sub-label"><strong>Background Checking</strong></h3>
            <hr style="margin-bottom: 5px; margin-top: 5px;">
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Applicant is known in the area as</span>
                    <select name="ci_reputation" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Friendly</option>
                        <option>Reclusive</option>
                        <option>Notorious</option>
                        <option>Having good reputation</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Religion</span>
                    <input type="text" name="ci_religion" class="form-control input-md field_input" value="" placeholder="Enter the applicant's religion">
                </div>
                <span class="mesmer-form-label label-block" style="margin: 60px 0px -5px 5px; display: block; height: 18px;">Applicant has been known to have engaged in</span>
                <div class="row" style="margin-bottom: 0px; padding-left: 5px;">
                    <div class="col-xs-6 col-md-6 col-lg-6">
                        <div class="checkbox">
                            <label><input type="checkbox" name="ci_violation[]" value="Excessive drinking">Excessive drinking</label><br />
                            <label><input type="checkbox" name="ci_violation[]" value="Infidelity to spouse">Infidelity to spouse</label><br />
                            <label><input type="checkbox" name="ci_violation[]" value="Live-in arrangement">Live-in arrangement</label>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6 col-lg-6">
                        <div class="checkbox">
                            <label><input type="checkbox" name="ci_violation[]" value="No known vices/crimes">No known vices/crimes</label><br />
                            <label><input type="checkbox" name="ci_violation[]" value="Gambling (cock fighting)">Gambling (cock fighting)</label><br />
                            <label><input type="checkbox" name="ci_violation[]" value="Illegal activities">Illegal activities</label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <table class="ci-bootstrap-table">
                        <thead>
                            <tr>
                                <th><span class="mesmer-form-label label-block">Resource Person</span></th>
                                <th><span class="mesmer-form-label label-block">Other Findings</span></th>
                            </tr>
                        </thead>
                        <tbody class="ci-contactfinding-tbody">
                            <!--
                            <tr>
                                <td>
                                    <input type="text" name="ci_contactfinding[0][person]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                                <td>
                                    <input type="text" name="ci_contactfinding[0][finding]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                            </tr>
                            -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td style="text-align: right;">
                                    <button closewindow="off" class="btn btn-xs btn-danger ci-contact-finding-remove"><span class="glyphicon glyphicon-minus"></span></button>
                                    <button closewindow="off" class="btn btn-xs btn-primary ci-contact-finding-add"><span class="glyphicon glyphicon-plus"></span></button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Residence is</span>
                    <select name="ci_residence_ownership" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Owned by applicant</option>
                        <option>Rented by applicant</option>
                        <option>Owned by applicant's relatives</option>
                        <option>Applicant is a boarder/transient</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">How long?</span>
                    <div class="col-xs-4 col-md-4 col-lg-4">
                        <input type="number" name="ci_residence_length" placeholder="Duration" class="form-control input-md field_input" value="">
                    </div>
                    <div class="col-xs-8 col-md-8 col-lg-8">
                        <select name="ci_residence_length_type" class="form-control input-md field_input">
                            <option disabled selected>Select an option below</option>
                            <option>Months</option>
                            <option>Years</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block">If rented, how much is the monthly rent?</span>
                    <input type="number" name="ci_residence_rent" placeholder="Enter the monthly rent" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <table class="ci-bootstrap-table">
                        <thead>
                            <tr>
                                <th><span class="mesmer-form-label label-block">Name of Dependents</span></th>
                                <th><span class="mesmer-form-label label-block">Relation</span></th>    
                            </tr>
                        </thead>
                        <tbody class="ci-dependents-tbody">
                            <!--
                            <tr>
                                <td>
                                    <input type="text" name="ci_dependents[0][name]" placeholder="Name other than the applicant's children" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                                <td>
                                    <input type="text" name="ci_dependents[0][relation]" placeholder="Relation to the applicant" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                            </tr>
                            -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td style="text-align: right;">
                                    <button closewindow="off" class="btn btn-xs btn-danger ci-dependents-remove"><span class="glyphicon glyphicon-minus"></span></button>
                                    <button closewindow="off" class="btn btn-xs btn-primary ci-dependents-add"><span class="glyphicon glyphicon-plus"></span></button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Applicant is known in the community as</span>
                    <select name="ci_communityrepuation" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>In good health</option>
                        <option>Sickly</option>
                        <option>Physically Disabled</option>
                        <option>Suffering from deadly/incurable disease</option>
                        <option value="With health problem">With health problem (diabetes/high blood presseure/others)</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Does collectors (other companies) visits residence</span>
                    <select name="ci_col_visit" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">If yes, how many?</span>
                    <input type="number" name="ci_col_number" placeholder="How many other collectors" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">How often?</span>
                    <input type="text" name="ci_col_visitfrequently" placeholder="How often do they visit" class="form-control input-md field_input" value="">
                </div>
            </div>

            <h3 class="ci-form-sub-label"><strong>Residence Checking - Observe furnishings and contents of residence</strong></h3>
            <hr style="margin-bottom: 5px; margin-top: 5px;">
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Residence of applicant is</span>
                    <select name="ci_residence_is" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Furnished</option>
                        <option>Semi-furnished</option>
                        <option>Not furnished</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Furnitures observed are mostly</span>
                    <select name="ci_residence_furnituretype" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Upholstered</option>
                        <option>Wood/Bamboo</option>
                        <option>Monobloc</option>
                        <option>None</option>
                    </select>
                </div>
                <br />
                <span class="mesmer-form-label label-block" style="margin: 45px 0px -5px 5px; display: block; height: 18px;">Applicances</span>
                    <div class="row" style="margin-bottom: 0px; padding-left: 5px;">
                    <div class="col-xs-6 col-md-6 col-lg-6">
                        <div class="checkbox">
                            <label><input type="checkbox" name="ci_appliance[]" value="Ref">Ref</label><br />
                            <label><input type="checkbox" name="ci_appliance[]" value="TV">TV</label><br />
                            <label><input type="checkbox" name="ci_appliance[]" value="DVD/VCD/Compo">DVD/VCD/Compo</label><br />
                            <label><input type="checkbox" name="ci_appliance[]" value="Others">Others</label>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6 col-lg-6">
                        <div class="checkbox">
                            <label><input type="checkbox" name="ci_appliance[]" value="Aircon">Aircon</label><br />
                            <label><input type="checkbox" name="ci_appliance[]" value="Washing Machine">Washing Machine</label><br />
                            <label><input type="checkbox" name="ci_appliance[]" value="Gas Range">Gas Range</label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Applicances purchased</span>
                    <select name="ci_appliancepurchasetype" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Cash</option>
                        <option>Installment</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">If on installment: Financed PN Amount</span>
                    <input type="text" name="ci_installment_financer" placeholder="Enter the financed PN Amount" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12"><span class="mesmer-form-label label-block" style="font-weight: normal; color: #797979;"><em>If financed, ask for the last two(2) months official receipts.</em></span></div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Applicant pays rent/utilities/other obligations</span>
                    <select name="ci_paymentpunctuality" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Always on time</option>
                        <option>Sometimes late</option>
                        <option>Never on time</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <table class="ci-bootstrap-table">
                        <thead>
                            <tr>
                                <th><span class="mesmer-form-label label-block">Resource Person</span></th>
                                <th><span class="mesmer-form-label label-block">Resource Address</span></th>
                            </tr>
                        </thead>
                        <tbody class="ci-residence-resource-tbody">
                            <tr>
                                <td>
                                    <input type="text" name="ci_residence_resource[0][name]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                                <td>
                                    <input type="text" name="ci_residence_resource[0][address]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td style="text-align: right;">
                                    <button closewindow="off" class="btn btn-xs btn-danger ci-residence-resource-remove"><span class="glyphicon glyphicon-minus"></span></button>
                                    <button closewindow="off" class="btn btn-xs btn-primary ci-residence-resource-add"><span class="glyphicon glyphicon-plus"></span></button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <span class="mesmer-form-label label-block">Applicant holds business/employed where?</span>
                <div class="col-xs-8 col-md-8 col-lg-8">
                    <span class="mesmer-form-label label-block">Company</span>
                    <input type="text" name="ci_business_name" placeholder="Enter company name" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <span class="mesmer-form-label label-block">Nature of business</span>
                    <input type="text" name="ci_business_nature" placeholder="Enter nature of business" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block">Address</span>
                    <input type="text" name="ci_business_address" placeholder="Enter company address" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <span class="mesmer-form-label label-block">Position</span>
                    <input type="text" name="ci_position" placeholder="Enter position in company" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-2 col-md-2 col-lg-2">
                    <span class="mesmer-form-label label-block">How long? years</span>
                    <input type="number" name="ci_positionduration_year" placeholder="# of Years" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-2 col-md-2 col-lg-2">
                    <span class="mesmer-form-label label-block">months</span>
                    <input type="number" name="ci_positionduration_month" placeholder="# of Months" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <span class="mesmer-form-label label-block">Monthly Income</span>
                    <input type="number" name="ci_income" placeholder="Enter monthyly income" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <table class="ci-bootstrap-table">
                        <thead>
                            <tr>
                                <th><span class="mesmer-form-label label-block">Resource Person</span></th>
                                <th><span class="mesmer-form-label label-block">Resource Address</span></th>
                            </tr>
                        </thead>
                        <tbody class="ci-business-resource-tbody">
                            <tr>
                                <td>
                                    <input type="text" name="ci_business_resource[0][name]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                                <td>
                                    <input type="text" name="ci_business_resource[0][address]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td style="text-align: right;">
                                    <button closewindow="off" class="btn btn-xs btn-danger ci-business-resource-remove"><span class="glyphicon glyphicon-minus"></span></button>
                                    <button closewindow="off" class="btn btn-xs btn-primary ci-business-resource-add"><span class="glyphicon glyphicon-plus"></span></button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <h3 class="ci-form-sub-label"><strong>Bank checkings reveal that the applicant has</strong></h3>
            <hr style="margin-bottom: 5px; margin-top: 5px;">
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Account Status</span>
                    <select name="ci_bank_status" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Poorly handled checking accts/loans</option>
                        <option>Properly checking accts/loans</option>
                        <option>Savings/time deposits on record</option>
                        <option>Valued client status</option>
                        <option>No bank accounts</option>
                    </select>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">How long?</span>
                    <input type="number" name="ci_bank_duration" placeholder="Enter how long is the account" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <table class="ci-bootstrap-table">
                        <thead>
                            <tr>
                                <th><span class="mesmer-form-label label-block">Resource Person</span></th>
                                <th><span class="mesmer-form-label label-block">Resource Address</span></th>
                            </tr>
                        </thead>
                        <tbody class="ci-bank-resource-tbody">
                            <tr>
                                <td>
                                    <input type="text" name="ci_bank_resource[0][name]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                                <td>
                                    <input type="text" name="ci_bank_resource[0][address]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td style="text-align: right;">
                                <button closewindow="off" class="btn btn-xs btn-danger ci-bank-resource-remove"><span class="glyphicon glyphicon-minus"></span></button>
                                <button closewindow="off" class="btn btn-xs btn-primary ci-bank-resource-add"><span class="glyphicon glyphicon-plus"></span></button>
                            </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Name of Bank</span>
                    <input type="text" name="ci_bank_name" placeholder="Enter name of the bank" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Branch of Bank</span>
                    <input type="text" name="ci_bank_address" placeholder="Enter branch of the bank" class="form-control input-md field_input" value="">
                </div>
            </div>

            <h3 class="ci-form-sub-label"><strong>Loan Purpose</strong></h3>
            <hr style="margin-bottom: 5px; margin-top: 5px;">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block">Unit is for</span>
                    <select name="ci_loan_purpose" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option> 
                        <option>Public utility tricycle</option>
                        <option>Personal</option>
                        <option>Business/Delivery use</option>
                        <option>Habal-habal</option>
                        <option>Others</option>
                    </select>
                </div>
            </div>
            
            <h3 class="ci-form-sub-label"><strong>Computation of Disposable Income</strong></h3>
            <span class="mesmer-form-label label-block">Note: Co-maker's income should NOT be added to Maker's income except if spouse</span>
            <hr style="margin-bottom: 5px; margin-top: 5px;">
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Monthly Income</span>
                    <table class="table">
                        <tr>
                            <td>Applicant</td>
                            <td><input type="number" name="a_monthly_income" class="form-control input-md field_input" value=""></td>
                        </tr>
                        <tr>
                            <td>Spouse</td>
                            <td><input type="number" name="spouse_monthly_income" class="form-control input-md field_input" value=""></td>
                        </tr>
                        <tr>
                            <td>Less: Deductions</td>
                            <td><input type="number" name="less_deductions" class="form-control input-md field_input" value=""></td>
                        </tr>
                    </table>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">
                    <span class="mesmer-form-label label-block">Itemize living expenses</span>
                    <table class="table">
                        <tr>
                            <td>Food</td>
                            <td><input type="number" name="expense_food" class="form-control input-md field_input" value=""></td>
                        </tr>
                        <tr>
                            <td>Rent</td>
                            <td><input type="number" name="expense_rent" class="form-control input-md field_input" value=""></td>
                        </tr>
                        <tr>
                            <td>Transport</td>
                            <td><input type="number" name="expense_transport" class="form-control input-md field_input" value=""></td>
                        </tr>
                        <tr>
                            <td>Utilities</td>
                            <td><input type="number" name="expense_utility" class="form-control input-md field_input" value=""></td>
                        </tr>
                        <tr>
                            <td>Education</td>
                            <td><input type="number" name="expense_education" class="form-control input-md field_input" value=""></td>
                        </tr>
                        <tr>
                            <td>MA</td>
                            <td><input type="number" name="expense_ma" class="form-control input-md field_input" value=""></td>
                        </tr>
                        <tr>
                            <td>Others</td>
                            <td><input type="number" name="expense_others" class="form-control input-md field_input" value=""></td>
                        </tr>
                    </table>
                </div>
            </div>

            <h3 class="ci-form-sub-label"><strong>Recommendation</strong></h3>
            <span class="mesmer-form-label label-block">Justify basis for your recommendation</span>
            <hr style="margin-bottom: 5px; margin-top: 5px;">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block">Character: 40%</span>
                    <textarea name="recommendation_character" class="form-control input-md field_input" rows="3"></textarea>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block">Capacity: 20%</span>
                    <textarea name="recommendation_capacity" class="form-control input-md field_input" rows="3"></textarea>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block">Capital: 20%</span>
                    <textarea name="recommendation_capital" class="form-control input-md field_input" rows="3"></textarea>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block">Condition: 20%</span>
                    <textarea name="recommendation_condition" class="form-control input-md field_input" rows="3"></textarea>
                </div>

                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block" style="font-weight: normal; color: #797979"><em>(Please note one reference very close to the applicant in the province or permanent address)</em></span>
                    <span class="mesmer-form-label label-block">Name</span>
                    <input type="text" name="recommendation_reference_name"  placeholder="Enter recommended person name" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block">Present Address</span>
                    <input type="text" name="recommendation_reference_address"  placeholder="Enter recommended person address" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <span class="mesmer-form-label label-block">Latest Number</span>
                    <input type="text" name="recommendation_reference_contactnumber"  placeholder="Enter recommended person contact" class="form-control input-md field_input" value="">
                </div>
                <div class="col-xs-4 col-md-4 col-lg-4">
                    <span class="mesmer-form-label label-block">Application Status</span>
                    <select name="application_status" class="form-control input-md field_input">
                        <option disabled selected>Select an option below</option>
                        <option>Approved</option>
                        <option>Denied</option>
                    </select>
                </div>
                <div class="col-xs-8 col-md-8 col-lg-8">
                    <div class="row">
                        <div class="col-xs-6 col-md-6 col-lg-6">
                            <span class="mesmer-form-label label-block" style="padding-top: 0px;">RECOMMENDED BY</span>
                            <select name="recommender" class="form-control input-md field_input">
                                <option disabled selected>Select an option below</option>
                                <?php
                                    foreach ($employees as $employee) {
                                        echo '<option value="' . $employee["id"] . '">' . $employee["name"] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-xs-6 col-md-6 col-lg-6">
                            <span class="mesmer-form-label label-block" style="padding-top: 0px;">Date</span>
                            <input type="text" name="recommender_recommended" class="form-control input-md field_input" value="">
                        </div>
                        <div class="col-xs-6 col-md-6 col-lg-6">
                            <span class="mesmer-form-label label-block">APPROVED BY</span>
                            <select name="approver" class="form-control input-md field_input">
                                <option disabled selected>Select an option below</option>
                                <?php
                                    foreach ($employees as $employee) {
                                        echo '<option value="' . $employee["id"] . '">' . $employee["name"] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-xs-6 col-md-6 col-lg-6">
                            <span class="mesmer-form-label label-block">Date</span>
                            <input type="text" name="approver_approved" class="form-control input-md field_input" value="">
                        </div>
                        <div class="col-xs-6 col-md-6 col-lg-6">
                            <span class="mesmer-form-label label-block">NOTED BY</span>
                            <select name="noter" class="form-control input-md field_input">
                                <option disabled selected>Select an option below</option>
                                <?php
                                    foreach ($employees as $employee) {
                                        echo '<option value="' . $employee["id"] . '">' . $employee["name"] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-xs-6 col-md-6 col-lg-6">
                            <span class="mesmer-form-label label-block">Date</span>
                            <input type="text" name="noter_noted" class="form-control input-md field_input" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button save-ci"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
</div>
<script>

    //date pickers
    $('input[name="ci_application_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="recommender_recommended"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="approver_approved"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="noter_noted"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });

    //class
    var ItemizeObject = function(container, addbtn, removebtn, template) {
        var object = this;
        object.add = function(options){
            options = options || {};
            options.index = $(container).children().length;
            $(container).append(template(options));
        };
        object.minus = function() {
            if($(container).children().length > 1) $(container).children().last().remove();
        };
        $(removebtn).on("click", function(){ object.minus(); });
        $(addbtn).on("click", function(){ object.add(); });
        object.add();
    };
    var ci_contact_finding = new ItemizeObject(
        ".ci-contactfinding-tbody", 
        ".ci-contact-finding-add", 
        ".ci-contact-finding-remove",
        function(options){
            var person = options.person || "";
            var finding = options.finding || "";
            return (
                '<tr>' +
                    '<td>' +
                        '<input type="text" name="ci_contactfinding[' + options.index + '][person]" placeholder="Enter something" class="form-control input-md field_input" value="' + person + '" style="margin-bottom: 5px;">' +
                    '</td>' +
                    '<td>' +
                        '<input type="text" name="ci_contactfinding[' + options.index + '][finding]" placeholder="Enter something" class="form-control input-md field_input" value="' + finding + '" style="margin-bottom: 5px;">' +
                    '</td>' +
                '</tr>'
            );

        });
    var ci_dependents = new ItemizeObject(
        ".ci-dependents-tbody", 
        ".ci-dependents-add", 
        ".ci-dependents-remove",
        function(options){
            var name = options.name || "";
            var relation = options.relation || "";
            return (
                '<tr>' +
                '    <td>' +
                '        <input type="text" name="ci_dependents[' + options.index + '][name]" placeholder="Name other than the applicant\'s children" class="form-control input-md field_input" value="' + name + '" style="margin-bottom: 5px;">' +
                '    </td>' +
                '    <td>' +
                '        <input type="text" name="ci_dependents[' + options.index + '][relation]" placeholder="Relation to the applicant" class="form-control input-md field_input" value="' + relation + '" style="margin-bottom: 5px;">' +
                '    </td>' +
                '</tr>'
            );
        });




    /*
    //ci-dependents
    $(".ci-dependents-remove").on("click", function() {
      var tbody = $(".ci-dependents-tbody");
      if($(tbody).children().length > 1) $(tbody).children().last().remove();
    });
    $(".ci-dependents-add").on("click", function() {
        var tbody = $(".ci-dependents-tbody");
        var index = $(tbody).children().length;
        $(tbody).append(
            '<tr>' +
            '    <td>' +
            '        <input type="text" name="ci_dependents[' + index + '][name]" placeholder="Name other than the applicant\'s children" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">' +
            '    </td>' +
            '    <td>' +
            '        <input type="text" name="ci_dependents[' + index + '][relation]" placeholder="Relation to the applicant" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">' +
            '    </td>' +
            '</tr>'
        );
    });
*/



    //ci-residence-resource
    $(".ci-residence-resource-remove").on("click", function() {
      var tbody = $(".ci-residence-resource-tbody");
      if($(tbody).children().length > 1) $(tbody).children().last().remove();
    });
    $(".ci-residence-resource-add").on("click", function() {
        var tbody = $(".ci-residence-resource-tbody");
        var index = $(tbody).children().length;
        $(tbody).append(
            '<tr>' +
            '    <td>' +
            '        <input type="text" name="ci_residence_resource[' + index + '][name]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">' +
            '    </td>' +
            '    <td>' +
            '        <input type="text" name="ci_residence_resource[' + index + '][address]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">' +
            '    </td>' +
            '</tr>'
        );
    });

    //ci-business-resource
    $(".ci-business-resource-remove").on("click", function() {
      var tbody = $(".ci-business-resource-tbody");
      if($(tbody).children().length > 1) $(tbody).children().last().remove();
    });
    $(".ci-business-resource-add").on("click", function() {
        var tbody = $(".ci-business-resource-tbody");
        var index = $(tbody).children().length;
        $(tbody).append(
            '<tr>' +
            '    <td>' +
            '        <input type="text" name="ci_business_resource[' + index + '][name]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">' +
            '    </td>' +
            '    <td>' +
            '        <input type="text" name="ci_business_resource[' + index + '][address]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">' +
            '    </td>' +
            '</tr>'
        );
    });

    //ci-bank-resource
    $(".ci-bank-resource-remove").on("click", function() {
      var tbody = $(".ci-bank-resource-tbody");
      if($(tbody).children().length > 1) $(tbody).children().last().remove();
    });
    $(".ci-bank-resource-add").on("click", function() {
        var tbody = $(".ci-bank-resource-tbody");
        var index = $(tbody).children().length;
        $(tbody).append(
            '<tr>' +
            '    <td>' +
            '        <input type="text" name="ci_bank_resource[' + index + '][name]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">' +
            '    </td>' +
            '    <td>' +
            '        <input type="text" name="ci_bank_resource[' + index + '][address]" placeholder="Enter something" class="form-control input-md field_input" value="" style="margin-bottom: 5px;">' +
            '    </td>' +
            '</tr>'
        );
    });

    //name="ci_col_visit"
    $('[name="ci_col_visit"]').on("change", function(){
        if($(this).val() == "Yes") {
            $('[name="ci_col_number"]').removeAttr("disabled");
            $('[name="ci_col_visitfrequently"]').removeAttr("disabled");
        }
        else {
            $('[name="ci_col_number"]').attr("disabled", "disabled");
            $('[name="ci_col_visitfrequently"]').attr("disabled", "disabled");
        }
    }).trigger("change");

    //name="ci_appliancepurchasetype"
    $('[name="ci_appliancepurchasetype"]').on("change", function(){
        if($(this).val() == "Installment") $('[name="ci_installment_financer"]').removeAttr("disabled");
        else $('[name="ci_installment_financer"]').attr("disabled", "disabled");
    }).trigger("change");

    //name="ci_violation[]"
    $('[name="ci_violation[]"]').on("change", function() {
        $('[name="ci_violation[]"]').each(function(){
            if($(this).val().toUpperCase() == "NO KNOWN VICES/CRIMES" && $(this).prop("checked")) {
                $('[name="ci_violation[]"]').prop("checked", false); 
                $(this).prop("checked", true); 
            }
        });
    });

</script>