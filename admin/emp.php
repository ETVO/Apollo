
<?php
    include "head.php";
    include "login.php";
    $sel_title = "empréstimos";

    $page = 1;
                
    if(isset($_GET['page']))
        $page = $_GET['page'];


    $search = '';

    if(isset($_GET['search']))
    {   
        $search = utf8_decode($_GET['search']);
    }
    ?>
    <h2 class="textcenter dashboardTitle" >
        <a onclick="changeParentLocation('main.php?sel=e')" class="a" title="Recarregar">
            Empréstimos
        </a>
    </h2>

    <div class="admSearch">
        <form action="" method="get" class="frmSearch">
            <input type="hidden" name="sel" value="<?php echo $selected; ?>">
            <input type="hidden" name="filter_dev" value="<?php echo $filter_dev; ?>">
            <input type="search" name="search" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"'; ?>>
            <input type="submit"  value="Pesquisar <?php echo $sel_title ?>" class="frmInput">
        </form>
    </div>

    <table class="admTable">
        <tr class="header">
            <th>Livro</th>
            <th>Usuário</th>
            <!-- <th>Contato</th> -->
            <th>Autorizado por</th>
            <th>Emprestado em</th>
            <th>Devolução prevista</th>
            <th><a href="<?php 
            $echo_filter_dev = $filter_dev + 1; 
            if($echo_filter_dev > 3) 
                $echo_filter_dev = 1; 
            echo "?sel=$selected&search=$search&page=1&filter_dev=$echo_filter_dev";?>"
            
            class="a <?php 
            if($filter_dev==1) echo 'b1'; 
            else if($filter_dev==2) echo 'b2'; 
            else echo 'b3';?>" 
            
            title="<?php 
            if($filter_dev == 1) echo 'Mostrando apenas empréstimos não devolvidos.'; 
            else if($filter_dev == 2) echo 'Mostrando apenas empréstimos devolvidos.'; 
            else echo 'Mostrando todos os empréstimos'; ?>">Devolvido</a></th>
            <th>Devolução</th>
            <th></th>
            <th></th>
        </tr>
        
        <?php 
            try {
                include "../config/php/connect.php";
                

                $sql = "SELECT id_emprestimo, 
                                l.id_livro AS id_livro, 
                                l.titulo AS livro, 
                                u.nome AS usuario, 
                                u.email AS contato, 
                                a.nome AS admin, 
                                data_emp, 
                                data_prev_dev, 
                                devolvido, 
                                data_dev 
                FROM emprestimo AS e 
                    INNER JOIN livro AS l ON e.id_livro = l.id_livro 
                    INNER JOIN user AS a ON e.id_admin = a.id_user OR e.id_admin = 0
                    INNER JOIN user AS u ON e.id_user = u.id_user OR e.id_user = 0";

                // $sql_count = "SELECT COUNT(*) FROM emprestimo AS e 
                // INNER JOIN livro AS l ON e.id_livro = l.id_livro 
                // INNER JOIN user AS a ON e.id_admin = a.id_user";

                $sql_count = "SELECT COUNT(*) FROM emprestimo";
                
                $search = '';
                
                if(isset($_GET['search'])){   
                    $search = utf8_decode($_GET['search']);
                    $search = strtolower($search);
                    $search_str = " WHERE (lower(livro) LIKE '%$search%' OR lower(usuario) LIKE '%$search%' 
                    OR lower(contato) LIKE '%$search%' OR lower(admin) LIKE '%$search%' 
                    OR data_emp LIKE '%$search%' OR data_prev_dev LIKE '%$search%' 
                    OR data_dev LIKE '%$search%') AND $filter";
                }
                else {
                    $search_str = " WHERE $filter";
                }
                
                $sql .= $search_str;
                $sql_count .= $search_str;

                $sql .= " ORDER BY e.devolvido ASC";

                $res = mysqli_query($conn, $sql_count);

                $row = mysqli_fetch_array($res, MYSQLI_NUM);

                $count = $row[0];

                $limit = 10;

                $sql .= " LIMIT $limit";
                
                if($page > 1){
                    $offset = $page-1;
                    $offset = $offset * $limit;
                    $sql .= " OFFSET $offset";
                }

                $res = mysqli_query($conn, $sql);
                
                // echo $sql;

                if(mysqli_affected_rows($conn) > 0){
                    while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                    { 

                        $id = $row['id_emprestimo'];
                        $id_livro = $row['id_livro'];
                        $titulo = utf8_encode($row['titulo']);
                        $nome = utf8_encode($row['nome']);
                        $nome = flname($nome, ' ');  
                        $telefone = utf8_encode($row['telefone']);
                        $admin = utf8_encode($row['admin']);
                        $admin = flname($admin, ' ');
                        $data_emp = utf8_encode($row['data_emp']);
                        $data_prev_dev = utf8_encode($row['data_prev_dev']);
                        $devolvido = utf8_encode($row['devolvido']);
                        $data_dev = utf8_encode($row['data_dev']);

                        $atrasado = false;

                        if(strtotime(date('Y-m-d')) > strtotime($data_prev_dev) && !$devolvido) $atrasado = true;

                        ?>
                        <tr <?php if($atrasado) echo 'title="Este empréstimo está atrasado!"'; else if($devolvido) echo 'title="Este empréstimo já foi devolvido!"'; ?>>
                            <td><?php echo $titulo; ?></td>
                            <td><?php echo $nome; ?></td>
                            <td><?php echo $admin; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($data_emp)); ?></td>
                            <td <?php if($atrasado) echo 'class="red"'; ?>><?php echo date('d/m/Y', strtotime($data_prev_dev));?></td>
                            <td <?php if($devolvido) echo 'class="green"'; else echo ''; ?>><?php if($devolvido) echo 'Sim'; else echo 'Não'; ?></td>
                            <td><?php if($data_dev == null) echo '-'; else echo date('d/m/Y', strtotime($data_dev)); ?></td>
                            <td class=""><a href="visemp.php?id=<?php echo $id ?>" target="_blank" class="admVisualizar">Visualizar</a></td>
                            <td class=""><a onclick="<?php if(!($atrasado || $devolvido))
                            echo "swal({
                                title: 'Atenção!',
                                text:'Deseja realmente devolver o livro \'$titulo\'?',
                                icon: 'warning',
                                buttons: true,
                                dangerMode: true,
                            }).then((willDelete) =>{
                                if(willDelete){
                                    window.location.href = '?sel=$selected&page=$page&filter_dev=$filter_dev&search=$search&dev=$id_livro&devemp=$id';
                                }
                            });"; ?>" class="<?php if($atrasado || $devolvido) echo 'admDevolverDisabled'; else echo 'admDevolver';?>">Devolver</a></td>
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
            <th>Livro</th>
            <th>Usuário</th>
            <!-- <th>Contato</th> -->
            <th>Autorizado por</th>
            <th>Emprestado em</th>
            <th>Devolução prevista</th>
            <th>Devolvido</th>
            <th>Devolução</th>
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
                        <a href="<?php echo "?sel=$selected&page=1&search=$search&filter_dev=$filter_dev"?>" class="a">Primeiro</a>    
                    </li>
                    <?php
                }
                $anterior = $page - 1;
                ?>
                <li class="paginationLi">
                    <a href="<?php echo "?sel=$selected&page=$anterior&search=$search&filter_dev=$filter_dev"; ?>" class="a">Anterior</a>    
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
                    echo '<li class="paginationLi"><a href="?sel='.$selected.'&page='.$ival.'&search='.$search.'&filter_dev='.$filter_dev.'" class="a';
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
                    <a href="<?php echo "?sel=$selected&page=$proximo&search=$search&filter_dev=$filter_dev"?>" class="a">Próximo</a>    
                </li>
                <?php
                if($page_count >= $init + 11){
                    ?>
                    <li class="paginationLi">
                        <a href="<?php echo "?sel=$selected&page=$page_count&search=$search&filter_dev=$filter_dev"?>" class="a">Último</a>    
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
    <?php
    }