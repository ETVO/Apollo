<?php
    session_start();

    $login = $_SESSION['login'];
    $senha = $_SESSION['senha'];
    $nome = '';

    $newsenha = '';

    $edit = false;
    $exc = false;
    
    if(!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
        session_destroy();
        echo "a";
        exit;
        header("Location: index.php");
        
    }
    else if($login == 'root' && $senha == '632f4902f2afb597923c18ea897eefa7'){
    }
    else {
        try 
        {
            include "../config/php/connect.php";

            $sql = "SELECT nome FROM user WHERE login = '$login' AND bloqueado = 0 AND bloqueado = FALSE";

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

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        try {
            include "../config/php/connect.php";

            $sql = "SELECT id_user, nome, ra, login, senha, tipo, ano, bloqueado, telefone
            FROM user WHERE id_user=$id";
            
            $res = mysqli_query($conn, $sql);
            
            if(mysqli_affected_rows($conn) > 0)
            {
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                
                $nome = utf8_encode($row['nome']);
                $ra = utf8_encode($row['ra']);
                $newlogin = utf8_encode($row['login']);
                $newsenha = utf8_encode($row['senha']);
                $tipo = utf8_encode($row['tipo']);
                $ano = utf8_encode($row['ano']);
                $bloq = utf8_encode($row['bloqueado']);
                $telefone = utf8_encode($row['telefone']);
            }
            else {
                header("Location: main.php");
            }

        } catch (Exception $e) {

        }
    }

    if(isset($_GET['edit']))
    {
        $edit = $_GET['edit'];

        if($edit == 'true')
            $edit = true;
        else 
            $edit = false;
    }

    if(isset($_POST['cancelEdit']))
    {
        header("Location: ?id=$id");
    }

    if(isset($_POST['subUpUser']))
    {
        $success = false;
        $bloq = false;
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
            $newra  = "null";
            if(isset($_POST['ano'])){
                $newra = "'".utf8_decode(mysqli_real_escape_string($conn, $_POST['ra']))."'";
            }
            $newlogin = utf8_decode(mysqli_real_escape_string($conn, $_POST['login']));
            
            if($newlogin != 'root'){

                if(isset($_POST['senha']))
                {
                    $newsenha = utf8_decode(mysqli_real_escape_string($conn, $_POST['senha']));
                    $newsenha = md5($newsenha);
                }
                $tipo = utf8_decode(mysqli_real_escape_string($conn, $_POST['tipo']));
                $ano = "null";
                if(isset($_POST['ano'])){
                    $ano = utf8_decode(mysqli_real_escape_string($conn, $_POST['ano']));
                }
                $telefone = utf8_decode(mysqli_real_escape_string($conn, $_POST['telefone']));

                if(!isset($_POST['bloq']))
                    $bloq = '0';
                else
                    $bloq = '1';

                if($tipo != 'Aluno'){
                    $newra = 'null';
                    $ano = 'null';
                }
                
                
                $sql = "UPDATE user SET
                nome = '$nome', ra = $newra,
                login = '$newlogin', senha = '$newsenha',
                ano = $ano, tipo = '$tipo', telefone = '$telefone',
                bloqueado = $bloq
                WHERE id_user=$id;";

                echo $sql;
                
                $res = mysqli_query($conn, $sql);
                
                $nome = utf8_encode($nome);
                if(mysqli_affected_rows($conn) > 0){
                    // echo 'a';
                    echo '<script>
                    alert("Registro do administrador \"'.$nome.'\" alterado com sucesso!");
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
                    alert("Falha ao alterar o registro do administrador \"'.$nome.'\"!\nMais detalhes: '.$erro.'");
                    </script>';
                }
            }   
            else {
                echo '<script>
                    alert("Você não pode utilizar este login.");
                    </script>';
            }
            
            mysqli_close($conn);
            if($success)
            {
                $append = "Usuário \"$login\" alterou o registro do administrador id $id.<br>";
                $file = 'log.html';
                date_default_timezone_set("America/Sao_Paulo");

                $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
                
                if(file_get_contents($file) != '')
                    $append = file_get_contents($file).$append;

                file_put_contents($file, $append);
            }
            header("Location: ?id=$id");
        } catch (Exception $e){

        }
    }

    $count = 0;

    try {
        include "../config/php/connect.php";

        $sql = 'SELECT COUNT(1) FROM user;';

        if($res = mysqli_query($conn, $sql)) 
        {
            $row = mysqli_fetch_array($res, MYSQLI_NUM);

            $count = $row[0];
        }
    } catch (Exception $e) {

    }

    if(isset($_GET['exc']))
    {
        if($count > 1)
        {
            $exc = $_GET['exc'];
            $success = false;
            try {
                include "../config/php/connect.php";

                if($bloq == 1) $newbloq = 0; else $newbloq = 1;

                $sql = "UPDATE user SET bloqueado = $newbloq WHERE id_user = $id";

                $res = mysqli_query($conn, $sql);

                if($newbloq == 1) $popup = "bloqueado"; else $popup = "desbloqueado";
                
                if(mysqli_affected_rows($conn) > 0){
                    echo "<script>
                    alert('Administrador $popup com sucesso!');
                    </script>";

                    $success = true;
                } 
                else {
                }
            } catch(Exception $e) {

            }
            if($success)
            {
                if($bloq) $word = "desbloqueou"; else $word = "bloqueou";
                $append = "Usuário \"$login\" $word o administrador id $id.<br>";
                $file = 'log.html';
                date_default_timezone_set("America/Sao_Paulo");

                $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
                
                if(file_get_contents($file) != '')
                    $append = file_get_contents($file).$append;

                file_put_contents($file, $append);
            }
            header("Location: ?id=$id");  
        }
        else
        {
            header("Location: ?id=$id");  
        }

    }

    $popup = ($bloq) ? "desbloquear" : "bloquear";
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
    <link rel="stylesheet" href="../css/vis.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>    
    <a href="" onclick="window.close();" class="a voltaInicio">Fechar</a><br>
    <a href="main.php?sel=a" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h3>Visualizar <a href="main.php?sel=a" class="a">Usuário</a></h3>
    </div>
    
    <div class="visualizar">
        <form action="" method="post" class="visualizarFrm" <?php if($edit) echo 'style="display:block"'; ?>>
            <label for="nome">Nome</label><br>
            <input type="text" name="nome" id="nome" required autofocus maxlenght="70" value="<?php echo $nome; ?>">
            <br><br>
            <label for="ra" id="lblRa">RA</label><br>
            <input type="number" name="ra" id="ra" maxlenght="7" min="1000000" value="<?php echo $ra; ?>">
            <br><br>
            <label for="login">Login</label><br>
            <input type="text" name="login" id="login" required maxlenght="20" value="<?php echo $newlogin; ?>">
            <br><br>
            <div <?php if($login != 'root') echo 'style="display:none"'; ?>>
                <label for="senha">Senha</label><br>
                <input type="password" name="senha" id="senha" maxlenght="16" <?php if($login != 'root') echo 'disabled'; ?>>
                <br><br>
            </div>
            <label for="tipo">Tipo</label><br>
            <select name="tipo" id="tipo" required onchange="verAluno()">
                <option value="" disabled selected>-- Selecione uma opção --</option>
                <option value="Aluno" <?php if($tipo == 'Aluno') echo 'selected'; ?>>Aluno</option>
                <option value="Professor" <?php if($tipo == 'Professor') echo 'selected'; ?>>Professor</option>
                <option value="Funcionário" <?php if($tipo == 'Funcionário') echo 'selected'; ?>>Funcionário</option>
            </select>
            <br><br>
            <label for="ano" id="lblAno">Série</label><br>
            <input type="number" name="ano" id="ano" maxlenght="1" value="<?php echo $ano; ?>">
            <br><br>
            <label for="telefone">Telefone</label><br>
            <input type="text" name="telefone" id="telefone" maxlenght="15" placeholder="Ex.: 14987654321" value="<?php echo $telefone; ?>">
            <br><br>
            <div <?php if($count == 1) echo 'style="display:none"'; ?>>
                <label for="telefone">Bloqueado</label>
                <input type="checkbox" name="bloq" id="bloq" <?php if($bloq) echo 'checked'; ?>>
                <br><br>
            </div>
            <input type="hidden" name="newsenha" value="<?php echo $newsenha; ?>">
            <button type="submit" name="subUpUser" class="cadBtn">Salvar</button>
            <button type="submit" name="cancelEdit" class="cadBtn reset" formnovalidate>Cancelar Edição</button>
        </form>

        <div class="visualizarContent" <?php if($edit) echo 'style="display:none"'; ?>>    

            <div class="visualizarInfo">
                <label for="">Nome</label>
                <h3><?php echo $nome; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">RA</label>
                <h3><?php  if($ra != null) echo $ra; else echo "-";  ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Login</label>
                <h3><?php echo $newlogin; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="" title="Só pode ser alterada com o administrador padrão (root).">Senha</label>
                <h3><?php echo '*******' ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Tipo</label>
                <h3><?php echo $tipo; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Série</label>
                <h3><?php if($ano != null) echo $ano."º"; else echo '-'; ?></h3>
            </div>   

            <div class="visualizarInfo">
                <label for="">Telefone</label>
                <h3><?php echo $telefone; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Bloqueado</label>
                <h3><?php if($bloq) echo 'Sim'; else echo 'Não';?></h3>
            </div>   

            <div class="visualizarOptions">
                <button onclick="<?php echo "window.location.href = '?id=$id&edit=true';" ?>" class="btnEditar">
                    Editar
                </button>

                <button onclick="<?php 
                echo "swal({
                    title: 'Atenção!',
                    text:'Deseja realmente $popup o administrador $nome?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) =>{
                    if(willDelete){
                        window.location.href = '?id=$id&exc=true';
                    }
                });"; ?>" class="btnExcluir" <?php if($count == 1) echo 'disabled title="Você não pode bloquear este administrador, pois ele é o único cadastrado!"'; ?>>
                    <?php echo ($bloq) ? "Desbloquear" : "Bloquear"; ?>
                </button>
            </div>
        </div>
    </div>
    
</body>

<script src="../js/main.js">
</script>
</html>