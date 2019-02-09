<?php
    session_start();


    $login = $_SESSION['login'];
    $senha = $_SESSION['senha'];
    $nome = '';
    if(!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
        header("Location: ..");
    }
    else {
        try 
        {
            include "../config/php/connect.php";

            $sql = "SELECT nome FROM user WHERE login = '$login'";

            $res = mysqli_query($conn, $sql);
            
            if(mysqli_affected_rows($conn) > 0){
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                $nome = utf8_encode($row['nome']);
                $nome = explode(" ", $nome)[0];
            } 
            else {
                header("Location: ..");
            }

            close($conn);
        } catch (Exception $e){

        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<script>
    var page = "inicio";
</script>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Apolo</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/livros.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>
    <div class="main">
        <div class="header" id="topo">
            <div class="headerContent">
                <div class="headerGrid">
                    <div class="logo">
                        <a class="logo_image" href="../main">
                            <img src="" alt="">
                        </a>
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
                                <a href="../main" id="inicio" title="Visão Geral">Início
                                    <div></div>
                                </a>
                            </div>
                            <div class="menuOption">
                                <a href="../sobre/" id="sobre" title="Sobre o Sistema">Sobre
                                    <div></div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pesquisa">
                        <div class="pesquisaContent">    
                            <form action="" class="pesquisaFrm" id="frmPesquisa">
                                <input type="search" placeholder="Pesquisar..." name="search" class="pesquisaInput" title="Pesquisar">
                                <a onclick="submitPesquisa()"><img src="" alt="" class="pesquisaIcon" title="Pesquisar"></a>
                            </form>
                        </div>
                    </div>

                    <div class="conta">
                        <div class="contaContent">
                            <div class="contaText">
                                <div><label for=""><b>Administrador: </b><?php echo $nome?></label></div><a href="../admin" class="contaAdm" title="Funções de Administrador"><img src="" alt=""></a><a class="contaLogout" title="Sair da Conta" onclick="sure()"><img src="" alt=""></a>
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
                        while($i++ < 10){
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
                                    <div class="livroCodigo">
                                        <h4>LER-123</h4>
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
                        <a href="../sobre" title="Sobre">Apolo - Sistema de Biblioteca - CTI</a>
                        <br>
                        Desenvolvido por Estevão Rolim e Pedro Neves
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="js/main.js"></script>
<script>
    var menuActive = document.getElementById(page);

    menuActive.setAttribute("id", "active");

    function sure() {
        swal({
            title: "Sair da Conta",
            text: "Deseja realmente sair de sua conta?",
            icon: "warning",
            buttons: [{
                text: "Sim",
                value: true,
                visible: true,
                className: "",
                closeModal: true,
            }, 
            {
                text: "Não",
                value: false,
            }],
        }).then((value)=> {
            if(value){
                window.location.href="../?logout=true";
            }
        });
    }
</script>
</html>