<?php
    include 'head.php';
    include 'login.php';
    
    $id_existe = 0;
    $titulo = '';
    $genero = '';
    $autor = '';
    $editora = '';            
    $ano = 0;
    $edicao = 0;
    $qtde = 0;
    $obs = '';

    if(isset($_POST['subCadLivro'])) {
        try 
        {
            include "../config/php/connect.php";
            include "../config/php/util.php";


            $a = substr($titulo, 0, 2);
            $as = substr($titulo, 0, 3);

            if(strtolower($as) == 'as ' || strtolower($as) == 'os ')
                $titulo = substr($titulo, 3).", $as";
            else if(strtolower($a) == 'a ' || strtolower($a) == 'o ')
                $titulo = substr($titulo, 2).", $a";

            $titulo = (mysqli_real_escape_string($conn, $_POST['titulo']));
            $abrev = (mysqli_real_escape_string($conn, $_POST['genero']));
            $genero = getGenero($abrev);
            $autor = (mysqli_real_escape_string($conn, $_POST['autor']));
            $editora = (mysqli_real_escape_string($conn, $_POST['editora']));            
            $ano = (mysqli_real_escape_string($conn, $_POST['ano']));
            $edicao = (mysqli_real_escape_string($conn, $_POST['edicao']));
            $qtde = (mysqli_real_escape_string($conn, $_POST['qtde']));
            $obs = (mysqli_real_escape_string($conn, $_POST['obs']));

            if($qtde <= 0) $disp = false;
            else $disp = true;

            $sql = "INSERT INTO livro VALUES
            (DEFAULT, '$abrev', '$titulo', '$genero', '$autor', '$editora', $ano, $edicao, $disp, $qtde, '$obs', DEFAULT);";

            // echo $sql;            

            $res = mysqli_query($conn, $sql);
            
            $titulo = ($titulo);
            if(mysqli_affected_rows($conn) > 0){
                // echo 'a';
                $titulo = ($titulo);
                echo '<script>
                alert("Livro \"'.$titulo.'\" inserido com sucesso!");
                </script>';

                $sql = "SELECT LAST_INSERT_ID()";

                $res = mysqli_query($conn, $sql);

                $row = mysqli_fetch_array($res, MYSQLI_NUM);
    
                $id = $row[0];

                $codigo = getCodigo($abrev, $id);

                $sql = "UPDATE livro SET codigo = '$codigo' WHERE id_livro = $id";

                $res = mysqli_query($conn, $sql);
            } 
            else {
                // echo 'b';
                $erro = mysqli_error($conn);
                echo '<script>
                alert("Falha ao inserir livro \"'.$titulo.'\"!\nMais detalhes: '.$erro.'");
                </script>';
            }

            mysqli_close($conn);
            echo "<script>
            // window.location.href = 'prg.php?url=cadliv.php';
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
    <a href="main.php?sel=l" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h3>Cadastro de <a href="main.php?sel=l" class="a">Livro</a></h3>
    </div>
    
    <div class="cadastro">
        <form action="" method="post" class="cadastroFrm">
            <label for="titulo">Título</label><br>
            <input type="text" name="titulo" id="titulo" required autofocus>
            <br><br>
            <label for="genero">Gênero</label><br>
            <select name="genero" id="genero" required>
                <option value="" selected disabled>-- Selecione uma opção --</option>
                <option value="LB">Literatura Brasileira</option>
                <option value="LE">Literatura Estrangeira</option>
                <option value="HQ">História em Quadrinhos</option>
                <option value="CN">Ciências Naturais</option>
                <option value="CS">Ciências Sociais</option>
                <option value="LD">Literatura Didática</option>
            </select>
            <br><br>
            <label for="autor">Autor(es)</label><br>
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
            <label for="qtde">Quantidade</label><br>
            <input type="number" name="qtde" id="qtde" required min="0" max="100">
            <br><br>
            <label for="obs">Observação</label><br>
            <textarea type="text" name="obs" id="obs" class="resize_v"></textarea>
            <br><br>
            <button type="submit" name="subCadLivro" class="cadBtn">Salvar</button>
            <button type="reset" class="cadBtn reset">Limpar</button>
        </form>
    </div>
    
</body>

<script src="../js/main.js"></script>
</html>