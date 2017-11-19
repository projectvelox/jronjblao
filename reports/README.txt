NEW COLUMNS:
  
  table: customers
    add columns:
       c_birthplace         varchar(256) default ''
       c_contactpersion     varchar(256) default ''



DATA REQUIREMENTS FOR REPORTS

********** SALES REPORTS
x(1) Report File:  rpt-ar-customerpmt.php
       
       paper size: letter
       input     : 
           $_SESSION['salesID']       -->  sales_main.s_id --- THIS IS THE INVOICE NUMBER
        

x(2) Report File:  rpt-ar-aging-accts.php

       paper size: legal
       input     : 
           $_SESSION['reportDate']
           $_SESSION['branchID']
           $_SESSION['areaID']
           $_SESSION['collectorID']

x(3) Report File:   rpt-ar-collections.php
       paper size: letter
       types     : daily and monthly 
                    - if starting date and ending date are the same then switch to daily report
                    - if starting date and ending date are the same then switch to monthly report
       input     : 
           $_SESSION['dateStart']
           $_SESSION['dateEnd']
           $_SESSION['branchID']
           $_SESSION['areaID']
           $_SESSION['collectorID']






********** FORMS

x(1) Report File:  rpt-form-chattel.php
       paper size: letter
       input     : 
           $_SESSION['salesID']

x(2) Report File:  rpt-form-schedule-payment.php
       paper size: letter
       input     : 
           $_SESSION['salesID']


x(3) Report File:  rpt-form-waiver.php
       paper size: letter
       input     : 
           $_SESSION['salesID']


x(4) Report File:  rpt-form-deed-sale.php
       paper size: letter
       input     : 
           $_SESSION['salesID']

x(5) Report File:  rpt-form-promissory.php
       paper size: letter
       input     : 
           $_SESSION['salesID']

x(6) Report File:  rpt-form-credit-app.php
       paper size: letter
       input     : 
           $_SESSION['salesID']

x(7) Report File:  rpt-form-merc-transfer.php
       paper size: letter
       input     : 
           $_SESSION['transferID']
       note:  <<<<<<<<<<<<<<<<================================================
         there is no Unit of Measure in tables (products or inventory)
         assumed to in p_property_7 in products table
       









(7) Report File:  rpt-form-credit-inv.php
       paper size: 
       input     : 
           $_SESSION['']


