<?php
    session_start();
    
    $rol = array();

    $login_adm = false;

    $id_emp = 0;
    $nome = ''; 
    $email = '';
    $data_dev = '';

    $fb = 0;

    $login = '';

    $id_user = 0;

    if(isset($_SESSION['rol']))// rol de livros para empréstimo
    {
        $rol = $_SESSION['rol'];
    }

    if(isset($_POST['s']))
    {
        $_SESSION['s'] = $_POST['s'];

        $_SESSION['id_emp'] = $_POST['id_emp'];
        $_SESSION['data_dev'] = $_POST['data_dev'];

        header("Location: ?");
    }

    if(isset($_SESSION['s']))
    {
        $search = $_SESSION['s'];

        $id_emp = $_SESSION['id_emp'];
        $data_dev = $_SESSION['data_dev'];

        $data_dev = date('d/m/Y', strtotime($data_dev));
    }

    if(count($rol) == 0)
    {
        if($search != '')
            header("Location: index.php?search=$search");
        else
            header("Location: index.php");
    }

    if($id_emp != 0)
    {
        include "../config/php/connect.php";

        $sql = "SELECT nome, email, turma, telefone, login FROM user WHERE id_user = $id_emp";

        $res = mysqli_query($conn, $sql);

        if(mysqli_affected_rows($conn) > 0){
            $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

            $nome = ($row['nome']);
            $email = ($row['email']);
            $telefone = ($row['telefone']);
            $turma = ($row['turma']);
            if($turma == 'null' || $turma == '' ) $turma = "-";
        }
    }


    $successful = false;

    if(isset($_POST['subLogin']))
    {
        try 
        {
            include "../config/php/connect.php";

            $login = (mysqli_real_escape_string($conn, $_POST['login']));
            $senha = (mysqli_real_escape_string($conn, $_POST['password']));
            $md5 = md5($senha);

            if($login == 'root'){
                $fb = 5;
            }
            else {
                $sql = "SELECT id_user,senha FROM user WHERE login = '$login' AND bloqueado = 0 AND admin = 1";

                $res = mysqli_query($conn, $sql);
                
                if(mysqli_affected_rows($conn) > 0){
                    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                    $bd = $row['senha'];
                    
                    if($md5 == $bd){
                        $fb = 3;
                        $successful = true;
                        $id_user = $row['id_user'];
                        $_SESSION['id_user'] = $id_user;
                        $_SESSION['login'] = $login;
                    }
                    else
                        $fb = 2; // senha incorreta

                } 
                else {
                    $fb = 1; // login incorreto;
                }

                mysqli_close($conn);   
            }
        } catch (Exception $e){
            $fb = 5;
        }
    }

    if($successful) {
        
    }

    function successful ($id_emp) {
        $login = $_SESSION['login'];
        $append = "Usuário \"$login\" autorizou empréstimo id $id_emp.<br>";
        $file = '../admin/log.html';
        date_default_timezone_set("America/Sao_Paulo");

        $append = '['.date('d/m/Y H:i:s').'] '.$append;
        
        if(file_get_contents($file) != '')
            $append = file_get_contents($file).$append;

        file_put_contents($file, $append);
    }

    function updateLivro($id_livro) {
        try {
            include '../config/php/connect.php';

            $sql = "SELECT qtde FROM livro WHERE id_livro = $id_livro";
            
            $res = mysqli_query($conn, $sql);

            $row = mysqli_fetch_array($res, MYSQLI_NUM);

            $qtde = $row[0];

            if($qtde > 1) $newqtde = $qtde - 1;
            else $newqtde = 0;

            if($newqtde > 0)
            {
                $sql = "UPDATE livro SET
                qtde = $newqtde WHERE id_livro = $id_livro";
            }
            else 
            {
                $sql = "UPDATE livro SET
                qtde = $newqtde, disponivel = 0 WHERE id_livro = $id_livro";
            }

            $res = mysqli_query($conn, $sql);

            mysqli_close($conn);
        } catch (Exception $e) {

        }
    }

    if(isset($_GET['auth']))
    {
        $auth = $_GET['auth'];

        if($auth == '88b33545f1cb20d580832bafbf259adb')
        {
            try {
                include "../config/php/connect.php";

                $hoje = date('Y-m-d');
                $data_dev = str_replace('/', '-', $data_dev);
                $data_dev = date('Y-m-d', strtotime($data_dev));
                $id_user = $_SESSION['id_user'];

                foreach($rol as &$id_livro){
                    $sql = "INSERT INTO emprestimo VALUES (DEFAULT, $id_livro, $id_user, $id_emp, NOW(), '$data_dev', null, '', DEFAULT, DEFAULT);";
                    
                    if($res = mysqli_query($conn, $sql))
                    {
                        echo '<script>alert("Empréstimo realizado com sucesso!")</script>';
                        $id_sql = "SELECT LAST_INSERT_ID()";

                        $res = mysqli_query($conn, $id_sql);
    
                        $row = mysqli_fetch_array($res, MYSQLI_NUM);
            
                        $id_emprestimo = $row[0];

                        $success = true;
                        successful($id_emprestimo);
                        updateLivro($id_livro);
                    }
                }

                mysqli_close($conn);

                echo "<script> window.location.href = '..'";
            } catch (Exception $e) {

            }
        }
        else
            header("Location: index.php?search=$search");

        
        header("Location: ..");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Empréstimo - Apolo</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/final.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>
<?php

    $login = ($login);

    $error_msg = array("Login incorreto!", "Senha incorreta!");

    if($fb == 5){
        ?>
            <script>
                const el = document.createElement('div');
                el.innerHTML = 'Você <b>NÃO</b> pode utilizar o admin "root" para autorizar empréstimos!';
                                                
                swal({
                    title: "Erro!",
                    content: el,
                    icon: "error",
                });
            </script>
        <?php
    }

    // echo $fb;
    if($fb == 3){
        ?>
            <script>
                var delay = 1000;
                swal({
                    icon: "success",
                    title: "Empréstimo autorizado!",
                    text: "Carregando...",
                    buttons: false,
                    timer: delay,
                });
                setTimeout(function() { window.location.href="?auth=88b33545f1cb20d580832bafbf259adb"; }, delay);       
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
    <a href="finalizar.php?s=<?php echo $search; ?>" class="a voltaInicio">Voltar</a>
    </div>
    <!-- <div class="textcenter">
        <h4><a href=".." style="text-decoration:none">Apolo</a></h4>
        <h2>Autorizar Empréstimo</h2>
    </div>   -->

    <div class="centerLogin">
        <div class="centerLoginContent">     
            <div class="autoriza">
                <h3 class="autorizaApolo">Apolo</h3>
                <h1 class="autorizaTitle">Autorização de Empréstimo</h1>
            </div>
            <div class="login">
                <div class="loginContent">
                    <form action="" class="loginFrm" method="post">
                        <h3>Login de Administrador</h3>
                        <div class="loginField">
                            <div><label for="login">Login</label></div>
                            <input type="text" name="login" id="login" maxlength="20" <?php if($fb == 1) echo 'style="border-color:red"'; else if($fb == 2) echo 'value = "'.$login.'"'; ?>>
                        </div>
                        <div class="loginField">
                            <div for="senha" class="lblSenha"><label for="" id="senhaLabel">Senha</label><a onclick="senha()" id="senhaInfo" style="display: none">Esqueci minha senha</a></div>
                            <input type="password" name="password" id="senha"  <?php if($fb == 2) echo 'style="border-color:red"'; ?>>
                        </div>
                        <div class="loginSubmit">
                            <button type="submit" class="loginBtn" name="subLogin">Autorizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="info">
        <div class="infoTitle">
            <h3>Informações</h3>
        </div>
        <table class="infoTable">
            <tr>
                <th>
                    Nome:
                </th>
                <th>
                    Email:
                </th>
                <th>
                    Telefone:
                </th>
                <th>
                    Turma:
                </th>
                <th>
                    Devolução:
                </th>
            </tr>
            <tr>
                <td id="nome">
                    <h4><?php echo $nome; ?></h4>
                </td>
                <td id="email">    
                    <h4>
                        <?php echo $email; ?>
                    </h4>
                </td>
                <td id="telefone">    
                    <h4>
                        <?php echo $telefone; ?>
                    </h4>
                </td>
                <td id="turma">    
                    <h4><?php echo $turma; ?></h4>
                </td>
                <td id="data_dev">
                    <h4><?php echo $data_dev; ?></h4>
                </td>
            </tr>
        </table>
    </div>

    <div class="searchResults">
        <div class="searchContent redborder">
            <div class="rolTitle">
                <h3>Livros escolhidos</h3>
            </div>
            <table class="searchTable">
                <?php
                try {
                    include "../config/php/connect.php";
                    ?>
                    <tr>
                        <th>Livro</th>
                        <th>Gênero</th>
                        <th>Editora</th>
                        <th>Ano</th>
                        <th>Edição</th>
                    </tr>
                    <?php
                    foreach($rol as &$id_livro){
                        $sql = "SELECT id_livro, titulo, genero, autor, editora, ano, edicao, disponivel
                        FROM livro WHERE id_livro=$id_livro";
                
                        if($res = mysqli_query($conn, $sql))
                        {
                            $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                            $id = $row['id_livro'];
                            $titulo = ($row['titulo']);
                            $genero = ($row['genero']);
                            $autor = ($row['autor']);

                            $a_autor = explode("; ", $autor);

                            if(sizeof($a_autor) > 2)
                            {
                                $autor = $a_autor[0]."; ".$a_autor[1]."; et al.";
                            }

                            $editora = ($row['editora']);
                            $ano = ($row['ano']);
                            $edicao = ($row['edicao']);
                            $disp = ($row['disponivel']);
                            
                            ?>
                            <tr>
                                <td class="main"><?php echo $titulo; ?><br><b><?php echo $autor; ?></b></td>
                                <td><?php echo $genero; ?></td>
                                <td><?php echo $editora; ?></td>
                                <td><?php echo $ano; ?></td>
                                <td><?php echo $edicao."ᵃ"; ?></td>
                            </tr>
                            <?php
                        }
                        else    
                            header("..");
                    }

                                                
                }
                catch (Exception $e) {
                    header("..");
                }
                ?>
            </table>
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