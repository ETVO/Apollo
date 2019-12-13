<?php
    include "head.php";
    
    include 'login.php';
    
    include '../config/php/connect.php';
    
    $login = $_SESSION['login'];

    $sql = "SELECT nome FROM user WHERE login = '$login'";
    
    $res = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($res, MYSQLI_NUM);
        
    $nomeadmin = ($row[0]);

    function conta($tabela)
    {
        include '../config/php/connect.php';
        if($tabela == "user")
            $sql = "SELECT COUNT(*) FROM $tabela WHERE bloqueado = 0";
        else
            $sql = "SELECT COUNT(*) FROM $tabela WHERE excluido = 0";
    
        $res = mysqli_query($conn, $sql);

        $row = mysqli_fetch_array($res, MYSQLI_NUM);
        
        $n = ($row[0]);
            
        return $n;
    }
    
    $nlivro = conta("livro");
    
    $sql = "SELECT SUM(qtde) AS soma
    FROM livro WHERE excluido = 0";

    $res = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    $slivro = $row['soma'];

    $nuser = conta("user");
    $nemp = conta("emprestimo");
    
    try {
        include "../config/php/connect.php";
        
        $sql = "SELECT SUM(valor) AS soma
                FROM caixa WHERE tipo = 'e' AND excluido = 0";

        $res = mysqli_query($conn, $sql);
        
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

        $e = $row['soma'];

        $sql = "SELECT SUM(valor) AS soma
                FROM caixa WHERE tipo = 's' AND excluido = 0";

        $res = mysqli_query($conn, $sql);
        
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

        $s = $row['soma'];

        $total = $e + $s;   

        if($total > 0) $symb = "+";
        else $symb = "";
            
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
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body> 
    <a href="" onclick="" class="a voltaInicio"></a><br>
    <a href=".." class="a voltaInicio">Voltar ao Início</a>
    <div class="textcenter">
        <h4>Apolo</h4>
        <h2><a href="?sel=" style="text-decoration:none">Administração</a></h2>
    </div>
    <div class="double_grid">
        <div class="mainMenuAdm">
            <h4 style="padding:10px; margin: 0">Opções</h4>
            <ul class="mainMenuAdmUl">
                <li>
                    <a href="?sel=" class="a <?php if($selected == '') echo "selected"; ?>">Painel</a>
                </li>
                <li>
                    <a href="?sel=e" class="a <?php if($selected == 'e') echo "selected"; ?>">Empréstimos</a>
                </li>
                <li>
                    <a href="?sel=l" class="a <?php if($selected == 'l') echo "selected"; ?>">Livros</a>
                </li>
                <li>
                    <a href="?sel=u" class="a <?php if($selected == 'u') echo "selected"; ?>">Usuários</a>
                </li>
                <li>
                    <a href="?sel=c" class="a <?php if($selected == 'c') echo "selected"; ?>">Caixa</a>
                </li>
                <li>
                    <a href="?sel=h" class="a <?php if($selected == 'h') echo "selected"; ?>">Ajuda</a>
                </li>
                <li>
                    <a href="?sel=p" class="a <?php if($selected == 'p') echo "selected"; ?>">Alterar senha</a>
                </li>
            </ul>
        </div>
        <div class="dashboard">
            <div class="dashboardContent">
                <?php
                    if($selected == '')
                    {
                        ?>
                        <h2 class="textcenter dashboardTitle" ><a href="?sel=" class="a">Painel</a></h2>
                        <div class="contentaddnew" id="optionsContent">
                            <div id="options">
                                <a onclick="changeParentLocation('config.php')" class="a">Configurações do sistema</a>
                                |
                                <a onclick="changeParentLocation('codigos.php')" class="a">Imprimir códigos</a>
                            </div>
                        </div>
                        <div class="content">
                            <h2>Bem vindo, <?php echo $nomeadmin ?>!</h2>
                            <p>
                                Para utilizar o painel administrativo, guie-se pelas opções da barra lateral esquerda.
                            </p>
                            <p>
                                Para entender melhor o sistema e os parâmetros utilizados, consulte o menu "Ajuda".
                            </p>

                            <br>

                            <p class="sys_status_title">
                                Registros do sistema:
                            </p>
                            <p class="sys_status">
                                <?php 
                                    if($nlivro > 1) $s = "s"; else $s = ""; 
                                    
                                ?>
                                <span class="">
                                    <?php echo $nlivro;  ?>
                                </span>
                                livro<?php echo $s ?>
                                <?php 
                                    if($slivro > 1) $s = "es"; else $s = "";
                                     
                                    if($slivro != null)
                                    {
                                        ?>
                                        <span class="">
                                            (<?php echo $slivro;  ?>
                                        </span>
                                        exemplar<?php echo $s ?>)
                                        <?php
                                    }
                                ?>
                                
                            </p>
                            <p class="sys_status">
                                <?php 
                                    if($nuser > 1) $s = "s"; else $s = ""; 
                                    
                                ?>
                                <!-- Nº de <b>usuários</b>: -->
                                <span class="">
                                    <?php echo $nuser;  ?>
                                </span>
                                usuário<?php echo $s ?>
                            </p>
                            <p class="sys_status">
                                <?php 
                                    if($nemp > 1) $s = "s"; else $s = ""; 
                                    
                                ?>
                                <!-- Nº de <b>empréstimos</b>: -->
                                <span class="">
                                    <?php echo $nemp;  ?>
                                </span>
                                empréstimo<?php echo $s ?>
                            </p>
                            <p class="sys_status">
                                Balanço (R$):
                                <span class="<?php echo ($total >= 0) ? "green" : "red";?>">
                                    <?php echo $symb.number_format((float)$total, 2, ',', ''); ?>
                                </span>
                            </p>
                        </div>
                        <?php
                    }
                    else if($selected == 'e') {
                        ?>
                        <iframe src="emp.php" frameborder="0"></iframe>
                        <?php
                    }
                    else if($selected == 'l') {
                        ?>
                        <iframe src="liv.php" frameborder="0"></iframe>
                        <?php
                    } 
                    else if($selected == 'u') {
                        ?>
                        <iframe src="usu.php" frameborder="0"></iframe>
                        <?php
                    }
                    else if($selected == 'c') {
                        ?>
                        <iframe src="cxa.php" frameborder="0"></iframe>
                        <?php
                    }
                    else if($selected == 'h') {
                        ?>
                        <iframe src="ajuda.php" frameborder="0"></iframe>
                        <?php
                    }
                    else if($selected == 'p') {
                        ?>
                        <iframe src="pass.php" frameborder="0"></iframe>
                        <?php
                    }
                    ?>
            </div>
        </div>
    </div>
</body>

<script src="../js/main.js"></script>
</html>