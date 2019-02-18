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

    if(isset($_POST['subCadLivro'])) {
        try 
        {
            include "../config/php/connect.php";


            // $a = substr($titulo, 0, 2);
            // $as = substr($titulo, 0, 3);

            // if(strtolower($as) == 'as ' || strtolower($as) == 'os ')
            //     $titulo = substr($titulo, 3).", $as";
            // else if(strtolower($a) == 'a ' || strtolower($a) == 'o ')
            //     $titulo = substr($titulo, 2).", $a";

            $titulo = utf8_decode(mysqli_real_escape_string($conn, $_POST['titulo']));
            $genero = utf8_decode(mysqli_real_escape_string($conn, $_POST['genero']));
            $autor = utf8_decode(mysqli_real_escape_string($conn, $_POST['autor']));
            $editora = utf8_decode(mysqli_real_escape_string($conn, $_POST['editora']));            
            $ano = utf8_decode(mysqli_real_escape_string($conn, $_POST['ano']));
            $edicao = utf8_decode(mysqli_real_escape_string($conn, $_POST['edicao']));
            $obs = utf8_decode(mysqli_real_escape_string($conn, $_POST['obs']));

            $sql = "INSERT INTO livro VALUES
            (DEFAULT, '$titulo', '$genero', '$autor', '$editora', $ano, $edicao, DEFAULT, '$obs');";

            // echo $sql;            

            $res = mysqli_query($conn, $sql);
            
            $titulo = utf8_encode($titulo);
            if(mysqli_affected_rows($conn) > 0){
                // echo 'a';
                echo '<script>
                alert("Livro \"'.$titulo.'\" inserido com sucesso!");
                </script>';
            } 
            else {
                // echo 'b';
                $erro = mysqli_error($conn);
                echo '<script>
                alert("Falha ao inserir livro \"'.$titulo.'\"!\nMais detalhes: '.$erro.'");
                </script>';
            }

            mysqli_close($conn);
            // echo "<script>
            // window.location.href = 'prg.php?url=cadliv.php';
            // </script>";
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
        <h3>Cadastro de <a href="main.php?sel=l" class="a">Livro</a></h3>
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

            <label for="titulo">Título</label><br>
            <input type="text" name="titulo" id="titulo" required autofocus>
            <br><br>
            <label for="genero">Gênero</label><br>
            <select name="genero" id="genero" required>
                <option value="" selected disabled>-- Selecione uma opção --</option>
                <option value="Literatura Estrangeira">Literatura Estrangeira</option>
                <option value="Literatura Brasileira">Literatura Brasileira</option>
                <option value="História em Quadrinhos">História em Quadrinhos</option>
                <option value="Literatura de Ciências Naturais">Literatura de Ciências Naturais</option>
                <option value="Literatura de Ciências Sociais">Literatura de Ciências Sociais</option>
                <option value="Literatura Didática">Literatura Didática</option>
            </select>
            <br><br>
            <label for="autor">Autor</label><br>
            <input type="text" name="autor" id="autor" required>
            <br><br>
            <label for="editora">Editora</label><br>
            <input type="text" name="editora" id="editora" required>
            <br><br>
            <label for="ano">Ano</label><br>
            <input type="number" name="ano" id="ano" required min="-12000" max="<?php echo date('Y'); ?>">
            <br><br>
            <label for="edicao">Edição</label><br>
            <input type="number" name="edicao" id="edicao" required min="1" max="500">
            <br><br>
            <label for="obs">Obs.</label><br>
            <textarea type="text" name="obs" id="obs" class="resize_v"></textarea>
            <br><br>
            <button type="submit" name="subCadLivro" class="cadBtn">Salvar</button>
            <button type="reset" class="cadBtn reset">Limpar</button>
        </form>
    </div>
    
</body>

<script src="../js/main.js"></script>
</html>