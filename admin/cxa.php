
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
        
        $sql = "SELECT excluido FROM caixa WHERE id = $idexc";

        $res = mysqli_query($conn, $sql);

        if(mysqli_affected_rows($conn) > 0)
        {
            $row = mysqli_fetch_array($res, MYSQLI_NUM);
            $nexc = $row[0];
        }
        
        if($nexc == 0) $nexc = 1; else $nexc = 0;

        $sql = "UPDATE caixa SET excluido = $nexc WHERE id = $idexc";

        $res = mysqli_query($conn, $sql);
    }

    try {
        include "../config/php/connect.php";
        
        $sql = "SELECT SUM(valor) AS soma
                FROM caixa WHERE tipo = 'e'";

        $res = mysqli_query($conn, $sql);
        
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

        $e = $row['soma'];

        $sql = "SELECT SUM(valor) AS soma
                FROM caixa WHERE tipo = 's'";

        $res = mysqli_query($conn, $sql);
        
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

        $s = $row['soma'];

        $total = $e - $s;

        if($total > 0) $symb = "+";
            
    } catch (Exception $e)
    {

    }

    $print = false;
    if(isset($_GET['print']))
    {
        $print = true;
    }

    $url = $_SERVER['REQUEST_URI'];

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
    <h2 class="textcenter dashboardTitle" >
        <a onclick="changeParentLocation('main.php?sel=c')" class="a">
            Registros de Caixa
        </a>
    </h2>
    <div class="contentaddnew" id="optionsContent">
        <div id="options">
            <a href="<?php echo $printurl ?>" class="a">Imprimir</a>
            |
            <a onclick="changeParentLocation('cadcxa.php')" class="a">Adicionar novo</a>
        </div>
    </div>
    <div class="contentaddnew">
        <div id="balanco">
            Balanço (R$):
            <span class="<?php echo ($total >= 0) ? "green" : "red";?>">
                <?php echo $symb.number_format((float)$total, 2, ',', ''); ?>
            </span>
        </div>
    </div>

    <div class="admSearch">
        <form action="" method="get" class="frmSearch" id="frmSearch">
            <label for="f_exc" id="lbl_f_exc">Ocultar registros excluídos?</label>&nbsp;
            <input type="checkbox" name="f_exc" id="f_exc" onChange="this.form.submit()" <?php if($f_exc) echo "checked"; ?>>
            &nbsp;&nbsp;
            <input type="hidden" name="sel" value="<?php echo $selected; ?>">
            <input type="hidden" name="first" value="0">
            <input type="search" id="search" name="search" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"'; ?>>
            <input type="submit" id="submit" value="Pesquisar <?php echo $sel_title ?>" class="frmInput">
        </form>
    </div>

    <table class="admTable">
        <tr class="header">
            <th>Tipo</th>
            <th>Valor (R$)</th>
            <th>Descrição</th>
            <th>Data</th>
            <th id="actions_h">Ações</th>
        </tr>
        
        <?php 
            try {
                include "../config/php/connect.php";
                
                $page = 1;
                
                if(isset($_GET['page']))
                    $page = $_GET['page'];


                $sql = "SELECT id, 
                                tipo,
                                valor, 
                                descricao, 
                                data, 
                                excluido
                        FROM caixa";

                $sql_count = "SELECT COUNT(*) FROM caixa";
                
                $search = (isset($_GET['search'])) ? ($_GET['search']) : '';

                if($search != ''){       
                    $search = utf8_decode($_GET['search']);
                    $search = strtolower($search);  
                    $search_str = " WHERE (lower(descricao) LIKE '%$search%'
                    OR lower(tipo) LIKE '%$search%' OR lower(data) LIKE '%$search%' 
                    OR valor LIKE '%$search%') AND $filter";
                }
                else {
                    $search_str = " WHERE $filter";
                }
                
                $sql .= $search_str;
                $sql_count .= $search_str;

                $sql .= " ORDER BY data ASC";

                $res = mysqli_query($conn, $sql_count);

                $row = mysqli_fetch_array($res, MYSQLI_NUM);

                $count = $row[0];

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
                        /*
                            id, 
                            tipo,
                            valor, 
                            descricao, 
                            data, 
                            excluido
                        */
                        $id = $row['id'];
                        
                        $tipo = utf8_encode($row['tipo']);
                        
                        $valor = utf8_encode($row['valor']);
                        
                        $descricao = utf8_encode($row['descricao']);
                        
                        $data = utf8_encode($row['data']);

                        $exc = utf8_encode($row['excluido']);

                        ?>
                        <tr <?php if($exc) echo 'class = "exc"'; ?>>
                            <td><?php echo ($tipo == 'e') ? "Entrada" : "Saída"; ?></td>
                            <td class="<?php echo ($valor > 0) ? "green" : "red"; ?>"><?php echo $valor; ?></td>
                            <td class="ellipsis"><?php echo $descricao; ?></td>
                            <td><?php echo $data; ?></td>
                            <td class="action">
                                <a href="?exc=<?php echo $id ?>" class="a"><?php echo ($exc) ? 'Restaurar' : 'Excluir'; ?></a>
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
            <th>Tipo</th>
            <th>Valor (R$)</th>
            <th>Descrição</th>
            <th>Data</th>
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
                        <a href="<?php echo "?sel=$selected&page=1&search=$search"?>" class="a">Primeiro</a>    
                    </li>
                    <?php
                }
                $anterior = $page - 1;
                ?>
                <li class="paginationLi">
                    <a href="<?php echo "?sel=$selected&page=$anterior&search=$search"; ?>" class="a">Anterior</a>    
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
                    echo '<li class="paginationLi"><a href="?sel='.$selected.'&page='.$ival.'&search='.$search.'" class="a';
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
                    <a href="<?php echo "?sel=$selected&page=$proximo&search=$search"?>" class="a">Próximo</a>    
                </li>
                <?php
                if($page_count >= $init + 11){
                    ?>
                    <li class="paginationLi">
                        <a href="<?php echo "?sel=$selected&page=$page_count&search=$search"?>" class="a">Último</a>    
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