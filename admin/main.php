<?php
    include "head.php";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Administração - Apolo</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/mainadm.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>    
    <a href="" onclick="" class="a voltaInicio"></a><br>
    <a href="index.php" class="a voltaInicio">Voltar ao Início</a>
    <div class="textcenter">
        <h4>Apolo</h4>
        <h2><a href="?sel=" style="text-decoration:none">Administração</a></h2>
    </div>
    <div class="double_grid">
        <div class="mainMenuAdm">
            <h4 style="padding:10px; margin: 0">Opções</h4>
            <ul class="mainMenuAdmUl">
                <li>
                    <a href="?sel=" class="a <?php if($selected == '') echo "selected"; ?>">Painel</a>
                </li>
                <li>
                    <a href="?sel=e" class="a <?php if($selected == 'e') echo "selected"; ?>">Empréstimos</a>
                </li>
                <li>
                    <a href="?sel=l" class="a <?php if($selected == 'l') echo "selected"; ?>">Livros</a>
                </li>
                <li>
                    <a href="?sel=a" class="a <?php if($selected == 'a') echo "selected"; ?>">Administradores</a>
                </li>
            </ul>
        </div>
        <div class="dashboard">
            <div class="dashboardContent">
                <?php
                    if($selected == '')
                    {
                        ?>
                        <h2 class="textcenter dashboardTitle" ><a href="?sel=" class="a">Painel</a></h2>
                        <a href="config.php" target="_blank" class="addNew textcenter a">Configurações do Sistema</a>

                        <div class="grid_3">
                            <table class="painelTable">
                                <tr>
                                    <th colspan="3">Gerar Relação (.pdf)</th>
                                </tr>
                                <tr>
                                    <td><a href="pdf.php?ent=ea" target="_blank" class="a relLinks">Empréstimos Atrasados</a></td>
                                    <td><a href="pdf.php?ent=e" target="_blank" class="a relLinks">Empréstimos</a></td>
                                    <td><a href="pdf.php?ent=l" target="_blank" class="a relLinks">Livros</a></td>
                                    <td><a href="pdf.php?ent=a" target="_blank" class="a relLinks">Administradores</a></td>
                                    <td><a href="pdf.php?ent=log" target="_blank" class="a relLinks">Log de Administração</a></td>
                                </tr>
                            </table>
                            <table class="painelTable">
                                <tr>
                                    <th colspan="3">Backup (.csv)</th>
                                </tr>
                                <tr>
                                    <td><a href="csv.php?ent=e" target="_blank" class="a relLinks">Empréstimos</a></td>
                                    <td><a href="csv.php?ent=l" target="_blank" class="a relLinks">Livros</a></td>
                                    <td><a href="csv.php?ent=a" target="_blank" class="a relLinks">Administradores</a></td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    }
                    else if($selected == 'e') {
                        ?>
                        <iframe src="emp.php" frameborder="0"></iframe>
                        <?php
                    }
                    else if($selected == 'l') {
                        $sel_title = "livros";
                        ?>
                        <h2 class="textcenter dashboardTitle" ><a href="?sel=<?php echo $selected; ?>" class="a">Livros</a></h2>
                        <a href="cadliv.php" class="addNew textcenter a" target="_blank">Adicionar novo</a>

                        <div class="admSearch">
                            <form action="" method="get" class="frmSearch">
                                <input type="hidden" name="sel" value="<?php echo $selected; ?>">
                                <input type="search" name="search" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"'; ?>>
                                <input type="submit"  value="Pesquisar <?php echo $sel_title ?>" class="frmInput">
                            </form>
                        </div>

                        <table class="admTable">
                            <tr class="header">
                                <!-- <th>Id</th> -->
                                <th>Título</th>
                                <th>Gênero</th>
                                <th>Autor(es)</th>
                                <th>Editora</th>
                                <th>Ano</th>
                                <th>Edição</th>
                                <th>Disponível</th>
                                <th></th>
                                <th></th>
                            </tr>
                            
                            <?php 
                                try {
                                    include "../config/php/connect.php";
                                    
                                    $page = 1;
                                    
                                    if(isset($_GET['page']))
                                        $page = $_GET['page'];


                                    $sql = "SELECT id_livro, titulo, genero, autor, editora, ano, edicao, disponivel
                                    FROM livro";

                                    $sql_count = "SELECT COUNT(*) FROM livro";
                                    
                                    $search = '';

                                    if(isset($_GET['search'])){        
                                        $search = utf8_decode($_GET['search']);
                                        $search = strtolower($search);  
                                        $search_str = " WHERE (lower(titulo) LIKE '%$search%' OR lower(genero) LIKE '%$search%' 
                                        OR lower(autor) LIKE '%$search%' OR lower(editora) LIKE '%$search%' OR ano LIKE '%$search%' OR edicao LIKE '%$search%')";
                                        $sql .= $search_str;
                                        $sql_count .= $search_str;
                                    }

                                    $sql .= " ORDER BY titulo ASC";

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
                                        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                                        {
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

                                            // if($disp)
                                            //     $disp = 'Sim';
                                            // else
                                            //     $disp = 'Não';

                                            ?>
                                            <tr>
                                                <td><?php echo $titulo; ?></td>
                                                <td><?php echo $genero; ?></td>
                                                <td><?php echo $autor; ?></td>
                                                <td><?php echo $editora; ?></td>
                                                <td><?php echo $ano; ?></td>
                                                <td><?php echo $edicao."ᵃ"; ?></td>
                                                <td <?php echo ($disp) ? 'class="green"' : 'class="red"' ;?>><?php echo ($disp) ? 'Sim' : 'Não'; ?></td>
                                                <td class=""><a href="visliv.php?id=<?php echo $id ?>" target="_blank" class="admVisualizar">Visualizar</a></td>
                                                <script>
                                                const el = document.createElement('div');
                                                el.innerHTML = 'Deseja realmente excluir o livro "<?php echo $titulo; ?>"?<br><br><b>TODOS OS EMPRÉSTIMOS VINCULADOS AO LIVRO TAMBÉM SERÃO EXCLUÍDOS!</b>';
                                                
                                                </script>

                                                <td class=""><a onclick="<?php 
                                                echo "swal({
                                                    title: 'Atenção!',
                                                    content: el,
                                                    icon: 'warning',
                                                    buttons: true,
                                                    dangerMode: true,
                                                }).then((willDelete) =>{
                                                    if(willDelete){
                                                        window.location.href = '?sel=$selected&page=$page&search=$search&exc=$id';
                                                    }
                                                });"; ?>" class="admExcluir">Excluir</a></td>
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
                                <th>Título</th>
                                <th>Gênero</th>
                                <th>Autor(es)</th>
                                <th>Editora</th>
                                <th>Ano</th>
                                <th>Edição</th>
                                <th>Disponível</th>
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
                    } 
                    else if($selected == 'a') {
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
                    }
                    ?>
            </div>
        </div>
    </div>
</body>

<script src="../js/main.js"></script>
</html>