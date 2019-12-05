<?php
    include 'head.php';
    include 'login.php';

    if(isset($_POST['subCadCxa'])) {
        $id = 0;
        $success = false;
        try 
        {
            include "../config/php/connect.php";

            $valor = utf8_decode(mysqli_real_escape_string($conn, $_POST['valor']));
            $descricao = utf8_decode(mysqli_real_escape_string($conn, $_POST['descricao']));
            $tipo = utf8_decode(mysqli_real_escape_string($conn, $_POST['tipo']));
            $data = utf8_decode(mysqli_real_escape_string($conn, $_POST['data']));

            if($tipo == 's')
            {
                if($valor > 0)
                    $valor = (-1) * $valor;
            }
            else
            {
                if($valor < 0)
                    $valor = (-1) * $valor;
            }
            
            $sql = "INSERT INTO caixa (id, valor, descricao, tipo, data, excluido) 
            VALUES (DEFAULT, '$valor', '$descricao', '$tipo', '$data', DEFAULT)";

            $res = mysqli_query($conn, $sql);
            
            if(mysqli_affected_rows($conn) > 0){
                echo '<script>
                alert("Registro de caixa inserido com sucesso!");
                </script>';

                
                $id_sql = "SELECT LAST_INSERT_ID()";

                $res = mysqli_query($conn, $id_sql);

                $row = mysqli_fetch_array($res, MYSQLI_NUM);
    
                $id = $row[0];

                $success = true;
            } 
            else {
                $erro = mysqli_error($conn);
                echo '<script>
                alert("Falha ao inserir registro!\nMais detalhes: '.$erro.'");
                </script>';
            }
            
            if($success)
            {
                $append = "Usuário \"$login\" inseriu registro de caixa id $id.<br>";
                $file = 'log.html';
                date_default_timezone_set("America/Sao_Paulo");

                $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
                
                if(file_get_contents($file) != '')
                    $append = file_get_contents($file).$append;

                file_put_contents($file, $append);
            }

            mysqli_close($conn);
            echo "<script>
            window.location.href = 'prg.php?url=cadcxa.php';
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
    <a href="main.php?sel=c" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h3>Novo <a href="main.php?sel=c" class="a">registro de caixa</a></h3>
    </div>
    
    <div class="cadastro">
        <form action="" method="post" class="cadastroFrm">
            <label for="tipo">Tipo</label><br>
            <select name="tipo" id="tipo" required autofocus>
                <option value="" disabled selected>-- Selecione uma opção --</option>
                <option value="e">Entrada</option>
                <option value="s">Saída</option>
            </select>
            <br><br>
            <label for="valor" id="lblvalor">Valor</label><br>
            <input type="number" name="valor" id="valor" maxlenght="6" step="0.01" required>
            <br><br>
            <label for="descricao">Descrição</label><br>
            <textarea name="descricao" id="descricao" rows="2" required></textarea>
            <br><br>
            <label for="data" id="lbldata">Data</label><br>
            <input type="date" name="data" id="data" value="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d');?>">
            <br><br>
            <button type="submit" name="subCadCxa" class="cadBtn">Salvar</button>
            <button type="reset" class="cadBtn reset">Limpar</button>
        </form>
    </div>
    
</body>

<script src="../js/main.js"></script>
</html>