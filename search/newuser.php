<?php
    session_start();

    $search = "";
    
    if(isset($_GET['s']))
    {
        $search = $_GET['s'];
    }

    if(isset($_POST['subCadUser'])) {
        $id_user = 0;
        $success = false;
        try 
        {
            include "../config/php/connect.php";
            include "../config/php/util.php";

            $nome = (mysqli_real_escape_string($conn, $_POST['nome']));
            $ra  = (isset($_POST['turma'])) ? "'".(mysqli_real_escape_string($conn, $_POST['ra']))."'" : "";
            $newlogin = (mysqli_real_escape_string($conn, $_POST['login']));
            $newsenha = getStdPass();
            $newsenha = md5($newsenha);
            $tipo = (mysqli_real_escape_string($conn, $_POST['tipo']));
            $turma = (isset($_POST['turma'])) ? (mysqli_real_escape_string($conn, $_POST['turma'])) : "";
            $telefone = (mysqli_real_escape_string($conn, $_POST['telefone']));
            $email = (mysqli_real_escape_string($conn, $_POST['email']));
            $admin = "false";
            
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
                    echo "<script>
                    window.location.href = 'finalizar.php?s=$search';
                    </script>";

                    
                    $id_sql = "SELECT LAST_INSERT_ID()";

                    $res = mysqli_query($conn, $id_sql);

                    $row = mysqli_fetch_array($res, MYSQLI_NUM);
        
                    $id_user = $row[0];

                    $success = true;
                } 
                else {
                    $erro = mysqli_error($conn);
                    echo '<script>
                    alert("Falha ao inserir usuário \"'.$nome.'\"!\nMais detalhes: '.$erro.'");
                    </script>';
                }
            }
            
            if($success)
            {
                $append = "Para finalizar empréstimo, foi criado o usuário id $id_user.<br>";
                $file = '../admin/log.html';
                date_default_timezone_set("America/Sao_Paulo");

                $append = '['.date('d/m/Y H:i:s').'] '.$append;
                
                if(file_get_contents($file) != '')
                    $append = file_get_contents($file).$append;

                file_put_contents($file, $append);
            }

            mysqli_close($conn);
            echo "<script>
            window.location.href = 'finalizar.php?s=$search';
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
    <link rel="stylesheet" href="../css/final.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>
<a href="finalizar.php?s=<?php echo $search; ?>" class="a voltaInicio">Voltar a Finalizar</a>
    <div class="textcenter">
        <h4><a href=".." style="text-decoration:none">Apolo</a></h4>
        <h2>Adicionar usuário</h2>
    </div>
    <hr>
    
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
            <input type="text" name="nome" required maxlenght="70">
            <br><br>
            <label for="ra" id="lblRa">RA</label><br>
            <input type="number" name="ra" id="ra" maxlenght="7" min="1000000">
            <br><br>
            <label for="login">Login</label><br>
            <input type="text" name="login" id="login" required maxlenght="20">
            <br><br>
            <label for="ano" id="lblAno">Turma</label><br>
            <input type="text" name="turma" id="ano" maxlenght="3" placeholder="Ex.: 73A">
            <br><br>
            <label for="telefone">Telefone</label><br>
            <input type="text" name="telefone" maxlenght="15" placeholder="Ex.: 14987654321" required>
            <br><br>
            <label for="email">Email</label><br>
            <input type="email" name="email" maxlenght="255">
            <br><br>
            <button type="submit" name="subCadUser" class="cadBtn">Salvar</button>
            <button type="reset" class="cadBtn reset">Limpar</button>
        </form>
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