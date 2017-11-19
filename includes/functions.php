<?php

    function set_current_page($current_page, $current_tab, $menu_name) {
        if ($current_page == $current_tab) {
            echo '<u>' . $menu_name . '</u>';
        } else {
            echo $menu_name;
        }
    }


    function string_sanitize($s) {
        $result = preg_replace("/[^a-zA-Z0-9]+/", " ", html_entity_decode($s, ENT_QUOTES));
        return $result;
    }

/*if (class_exists('truncate_text') != true) { 
    function truncate_text($text, $nChar) {
    if(strlen($text) > $nChar ) {
        return substr($text,0,$nChar)."...";
        }
        else { return $text;}
    }
}*/

/*if (class_exists('number_pad') != true) { 
    function number_pad($number,$n) {
        return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
    }
}*/

/*
if (class_exists('getWorkingDays') != true) {
    function getWorkingDays($startDate,$endDate,$holidays){
        // do strtotime calculations just once
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);
        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;
        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);
        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);
        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)

            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }
        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
    //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
       $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
          $workingDays += $no_remaining_days;
        }
        if($holidays!=""){
            //We subtract the holidays
            foreach($holidays as $holiday){
                $time_stamp=strtotime($holiday);
                //If the holiday doesn't fall in weekend
                if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                    $workingDays--;
            }
        }
        return $workingDays;
    }
}

if (class_exists('set_current') != true) {



}
if (class_exists('show_employeephoto') != true) {

    function show_employeephoto($filename) {
        if (file_exists("photos/".$filename.".jpg")) {
            echo '<img src="photos/' . $filename . '.jpg" class="field_photo" >';
        } else {
            echo '<img src="photos/nophoto.png" class="field_photo" >';
        }
        
    }
}
if (class_exists('show_customerphoto') != true) {

    function show_customerphoto($filename) {
        if (file_exists("../reports/customerphotos/".$filename.".jpg")) {
            echo '<img src="reports/customerphotos/' . $filename . '.jpg" class="field_photo" >';
        } else {
            echo '<img src="reports/customerphotos/nophoto.png" class="field_photo" >';
        }
        
    }
}
if (class_exists('show_customerphoto2') != true) {

    function show_customerphoto2($filename) {
        if (file_exists("customerphotos/".$filename.".jpg")) {
            return '<img src="customerphotos/' . $filename . '.jpg" class="field_photo" >';
        } else {
            return '<img src="customerphotos/nophoto.png" class="field_photo" >';
        }
        //echo $filename;
    }
}

if (class_exists('show_employeephoto2') != true) {

    function show_employeephoto2($filename) {
        if (file_exists("../photos/".$filename.".jpg")) {
            echo '<img src="photos/' . $filename . '.jpg" class="field_photo" >';
        } else {
            echo '<img src="photos/nophoto.png" class="field_photo" >';
        }
        
    }
}
if (class_exists('show_salesagentphoto') != true) {

    function show_salesagentphoto($filename) {
        if (file_exists("../salesagentphotos/".$filename.".jpg")) {
            echo '<img src="salesagentphotos/' . $filename . '.jpg" class="field_photo" >';
        } else {
            echo '<img src="salesagentphotos/nophoto.png" class="field_photo" >';
        }
        
    }
}
if (class_exists('show_icon') != true) {

    function show_icon($icon) {
        if ($icon == "calendar") {
            echo '<img src="images/calendar.png" class="middle">';
        }
        if ($icon == "wais") {
            echo '<img src="images/wais.png" class="middle">';
        }
    }

}
if (class_exists('show_currentuser') != true) {

    function show_currentuser($username) {
        echo '<span class="text_style_white">Employee:</span>';
        echo '<span class="text_style_gray">' . $username . '</span>';
    }

}
if (class_exists('show_branch') != true) {

    function show_branch($branch_name) {
        echo '<span class="text_style_white">Branch:</span>';
        echo '<span class="text_style_gray">' . $branch_name . '</span>';
    }

}
if (class_exists('show_date') != true) {

    function show_date($param) {
        if ($param == 'fulldate') {
            echo '<span class="icon">' . show_icon("calendar") . '</span>';
            echo '<span class="text_style_gray">' . date('F j, Y (l)') . '</span>';
        }
    }

}
if (class_exists('timeago') != true) {

    function timeago($date) {
        $stf = 0;
        $cur_time = time();
        $diff = $cur_time - $date;
        $phrase = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
        $length = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);
        for ($i = sizeof($length) - 1; ($i >= 0) && (($no = $diff / $length[$i]) <= 1); $i--)
            ; if ($i < 0)
            $i = 0; $_time = $cur_time - ($diff % $length[$i]);
        $no = floor($no);
        if ($no <> 1)
            $phrase[$i] .='s'; $value = sprintf("%d %s ", $no, $phrase[$i]);
        if (($stf == 1) && ($i >= 1) && (($cur_tm - $_time) > 0))
            $value .= time_ago($_time);
        return $value . ' ago ';
    }

}
if (class_exists('tooltip_posting') != true) {

    function tooltip_posting($link_url, $link_subject, $link_message, $employee_name) {
        echo "<span class='posting_date'>" . $employee_name . ', posted <a href="../css/style.css"' . ' onMouseover="' . 'show_tooltip(' . "'" . $link_message . "')" . '"; onMouseout="hide_tooltip()">' . $link_subject . '</a></span>';
    }

}
if (class_exists('tooltip_image') != true) {

    function tooltip_image($link_url, $link_message, $link_img) {
        echo '<a href="' . $link_url . '"' . ' onMouseover="' . 'show_tooltip(' . "'" . $link_message . "')" . '"; onMouseout="hide_tooltip()"><img src="' . $link_img . '"></a>';
    }

}
*/
?>
