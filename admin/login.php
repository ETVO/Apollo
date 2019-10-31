<?php
    session_start();
    include '../config/php/support.php';
    if(!isset($_SESSION['login']) || !isset($_SESSION['pass'])) {
        session_destroy();
        header("Location: index.php");
    }
    else {
        $login = $_SESSION['login'];
        $pass = $_SESSION['pass'];

        if(!checkLogin($login, $pass))
        {
            echo 'b';
            session_destroy();
            header("Location: index.php");
        }
    }
?>
