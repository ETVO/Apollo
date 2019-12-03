<?php
    $edit = false;
    $bloq = false;
    
    include 'head.php';
    include 'login.php';

    $login = $_SESSION['login'];

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        try {
            include "../config/php/connect.php";

            $sql = "SELECT id_user, nome, ra, login, senha, turma, tipo, telefone, email, bloqueado, admin
            FROM user WHERE id_user=$id";
            
            $res = mysqli_query($conn, $sql);
            
            if(mysqli_affected_rows($conn) > 0)
            {
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                
                $nome = utf8_encode($row['nome']);
                $ra = utf8_encode($row['ra']);
                $vislogin = utf8_encode($row['login']);
                $senha = utf8_encode($row['senha']);
                $tipo = utf8_encode($row['tipo']);
                $turma = utf8_encode($row['turma']);
                $telefone = utf8_encode($row['telefone']);
                $email = utf8_encode($row['email']);
                $bloq = utf8_encode($row['bloqueado']);
                $admin = utf8_encode($row['admin']);
            }
            else {
                // header("Location: main.php");
            }

        } catch (Exception $e) {

        }
    }

    $self = $login == $vislogin; 

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


            $nome = utf8_decode(mysqli_real_escape_string($conn, $_POST['nome']));
            $newra  = (isset($_POST['turma'])) ? "'".utf8_decode(mysqli_real_escape_string($conn, $_POST['ra']))."'" : "null";
            $newlogin = utf8_decode(mysqli_real_escape_string($conn, $_POST['login']));
            $oldlogin = utf8_decode(mysqli_real_escape_string($conn, $_POST['login_atual']));
            $newsenha = (isset($_POST['senha'])) ? utf8_decode(mysqli_real_escape_string($conn, $_POST['senha'])) : "null";
            $newsenha = md5($newsenha);
            $tipo = utf8_decode(mysqli_real_escape_string($conn, $_POST['tipo']));
            $turma = utf8_decode(mysqli_real_escape_string($conn, $_POST['turma']));
            $telefone = utf8_decode(mysqli_real_escape_string($conn, $_POST['telefone']));
            $email = utf8_decode(mysqli_real_escape_string($conn, $_POST['email']));
            $admin = (isset($_POST['admin'])) ? utf8_decode(mysqli_real_escape_string($conn, $_POST['admin'])) : "false";
            
            $samelogin = $newlogin == $oldlogin;
            
            $sql = "SELECT * FROM user WHER login = '$newlogin'";
            $res = mysqli_query($conn, $sql);
            if(mysqli_affected_rows($conn) > 0 && !$samelogin){
                echo '<script>
                    alert("O login '.$newlogin.' já está sendo utilizado.");
                    </script>';
            }
            else
            {
                $strnewsenha = (isset($_POST['senha'])) ? " senha = '$newsenha'," : "";
                $sql = "UPDATE user SET
                nome = '$nome', ra = $newra,
                login = '$newlogin', $strnewsenha
                turma = '$turma', tipo = '$tipo', telefone = '$telefone', email = '$email', admin = $admin
                WHERE id_user=$id;";
                
                $res = mysqli_query($conn, $sql);
                
                if(mysqli_affected_rows($conn) > 0){
                    // echo 'a';
                    echo '<script>
                    alert("Registro do administrador \"'.$nome.'\" alterado com sucesso!");
                    </script>';

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
            if($success)
            {
                $append = "Usuário \"$login\" alterou o registro do administrador id $id.<br>";
                if($self)
                    $append = "Usuário id \"$id\" alterou o seu próprio registro, utilizando agora o login $newlogin.<br>";
                $file = 'log.html';
                date_default_timezone_set("America/Sao_Paulo");

                $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
                
                if(file_get_contents($file) != '')
                    $append = file_get_contents($file).$append;

                file_put_contents($file, $append);
            }
            
            mysqli_close($conn);
            if($self)
            {
                echo "<script>
                    alert('Você alterou seu próprio registro, $newlogin. Por isso, você será redirecionado novamente à página de login.');
                    window.location.href = 'prg.php?url=index.php';
                </script>";
            }
            else
            {
                echo "<script>
                   window.location.href = 'prg.php?url=visusu.php?id=$id';
                </script>";
            }
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

    if(isset($_GET['bloq']))
    {
        if($count > 1)
        {
            $bloq = $_GET['bloq'];
            $success = false;
            try {
                include "../config/php/connect.php";

                if($vislogin == $login)
                {
                    echo "<script>
                    alert('Você não pode bloquear seu próprio usuário, $login.');
                    </script>";
                }
                else
                {
                    if($bloq == 0) $nbloq = 1; else $nbloq = 0;

                    $sql = "UPDATE user SET bloqueado = $nbloq WHERE id_user = $id";

                    $res = mysqli_query($conn, $sql);
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
    <a href="main.php?sel=u" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h3>Visualizar <a href="main.php?sel=u" class="a">Usuário</a></h3>
    </div>
    
    <div class="visualizar">
        <form action="" method="post" class="visualizarFrm" <?php if($edit) echo 'style="display:block"'; ?>>
            <label for="nome">Nome</label><br>
            <input type="text" name="nome" id="nome" required autofocus maxlenght="70" value="<?php echo $nome; ?>">
            <br><br>
            <label for="ra" id="lblRa">RA</label><br>
            <input type="number" name="ra" id="ra" maxlenght="7" min="1000000" max="9999999" value="<?php echo $ra; ?>">
            <br><br>
            <label for="login">Login</label><br>
            <input type="text" name="login" id="login" required maxlenght="20" value="<?php echo $vislogin; ?>">
            <br><br>
            <div <?php if($login != 'admin') echo 'style="display:none"'; ?>>
                <label for="senha">Senha</label><br>
                <input type="password" name="senha" id="senha" maxlenght="16" <?php if($login != 'admin') echo 'disabled'; ?>>
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
            <label for="turma" id="lblAno">Turma</label><br>
            <input type="text" name="turma" id="turma" maxlenght="6" value="<?php echo $turma; ?>">
            <br><br>
            <label for="telefone">Telefone</label><br>
            <input type="text" name="telefone" id="telefone" maxlenght="15" placeholder="Ex.: 14987654321" value="<?php echo $telefone; ?>">
            <br><br>
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" maxlenght="255" required value="<?php echo $email; ?>">
            <br><br>
            <input type="checkbox" name="admin" id="admin" value="true" <?php echo ($admin) ? "checked" : ""; ?>>
            <label for="admin">Administrador?</label>
            <br><br>
            <input type="hidden" name="senha_atual" value="<?php echo ($self) ? $senha : $vissenha; ?>">
            <input type="hidden" name="login_atual" value="<?php echo ($self) ? $login : $vislogin; ?>">
            <input type="hidden" name="ra_atual" value="<?php echo $ra; ?>">
            <input type="hidden" name="self_edit" value="<?php echo $self; ?>">
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
                <h3><?php echo $vislogin; ?></h3>
            </div>    
                
            <div class="visualizarInfo">
                <label for="" title="Só pode ser alterada com o administrador padrão ('admin').">Senha</label>
                <h3><?php echo '*******' ?></h3>
            </div>  
            
            <div class="visualizarInfo">
                <label for="">Tipo</label>
                <h3><?php echo $tipo; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Turma</label>
                <h3><?php if($turma != null) echo $turma; else echo '-'; ?></h3>
            </div>   

            <div class="visualizarInfo">
                <label for="">Telefone</label>
                <h3><?php echo ($telefone != "") ? $telefone : "-"; ?></h3>
            </div>   

            <div class="visualizarInfo">
                <label for="">Email</label>
                <h3><?php echo $email; ?></h3>
            </div>   

            <div class="cad_2grid">
                <div class="visualizarInfo">
                    <label for="">Bloqueado?</label>
                    <h3><?php if($bloq) echo 'Sim'; else echo 'Não';?></h3>
                </div>    
                
                <div class="visualizarInfo">
                    <label for="">Administrador?</label>
                    <h3><?php if($admin) echo 'Sim'; else echo 'Não';?></h3>
                </div> 
            </div>   

            <div class="visualizarOptions">
                <button onclick="<?php echo "window.location.href = '?id=$id&edit=true';" ?>" class="btnEditar" <?php echo ($bloq) ? 'disabled' : ''; ?>>
                    Editar
                </button>

                <button onclick="<?php echo "window.location.href = '?id=$id&bloq=$bloq';" ?>" class="btnExcluir">
                    <?php echo ($bloq) ? 'Desbloquear' : 'Bloquear';?>
                </button>
            </div>
        </div>
    </div>
    
</body>

<script src="../js/main.js">
</script>
</html>