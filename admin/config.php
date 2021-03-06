<?php
    include 'login.php';

    $edit = false;

    if(isset($_GET['red']))
    {
        $success = false;
        $red = $_GET['red'];
        
        if($red)
        {
            try{
                include "../config/php/connect.php";
                include "../config/php/presets.php";

                $sql = "UPDATE config SET
                valor = '$multa' WHERE nome = 'multa'";

                if(!($res = mysqli_query($conn, $sql)))
                    echo "<script>window.close();</script>";
            
                
                $sql = "UPDATE config SET
                valor = '$dias_dev' WHERE nome = 'dias_dev'";

                if(!($res = mysqli_query($conn, $sql)))
                    echo "<script>window.close();</script>";
                
            
                $sql = "UPDATE config SET
                valor = '$std_pass' WHERE nome = 'std_pass'";
    
                if(!($res = mysqli_query($conn, $sql)))
                    echo '<script>window.location.href="?"</script>';
                

                $success = true;

                mysqli_close($conn);
            } catch(Exception $e) {

            }
        }   
        if($success)
        {
            $append = "Usuário \"$login\" redefiniu as configurações para: MULTA para 1 e DIAS_DEV para 7.<br>";
            $file = 'log.html';
            date_default_timezone_set("America/Sao_Paulo");

            $append = '['.date('d/m/Y H:i:s').'] '.$append;
            
            if(file_get_contents($file) != '')
                $append = file_get_contents($file).$append;

            file_put_contents($file, $append);
        }
        echo '<script>window.location.href="?"</script>';
    }

    if(isset($_GET['edit']))
    {
        $edit = true;
    }

    if(isset($_POST['cancelEdit']))
    {
        $edit = false;
        echo '<script>window.location.href="?"</script>';
    }

    if(isset($_POST['subConfig']))
    {
        $success = false;
        try {
            include '../config/php/connect.php';

            $multa = $_POST['multa'];
            $dias_dev = $_POST['dias_dev'];
            $std_pass = $_POST['std_pass'];

            $sql = "UPDATE config SET
            valor = '$multa' WHERE nome = 'multa'";

            if(!($res = mysqli_query($conn, $sql)))
                echo '<script>window.location.href="?"</script>';
        
            
            $sql = "UPDATE config SET
            valor = '$dias_dev' WHERE nome = 'dias_dev'";

            if(!($res = mysqli_query($conn, $sql)))
                echo '<script>window.location.href="?"</script>';

            
            $sql = "UPDATE config SET
            valor = '$std_pass' WHERE nome = 'std_pass'";

            if(!($res = mysqli_query($conn, $sql)))
                echo '<script>window.location.href="?"</script>';

            $success = true;

            mysqli_close($conn);
        } catch (Exception $e){

        }
        if($success)
        {
            $append = "Usuário \"$login\" alterou as configurações para: MULTA para $multa, DIAS_DEV para $dias_dev E STD_PASS para '$std_pass'.<br>";
            $file = 'log.html';
            date_default_timezone_set("America/Sao_Paulo");

            $append = '['.date('d/m/Y H:i:s').'] '.$append;
            
            if(file_get_contents($file) != '')
                $append = file_get_contents($file).$append;

            file_put_contents($file, $append);
        }

        echo '<script>window.location.href="?"</script>';
        
    }

    $multa = 0;
    $dias_dev = 0;
    $std_pass = "";

    try{
        include '../config/php/connect.php';

        $sql = "SELECT * FROM config";

        $res = mysqli_query($conn, $sql);

        if(mysqli_affected_rows($conn) > 0)
        {
            while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
            {
                if($row['nome'] == 'multa')
                {
                    $multa = $row['valor'];
                    $multa = (float) $multa;
                }
                else if($row['nome'] == 'dias_dev')
                {
                    $dias_dev = $row['valor'];
                    $dias_dev = (int) $dias_dev;
                }
                else if($row['nome'] == 'std_pass')
                {
                    $std_pass = ($row['valor']);
                }
            }
        }

        mysqli_close($conn);
    } catch (Exception $e)
    {

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
    <link rel="stylesheet" href="../css/config.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>    
    <a href="main.php" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h4>Apolo</h4>
        <h2>Configurações</h2>
    </div>
    
    <div class="config">
        <form action="" method="post" class="configFrm" <?php if($edit) echo 'style="display:block"'; ?>>
            <label for="multa" title="Multa que deve ser paga por dia de atraso.">Multa <b>(por dia atrasado)</b></label><br>
            <input type="number" name="multa" id="multa" autofocus min="0.1" step="0.1" value="<?php echo $multa; ?>">
            <br><br>
            <label for="dias_dev" title="Prazo de dias para devolução de livros emprestados.">Prazo de Devolução</label><br>
            <input type="number" name="dias_dev" id="dias_dev" min="1" max="30" value="<?php echo $dias_dev; ?>">
            <br><br>
            <label for="std_pass" title="Senha utilizada por padrão quando um usuário é criado na tela 'Finalizar Empréstimo'">Senha padrão</label><br>
            <input type="text" name="std_pass" id="std_pass" value="<?php echo $std_pass; ?>">
            <br><br>
            <button type="submit" name="subConfig" class="configBtn">Salvar Alterações</button>
            <button type="submit" name="cancelEdit" class="configBtn reset" formnovalidate>Cancelar</button>
        </form>

        <div class="configContent" <?php if($edit) echo 'style="display:none"'; ?>>
            <div class="configInfo">
                <label for="" title="Multa que deve ser paga por dia de atraso.">Multa <b>(por dia atrasado)</b></label>
                <h3>R$ <?php echo number_format((float)$multa, 2, ',', ''); ?></h3>
            </div>
            
            <div class="configInfo">
                <label for="" title="Prazo de dias para devolução de livros emprestados.">Prazo de Devolução</label>
                <h3><?php echo $dias_dev." dia"; if($dias_dev > 1) echo 's'; ?></h3>
            </div>  
            
            <div class="configInfo">
                <label for="" title="Senha utilizada por padrão quando um usuário é criado na tela 'Finalizar Empréstimo'">Senha padrão</label>
                <h3>"<?php echo $std_pass?>"</h3>
            </div>  

            <div class="configOptions">
                <button onclick="<?php echo "window.location.href = '?edit=true';" ?>" class="btnEditar">
                    Editar
                </button>
                
                <button onclick="<?php 
                echo "swal({
                    title: 'Atenção!',
                    text:'Deseja realmente redefinir as variáveis de configuração?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((willRedefine) =>{
                    if(willRedefine){
                        window.location.href = '?red=true';
                    }
                });"; ?>" class="btnRedefinir" title="Redefinir as variáveis de configuração">
                    Restaurar
                </button>
            </div>
        </div>
    </div>
    
</body>

<script src="../js/main.js">
</script>
</html>