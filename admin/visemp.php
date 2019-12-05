<?php
    
    
    include 'head.php';
    include 'login.php';

    if(isset($_GET['id'])){
        $id_emp = $_GET['id'];

        try {
            include "../config/php/connect.php";
            include "../config/php/util.php";

            $sql = "SELECT l.id_livro AS id_livro, 
                            l.codigo AS codigo, 
                            l.titulo AS titulo, 
                            u.nome AS usuario, 
                            u.email AS email, 
                            u.telefone AS telefone,
                            u.turma AS turma, 
                            a.nome AS admin, 
                            data_emp, 
                            data_prev_dev, 
                            devolvido, 
                            data_dev,
                            e.excluido,
                            e.obs
                FROM emprestimo AS e 
                    INNER JOIN livro AS l ON e.id_livro = l.id_livro 
                    INNER JOIN user AS a ON e.id_admin = a.id_user
                    INNER JOIN user AS u ON e.id_user = u.id_user WHERE id_emprestimo = $id_emp";

            $res = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0)
            {
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

                $id_livro = $row['id_livro'];
                $codigo = utf8_encode($row['codigo']);
                $livro = utf8_encode($row['titulo']);
                $usuario = utf8_encode($row['usuario']);
                $email = utf8_encode($row['email']);
                $telefone = utf8_encode($row['telefone']);
                $turma = utf8_encode($row['turma']);
                $admin = utf8_encode($row['admin']);
                $data_emp = utf8_encode($row['data_emp']);
                $data_prev_dev = utf8_encode($row['data_prev_dev']);
                $devolvido = utf8_encode($row['devolvido']);
                $data_dev = utf8_encode($row['data_dev']);
                $exc = utf8_encode($row['excluido']);
                $obs = utf8_encode($row['obs']);
                
                $atrasado = false;
                $d_atraso = false;

                if(strtotime(date('Y-m-d')) > strtotime($data_prev_dev) && !$devolvido) 
                {
                    $status = 'a';
                    $dias = getWorkdays($data_prev_dev, date('Y-m-d'), false, null);
                }
                else if($devolvido){
                    if(strtotime($data_dev) > strtotime($data_prev_dev))
                        $d_atraso = true;
                    $status = 'd'; 
                }
                else
                    $status = 'e';
            }
            else {
            }

        } catch (Exception $e) {

        }
    }

    if($status == 'a'){
        $val_multa = calculaMulta($dias);
        $multa = $val_multa;
        if($multa > 1) $multa .= ' reais';
        else $multa .= ' real';
        $text = "A multa de $multa será paga?"; 
        $opt = 'multa';
        $_SESSION['multa'] = $multa;
    } 
    else if($status == 'e'){
        $multa = 0; 
        $text = "Deseja realmente renovar este empréstimo?";
        $opt = 'renov';
        $_SESSION['multa'] = $multa;
    }
    else{
        $multa = 0; 
        $text = "Deseja realmente excluir este empréstimo?";
        $opt = 'exc';
        $_SESSION['multa'] = $multa;
    }

    if($exc) 
        $text = "Deseja realmente restaurar este empréstimo?";

    $form = false;

    if(isset($_GET['jus']))
    {
        $jus = $_GET['jus'];
        if($jus)
        {
            if($status = 'a')
            {
                $form = true;
                $name = 'just';
                $label = 'Justificativa do atraso';
            }
            else {
                header("Location: ?id=$id_emp");
            }
        }
        else
            header("Location: ?id=$id_emp");
    }

    if(isset($_GET['dev']))
    {
        if($status == 'e')
        {
            $form = true;
            $name = 'dev';
            $label = 'Observação sobre o empréstimo';
        }
    }

    if(isset($_POST['cancelForm']))
    {
        header("Location: ?id=$id_emp");
    }

    function updateLivro($id_livro) {
        try {
            include '../config/php/connect.php';

            $sql = "SELECT qtde FROM livro WHERE id_livro = $id_livro";
            
            $res = mysqli_query($conn, $sql);

            $row = mysqli_fetch_array($res, MYSQLI_NUM);

            $qtde = $row[0];

            $newqtde = $qtde + 1;

            $sql = "UPDATE livro SET
            qtde = $newqtde, disponivel = 1 WHERE id_livro = $id_livro";

            $res = mysqli_query($conn, $sql);

            mysqli_close($conn);
        } catch (Exception $e) {

        }
    }
    
    if(isset($_POST['subjust']))
    {
        try {
            include '../config/php/connect.php';

            $justificativa = utf8_decode("Justificativa de atraso:".mysqli_real_escape_string($conn, $_POST['just']));

            $data_dev = date('Y-m-d');

            $sql = "UPDATE emprestimo SET
            obs = '$justificativa',
            devolvido = 1,
            data_dev = '$data_dev'
            WHERE id_emprestimo=$id_emp";
            
            $res = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0)
            {   

                updateLivro($id_livro);
                echo '<script>
                    alert("Atraso justificado com sucesso!");
                    </script>';
                $success = true;
            }
            else {
                $error = mysqli_error($conn);
                echo '<script>
                    alert("Algo deu errado!\nMais detalhes:'.$error.'");
                    
                    </script>';
            }



            mysqli_close($conn);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if($success)
        {
            $append = "Usuário \"$login\" justificou o empréstimo id $id_emp.<br>";
            $file = 'log.html';
            date_default_timezone_set("America/Sao_Paulo");

            $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
            
            if(file_get_contents($file) != '')
                $append = file_get_contents($file).$append;

            file_put_contents($file, $append);
        }

        echo '<script>window.location.href="main.php?sel=e"</script>';
    }

    if(isset($_POST['subdev']))
    {
        try {
            include '../config/php/connect.php';

            $dev = utf8_decode(mysqli_real_escape_string($conn, $_POST['dev']));

            $data_dev = date('Y-m-d');

            $sql = "UPDATE emprestimo SET
            obs = '$dev',
            devolvido = 1,
            data_dev = '$data_dev'
            WHERE id_emprestimo=$id_emp";
            
            if($res = mysqli_query($conn, $sql))
            {   
                updateLivro($id_livro);
                echo '<script>
                    alert("Livro devolvido com sucesso!");
                    
                    </script>';
                $success = true;
            }
            else {
                $error = mysqli_error($conn);
                echo '<script>
                    alert("Algo deu errado!\nMais detalhes:'.$error.'");
                    
                    </script>';
            }



            mysqli_close($conn);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if($success)
        {
            $append = "Usuário \"$login\" devolveu o empréstimo id $id_emp.<br>";
            $file = 'log.html';
            date_default_timezone_set("America/Sao_Paulo");

            $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
            
            if(file_get_contents($file) != '')
                $append = file_get_contents($file).$append;

            file_put_contents($file, $append);
        }

        echo '<script>window.location.href="main.php?sel=e"</script>';
    }

    if(isset($_GET['renov']) && $status == 'e')
    {
        try {
            $prazo = getPrazo();
        
            $data_dev = addDays(strtotime(date('Y-m-d')), $prazo);
            
            $data_dev = date('Y-m-d', $data_dev);

            if($data_dev == $data_prev_dev)
            {
                $data_dev = date('d/m/Y', strtotime($data_dev));
                if($prazo > 1) $art = 's'; else $art = '';
                echo '<script>
                alert("A devolução já está prevista para o dia '.$data_dev.'!\nO prazo máximo é de '.$prazo.' dia'.$art.'!");
                </script>';
            }
            else
            {
                include '../config/php/connect.php';

                $sql = "UPDATE emprestimo SET
                data_prev_dev = '$data_dev'
                WHERE id_emprestimo=$id_emp";
                
                $res = mysqli_query($conn, $sql);

                if(mysqli_affected_rows($conn) > 0)
                {   
                    $data_dev = date('d/m/Y', strtotime($data_dev));
                    echo '<script>
                    alert("Empréstimo renovado com sucesso para o dia '.$data_dev.'!");
                    
                    </script>';
                    $success = true;
                }
                else {
                    $error = mysqli_error($conn);
                    echo '<script>
                        alert("Algo deu errado!\nMais detalhes:'.$error.'");
                        
                        </script>';
                }
                

                mysqli_close($conn);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if($success)
        {
            $append = "Usuário \"$login\" renovou o empréstimo id $id_emp.<br>";
            $file = 'log.html';
            date_default_timezone_set("America/Sao_Paulo");

            $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
            
            if(file_get_contents($file) != '')
                $append = file_get_contents($file).$append;

            file_put_contents($file, $append);
        }

        echo '<script>window.location.href="?id='.$id_emp.'"</script>';
    }

    if(isset($_GET['multa']) && $status == 'a')
    {
        try {
            include '../config/php/connect.php';

            $obs_multa = "Multa de atraso no valor de $multa foi paga";

            $data_dev = date('Y-m-d');

            $sql = "UPDATE emprestimo SET
            obs = '$obs_multa',
            devolvido = 1,
            data_dev = '$data_dev'
            WHERE id_emprestimo=$id_emp";
            
            $res = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0)
            {   
                updateLivro($id_livro);

                $data_dev = date('d/m/Y', strtotime($data_dev));
                $desc = "Multa de empréstimo atrasado";
                $desc = utf8_decode($desc);
                $sql = "INSERT INTO caixa VALUES (DEFAULT, $val_multa, '$desc', 'e', NOW(), DEFAULT);";

                echo $sql;

                $res = mysqli_query($conn, $sql);

                if(mysqli_affected_rows($conn) > 0)
                {
                    echo '<script>
                    alert("Multa de atraso paga com sucesso!");
                    
                    </script>';

                    $success = true;
                }
                else {    
                    $error = mysqli_error($conn);
                    echo '<script>
                        alert("Algo deu errado!\nMais detalhes:'.$error.'");
                        
                        </script>';
                }
            }
            else {
                $error = mysqli_error($conn);
                echo '<script>
                    alert("Algo deu errado!\nMais detalhes:'.$error.'");
                    </script>';
            }

            mysqli_close($conn);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if($success)
        {
            $append = "Usuário \"$login\" autorizou o pagamento da multa de atraso do empréstimo id $id_emp.<br>";
            $file = 'log.html';
            date_default_timezone_set("America/Sao_Paulo");

            $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
            
            if(file_get_contents($file) != '')
                $append = file_get_contents($file).$append;

            file_put_contents($file, $append);
        }

        // echo '<script>window.location.href="main.php?sel=e"</script>';
    }

    if(isset($_GET['exc']) && $status == 'd')
    {
        $nexc = $_GET['exc'];

        if($nexc == 0) $nexc = 1; else $nexc = 0;

        include '../config/php/connect.php';
        
        $sql = "UPDATE emprestimo SET excluido = $nexc WHERE id_emprestimo = $id_emp";

        $res = mysqli_query($conn, $sql);

        header("Location: ?id=$id_emp");
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
    <link rel="stylesheet" href="../css/vis.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>    
    <a href="main.php?sel=e" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h3>Visualizar <a href="main.php?sel=e" class="a">Empréstimo</a> (<?php if($status == 'a') echo '<b class="status hAtrasado">ATRASADO</b>'; 
                else if($status == 'e') echo '<b class="status hEmDia">EM DIA</b>'; 
                else{ echo '<b class="status hDevolvido"><b>DEVOLVIDO</b>';} ?>)</h3>
    </div>
    
    <div class="visualizar">
        <form action="" method="post" class="visualizarFrm" <?php if($form) echo 'style="display:block"'; ?>>
            <label for="<?php echo $name; ?>"><?php echo $label; ?></label><br>
            <textarea type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" minlength="2" <?php if($name == 'just') echo 'required'; ?> class="resize_v"><?php echo $obs; ?></textarea>
            <br><br>
            <button type="submit" name="sub<?php echo $name; ?>" class="cadBtn" title="Não será possível alterar essa informação!">Devolver</button>
            <button type="submit" name="cancelForm" class="cadBtn reset" formnovalidate>Cancelar</button>
        </form>

        <div class="visualizarContent" <?php if($form) echo 'style="display:none"'; ?>>    

            <!-- <div class="visualizarInfo status">
                <?php if($status == 'a') echo '<h3 class="hAtrasado">ATRASADO</h3>'; 
                else if($status == 'e') echo '<h3 class="hEmDia">EM DIA</h3>'; 
                else{ echo '<h3 class="hDevolvido"><b>DEVOLVIDO</b>'; if($d_atraso) echo ' (COM ATRASO)'; echo '</h3>';} ?>
            </div>  -->

            <div class="visualizarInfo">
                <label for="">Livro</label>
                <h3><?php echo "$codigo ($livro)"; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Emprestado para</label>
                <h3><?php echo $usuario; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Telefone</label>
                <h3><?php echo $telefone; ?></h3>
            </div>
            
            <div class="visualizarInfo">
                <label for="">Email</label>
                <h3><?php echo $email; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Turma</label>
                <h3><?php echo $turma; ?></h3>
            </div>  
            
            <div class="visualizarInfo">
                <label for="">Autorizado por</label>
                <h3><?php echo $admin; ?></h3>
            </div>   
            
            <div class="visDouble">
                <div class="visualizarInfo">
                    <label for="">Emprestado em</label>
                    <h3><?php echo date('d/m/Y', strtotime($data_emp)); ?></h3>
                </div>   
                
                <div class="visualizarInfo">
                    <label for="">Prazo de devolução</label>
                    <h3><?php echo date('d/m/Y', strtotime($data_prev_dev)); ?></h3>
                </div>   
            </div>
            
            <div class="visualizarInfo">
                <label for="">Devolvido</label>
                <h3 class="<?php if($devolvido) echo "green"; ?>"><?php $datadev = date('d/m/Y', strtotime($data_dev)); echo ($devolvido) ? "Sim (em $datadev)" : 'Não'; ?></h3>
            </div>  
            
            <div class="visualizarInfo">
                <label for="">Observação</label>
                <p>
                <?php if($obs != '') echo $obs; else echo "-"; ?>
                </p>
            </div>   

            <div class="visualizarOptions">
                <button <?php if($devolvido) echo 'style="display:none"'; ?> onclick="<?php echo ($status == 'a') ? "window.location.href = '?id=$id_emp&jus=true';" : "window.location.href = '?id=$id_emp&dev=true';"; ?>" class="btnDevolver">
                    <?php echo ($status == 'a') ? 'Justificar' : 'Devolver';  ?>
                </button>

                <button onclick="<?php 
                echo "swal({
                    title: 'Atenção!',
                    text:'$text',
                    icon: 'warning',
                    buttons: true,
                }).then((yes) =>{
                    if(yes){
                        window.location.href = '?id=$id_emp&$opt=$exc';
                    }
                });"; ?>" class="<?php if($status == 'd') echo 'btnExcluir'; else echo 'btnRenovar'; ?>">
                    <?php if($status == 'a') echo 'Pagar Multa'; else if($status == 'e') echo 'Renovar'; else if($exc) echo "Restaurar"; else echo 'Excluir';  ?>
                </button>
            </div>
        </div>
    </div>
    
</body>

<script src="../js/main.js">
</script>
</html>