<?php
include 'head.php';
include "login.php";

$sel_title = "livros";

$filter = '1 ';
    
$f_exc = true;
$first = 1;

$limit = 15;

if(isset($_GET['first']))
{   
    $first = 0;
}

$f_exc = (!isset($_GET['f_exc']) && $first == 0) ? false : true;

if($f_exc)
{
    $filter = 'excluido = 0';
}

if(isset($_GET['exc']))
{
    $idexc = $_GET['exc'];
    $nexc = 0;

    include '../config/php/connect.php';
    
    $sql = "SELECT excluido FROM livro WHERE id_livro = $idexc";

    $res = mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn) > 0)
    {
        $row = mysqli_fetch_array($res, MYSQLI_NUM);
        $nexc = $row[0];
    }
    
    if($nexc == 0) $nexc = 1; else $nexc = 0;

    $sql = "UPDATE livro SET excluido = $nexc WHERE id_livro = $idexc";

    $res = mysqli_query($conn, $sql);
}

$print = false;
if(isset($_GET['print']))
{
    $print = true;
}

$url = $_SERVER['REQUEST_URI'];
$current_url = $url;

$query = parse_url($url, PHP_URL_QUERY);

if($query)
{
    $printurl = $url."&print=true";
}
else
{
    $printurl = $url."?print=true";
}

