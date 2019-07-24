<?php
session_start();
    session_destroy();
    session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Sobre - Apolo</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/sobre.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>
    <div class="sobre">
        <div class="sobreContent">
            <div class="logo">
                <div class="presentLogo">
                    <img src="" alt="">
                </div>
            </div>
            <div class="textcenter">
                <h1><a href=".." class="a">Apolo</a></h1>
                <h2>Sistema para Biblioteca - CTI Bauru</h2>
                <div class="sobreDesc">
                <p>
                    Experimentando a simplificação dos processos. Priorizando as classificações essenciais, propõe a utilização de estruturas mais práticas para otimizar a experiência de usuário e a eficiência do sistema.
                </p>
                <p class="opensource">
                    Open Source:<br>pensado para melhorar, não para restringir.
                </p>
                <p>
                    Em <b>2019</b> por <b>Estevão Rolim</b>
                </p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="footerDesc">
            © 2019 <b><a href="../main" title="Início">Apolo</a></b> - Sistema da Biblioteca CTI
        </div>
        <div class="footerItems">
            <ul>
                <li><a href="../admin" target="_blank" class="footerOpt" title="Funções administrativas">Administração</a></li>
                <li><a href="../sobre" class="footerOpt"  title="Sobre o sistema">Sobre</a></li>
            </ul>
        </div>
    </div>
</body>

<script src="../js/main.js"></script>
</html>