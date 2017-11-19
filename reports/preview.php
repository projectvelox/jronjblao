<?php
define('rptFolderPath', '/');   // insert path as needed
define('rptChattel'  ,  1);     // chattel with mortgage 
define('rptCreditApp',  2);     // credit application
define('rptCreditInv',  3);     // credit investigation
define('rptDeedSale' ,  4);     // deed of sale 
define('rptMercXfer' ,  5);     // merchandise transfer 
define('rptPromNote' ,  6);     // promissory note 
define('rptSchedPmt' ,  7);     // schedule of payment 
define('rptWaiver'   ,  8);     // waiver 

$rpt = '';

function generateReport($thisRpt = NULL)
{
    if(!$thisRpt == NULL) { $rpt = '';}
    else 
    {
        switch($thisRpt)
        {
            case rptChattel:
                $rpt = rptFolderPath . "rpt-form-chattel.php#view=FitH&scrollbar=1&toolbar=1&statusbar=1&messages=1&navpanes=1";
                break;
            case rptCreditApp:
                $rpt = rptFolderPath . "rpt-form-credit-app.php#view=FitH&scrollbar=1&toolbar=1&statusbar=1&messages=1&navpanes=1";
                break;
            case rptCreditInv:
                $rpt = rptFolderPath . "rpt-form-credit-inv.php#view=FitH&scrollbar=1&toolbar=1&statusbar=1&messages=1&navpanes=1";
                break;
            case rptDeedSale:
                $rpt = rptFolderPath . "rpt-form-deed-sale.php#view=FitH&scrollbar=1&toolbar=1&statusbar=1&messages=1&navpanes=1";
                break;
            case rptMercXfer:
                $rpt = rptFolderPath . "rpt-form-merc-transfer.php#view=FitH&scrollbar=1&toolbar=1&statusbar=1&messages=1&navpanes=1";
                break;
            case rptPromNote:
                $rpt = rptFolderPath . "rpt-form-promisorry.php#view=FitH&scrollbar=1&toolbar=1&statusbar=1&messages=1&navpanes=1";
                break;
            case rptSchedPmt:
                $rpt = rptFolderPath . "rpt-form-schedule-payment.php#view=FitH&scrollbar=1&toolbar=1&statusbar=1&messages=1&navpanes=1";
                break;
            case rptWaiver:
                $rpt = rptFolderPath . "rpt-form-waiver.php#view=FitH&scrollbar=1&toolbar=1&statusbar=1&messages=1&navpanes=1";
                break;
        }
    }
    return $rpt;
}

?>

<div class="pdf">
    <object data="report.pdf" type="application/pdf" width="100%" height="550px"></object>
    <?php
    //echo generateReport($r); 
    ?>
</div>