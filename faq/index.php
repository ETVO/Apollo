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
    ['Do magna pariatur voluptate ullamco sint aute cupidatat sunt aliqua eiusmod fugiat enim.',
    ' Eu nulla in ea qui labore esse in culpa excepteur eiusmod.',
    ' Esse irure culpa duis nulla ut esse voluptate elit consectetur ullamco nulla laboris aliqua. ',
    'Quis eu sint esse tempor mollit anim irure ea quis elit ad fugiat mollit. Adipisicing quis dolor ullamco anim aliquip amet enim.']


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
    <link rel="stylesheet" href="../css/ajuda.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="https://kit.fontawesome.com/085f790a05.js"></script>

    <title>Ajuda</title>
</head>

<body>
    <div class="ajuda">
        <div class="ajudaContent">
            <h1>Ajuda</h1>
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
    
    <div class="footer">
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