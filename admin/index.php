<?php
    session_start();
    session_destroy();
    session_start();

    $fb = 0; // inicio

    $login = '';
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
            include "../config/php/connect.php";

            $login = $_POST['login'];
            $senha = $_POST['password'];
            $md5 = md5($senha);

            $sql = "SELECT senha FROM user WHERE login = '$login'";

            $res = mysqli_query($conn, $sql);
            
            if(mysqli_affected_rows($conn) > 0){
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                $bd = $row['senha'];
                
                if($md5 == $bd){
                    $fb = 3;
                    successful($md5, $login);
                }
                else
                    $fb = 2; // senha incorreta

            } 
            else {
                $fb = 1; // login incorreto;
            }

            mysqli_close($conn);
        } catch (Exception $e){
            $fb = 5;
        }
    }

    function successful ($md5, $login) {
        $_SESSION['login'] = $login;
        $_SESSION['senha'] = $md5;

        // echo $_SESSION['senha'];
        // echo $_SESSION['login'];
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

    $error_msg = array("Login incorreto!", "Senha incorreta!");
    
    if($fb == 5){
        
    }

    // echo $fb;
    if($fb == 3){
        ?>
            <script>
                swal({
                    title: "Conexão bem sucedida!",
                    icon: "success",
                }).then((value) =>{
                    if(value){
                        window.location.href="main.php";
                    }
                    else {
                        window.location.href="main.php";
                    }
                });
            </script>
        <?php
    }
    else if($fb != 0){
        ?>
            <script>
                swal({
                    title: "<?php echo $error_msg[$fb-1] ?>",
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
                            <input type="text" name="login" id="login" maxlength="20" <?php if($fb == 1) echo 'style="border-color:red"'; else if($fb == 2) echo 'value = "'.$login.'"'; ?>>
                        </div>
                        <div class="loginField">
                            <div for="senha" class="lblSenha"><label for="" id="senhaLabel">Senha</label><a onclick="senha()" id="senhaInfo" style="display: none">Esqueci minha senha</a></div>
                            <input type="password" name="password" id="senha"  <?php if($fb == 2) echo 'style="border-color:red"'; ?>>
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
                <li><a href="../admin" target="_blank" class="footerOpt" title="Funções administrativas">Administração</a></li>
                <li><a href="../sobre" class="footerOpt"  title="Sobre o sistema">Sobre</a></li>
            </ul>
        </div>
    </div>
</body>

<script src="../js/main.js"></script>
</html>