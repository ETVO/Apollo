<?php
    // error_reporting(0);

    $server = "localhost";
    $username = "root";
    // $password = "rootAdmBibliotecaCTI";
    $password = "";
    $db = "apolo";

    //Connection
    $conn = new mysqli($server, $username, $password, $db);

    $conn->set_charset("utf8");
    header("Content-type: text/html; charset=utf-8"); 

    if($conn->connect_error){
        // return array("A conexão falhou!", $conn->connect_error, false);
        // die("A conexão falhou: ".$conn->connect_error);
        throw new Exception ("Não foi possível conectar ao banco de dados!");
        exit;
    }

    // function close($conn)
    // {
    //     mysqli_close($conn);
    // }
?>