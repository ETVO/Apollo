<!DOCTYPE html>
<html lang="pt-br">
<script>
    var page = "inicio";
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Apolo</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/livros.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>
    <div class="index">
        <div class="header" id="topo">
            <div class="headerContent">
                <div class="headerGrid">
                    <div class="logo">
                        <div class="logo_image">
                            <img src="" alt="">
                        </div>
                        <div class="logo_title">
                            <h1>Apolo</h1>
                        </div>
                    </div>
                    <div class="sublogo">
                        <p>Organizando livros e leitores</p>
                    </div>
                    <div class="menu">
                        <div class="menuContent">
                            <div class="menuOption">
                                <a href="" id="inicio" title="Visão Geral">Início
                                    <div></div>
                                </a>
                            </div>
                            <div class="menuOption">
                                <a href="sobre/" id="sobre" title="Sobre o Sistema">Sobre
                                    <div></div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pesquisa">
                        <div class="pesquisaContent">    
                            <form action="" class="pesquisaFrm" id="frmPesquisa">
                                <input type="search" name="search" class="pesquisaInput" title="Pesquisar">
                                <a onclick="submitPesquisa()"><img src="" alt="" class="pesquisaIcon" title="Pesquisar"></a>
                            </form>
                        </div>
                    </div>

                    <div class="conta">
                        <div class="contaContent">
                            <div class="contaText">
                                <a href="" title="Faça seu login!">Fazer Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="livros">
            <div class="livrosContent">
                <div class="livrosTitle">
                    <h2>Livros</h3>
                </div>
                <div class="livrosCatalog">
                    <div class="livrosLivros">
                        <?php
                        $i = 0;
                        while($i++ < 4){
                        ?>
                        <div class="livro">
                            <form action="">
                                <div class="livroImage">
                                    <img src="https://images-na.ssl-images-amazon.com/images/I/41Q11CAxpcL._SX321_BO1,204,203,200_.jpg"
                                        alt="" title="A História do Lorem Ipsum">
                                </div>
                                <div class="livroInfo">
                                    <div class="livroTitle">
                                        <h3>
                                            A História do Lorem Ipsum
                                        </h3>
                                    </div>
                                    <div class="livroAutor">
                                        <h4>
                                            Marcus Tullius Cicero
                                        </h4>
                                    </div>
                                </div>
                                <div class="btnSubmit">
                                    <input type="submit" value="Empréstimo" title="Emprestar este livro">
                                </div>
                            </form>
                        </div>
                        <?php 
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="footerContent">
                <div class="footerGrid">
                    <div class="voltaTopo">
                        <a href="#topo" title="Voltar ao topo da página">Voltar ao topo</a>
                    </div>
                    <div class="footerText">
                        @ 2019
                        <br>
                        <a href="sobre" title="Sobre">Apolo - Sistema de Biblioteca - CTI</a>
                        <br>
                        Desenvolvido por Estevão Rolim e Pedro Neves
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    var menuActive = document.getElementById(page);

    menuActive.setAttribute("id", "active");
</script>

<script src="js/main.js"></script>
</html>