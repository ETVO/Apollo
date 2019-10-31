<?php
    include "head.php";
    include "login.php";

    $sel_title = "administradores";
    ?>
    <h2 class="textcenter dashboardTitle" ><a href="?sel=<?php echo $selected; ?>" class="a">Administradores</a></h2>
    <a href="cadusu.php" class="addNew textcenter a" target="_blank">Adicionar novo</a>

    <div class="admSearch">
        <form action="" method="get" class="frmSearch">
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
            <!-- <th>Turma</th> -->
            <th>Tipo</th>
            <th>Série</th>
            <th>Bloqueado</th>
            <th></th>
            <th></th>
        </tr>
        
        <?php 
            try {
                include "../config/php/connect.php";
                
                $page = 1;
                
                if(isset($_GET['page']))
                    $page = $_GET['page'];

                $sql = "SELECT id_user, nome, ra, login, tipo, ano, bloqueado
                FROM user";

                $sql_count = "SELECT COUNT(*) FROM user";

                $sql_bloq = "SELECT COUNT(*) FROM user WHERE bloqueado = 0";
                
                $search = '';
                
                if(isset($_GET['search'])){   
                    $search = utf8_decode($_GET['search']);
                    $search = strtolower($search);
                    $search_str = " WHERE (lower(nome) LIKE '%$search%' OR lower(login) LIKE '%$search%' 
                    OR ano LIKE '%$search%' OR lower(tipo) LIKE '%$search%' OR ra LIKE '%$search%')";
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
                        $ra = utf8_encode($row['ra']);
                        $login = utf8_encode($row['login']);
                        // $turma = utf8_encode($row['turma']);
                        $tipo = utf8_encode($row['tipo']);
                        $ano = utf8_encode($row['ano']);
                        $bloq = utf8_encode($row['bloqueado']);

                        $popup = "bloquear";

                        if($bloq)
                        {
                            $popup = "desbloquear";
                            $bloq = 'Sim';
                        }
                        else
                            $bloq = 'Não';

                        ?>
                        <tr>
                            <td><?php echo $nome; ?></td>
                            <td><?php if($ra != null) echo $ra; else echo "-"; ?></td>
                            <td><?php echo $login; ?></td>
                            <!-- <td><?php echo $turma; ?></td> -->
                            <td><?php echo $tipo; ?></td>
                            <td><?php if($ano != null) echo $ano."º"; else echo '-'; ?></td>
                            <td><?php echo $bloq; ?></td>
                            <td class=""><a href="visusu.php?id=<?php echo $id ?>" target="_blank" class="admVisualizar">Visualizar</a></td>
                            <td class=""><a <?php if($count <= 1 || ($bloqs == 1 && $bloq == 'Não')) echo 'disabled';?> onclick="<?php if($count > 1)
                            echo "swal({
                                title: 'Atenção!',
                                text:'Deseja realmente $popup o administrador \'$nome\'?',
                                icon: 'warning',
                                buttons: true,
                                dangerMode: true,
                            }).then((willDelete) =>{
                                if(willDelete){
                                    window.location.href = '?sel=$selected&exc=$id';
                                }
                            });"; ?>" class="admExcluir"><?php echo ucfirst($popup)?></a></td>
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
            <th>Nome</th>
            <th>RA</th>
            <th>Login</th>
            <!-- <th>Turma</th> -->
            <th>Tipo</th>
            <th>Série</th>
            <th>Bloqueado</th>
            <th></th>
            <th></th>
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