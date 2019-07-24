<?php
    session_start();
    include '../config/php/util.php';

    $login = $_SESSION['login'];
    $senha = $_SESSION['senha'];
    $nome = '';

    $edit = false;
    $exc = false;
    $success = false;
    
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

    $id_emp = '';
    $titulo = '';
    $nome = '';
    $telefone = '';
    $admin = '';
    $data_emp = '';
    $data_prev_dev = '';
    $devolvido = '';
    $data_dev = '';
    $obs = '';

    if(isset($_GET['id'])){
        $id_emp = $_GET['id'];

        try {
            include "../config/php/connect.php";

            $sql = "SELECT l.id_livro, l.titulo, e.nome, e.telefone, e.turma, a.nome AS admin, data_emp, 
            data_prev_dev, devolvido, data_dev, e.obs FROM emprestimo AS e 
            INNER JOIN livro AS l ON e.id_livro = l.id_livro 
            INNER JOIN user AS a ON e.id_admin = a.id_user OR e.id_admin = 0 WHERE id_emprestimo = $id_emp";

            $res = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0)
            {
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

                $titulo = utf8_encode($row['titulo']);
                $id_livro = $row['id_livro'];
                $nome = utf8_encode($row['nome']);
                $telefone = utf8_encode($row['telefone']);
                $turma = utf8_encode($row['turma']);
                $admin = utf8_encode($row['admin']);
                $data_emp = utf8_encode($row['data_emp']);
                $data_prev_dev = utf8_encode($row['data_prev_dev']);
                $devolvido = utf8_encode($row['devolvido']);
                $data_dev = utf8_encode($row['data_dev']);
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

    if(isset($_GET['exc']))
    {
        // $exc = $_GET['exc'];
        // $success = false;
        // try {
        //     include "../config/php/connect.php";

        //     $sql = "DELETE FROM livro WHERE id_livro = $id";

        //     $res = mysqli_query($conn, $sql);
            
        //     if(mysqli_affected_rows($conn) > 0){
        //         echo "<script>
        //         alert('Livro excluído com sucesso!');
        //         </script>";

        //         $success = true;
        //     } 
        //     else {
        //     }
        // } catch(Exception $e) {

        // }
        // if($success)
        // {
        //     $append = "Usuário \"$login\" excluiu o livro id $id.<br>";
        //     $file = 'log.html';
        //     date_default_timezone_set("America/Sao_Paulo");

        //     $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
            
        //     if(file_get_contents($file) != '')
        //         $append = file_get_contents($file).$append;

        //     file_put_contents($file, $append);
        // }

        // header("Location: main.php?sel=l");

    }

    if(isset($_GET['multa']))
    {
        // if($_GET['multa'])
        // {
        //     try {
        //         include '../config/php/connect.php';

        //         $multa = $_SESSION['multa'];
        //         $desc = "Multa do empréstimo id $id_emp";

        //         $sql = "INSERT INTO caixa VALUES (DEFAULT, $multa, $desc);";

        //         // $res = mysqli_query($conn, $sql);

        //         if(mysqli_affected_rows($conn) > 0)
        //         {
        //             header('Location: main.php?sel=e');
        //         }
        //         else
        //             header("Location: ?id=$id_emp");

        //         mysqli_close($conn);
        //     } catch (Exception $e) {
        //         header("Location: ?id=$id_emp");
        //     }
        // }
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
        $opt = 'del';
        $_SESSION['multa'] = $multa;
    }

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
    
    if(isset($_POST['subjust']))
    {
        try {
            include '../config/php/connect.php';

            $justificativa = utf8_decode(mysqli_real_escape_string($conn, $_POST['just']));

            $data_dev = date('Y-m-d');

            $sql = "UPDATE emprestimo SET
            obs = '$justificativa',
            devolvido = 1,
            data_dev = '$data_dev'
            WHERE id_emprestimo=$id_emp";
            
            $res = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0)
            {   
                $sql = "UPDATE livro SET
                disponivel = 1 
                WHERE id_livro = $id_livro";

                $res = mysqli_query($conn, $sql);

                if(mysqli_affected_rows($conn) > 0)
                {
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
                $sql = "UPDATE livro SET
                disponivel = 1 
                WHERE id_livro = $id_livro";

                if($res = mysqli_query($conn, $sql))
                {
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

            $obs_multa = "Multa de $multa paga";

            $data_dev = date('Y-m-d');

            $sql = "UPDATE emprestimo SET
            obs = '$obs_multa',
            devolvido = 1,
            data_dev = '$data_dev'
            WHERE id_emprestimo=$id_emp";
            
            $res = mysqli_query($conn, $sql);
            
            // echo $sql;

            if(mysqli_affected_rows($conn) > 0)
            {   
                $sql = "UPDATE livro SET
                disponivel = 1 
                WHERE id_livro = $id_livro";

                $res = mysqli_query($conn, $sql);

                if(mysqli_affected_rows($conn) > 0)
                {
                    $data_dev = date('d/m/Y', strtotime($data_dev));
                    $desc = "Multa de empréstimo atrasado, paga em $data_dev";
                    $desc = utf8_decode($desc);
                    $sql = "INSERT INTO caixa VALUES (DEFAULT, $val_multa, '$desc');";

                    $res = mysqli_query($conn, $sql);

                    if(mysqli_affected_rows($conn) > 0)
                    {
                        echo '<script>
                        alert("Multa de atraso paga com sucesso!");
                        
                        </script>';
                    }
                    else {    
                        $error = mysqli_error($conn);
                        echo '<script>
                            alert("Algo deu errado!\nMais detalhes:'.$error.'");
                            
                            </script>';
                        $success = true;
                    }
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

        echo '<script>window.location.href="main.php?sel=e"</script>';
    }

    if(isset($_GET['del']) && $status == 'd')
    {
        try {
            include '../config/php/connect.php';

            $sql = "DELETE FROM emprestimo
            WHERE id_emprestimo=$id_emp";
            
            $res = mysqli_query($conn, $sql);

            if($res = mysqli_query($conn, $sql))
            {   
                echo '<script>
                alert("Empréstimo excluído com sucesso!");
                
                </script>';
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
            $append = "Usuário \"$login\" excluiu o empréstimo id $id_emp.<br>";
            $file = 'log.html';
            date_default_timezone_set("America/Sao_Paulo");

            $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
            
            if(file_get_contents($file) != '')
                $append = file_get_contents($file).$append;

            file_put_contents($file, $append);
        }

        echo '<script>window.location.href="main.php?sel=e"</script>';
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
    <a href="" onclick="window.close();" class="a voltaInicio">Fechar</a><br>
    <a href="main.php?sel=e" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h3>Visualizar <a href="main.php?sel=e" class="a">Empréstimo</a></h3>
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

            <div class="visualizarInfo">
                <?php if($status == 'a') echo '<h3 class="hAtrasado">ATRASADO</h3>'; 
                else if($status == 'e') echo '<h3 class="hEmDia">EM DIA</h3>'; 
                else{ echo '<h3 class="hDevolvido"><b>DEVOLVIDO</b>'; if($d_atraso) echo ' (COM ATRASO)'; echo '</h3>';} ?>
            </div> 

            <div class="visualizarInfo">
                <label for="">Livro</label>
                <h3><?php echo $titulo; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Usuário</label>
                <h3><?php echo $nome; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Contato (telefone)</label>
                <h3><?php echo $telefone; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Turma</label>
                <h3><?php echo $turma; ?></h3>
            </div>  
            
            <div class="visualizarInfo">
                <label for="">Autorizado por</label>
                <h3><?php echo $admin; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Emprestado em</label>
                <h3><?php echo date('d/m/Y', strtotime($data_emp)); ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Prazo de devolução</label>
                <h3><?php echo date('d/m/Y', strtotime($data_prev_dev)); ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Devolvido</label>
                <h3><?php $datadev = date('d/m/Y', strtotime($data_dev)); echo ($devolvido) ? "Sim (em $datadev)" : 'Não'; ?></h3>
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
                        window.location.href = '?id=$id_emp&$opt=true';
                    }
                });"; ?>" class="<?php if($status == 'd') echo 'btnExcluir'; else echo 'btnRenovar'; ?>">
                    <?php if($status == 'a') echo 'Pagar Multa'; else if($status == 'e') echo 'Renovar'; else echo 'Excluir';  ?>
                </button>
            </div>
        </div>
    </div>
    
</body>

<script src="../js/main.js">
</script>
</html>