<?php

    function addDays($timestamp, $days, $skipdays = array("Saturday", "Sunday"), $skipdates = array()) {
        // $skipdays: array (Monday-Sunday) eg. array("Saturday","Sunday")
        // $skipdates: array (YYYY-mm-dd) eg. array("2012-05-02","2015-08-01");
    //timestamp is strtotime of ur $startDate
        $i = 1;

        while ($days >= $i) {
            $timestamp = strtotime("+1 day", $timestamp);
            if ( (in_array(date("l", $timestamp), $skipdays)) || (in_array(date("Y-m-d", $timestamp), $skipdates)) )
            {
                $days++;
            }
            $i++;
        }

        return $timestamp;
        //return date("m/d/Y",$timestamp);
    }


?>