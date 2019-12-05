<?php
    session_start();
    if(isset($_GET['search']))
    {
        $search = $_GET['search'];
    }
    else {
        header("Location: ..");
    }
    $page = 1;

    $rol = array();

    if(isset($_SESSION['rol']))// rol de livros para empréstimo
    {
        $rol = $_SESSION['rol'];
    }

    $rolcount = count($rol);

    if(isset($_GET['emp']))
    {
        if($rolcount <= 4){
            $id_emp = $_GET['emp'];

            include '../config/php/connect.php';

            $sql = "SELECT disponivel FROM livro WHERE id_livro = $id_emp";

            $res = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0)
            {
                $row = mysqli_fetch_array($res, MYSQLI_NUM);

                $disponivel = $row[0];

                if(!$disponivel)
                    header("Location: ?search=$search&page=$page");
            }
            else
                header("Location: ?search=$search&page=$page");

            if(!in_array($id_emp, $rol))
                array_push($rol, $id_emp);
            
            $_SESSION['rol'] = $rol;

            $rolcount = count($rol);
            
            $search = $_GET['search'];
            $page = $_GET['page'];

            header("Location: ?search=$search&page=$page");
        }
        else
            header("Location: ?search=$search&page=$page&max=true");
    }
    
    if(isset($_GET['exc']))
    {
        $id_exc = $_GET['exc'];
        if (($key = array_search($id_exc, $rol)) !== false) {
            unset($rol[$key]);
        }
        
        $_SESSION['rol'] = $rol;

        $rolcount = count($rol);
        
        $search = $_GET['search'];
        $page = $_GET['page'];

        header("Location: ?search=$search&page=$page");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title><?php echo $search; ?> - Pesquisa Apolo</title>
        <link rel="stylesheet" href="../css/search.css">
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" href="../css/main.css">
        <script src="../config/js/sweetalert.min.js"></script>
        <link rel="shortcut icon" href="../favicon.ico"> 
    </head>

    <body>
        <?php
        if(isset($_GET['max']))
        {
            $max = $_GET['max'];
            if($max)
            {
                echo "<script>
                    swal({
                        title: 'Atenção!',
                        text: 'Você só pode emprestar até 5 livros!',
                        icon: 'error',
                    });
                </script>";
            }
        }
        ?>
        
        <div class="generalSearch">
            <div class="searchHead">
                <div class="presentContent">
                    <div class="presentTitle">
                        <h2><a href=".." class="homeLink" title="Voltar ao Início">Apolo</a></h2>
                    </div>
                </div>
                <div class="search">
                    <div class="searchBar">
                        <form action="" class="frmSearch" id="searchForm">
                            <input type="search" name="search" value="<?php echo $search; ?>"  class="searchField" required>
                            <input type="submit" value="Pesquisar" class="searchBtn">
                        </form>
                    </div>
                </div>
                <div class="emprestimo">
                    <div class="emprestimoContent">
                        <a <?php if($rolcount >= 1) echo 'href="finalizar.php?s='.$search.'" class="emprestimoFinalizar" title="Clique aqui para finalizar o empréstimo com os livros selecionados"'; else echo 'class="emprestimoFinalizar empDisabled" title="Selecione algum livro para finalizar o empréstimo!"'; ?>>Finalizar Empréstimo</a>
                        <a <?php if($rolcount >= 1) echo 'href="finalizar.php?s='.$search.'" class="a" title="Clique para ver os livros selecionados"'; else echo 'style="cursor: default"'; ?>><?php echo "$rolcount livro(s) selecionado(s)"; ?></a>
                    </div>
                </div>              
            </div>

            <div class="content">
                
            </div>
            
            <div class="searchResults">
                <div class="searchContent">
                    <table class="searchTable">

                        <?php
                        if(isset($_GET['search']))
                        {
                            $search = utf8_decode($_GET['search']);
                            $or_search = $search;
                            try {
                                include "../config/php/connect.php";

                                $search = strtolower($search);

                                $page = 1;
                                
                                if(isset($_GET['page']))
                                    $page = $_GET['page'];

                                $sql = "SELECT id_livro, codigo, titulo, genero, autor, editora, ano, edicao, qtde, disponivel, excluido FROM livro";
                                $sql_count = "SELECT COUNT(*) FROM livro";
                                
                                $search_str = " WHERE (lower(codigo) LIKE '%$search%' OR lower(titulo) LIKE '%$search%' OR lower(genero) LIKE '%$search%' 
                                OR lower(autor) LIKE '%$search%' OR lower(editora) LIKE '%$search%' 
                                OR ano LIKE '%$search%' OR edicao LIKE '%$search%') AND excluido = 0 ORDER BY titulo ASC";

                                $sql .= $search_str;

                                $sql_count .= $search_str;

                                $res = mysqli_query($conn, $sql_count);

                                $row = mysqli_fetch_array($res, MYSQLI_NUM);

                                $count = $row[0];

                                $limit = 20;

                                $sql .= " LIMIT $limit";

                                if($page > 1){
                                    $offset = $page-1;
                                    $offset = $offset * $limit;
                                    $sql .= " OFFSET $offset";
                                }

                                $res = mysqli_query($conn, $sql);

                                if(mysqli_affected_rows($conn) > 0){
                                    ?>
                                    <tr>
                                        <th></th>
                                        <th>Código</th>
                                        <th>Livro</th>
                                        <th>Editora</th>
                                        <th>Ano</th>
                                        <th>Edição</th>
                                        <th>Disponível</th>
                                        <th>Ações</th>
                                    </tr>
                                    <?php
                                    while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                                    {
                                        $id = $row['id_livro'];
                                        $codigo = utf8_encode($row['codigo']);
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
                                        $qtde = utf8_encode($row['qtde']);
                                        $disp = utf8_encode($row['disponivel']);
                                        $exc = utf8_encode($row['excluido']);

                                        if($exc) $disp = false;

                                        // if($disp)
                                        //     $disp = 'Sim';
                                        // else
                                        //     $disp = 'Não';

                                        ?>
                                        <tr class="<?php if(!$disp) echo 'trIndisp'; ?>">
                                            <td><div class="<?php if(!$disp) echo 'xIndisp'; else if(in_array($id, $rol)) echo ' trSelecionado'; else echo 'trNormal';?>"><div class="symb"></div></div></td>
                                            <td title="<?php echo $genero; ?>"><?php echo $codigo; ?></td>
                                            <td>
                                                <div class="doubletd">
                                                    <?php echo $titulo; ?>
                                                    <br>
                                                    <b><?php echo $autor; ?></b>
                                                </div>
                                            </td>
                                            <td><?php echo $editora; ?></td>
                                            <td><?php echo $ano; ?></td>
                                            <td><?php echo $edicao."ᵃ"; ?></td>
                                            <td class="<?php echo ($disp) ? 'green' : 'red'; ?>"><?php if($disp) echo 'Sim ('.$qtde.')'; else echo 'Não';?></td>
                                            <td class="searchEmprestar <?php if(!$disp) echo 'empIndisp'; if(in_array($id, $rol)) echo ' empSelecionado';?>" >
                                                <a href="<?php if($disp){ if(in_array($id, $rol)) echo "?exc=$id&search=$search&page=$page"; else echo "?emp=$id&search=$search&page=$page"; } else echo ""; ?>" class="<?php if(!$disp) echo 'empIndispA'; else if(in_array($id, $rol)) echo ' empSelecionadoA';?>" <?php if(!$disp) echo 'disabled';?> title="<?php if($disp) echo "Emprestar '$titulo'"; else echo 'Livro indisponível!'?>">Emprestar</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }

                                    ?>
                                    
                                    <tr class="searchTableFooter">
                                        <th></th>
                                        <th>Código</th>
                                        <th>Livro</th>
                                        <th>Editora</th>
                                        <th>Ano</th>
                                        <th>Edição</th>
                                        <th>Disponível</th>
                                        <th>Ações</th>
                                    </tr>
                                    <?php
                                }
                                else {
                                    ?>
                                        <tr>
                                            <td colspan="9" class="textcenter">
                                                Nenhum resultado para "<?php echo $or_search; ?>"!
                                            </td>
                                        </tr>
                                    <?php
                                }

                                                            
                            }
                            catch (Exception $e) {
                                ?>
                                    <tr>
                                        <td colspan="9" class="textcenter">
                                                Nenhum resultado para "<?php echo $or_search; ?>"!
                                        </td>
                                    </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>

                <?php
                    if($count > $limit)
                    {
                        $page_count = ceil($count/$limit);
                ?>
                <div class="pagination">
                    <ul class="paginationUl">
                        <?php
                        if($page > 1) {
                            if($page > 6){
                                ?>
                                <li class="paginationLi">
                                    <a href="<?php echo "?page=1&search=$search"?>" class="a">Primeiro</a>    
                                </li>
                                <?php
                            }
                            $anterior = $page - 1;
                            ?>
                            <li class="paginationLi">
                                <a href="<?php echo "?page=$anterior&search=$search"; ?>" class="a">Anterior</a>    
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                        if($page > 6) {
                            $init = $page - 5;
                        }
                        else 
                            $init = 0;
                        
                        if($page_count >= $init + 10){
                            $end = $init + 10;
                        }
                        else {
                            $end = $page_count;
                        }
                        for($i = $init; $i < $end; $i++){
                            $ival = $i +1;
                            echo '<li class="paginationLi"><a href="?page='.$ival.'&search='.$search.'" class="a';
                            if($ival == $page)
                                echo ' pageSelected '; 
                            echo '">'.$ival.'</a></li>';
                        }
                        ?>
                        <?php
                        if($page < $page_count) {
                            $proximo = $page + 1;
                            ?>
                            <li class="paginationLi">
                                <a href="<?php echo "?page=$proximo&search=$search"?>" class="a">Próximo</a>    
                            </li>
                            <?php
                            if($page_count >= $init + 11){
                                ?>
                                <li class="paginationLi">
                                    <a href="<?php echo "?page=$page_count&search=$search"?>" class="a">Último</a>    
                                </li>
                                <?php
                            }
                        }
                    }
                ?>
                    </ul>
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
</html>