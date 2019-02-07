<!DOCTYPE html>
<html lang="pt-br">
<script>
    var page = "sobre";
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Sobre - Apolo</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/sobre.css">
    <link rel="stylesheet" href="../css/footer.css">
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
                                <a href="../" id="inicio" title="Visão Geral">Início
                                    <div></div>
                                </a>
                            </div>
                            <div class="menuOption">
                                <a href="" id="sobre" title="Sobre o Sistema">Sobre
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
        <div class="sobre">
            <div class="sobreContent">
                <div class="sobreTitle">
                    <h2>Sobre o Sistema</h2>  
                </div>
                <div class="sobreText">
                    <p>
                        Apolo é um sistema desenvolvido para organizar os livros e os leitores da Biblioteca do CTI Unesp Bauru. Um jeito de facilitar e incentivar a leitura e a descoberta de novos autores e novas histórias.
                    </p>    
                </div>

                <div class="desenv">
                    <div class="desenvGrid">
                        <div class="desenvPerson">
                            <img src="https://scontent.fbau2-1.fna.fbcdn.net/v/t1.0-9/49209942_1968739469900280_5251711281689264128_n.jpg?_nc_cat=108&_nc_ht=scontent.fbau2-1.fna&oh=c0badc34bd2aaa28c0c11b4912a67af2&oe=5CB6252E" alt="">
                            <h3>Estevão Rolim</h3>
                        </div>
                        <div class="desenvPerson">
                            <img src="https://scontent.fbau2-1.fna.fbcdn.net/v/t1.0-9/48359281_1813342852127959_3048141635750723584_n.jpg?_nc_cat=111&_nc_ht=scontent.fbau2-1.fna&oh=8c8fa13b053b9f1b5cadfa3e7e010440&oe=5CEA1D7B" alt="">
                            <h3>Pedro Neves</h3>
                        </div>
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
                        <a href="" title="Sobre">Apolo - Sistema de Biblioteca - CTI</a>
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

<script src="../js/main.js"></script>
</html>