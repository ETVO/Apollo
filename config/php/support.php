<?php

    function checkLogin($login, $pass)
    {
        include "connect.php";
        
        $login = (mysqli_real_escape_string($conn, $login));
        $pass = (mysqli_real_escape_string($conn, $pass));
        $md5 = md5($pass);

        $sql = "SELECT senha FROM user WHERE login = '$login' AND bloqueado = 0 AND admin = 1";

        $res = mysqli_query($conn, $sql);
        
        if(mysqli_affected_rows($conn) > 0){
            $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
            $bd = $row['senha'];
            
            if($md5 == $bd){
                return 1;
            }

        } 
        
        return 0;  
    }

?>