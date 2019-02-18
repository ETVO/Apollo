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

        <title>Apolo</title>
        <link rel="stylesheet" href="../css/main.css">
        <link rel="stylesheet" href="../css/main_search.css">
        <link rel="stylesheet" href="../css/footer.css">
        <script src="../config/js/sweetalert.min.js"></script>
        <link rel="shortcut icon" href="../favicon.ico"> 
    </head>

    <body>
        <div class="index">
            <div class="indexContent">
                <div class="present">
                    <div class="presentContent">
                        <div class="presentLogo">
                            <img src="" alt="">
                        </div>
                        <div class="presentTitle">
                            <h1 onclick="window.location.href='main';">Apolo</h1>
                        </div>
                    </div>
                </div>
                <div class="search">
                    <div class="searchContent">
                        <form action="../search" class="searchFrm" method="get">
                            <div class="searchField">
                                <input type="search" name="search" id="search" autofocus required>
                            </div>
                            <div class="searchSubmit">
                                <button class="searchBtn" title="Pesquisar livros">Pesquisar</button>
                            </div>
                        </form>
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