?>
    <h2 class="textcenter dashboardTitle">
        <a onclick="changeParentLocation('main.php?sel=l')" class="a" id="title" value="Livros">
            Livros
        </a>
    </h2>
    <div class="contentaddnew" id="optionsContent">
        <div id="options">
            <a href="<?php echo $printurl ?>" class="a">Imprimir</a>
            |
            <a href="csv.php?ent=l" class="a">Baixar planilha</a>
            |
            <a onclick="changeParentLocation('cadliv.php')" class="a">Adicionar novo</a>
        </div>
    </div>
    <div class="contentaddnew">
        <div id="balanco" title="De acordo com os filtros selecionados...">
            N° de livros:
            <span class="blue" id="numero">
            </span>
        </div>
    </div>

    <script>
        var lblNum = document.getElementById("numero");
    </script>

    <div class="admSearch">
        <form action="" method="get" class="frmSearch" id="searchForm" id="frmSearch">
            <label for="f_exc" id="lbl_f_exc">Ocultar livros excluídos?</label>&nbsp;
            <input type="checkbox" name="f_exc" id="f_exc" onChange="submitForm('searchForm')" <?php if($f_exc) echo "checked"; ?>>
            &nbsp;&nbsp;
            <input type="hidden" name="sel" value="<?php echo $selected; ?>">
            <input type="hidden" name="first" value="0">
            <input type="search" id="search" name="search" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"'; ?>>
            <input type="submit" id="submitBtn" value="Pesquisar <?php echo $sel_title ?>" class="frmInput">
        </form>
    </div>

    <table class="admTable">
        <tr class="header">
            <!-- <th>Id</th> -->
            <th>Código</th>
            <th>Título</th>
            <th>Autor(es)</th>
            <th>Editora</th>
            <th>Ano</th>
            <th>Edição</th>
            <th>Disponível</th>
            <th id="actions_h">Ações</th>
        </tr>
        
        <?php 
            try {
                include "../config/php/connect.php";
                
                $page = 1;
                
                if(isset($_GET['page']))
                    $page = $_GET['page'];


                $sql = "SELECT id_livro, 
                                codigo, 
                                titulo, 
                                genero,
                                autor, 
                                editora, 
                                qtde, 
                                ano,
                                edicao,    
                                disponivel, 
                                excluido
                        FROM livro";

                $sql_count = "SELECT COUNT(*) FROM livro";
                
                $search = (isset($_GET['search'])) ? ($_GET['search']) : '';

                if($search != ''){       
                    $search = utf8_decode($_GET['search']);
                    $search = strtolower($search);  
                    $search_str = " WHERE (lower(codigo) LIKE '%$search%'
                    OR lower(titulo) LIKE '%$search%' OR lower(genero) LIKE '%$search%' OR lower(autor) LIKE '%$search%' OR lower(editora) LIKE '%$search%' OR ano LIKE '%$search%' OR edicao LIKE '%$search%') AND $filter";
                }
                else {
                    $search_str = " WHERE $filter";
                }
                
                $sql .= $search_str;
                $sql_count .= $search_str;

                $sql .= " ORDER BY titulo ASC";

                $res = mysqli_query($conn, $sql_count);

                $row = mysqli_fetch_array($res, MYSQLI_NUM);

                $count = $row[0];

                echo "<script>lblNum.innerText = $count + '';</script>";

                if(!$print)
                {
                    $sql .= " LIMIT $limit";
                
                    if($page > 1){
                        $offset = $page-1;
                        $offset = $offset * $limit;
                        $sql .= " OFFSET $offset";
                    }
                }

                $res = mysqli_query($conn, $sql);

                if(mysqli_affected_rows($conn) > 0){
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

                        // if($disp)
                        //     $disp = 'Sim';
                        // else
                        //     $disp = 'Não';

                        ?>
                        <tr <?php if($exc) echo 'class = "exc"'; ?>>
                            <td title="<?php echo $genero; ?>"><?php echo $codigo; ?></td>
                            <td><?php echo $titulo; ?></td>
                            <td><?php echo $autor; ?></td>
                            <td><?php echo $editora; ?></td>
                            <td><?php echo $ano; ?></td>
                            <td><?php echo $edicao."ᵃ"; ?></td>
                            <td <?php echo ($disp && !$exc) ? 'class="green"' : 'class="red"' ;?>><?php if($disp && !$exc) echo 'Sim ('.$qtde.')'; else echo 'Não'; ?></td>
                            <td class="action">
                                <a onclick="changeParentLocation('visliv.php?id=<?php echo $id ?>')" target="_blank" class="a">Visualizar</a>
                                |
                                <a href="<?php echo $current_url; echo ($query) ? "&" : "?"; ?>exc=<?php echo $id ?>" class="a"><?php echo ($exc) ? 'Restaurar' : 'Excluir'; ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                } 
                else {
                    ?>
                        <tr>
                            <td colspan="9" class="textcenter">
                                Não há nenhum registro!
                            </td>
                        </tr>
                    <?php
                }

                mysqli_close($conn);
            } catch (Exception $e) {
                ?>
                    <tr>
                        <td colspan="9" class="textcenter">
                            Não há nenhum registro!
                        </td>
                    </tr>
                <?php
            }

        ?>

        <tr class="footer">
            <!-- <th>Id</th> -->
            <th>Código</th>
            <th>Título</th>
            <th>Autor(es)</th>
            <th>Editora</th>
            <th>Ano</th>
            <th>Edição</th>
            <th>Disponível</th>
            <th id="actions_f">Ações</th>
        </tr>
    </table>

    <?php
    if($count > $limit && !$print){

        $page_count = ceil($count/$limit);
        // $page_count = $count;
        // $page = 20;
    ?>
    <div class="pagination">
        <ul class="paginationUl">
            <?php
            if($page > 1) {
                if($page > 6){
                    ?>
                    <li class="paginationLi">
                        <a href="<?php echo $current_url; echo ($query) ? "&" : "?"; echo "page=$anterior"; ?>" class="a">Primeiro</a>    
                    </li>
                    <?php
                }
                $anterior = $page - 1;
                ?>
                <li class="paginationLi">
                    <a href="<?php echo $current_url; echo ($query) ? "&" : "?"; echo "page=$anterior"; ?>" class="a">Anterior</a>    
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
                // if($ival == $page){
                    echo '<li class="paginationLi"><a href="'.$current_url.'&page='.$ival.'" class="a';
                    if($ival == $page)
                        echo ' pageSelected '; 
                    echo '">'.$ival.'</a></li>';
                // }
                // else
                    // echo '<li class="paginationLi"><a href="?sel=l&page='.$ival.'" class="a">'.$ival.'</a></li>';
                // if($ival == $page + 20){
                //     echo '<li class="paginationLi">...</li>';
                //     $i = $count - 10;
                // }
            }
            ?>
            <?php
            if($page < $page_count) {
                $proximo = $page + 1;
                ?>
                <li class="paginationLi">
                    <a href="<?php echo "$current_url&page=$proximo";?>" class="a">Próximo</a>    
                </li>
                <?php
                if($page_count >= $init + 11){
                    ?>
                    <li class="paginationLi">
                        <a href="<?php echo "$current_url&page=$page_count"?>" class="a">Último</a>    
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
    <?php
    }
    
    if(isset($_GET['print']))
    {
        echo "<script>
            imprimir();
        </script>";
    }
?>

<script src="../js/admin.js"></script>