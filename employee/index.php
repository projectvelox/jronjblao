<?php 
    require '../includes/document_head.php'; 

    $query = "SELECT 
                    *,
                    (SELECT b_name FROM branches WHERE e_branch_id=branches.b_id) AS branch_name,
                    (SELECT d_name FROM departments WHERE e_department_id=departments.d_id) AS department_name,
                    (SELECT d_name FROM employee_designations WHERE e_designation_id=employee_designations.d_id) AS designation                   
                FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "e_id":"'.$row['e_id'].'",
            "e_no":"'.'E'.$row['e_id'].'",
            "e_lastname":"'.$row['e_lastname'].'",
            "e_firstname":"'.$row['e_firstname'].'",
            "branch_name":"'.$row['branch_name'].'",
            "department_name":"'.$row['department_name'].'",
            "designation":"'.$row['designation'].'",
        },';
    }
    $data .= ']';  

?>

<div id="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/navigation.php' ?>
    <div class="main_container container_16 clearfix fullsize">
        <div class="table_header">
            <i class="fa fa-th-large" aria-hidden="true"></i> Employees
            <button class="toolbar-button edit-employee"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Employee</button>
            <button class="toolbar-button new-employee"><i class="fa fa-plus" aria-hidden="true"></i> New Employee</button>
        </div>
        <div class="page-wrapper">
            <div class="col-xs-12 col-md-12 col-lg-12 search-container">
                <input name="search" type="text" class="form-control input-md field_input search-box" value="" required>
                <button class="toolbar-button search-buttons action-search"><i class="fa fa-times" aria-hidden="true"></i> Clear</button>
                <button class="toolbar-button search-buttons action-searchclear"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
            </div>                     
            <div id="dbgrid"></div>                           
        </div>
    </div>  
</div>

<script>
    $("#dbgrid").jsGrid({
        height: "100%",
        width: "100%",
        filtering: true,
        editing: false,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 15,
        pageButtonCount: 15,
        data: <?php echo $data; ?>,
        fields: [
            {   
                title:"", align:"left",width:30, align:"center", sorting:false,
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-record" value="'+e.e_id+'"></div>'); },
            },   
            {   
                title:"ID", align:"left",width:55, align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-id="'+e.e_id+'" class="grid-link view_employee">'+e.e_no+'</span></div>'); },                
            },  
            {name:"e_firstname", type:"text", width:100, title:"FIRST NAME", align:"left"},
            {name:"e_lastname", type:"text", width:100, title:"LASTNAME", align:"left"},                     
            {name:"branch_name", type:"text", width:150, title:"BRANCH", align:"left"},
            {name:"department_name", type:"text", width:150, title:"DEPARTMENT", align:"left"},
            {name:"designation", type:"text", width:150, title:"DESIGNATION", align:"left"},
        ]
    });
</script>
<script src="employee.js"></script>
<?php require '../includes/closing_items.php' ?>
