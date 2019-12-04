<?php

    function addDays($timestamp, $days, $skipdays = array("Saturday", "Sunday"), $skipdates = array()) {
        // $skipdays: array (Monday-Sunday) eg. array("Saturday","Sunday")
        // $skipdates: array (YYYY-mm-dd) eg. array("2012-05-02","2015-08-01");
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
    }

    function getWorkdays($date1, $date2, $workSat = FALSE, $patron = NULL) {
        if (!defined('SATURDAY')) define('SATURDAY', 6);
        if (!defined('SUNDAY')) define('SUNDAY', 0);
        // Array of all public festivities
        $publicHolidays = array('01-01', '01-06', '04-25', '05-01', '06-02', '08-15', '11-01', '12-08', '12-25', '12-26');
        // The Patron day (if any) is added to public festivities
        if ($patron) {
            $publicHolidays[] = $patron;
        }
        /*
            * Array of all Easter Mondays in the given interval
            */
        $yearStart = date('Y', strtotime($date1));
        $yearEnd   = date('Y', strtotime($date2));
        for ($i = $yearStart; $i <= $yearEnd; $i++) {
            $easter = date('Y-m-d', easter_date($i));
            list($y, $m, $g) = explode("-", $easter);
            $monday = mktime(0,0,0, date($m), date($g)+1, date($y));
            $easterMondays[] = $monday;
        }
        $start = strtotime($date1);
        $end   = strtotime($date2);
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if ($day != SUNDAY &&
            !in_array($mmgg, $publicHolidays) &&
            !in_array($i, $easterMondays) &&
            !($day == SATURDAY && $workSat == FALSE)) {
                $workdays++;
            }
        }
        return intval($workdays);
    }

    function calculaMulta($dias){
        include 'connect.php';

        $sql = "SELECT valor FROM config WHERE nome='multa'";

        $res = mysqli_query($conn, $sql);

        if(mysqli_affected_rows($conn) > 0)
        {
            $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

            $multa = $row['valor'];
        }

        $multa = $dias * $multa;

        return $multa;
    }

    function getPrazo()
    {
        try {
            include '../config/php/connect.php';

            $sql = "SELECT valor FROM config WHERE nome='dias_dev'";

            $res = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0)
            {
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

                $dias_dev = $row['valor'];
            }

            mysqli_close($conn);
        } catch (Exception $e) {

        }

        return $dias_dev;
    }

    function flname($name, $del)
    {
        $a_nome = explode($del,$name);

        if(count($a_nome) >= 3)
        {
            $name = $a_nome[0].' '.$a_nome[count($a_nome) - 1];
        }
        
        return $name;
    }

    

    function getAbrev($genero)
    {
        if($genero == "Literatura Brasileira") return "LB";
        if($genero == "Literatura Estrangeira") return "LE";
        if($genero == "História em Quadrinhos") return "HQ";
        if($genero == "Ciências Sociais") return "CS";
        if($genero == "Ciências Naturais") return "CN";
        if($genero == "Literatura Didática") return "LD";
    }

    function getGenero($abrev)
    {
        if($abrev == "LB") return "Literatura Brasileira";
        if($abrev == "LE") return "Literatura Estrangeira";
        if($abrev == "HQ") return "História em Quadrinhos";
        if($abrev == "CS") return "Ciências Sociais";
        if($abrev == "CN") return "Ciências Naturais";
        if($abrev == "LD") return "Literatura Didática";
    }

    function getCodigo($abrev, $id)
    {
        if($id < 10)
        {
            $strid = "00$id";
        }
        else if($id < 100)
        {
            $strid = "0$id";
        }
        else
        {
            $strid = $id;
        }
        return $abrev.'-'.$strid;
    }

?>