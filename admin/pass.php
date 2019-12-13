
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

    $newsenha = "";
    $consenha = "";

    if(isset($_POST['subCadPass'])) {
        $id_user = 0;
        $success = false;
        try 
        {
            include "../config/php/connect.php";

            $newsenha = (mysqli_real_escape_string($conn, $_POST['senha']));
            $consenha = (mysqli_real_escape_string($conn, $_POST['consenha']));

            if($newsenha != $consenha)
            {
                ?>
                <script>
                    alert("As senhas não coincidem!");
                </script>
                <?php
            }
            else
            {
                $newsenha = md5($newsenha);

                $sql = "UPDATE user SET
                senha = '$newsenha'
                WHERE login = '$login'";

                $res = mysqli_query($conn, $sql);
                                
                if(mysqli_affected_rows($conn) > 0){
                    $_SESSION['pass'] = $consenha;
                    echo '<script>
                    alert("Senha alterada com sucesso!");
                        changeParentLocation("main.php");
                    </script>';
                } else {
                    // echo 'b';
                    $erro = mysqli_error($conn);
                    echo '<script>
                    alert("Falha ao alterar a senha!\nMais detalhes: '.$erro.'");
                    </script>';
                }
            }

        } catch (Exception $e)
        {

        }
    }
    

?>
<link rel="stylesheet" href="../css/pass.css">

    <h2 class="textcenter dashboardTitle" >
        Alterar senha
    </h2>

    <div class="passchange">
        <form onsubmit="verificarSenha()" method="post" id="changepass">
            <label for="senha">Nova senha</label><br>
            <input id="senha" type="password" class="form-control" name="senha" required value="<?php if($newsenha!="") echo $newsenha; ?>">
              <span toggle="#senha" class="field-icon toggle-password"></span>
            <br><br>
            <label for="consenha">Confirmar nova senha</label><br>
            <input id="consenha" type="password" class="form-control" name="consenha" required value="<?php if($consenha!="") echo $consenha; ?>">
              <span toggle="#consenha" class="field-icon toggle-password"></span>
            <br><br>
            <button type="submit" name="subCadPass" class="cadBtn">Alterar</button>
        </form>
    </div>


    <script src="../js/main.js"></script>
    <script>

        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");

            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");

            } else {
                input.attr("type", "password");
            }
        });
    </script>
</html>