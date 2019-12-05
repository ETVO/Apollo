<?php
    session_start();
    session_destroy();
    session_start();

    $titles = [
        'Como achar um livro?', 
        'Como entrar no Painel de Administração?', 
        'Como fazer um empréstimo?', 
        'Como devolver um empréstimo?', 
        'Para que serve o sistema?', 
        'O design do sistema foi inspirado no Google?'
    ];

    $items = 
    ['Vá para a página Início e digite os termos de busca do livro que você quer encontrar. Logo em seguida, você será redirecionado para uma página com os resultados da sua pesquisa.',
    'Para acessar a Administração, basta clicar na opção Administração, do rodapé do site. Ali, você pode configurar diversos aspectos do Apolo e dos livros, empréstimos e usuários registrados.',
    'Após encontrar o livro ou os livros que queria, na página de resultados, basta clicar em "Emprestar" e depois em "Finalizar Empréstimo". Ao fazer isso, você será enviado para uma página em que pode revisar a(s) obra(s) escolhidas e ali deve escolher o usuário para quem esses livros serão emprestados. Depois disso, um administrador deve utilizar seu Login e Senha para autorizar o empréstimo.',
    'Um empréstimo pode ser devolvido apenas no painel administrativo. Por isso, sempre que quiser devolver um empréstimo, peça a um administrador que encontre o registro do seu empréstimo e realize as operações necessárias.',
    'O sistema Apolo foi criado para facilitar o dia a dia da organização da Biblioteca do CTI. No painel administrativo, os usuários administradores podem adicionar livros, usuários e até monitorar as movimentações do caixa da biblioteca.<br>
    Pensado para simplificar, os formulários são padronizados e seguem uma sequência intuitiva ao longo das diferentes páginas.',
    'Sim! Algumas das páginas seguem a paleta minimalista do Google, e a própria página inicial do sistema é inspirada na famosa página de pesquisa do Google.<br>
    Apesar de ser inspirado, todas as páginas foram feitas inteiramente para o sistema, ou seja, sem nenhuma cópia de código ou formulário: tudo feito à medida, à mão e com carinho.']


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/ajuda.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Ajuda - Apolo</title>
</head>

<body>
    <div class="ajuda" id="principal">
        <div class="ajudaContent">
            <h1 id="title">Ajuda</h1>
            <div class="contentaddnew" id="optionsContent">
                <div id="options">
                    <a onclick="imprime()" class="a">Imprimir</a>
                </div>
            </div>
            <div class="tabs">
                <?php 
                    for($i = 0; $i < sizeof($titles); $i++)
                    {
                        ?>
                            <div class="tab">
                                <input type="checkbox" id="chck<?php echo $i ?>">
                                <label class="tab-label" for="chck<?php echo $i ?>"><?php echo $titles[$i] ?></label>
                                <div class="tab-content">
                                <?php echo $items[$i] ?>
                                </div>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    
    <div class="footer" id="footer">
        <div class="footerDesc">
            © 2019 <b><a href="../main" title="Início">Apolo</a></b> - Sistema da Biblioteca CTI
        </div>
        <div class="footerItems">
            <ul>
                <li><a href="../admin" class="footerOpt" title="Funções administrativas">Administração</a></li>
                <li><a href="../sobre" class="footerOpt"  title="Sobre o sistema">Sobre</a></li>
                <li><a href="../faq" class="footerOpt"  title="Ajuda">Ajuda</a></li>
                <li><a href="../" class="footerOpt"  title="Página inicial">Início</a></li>
            </ul>
        </div>
    </div>
</body>

<script src="../js/main.js"></script>
<script src="../js/ajuda.js"></script>
</html>