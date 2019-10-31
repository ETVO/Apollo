<?php
    include "head.php";
    
    include 'login.php';
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
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>    
    <a href="" onclick="" class="a voltaInicio"></a><br>
    <a href="index.php" class="a voltaInicio">Voltar ao Início</a>
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
                    <a href="?sel=u" class="a <?php if($selected == 'u') echo "selected"; ?>">Administradores</a>
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
                        <a href="config.php" target="_blank" class="addNew textcenter a">Configurações do Sistema</a>

                        <div class="grid_3">
                            <table class="painelTable">
                                <tr>
                                    <th colspan="3">Gerar Relação (.pdf)</th>
                                </tr>
                                <tr>
                                    <td><a href="pdf.php?ent=ea" target="_blank" class="a relLinks">Empréstimos Atrasados</a></td>
                                    <td><a href="pdf.php?ent=e" target="_blank" class="a relLinks">Empréstimos</a></td>
                                    <td><a href="pdf.php?ent=l" target="_blank" class="a relLinks">Livros</a></td>
                                    <td><a href="pdf.php?ent=a" target="_blank" class="a relLinks">Administradores</a></td>
                                    <td><a href="pdf.php?ent=log" target="_blank" class="a relLinks">Log de Administração</a></td>
                                </tr>
                            </table>
                            <table class="painelTable">
                                <tr>
                                    <th colspan="3">Backup (.csv)</th>
                                </tr>
                                <tr>
                                    <td><a href="csv.php?ent=e" target="_blank" class="a relLinks">Empréstimos</a></td>
                                    <td><a href="csv.php?ent=l" target="_blank" class="a relLinks">Livros</a></td>
                                    <td><a href="csv.php?ent=a" target="_blank" class="a relLinks">Administradores</a></td>
                                </tr>
                            </table>
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
                    ?>
            </div>
        </div>
    </div>
</body>

<script src="../js/main.js"></script>
</html>