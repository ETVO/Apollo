<?php
    session_start();
    session_destroy();
    session_start();

    $fb = -1; // inicio

    $login = '';
    $pass = '';    
    $bdsession = '';

    // if(isset($_GET['logout']))
    // {
    //     $logout = $_GET['logout'];
    //     if($logout){
    //         session_destroy();
    //         session_start();
    //     }
    // }

    if(isset($_POST['subLogin']))
    {
        try 
        {
            include "../config/php/support.php";

            $login = $_POST['login'];
            $pass = $_POST['pass'];

            $fb = checkLogin($login, $pass);

        } catch (Exception $e){
            $fb = false;
        }
    }

    function successful ($login, $pass) {
        $_SESSION['login'] = $login;
        $_SESSION['pass'] = $pass;

        $append = "Usuário \"$login\" logou no sistema.<br>";
        $file = 'log.html';
        date_default_timezone_set("America/Sao_Paulo");

        $append = '['.date('d/m/Y H:i:s').'] '.$append;
        
        if(file_get_contents($file) != '')
            $append = file_get_contents($file).$append;

        file_put_contents($file, $append);
    }

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Administração - Apolo</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>
<?php

    $login = ($login);

    // echo $fb;
    if($fb == 1){
        successful($login, $pass);
        ?>
            <script>
                var delay = 1000;
                swal({
                    icon: "success",
                    title: "Conexão bem sucedida!",
                    text: "Entrando...",
                    buttons: false,
                    timer: delay,
                });
                setTimeout(function() { window.location.href="main.php"; }, delay);       
            </script>
        <?php
    }
    else if($fb == 0){
        ?>
            <script>
                swal({
                    title: "Senha incorreta ou usuário indisponível!",
                    icon: "error",
                });
            </script>
        <?php
    }
?>
    <div class="centerAdm heightAdm">
        <div class="centerAdmContent">     
            <div class="admin">
                <h3 class="adminApolo">Apolo</h3>
                <h1 class="adminTitle">Administração</h1>
            </div>
            <div class="login">
                <div class="loginContent">
                    <form action="" class="loginFrm" method="post">
                        <div class="loginField">
                            <div><label for="login">Login</label></div>
                            <input type="text" name="login" id="login" maxlength="20" <?php if(!$fb) echo 'value = "'.$login.'"'; ?>>
                        </div>
                        <div class="loginField">
                            <div for="senha" class="lblSenha">
                                <label for="" id="senhaLabel">Senha</label>
                                <a onclick="senha()" id="senhaInfo" style="display: none">Esqueci minha senha</a>
                            </div>
                            <input type="password" name="pass" id="senha">
                        </div>
                        <div class="loginSubmit">
                            <button type="submit" class="loginBtn" name="subLogin">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="footerDesc">
            © 2019 <b><a href="../main" title="Início">Apolo</a></b> - Sistema da Biblioteca CTI
        </div>
        <div class="footerItems">
            <ul>
                <li><a href="../admin" class="footerOpt" title="Funções administrativas">Administração</a></li>
                <li><a href="../sobre" class="footerOpt"  title="Sobre o sistema">Sobre</a></li>
                <li><a href="../faq" class="footerOpt"  title="Ajuda">Ajuda</a></li>
                <li><a href="../" class="footerOpt"  title="Página inicial">Início</a></li>
            </ul>
        </div>
    </div>
</body>

<script src="../js/main.js"></script>
</html>