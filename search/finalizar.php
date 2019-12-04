<?php
    session_start();
    
    $rol = array();

    $login_adm = false;

    if(isset($_SESSION['rol']))// rol de livros para empréstimo
    {
        $rol = $_SESSION['rol'];
    }

    if(isset($_GET['s']))
    {
        $search = $_GET['s'];
    }

    if(isset($_GET['rem']))
    {
        $id_exc = $_GET['rem'];
        if (($key = array_search($id_exc, $rol)) !== false) {
            unset($rol[$key]);
            echo $key;
        }
        
        $_SESSION['rol'] = $rol;

        header("Location: ?s=$search");
    }

    if(count($rol) == 0)
        header("Location: index.php?search=$search");


    date_default_timezone_set("America/Sao_Paulo");

    include '../config/php/util.php';

    $dias_dev = getPrazo();

    $data_dev = addDays(strtotime(date('Y-m-d')), $dias_dev);

    $data_dev = date('Y-m-d', $data_dev);

    
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Empréstimo - Apolo</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/final.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>
    <a href="index.php?search=<?php echo $search; ?>" class="a voltaInicio">Voltar à Pesquisa</a>
    <div class="textcenter">
        <h4><a href=".." style="text-decoration:none">Apolo</a></h4>
        <h2>Finalizar Empréstimo</h2>
    </div>
    <hr>
    <div class="searchResults">
        <div class="searchContent">
            <div class="rolTitle">
                <h3>Livros escolhidos</h3>
            </div>
            <table class="searchTable">
                <?php
                try {
                    include "../config/php/connect.php";
                    ?>
                    <tr>
                        <th>Livro</th>
                        <th>Gênero</th>
                        <th>Editora</th>
                        <th>Ano</th>
                        <th>Edição</th>
                        <th></th>
                    </tr>
                    <?php
                    foreach($rol as &$id_livro){
                        $sql = "SELECT id_livro, titulo, genero, autor, editora, ano, edicao, disponivel
                        FROM livro WHERE id_livro=$id_livro";
                
                        if($res = mysqli_query($conn, $sql))
                        {
                            $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                            $id = $row['id_livro'];
                            $titulo = utf8_encode($row['titulo']);
                            $genero = utf8_encode($row['genero']);
                            $autor = utf8_encode($row['autor']);

                            $a_autor = explode("; ", $autor);

                            if(sizeof($a_autor) > 2)
                            {
                                $autor = $a_autor[0]."; ".$a_autor[1]."; et al.";
                            }

                            $editora = utf8_encode($row['editora']);
                            $ano = utf8_encode($row['ano']);
                            $edicao = utf8_encode($row['edicao']);
                            $disp = utf8_encode($row['disponivel']);
                            
                            ?>
                            <tr>
                                <td class="main"><?php echo $titulo; ?><br><b><?php echo $autor; ?></b></td>
                                <td><?php echo $genero; ?></td>
                                <td><?php echo $editora; ?></td>
                                <td><?php echo $ano; ?></td>
                                <td><?php echo $edicao."ᵃ"; ?></td>
                                <td><a href="?s=<?php echo $search; ?>&rem=<?php echo $id; ?>" class="a remFinal" title="Remover '<?php echo $titulo; ?>'">Remover</a></td>
                            </tr>
                            <?php
                        }
                        else    
                            header("..");
                    }

                                                
                }
                catch (Exception $e) {
                    header("..");
                }
                ?>
            </table>
        </div>
            
    </div>
    <br>
    
    <div class="info">
        <div class="infoTitle">
            <h3>Qual usuário fará o empréstimo?</h3>
        </div>
        <form action="autorizar.php" method="post" class="infoFrm">
            <div class="infoField">
                <label for="users">Selecione o usuário</label>
                <select name="id_emp" id="users" required>
                    <!-- <option value="" disabled selected>-- Selecione uma opção --</option> -->
                    <?php

                    $sql = "SELECT id_user, nome, ra, tipo, login FROM user WHERE bloqueado = 0 AND login != 'admin'";

                    $res = mysqli_query($conn, $sql);

                    if(mysqli_affected_rows($conn) > 0){
                        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                        {
                            $id_emp = utf8_encode($row['id_user']);
                            $nome = utf8_encode($row['nome']);
                            $ra = utf8_encode($row['ra']);
                            $tipo = utf8_encode($row['tipo']);
                            $rastr = ($tipo == "Aluno") ? "(RA $ra)" : "";
                            $login = utf8_encode($row['login']);

                            ?>
                            <option value="<?php echo $id_emp ?>"><?php echo "$nome ($login) - $tipo $rastr"; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <div class="newuser">
                    <a href="newuser.php?s=<?php echo $search; ?>" class="a">Adicionar novo</a>
                </div>
            </div>
            <div class="infoField">
                <label for="data_dev">Data de devolução</label>
                <input type="date" name="" id="data_dev" disabled value="<?php echo $data_dev; ?>" title="Apenas um administrador pode editar o prazo de devolução. (Painel > Configurações)">
            </div>
            <div class="infoBtns">
                <input type="hidden" name='s' value='<?php echo $search; ?>'>
                <input type="hidden" name='data_dev' value='<?php echo $data_dev; ?>'>
                <button type="submit" name="subInfo" class="infoBtn">Continuar</button>
            </div>
        </form>
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
</html>