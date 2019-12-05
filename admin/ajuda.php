
<!-- <ul>
<li>
    Empréstimos<br>
    <span></span>
</li>
<li>
    Livros<br>
    <span>Visualize todos os livros registrados no sistema e consultar sua disponibilidade. Aqui, você pode editar as informações individuais de cada obra e adicionar novos exemplares.</span>
</li>
<li>
    Usuários<br>
    <span>Selecionando essa opção, você pode consultar todos os usuários registrados no sistema, sejam eles administradores ou não. Você pode criar novos usuários, editar seus registros e bloqueá-los.</span>
</li>
<li>
    Caixa<br>
    <span>O Caixa registra todas as transações realizadas no sistema, desde o pagamento de multas até outras entradas e saídas.</span>
</li> -->

<?php
    include "head.php";
    include "login.php";

    $titles = [
        'Para que servem os empréstimos?', 
        'Como posso alterar minha própria senha?', 
        'Temos dois exemplares do mesmo livro. Como faço para registrá-los?', 
        'Para que serve a página "Caixa"?', 
        'Vi que no Painel há a opção "Configurações do sistema".<br>Para que isso serve?'
    ];

    $items = 
    ['Os usuários podem pesquisar e emprestar livros que estejam registrados no sistema. Para fazer isso, eles precisam da autorização de um administrador.',
    'Para alterar a sua senha, basta selecionar a página "Alterar minha senha" e preencher os campos indicados.',
    'Entre na página "Livros", clique em "Adicionar novo" e indique, durante o cadastro, a quantidade de exemplares do livro sendo cadastrado.<br>
    Todos os exemplares terão o mesmo código, mas serão tratados de maneira individual pelo sistema.',
    'Na página "Caixa", os administradores, como você, podem visualizar todas as transações realizadas e registradas no sistema. Isso serve para controlar o pagamento das multas de atraso e outras movimentações da Biblioteca ou do Grêmio.',
    'Nessa página, você pode ajustar algumas configurações do sistema, como prazo de devolução, multa por dia de atraso ou até a senha padrão com que são criados os usuários.<br>
    Obs.: Essa senha será utilizada somente para usuários criados na página "Finalizar Empréstimo", pois no painel administrativo a senha pode ser definida logo no registro.'
];

?>
<link rel="stylesheet" href="../css/ajudaadm.css">
    <div class="ajuda" id="principal">
        <div class="ajudaContent">
            <h2 class="textcenter dashboardTitle" id="title">
                Ajuda
            </h2>
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
    <script>
        function imprime()
        {
            var tabs = document.getElementsByClassName("tab");
            var num = tabs.length;

            for(var i = 0; i < num; i++)
            {
                var id = "chck" + i;
                var input = document.getElementById(id);
                input.checked = true;
            }

            var options = document.getElementById("optionsContent");
            var title = document.getElementById("title");
            var original = title.innerText;
            var principal = document.getElementById("principal");

            options.style.display = "none";
            title.style = "font-size: 1.5em; padding: 10px";
            title.innerText += " Adm. Apolo";
            principal.style.width = "70%";

            print();

            options.style = null;
            title.style = null;
            title.innerText = original;
            principal.style = null;

            for(var i = 0; i < num; i++)
            {
                var id = "chck" + i;
                var input = document.getElementById(id);
                input.checked = false;
            }
        }
    </script>
</html>