<?php
    session_start();

    $fb = 0; // inicio

    $login = '';
    $bdsession = '';

    if(isset($_GET['logout']))
    {
        $logout = $_GET['logout'];
        if($logout){
            session_destroy();
            session_start();
        }
    }

    if(isset($_POST['subLogin']))
    {
        try 
        {
            include "config/php/connect.php";

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

            close($conn);
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
<script>
    var page = "inicio";
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Apolo</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css"> 
    <script src="config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="favicon.ico">    
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
                    text: "Entrando em sua conta...",
                    icon: "success",
                }).then((value) =>{
                    if(value){
                        window.location.href="main";
                    }
                    else {
                        window.location.href="main";
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

<div class="index">
            <div class="indexContent">
                <div class="present">
                    <div class="presentContent">
                        <div class="presentLogo">
                            <img src="" alt="">
                        </div>
                        <div class="presentTitle">
                            <h1 onclick="window.location.href='main';">Apolo</h1>
                        </div>
                    </div>
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
                            <div class="btnSubmit">
                                <input type="submit" name="subLogin" value="Entrar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    ?>
</body>
<script src="js/main.js"></script>
</html>