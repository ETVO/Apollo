<?php
    session_start();

    $login = $_SESSION['login'];
    $senha = $_SESSION['senha'];
    $nome = '';
    
    if(!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
        session_destroy();
        header("Location: index.php");
    }
    else if($login == 'root' && $senha == '632f4902f2afb597923c18ea897eefa7'){
    }
    else {
        try 
        {
            include "../config/php/connect.php";

            $sql = "SELECT nome FROM user WHERE login = '$login' AND bloqueado = 0";

            $res = mysqli_query($conn, $sql);
            
            if(mysqli_affected_rows($conn) > 0){
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                $nome = utf8_encode($row['nome']);
                $nome = explode(" ", $nome)[0];
            } 
            else {
                session_destroy();
                header("Location: index.php");
            }

            mysqli_close($conn);
        } catch (Exception $e){

        }
    }

    if(isset($_POST['subCadUser'])) {
        $id_user = 0;
        $success = false;
        try 
        {
            include "../config/php/connect.php";


            // $a = substr($titulo, 0, 2);
            // $as = substr($titulo, 0, 3);

            // if(strtolower($as) == 'as ' || strtolower($as) == 'os ')
            //     $titulo = substr($titulo, 3).", $as";
            // else if(strtolower($a) == 'a ' || strtolower($a) == 'o ')
            //     $titulo = substr($titulo, 2).", $a";


            $nome = utf8_decode(mysqli_real_escape_string($conn, $_POST['nome']));
            $ra  = "null";if(isset($_POST['ano'])){
                $ra = "'".utf8_decode(mysqli_real_escape_string($conn, $_POST['ra']))."'";
            }
            $newlogin = utf8_decode(mysqli_real_escape_string($conn, $_POST['login']));
            
            if($newlogin != 'root'){
                $newsenha = utf8_decode(mysqli_real_escape_string($conn, $_POST['senha']));
                $newsenha = md5($newsenha);
                $tipo = utf8_decode(mysqli_real_escape_string($conn, $_POST['tipo']));
                $ano = "null";
                if(isset($_POST['ano'])){
                    $ano = utf8_decode(mysqli_real_escape_string($conn, $_POST['ano']));
                }
                $telefone = utf8_decode(mysqli_real_escape_string($conn, $_POST['telefone']));
                
                $sql = "INSERT INTO user VALUES
                (DEFAULT, '$nome', $ra, '$newlogin', '$newsenha', $ano, '$tipo', '$telefone', DEFAULT);";

                $res = mysqli_query($conn, $sql);
                
                $nome = utf8_encode($nome);
                if(mysqli_affected_rows($conn) > 0){
                    // echo 'a';
                    echo '<script>
                    alert("Administrador \"'.$nome.'\" inserido com sucesso!");
                    </script>';

                    
                    $id_sql = "SELECT LAST_INSERT_ID()";

                    $res = mysqli_query($conn, $id_sql);

                    $row = mysqli_fetch_array($res, MYSQLI_NUM);
        
                    $id_user = $row[0];

                    $success = true;
                } 
                else {
                    // echo 'b';
                    $erro = mysqli_error($conn);
                    echo '<script>
                    alert("Falha ao inserir administrador \"'.$nome.'\"!\nMais detalhes: '.$erro.'");
                    </script>';
                }
            }   
            else {
                echo '<script>
                    alert("Você não pode utilizar este login.");
                    </script>';
            }
            
            if($success)
            {
                $append = "Usuário \"$login\" criou o administrador id $id_user.<br>";
                $file = 'log.html';
                date_default_timezone_set("America/Sao_Paulo");

                $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
                
                if(file_get_contents($file) != '')
                    $append = file_get_contents($file).$append;

                file_put_contents($file, $append);
            }

            mysqli_close($conn);
            echo "<script>
            window.location.href = 'prg.php?url=cadusu.php';
            </script>";
        } catch (Exception $e){

        }
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
    <link rel="stylesheet" href="../css/mainadm.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>    
    <a href="main.php" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h3>Cadastro de <a href="main.php?sel=a" class="a">Administrador</a></h3>
    </div>
    
    <div class="cadastro">
        <form action="" method="post" class="cadastroFrm">
            <label for="nome">Nome</label><br>
            <input type="text" name="nome" id="nome" required autofocus maxlenght="70">
            <br><br>
            <label for="ra" id="lblRa">RA</label><br>
            <input type="number" name="ra" id="ra" maxlenght="7" min="1000000">
            <br><br>
            <label for="login">Login</label><br>
            <input type="text" name="login" id="login" required maxlenght="20">
            <br><br>
            <label for="senha">Senha</label><br>
            <input type="password" name="senha" id="senha" required maxlenght="16">
            <br><br>
            <label for="tipo">Tipo</label><br>
            <select name="tipo" id="tipo" required onchange="verAluno()">
                <option value="" disabled selected>-- Selecione uma opção --</option>
                <option value="Aluno">Aluno</option>
                <option value="Professor">Professor</option>
                <option value="Funcionário">Funcionário</option>
            </select>
            <br><br>
            <label for="ano" id="lblAno">Série</label><br>
            <input type="number" name="ano" id="ano" maxlenght="1" min="1" max="5" placeholder="1 a 3">
            <br><br>
            <label for="telefone">Telefone</label><br>
            <input type="text" name="telefone" id="telefone" maxlenght="15" placeholder="Ex.: 14987654321">
            <br><br>
            <button type="submit" name="subCadUser" class="cadBtn">Salvar</button>
            <button type="reset" class="cadBtn reset">Limpar</button>
        </form>
    </div>
    
</body>

<script src="../js/main.js"></script>
</html>