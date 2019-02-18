<?php
    session_start();

    $login = $_SESSION['login'];
    $senha = $_SESSION['senha'];
    $nome = '';
    
    if(!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
        session_destroy();
        header("Location: index.php");
    }
    else {
        try 
        {
            include "../config/php/connect.php";

            $sql = "SELECT nome FROM user WHERE login = '$login'";

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
            $ra = utf8_decode(mysqli_real_escape_string($conn, $_POST['ra']));
            $login = utf8_decode(mysqli_real_escape_string($conn, $_POST['login']));
            $senha = utf8_decode(mysqli_real_escape_string($conn, $_POST['senha']));
            $tipo = utf8_decode(mysqli_real_escape_string($conn, $_POST['tipo']));
            $ano = "null";
            if(isset($_POST['ano'])){
                $ano = utf8_decode(mysqli_real_escape_string($conn, $_POST['ano']));
            }
            $telefone = utf8_decode(mysqli_real_escape_string($conn, $_POST['telefone']));
            
            
            $sql = "INSERT INTO user VALUES
            (DEFAULT, '$nome', '$ra', '$login', '$senha', $ano, '$tipo', '$telefone', DEFAULT);";

            // echo $sql;            

            $res = mysqli_query($conn, $sql);
            
            $nome = utf8_encode($nome);
            if(mysqli_affected_rows($conn) > 0){
                // echo 'a';
                echo '<script>
                alert("Administrador \"'.$nome.'\" inserido com sucesso!");
                </script>';
            } 
            else {
                // echo 'b';
                $erro = mysqli_error($conn);
                echo '<script>
                alert("Falha ao inserir administrador \"'.$nome.'\"!\nMais detalhes: '.$erro.'");
                </script>';
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
            <!-- <div class="cadastroField">
                <label for="">Título</label>
                <input type="text" name="titulo">
            </div>
            <div class="cadastroField">
                <label for="genero">Gênero</label>
                <select name="genero" id="genero">
                    <option value=""></option>
                </select>
            </div> -->

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
            <input type="number" name="ano" id="ano" maxlenght="1">
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