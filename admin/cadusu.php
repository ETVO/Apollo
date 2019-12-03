<?php
    include 'head.php';
    include 'login.php';

    if(isset($_POST['subCadUser'])) {
        $id_user = 0;
        $success = false;
        try 
        {
            include "../config/php/connect.php";

            $nome = utf8_decode(mysqli_real_escape_string($conn, $_POST['nome']));
            $ra  = (isset($_POST['turma'])) ? "'".utf8_decode(mysqli_real_escape_string($conn, $_POST['ra']))."'" : "null";
            $newlogin = utf8_decode(mysqli_real_escape_string($conn, $_POST['login']));
            $newsenha = utf8_decode(mysqli_real_escape_string($conn, $_POST['senha']));
            $newsenha = md5($newsenha);
            $tipo = utf8_decode(mysqli_real_escape_string($conn, $_POST['tipo']));
            $turma = (isset($_POST['turma'])) ? utf8_decode(mysqli_real_escape_string($conn, $_POST['turma'])) : "null";
            $telefone = utf8_decode(mysqli_real_escape_string($conn, $_POST['telefone']));
            $email = utf8_decode(mysqli_real_escape_string($conn, $_POST['email']));
            $admin = (isset($_POST['admin'])) ? utf8_decode(mysqli_real_escape_string($conn, $_POST['admin'])) : "false";
            
            $sql = "SELECT * FROM user WHER login = '$newlogin'";
            $res = mysqli_query($conn, $sql);
            if(mysqli_affected_rows($conn) > 0){
                echo '<script>
                    alert("O login '.$newlogin.' já está sendo utilizado.");
                    </script>';
            }
            else
            {
                $sql = "INSERT INTO user VALUES
                (DEFAULT, '$nome', $ra, '$newlogin', '$newsenha', '$turma', '$tipo', '$email', '$telefone', $admin, DEFAULT);";

                $res = mysqli_query($conn, $sql);
                if(mysqli_affected_rows($conn) > 0){
                    echo '<script>
                    alert("Usuário \"'.$nome.'\" inserido com sucesso!");
                    window.location.href = "prg.php?url=main.php?sel=u";
                    </script>';

                    
                    $id_sql = "SELECT LAST_INSERT_ID()";

                    $res = mysqli_query($conn, $id_sql);

                    $row = mysqli_fetch_array($res, MYSQLI_NUM);
        
                    $id_user = $row[0];

                    $success = true;
                } 
                else {
                    $erro = mysqli_error($conn);
                    echo '<script>
                    alert("Falha ao inserir administrador \"'.$nome.'\"!\nMais detalhes: '.$erro.'");
                    </script>';
                }
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
    <a href="main.php?sel=u" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h3>Cadastro de <a href="main.php?sel=a" class="a">Administrador</a></h3>
    </div>
    
    <div class="cadastro">
        <form action="" method="post" class="cadastroFrm">
            <label for="tipo">Tipo</label><br>
            <select name="tipo" id="tipo" required onchange="verAluno()" autofocus>
                <option value="" disabled selected>-- Selecione uma opção --</option>
                <option value="Aluno">Aluno</option>
                <option value="Professor">Professor</option>
                <option value="Funcionário">Funcionário</option>
            </select>
            <br><br>
            <label for="nome">Nome</label><br>
            <input type="text" name="nome" id="nome" required maxlenght="70">
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
            <label for="ano" id="lblAno">Turma</label><br>
            <input type="text" name="turma" id="ano" maxlenght="3" placeholder="Ex.: 73A">
            <br><br>
            <label for="telefone">Telefone</label><br>
            <input type="text" name="telefone" id="telefone" maxlenght="15" placeholder="Ex.: 14987654321">
            <br><br>
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" maxlenght="255" required>
            <br><br>
            <input type="checkbox" name="admin" id="admin" value="true">
            <label for="admin">Administrador?</label>
            <br><br>
            <button type="submit" name="subCadUser" class="cadBtn">Salvar</button>
            <button type="reset" class="cadBtn reset">Limpar</button>
        </form>
    </div>
    
</body>

<script src="../js/main.js"></script>
</html>