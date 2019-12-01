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
    $f_exc = false;

    if(isset($_GET['bloq']))
    {
        $idexc = $_GET['bloq'];

        if($idexc == 0)
        {
            echo "<script>
            alert('Você não pode bloquear usuário admin, pois ele é o usuário raíz do sistema.');
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

    if(isset($_GET['f_exc']))
    {
        $f_exc = true;
        $filter = 'bloqueado = 0 ';
    }
?>
    <h2 class="textcenter dashboardTitle" ><a href="?sel=<?php echo $selected; ?>" class="a">Usuários</a></h2>
    <div class="contentaddnew">
        <a onclick="changeParentLocation('cadusu.php')" class="addNew textcenter a">
            Adicionar novo
        </a>
    </div>

    <div class="admSearch">
        <form action="" method="get" class="frmSearch">
            <label for="f_exc" id="lbl_f_exc">Ocultar usuários bloqueados?</label>&nbsp;
            <input type="checkbox" name="f_exc" id="f_exc" onChange="this.form.submit()" <?php if($f_exc) echo "checked"; ?>>
            &nbsp;&nbsp;
            <input type="hidden" name="sel" value="<?php echo $selected; ?>">
            <input type="search" name="search" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"'; ?>>
            <input type="submit"  value="Pesquisar <?php echo $sel_title ?>" class="frmInput">
        </form>
    </div>

    <table class="admTable">
        <tr class="header">
            <th>Nome</th>
            <th>RA</th>
            <th>Login</th>
            <th>Tipo</th>
            <th>Admin</th>
            <th>Bloqueado</th>
            <th>Ações</th>
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
                
                $search = '';
                
                if(isset($_GET['search'])){   
                    $search = utf8_decode($_GET['search']);
                    $search = strtolower($search);
                    $search_str = " WHERE (lower(nome) LIKE '%$search%' OR lower(login) LIKE '%$search%' 
                    OR lower(tipo) LIKE '%$search%' OR ra LIKE '%$search%') AND $filter";
                    $sql .= $search_str;
                    $sql_count .= $search_str;
                }

                $sql .= " ORDER BY nome ASC";

                $res = mysqli_query($conn, $sql_count);

                $row = mysqli_fetch_array($res, MYSQLI_NUM);

                $count = $row[0];
                
                // qtde de admins bloqueados 
                $res = mysqli_query($conn, $sql_bloq);

                $row = mysqli_fetch_array($res, MYSQLI_NUM);

                $bloqs = $row[0];

                $limit = 20;

                $sql .= " LIMIT $limit";
                
                if($page > 1){
                    $offset = $page-1;
                    $offset = $offset * $limit;
                    $sql .= " OFFSET $offset";
                }

                $res = mysqli_query($conn, $sql);

                if(mysqli_affected_rows($conn) > 0){
                    while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                    {
                        $id = $row['id_user'];
                        $nome = utf8_encode($row['nome']);
                        $ra = ($row['ra'] != null) ? utf8_encode($row['ra']) : "-";
                        $login = utf8_encode($row['login']);
                        $tipo = utf8_encode($row['tipo']);
                        $admin = ($row['admin']) ? "Sim" : "Não";
                        $admin = ($row['admin']) ? "Sim" : "Não";
                        $bloq = utf8_encode($row['bloqueado']);
                        
                        $status = ($bloq) ? "Sim" : "Não";

                        ?>
                        <tr>
                            <td><?php echo $nome; ?></td>
                            <td><?php echo $ra; ?></td>
                            <td><?php echo $login; ?></td>
                            <td><?php echo $tipo; ?></td>
                            <td><?php echo $admin; ?></td>
                            <td><?php echo $status; ?></td>
                            <td class="action">
                                <a onclick="changeParentLocation('visusu.php?id=<?php echo $id ?>')" target="_blank" class="a">Visualizar</a>
                                |
                                <a href="?bloq=<?php if($login != 'admin') echo $id; else echo '0'; ?>" class="a"><?php echo ($bloq) ? 'Desbloquear' : 'Bloquear'; ?></a>
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
            <th>Admin</th>
            <th>Bloqueado</th>
            <th>Ações</th>
        </tr>
    </table>

    <?php
    if($count > $limit){

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
?>

<script src="../js/admin.js"></script>

</html>