<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<?php
    include "head.php";
    include "login.php";

    $sel_title = "usuários";

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
        $filter = 'bloqueado = 0';
    }

    if(isset($_GET['bloq']))
    {
        $idexc = $_GET['bloq'];

        if($idexc == 0)
        {
            echo "<script>
            alert('Você não pode bloquear usuário admin, pois ele é o usuário raíz do sistema.');
            changeParentLocation('main.php?sel=u');
            </script>";
        }
        else
        {
            $nexc = 0;

            include '../config/php/connect.php';
            
            $sql = "SELECT bloqueado, login FROM user WHERE id_user = $idexc";

            $res = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0)
            {
                $activelogin = $_SESSION['login'];
                
                $row = mysqli_fetch_array($res, MYSQLI_NUM);
                $nexc = $row[0];

                $exclogin = $row[1];
                
                if($activelogin == $exclogin)
                {
                    echo "<script>
                    alert('Você não pode bloquear seu próprio usuário, $activelogin.');
                    changeParentLocation('main.php?sel=u');
                    </script>";
                }
                else
                {
                    if($nexc == 0) $nexc = 1; else $nexc = 0;

                    $sql = "UPDATE user SET bloqueado = $nexc WHERE id_user = $idexc";

                    $res = mysqli_query($conn, $sql);
                }
            }
            

        }
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
    <h2 class="textcenter dashboardTitle" ><a href="?sel=<?php echo $selected; ?>" class="a">Usuários</a></h2>
    
    <div class="contentaddnew" id="optionsContent">
        <div id="options">
            <a href="<?php echo $printurl ?>" class="a">Imprimir</a>
            |
            <a href="csv.php?ent=u" class="a">Baixar planilha</a>
            |
            <a onclick="changeParentLocation('cadusu.php')" class="a">Adicionar novo</a>
        </div>
    </div>
    <div class="contentaddnew">
        <div id="balanco" title="De acordo com os filtros selecionados...">
            N° de usuários:
            <span class="blue" id="numero">
            </span>
        </div>
    </div>

    <script>
        var lblNum = document.getElementById("numero");
    </script>

    <div class="admSearch">
        <form action="" method="get" class="frmSearch" id="searchForm">
            <label for="f_exc" id="lbl_f_exc">Ocultar usuários bloqueados?</label>&nbsp;
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
            <th>Nome</th>
            <th>RA</th>
            <th>Login</th>
            <th>Tipo</th>
            <th>Admin?</th>
            <th>Bloqueado?</th>
            <th id="actions_h">Ações</th>
        </tr>
        
        <?php 
            try {
                include "../config/php/connect.php";
                
                $page = 1;
                
                if(isset($_GET['page']))
                    $page = $_GET['page'];

                $sql = "SELECT id_user, nome, ra, login, tipo, bloqueado, admin
                FROM user";

                $sql_count = "SELECT COUNT(*) FROM user";

                $sql_bloq = "SELECT COUNT(*) FROM user WHERE bloqueado = 0";
                
                $search = (isset($_GET['search'])) ? ($_GET['search']) : '';

                if($search != ''){  
                    $search = ($_GET['search']);
                    $search = strtolower($search);
                    $search_str = " WHERE (lower(nome) LIKE '%$search%' OR lower(login) LIKE '%$search%' 
                    OR lower(tipo) LIKE '%$search%' OR ra LIKE '%$search%') AND $filter";
                }
                else {
                    $search_str = " WHERE $filter";
                }

                $sql .= $search_str;
                $sql_count .= $search_str;

                $sql .= " ORDER BY nome ASC";

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
                        $id = $row['id_user'];
                        $nome = ($row['nome']);
                        $ra = ($row['ra'] != null) ? ($row['ra']) : "-";
                        $login = ($row['login']);
                        $tipo = ($row['tipo']);
                        $admin = $row['admin'];
                        $bloq = ($row['bloqueado']);
                        
                        $status = ($bloq) ? "Sim" : "Não";

                        ?>
                        <tr>
                            <td><?php echo $nome; ?></td>
                            <td><?php echo $ra; ?></td>
                            <td><?php echo $login; ?></td>
                            <td><?php echo $tipo; ?></td>
                            <td><?php echo ($admin) ? "Sim" : "Não"; ?></td>
                            <td class="<?php echo ($bloq) ? "red" : "green"; ?>"><?php echo $status; ?></td>
                            <td class="action">
                                <a onclick="changeParentLocation('visusu.php?id=<?php echo $id ?>')" target="_blank" class="a">Visualizar</a> |
                                <a href="<?php echo $current_url; echo ($query) ? "&" : "?"; ?>bloq=<?php if($login != 'admin') echo $id; else echo '0'; ?>" class="a"><?php echo ($bloq) ? 'Desbloquear' : 'Bloquear'; ?></a>
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
            <th>Nome</th>
            <th>RA</th>
            <th>Login</th>
            <th>Tipo</th>
            <th>Admin?</th>
            <th>Bloqueado?</th>
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
                    echo '<li class="paginationLi"><a href="'; echo $current_url; echo ($query) ? "&" : "?"; echo '&page='.$ival.'" class="a';
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
                    <a href="<?php echo $current_url; echo ($query) ? "&" : "?"; echo "page=$proximo"; ?>" class="a">Próximo</a>    
                </li>
                <?php
                if($page_count >= $init + 11){
                    ?>
                    <li class="paginationLi">
                        <a href="<?php echo $current_url; echo ($query) ? "&" : "?"; echo "page=$page_count"; ?>" class="a">Último</a>    
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

</html>