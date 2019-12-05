
<?php
    include "head.php";
    include "login.php";
    $sel_title = "empréstimos";

    $page = 1;
    $f_dev = false;
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
        $filter = 'e.excluido = 0';
    }
                
    if(isset($_GET['page']))
        $page = $_GET['page'];


    $search = '';

    if(isset($_GET['search']))
    {   
        $search = utf8_decode($_GET['search']);
    }
    
    if(isset($_GET['f_dev']))
    {
        $f_dev = true;

        if($f_exc)
        {
            $filter .= ' AND e.devolvido = 0';
        }
        else
        {
            $filter = 'e.devolvido = 0';
        }
    }

    function updateLivro($id_livro) {
        try {
            include '../config/php/connect.php';

            $sql = "SELECT qtde FROM livro WHERE id_livro = $id_livro";
            
            $res = mysqli_query($conn, $sql);

            $row = mysqli_fetch_array($res, MYSQLI_NUM);

            $qtde = $row[0];

            $newqtde = $qtde + 1;

            $sql = "UPDATE livro SET
            qtde = $newqtde, disponivel = 1 WHERE id_livro = $id_livro";

            $res = mysqli_query($conn, $sql);

            mysqli_close($conn);
        } catch (Exception $e) {

        }
    }

    if(isset($_GET['dev']))
    {
        try {
            include '../config/php/connect.php';

            $id_emp = $_GET['dev'];

            $data_dev = date('Y-m-d');

            $sql = "UPDATE emprestimo SET
            devolvido = 1,
            data_dev = '$data_dev'
            WHERE id_emprestimo=$id_emp";
            
            if($res = mysqli_query($conn, $sql))
            {   
                updateLivro($id_livro);
                echo '<script>
                    alert("Livro devolvido com sucesso!");
                    
                    </script>';
                $success = true;
            }
            else {
                $error = mysqli_error($conn);
                echo '<script>
                    alert("Algo deu errado!\nMais detalhes:'.$error.'");
                    
                    </script>';
            }

            mysqli_close($conn);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if($success)
        {
            $append = "Usuário \"$login\" devolveu o empréstimo id $id_emp.<br>";
            $file = 'log.html';
            date_default_timezone_set("America/Sao_Paulo");

            $append = '['.date('d/m/Y H:i:s', ).'] '.$append;
            
            if(file_get_contents($file) != '')
                $append = file_get_contents($file).$append;

            file_put_contents($file, $append);
        }
    }

    if(isset($_GET['exc']))
    {
        $idexc = $_GET['exc'];
        $nexc = 0;

        include '../config/php/connect.php';

        $sql = "SELECT excluido FROM emprestimo WHERE id_emprestimo = $idexc";

        $res = mysqli_query($conn, $sql);

        if(mysqli_affected_rows($conn) > 0)
        {
            $row = mysqli_fetch_array($res, MYSQLI_NUM);
            $nexc = $row[0];
        }

        if($nexc == 0) $nexc = 1; else $nexc = 0;

        $sql = "UPDATE emprestimo SET excluido = $nexc WHERE id_emprestimo = $idexc";

        $res = mysqli_query($conn, $sql);
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
        <a onclick="changeParentLocation('main.php?sel=e')" class="a" title="Recarregar">
            Empréstimos
        </a>
    </h2>
    <div class="contentaddnew" id="optionsContent">
        <div id="options">
            <a href="<?php echo $printurl ?>" class="a">Imprimir</a>
        </div>
    </div>
    <div class="contentaddnew">
        <div id="balanco" title="De acordo com os filtros selecionados...">
            N° de empréstimos:
            <span class="blue" id="numero">
            </span>
        </div>
    </div>

    <script>
        var lblNum = document.getElementById("numero");
    </script>

    <div class="admSearch">
        <form action="" method="get" class="frmSearch" id="frmSearch">
            <label for="f_dev" id="lbl_f_dev">Ocultar empréstimos devolvidos?</label>&nbsp;
            <input type="checkbox" name="f_dev" id="f_dev" onChange="this.form.submit()" <?php if($f_dev) echo "checked"; ?>>
            &nbsp;&nbsp;&nbsp;
            <label for="f_exc" id="lbl_f_exc">Ocultar registros excluídos?</label>&nbsp;
            <input type="checkbox" name="f_exc" id="f_exc" onChange="this.form.submit()" <?php if($f_exc) echo "checked"; ?>>
            &nbsp;&nbsp;
            <input type="hidden" name="sel" value="<?php echo $selected; ?>">
            <input type="hidden" name="first" value="0">
            <input type="search" name="search" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"'; ?>>
            <input type="submit"  value="Pesquisar" class="frmInput">
        </form>
    </div>

    <table class="admTable">
        <tr class="header">
            <th>Livro</th>
            <th>Emprestado para</th>
            <th>Autorizado por</th>
            <th>Emprestado em</th>
            <th>Devolução prevista</th>
            <th>Devolvido?</th>
            <th id="actions_h">Ações</th>
        </tr>
        
        <?php 
            try {
                include "../config/php/connect.php";
                include "../config/php/util.php";
                

                $sql = "SELECT id_emprestimo, 
                                l.id_livro AS id_livro, 
                                l.codigo AS codigo, 
                                l.titulo AS titulo, 
                                u.nome AS usuario, 
                                u.email AS email, 
                                u.telefone AS telefone,
                                u.turma AS turma, 
                                a.nome AS admin, 
                                data_emp, 
                                data_prev_dev, 
                                devolvido, 
                                data_dev,
                                e.excluido
                FROM emprestimo AS e 
                    INNER JOIN livro AS l ON e.id_livro = l.id_livro 
                    INNER JOIN user AS a ON e.id_admin = a.id_user
                    INNER JOIN user AS u ON e.id_user = u.id_user ";

                $sql_count = "SELECT COUNT(*) FROM emprestimo AS e ";
                
                $search = (isset($_GET['search'])) ? ($_GET['search']) : '';

                if($search != ''){   
                    $search = utf8_decode($_GET['search']);
                    $search = strtolower($search);
                    $search_str = " WHERE (lower(codigo) LIKE '%$search%' OR lower(titulo) LIKE '%$search%' OR lower(usuario) LIKE '%$search%' 
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

                echo "<script>lblNum.innerText = $count + '';</script>";

                $limit = 10;

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
                
                // echo $sql;

                if(mysqli_affected_rows($conn) > 0){
                    while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                    { 

                        $id = $row['id_emprestimo'];
                        $id_livro = $row['id_livro'];
                        $id_admin = $row['id_livro'];
                        
                        $codigo = utf8_encode($row['codigo']);
                        $titulo = utf8_encode($row['titulo']);
                        
                        $nome = utf8_encode($row['usuario']);
                        $nome = flname($nome, ' ');
                        $email = utf8_encode($row['email']);
                        $telefone = utf8_encode($row['telefone']);
                        $turma = utf8_encode($row['turma']);
                        
                        $admin = utf8_encode($row['admin']);
                        $admin = flname($admin, ' ');
                        
                        $data_emp = utf8_encode($row['data_emp']);
                        $data_prev_dev = utf8_encode($row['data_prev_dev']);
                        $data_dev = utf8_encode($row['data_dev']);
                        
                        $dev = utf8_encode($row['devolvido']);
                        $exc = utf8_encode($row['excluido']);

                        $atrasado = false;

                        if(strtotime(date('Y-m-d')) > strtotime($data_prev_dev) && !$dev) $atrasado = true;

                        ?>
                        <tr <?php if($atrasado) echo 'title="Este empréstimo está atrasado!"'; else if($exc) echo 'title="Este empréstimo foi excluído, pois o livro emprestado foi excluído!"';  else if($dev) echo 'title="Este empréstimo já foi devolvido!"'; ?>>
                            <td title='"<?php echo $titulo; ?>"'><?php echo $codigo; ?></td>
                            <td title="<?php 
                                if($turma != '') echo 'Telefone: '.$telefone.'; Turma: '.$turma;
                                else echo 'Email: '.$email.'; Telefone: '.$telefone;
                             ?>"><?php echo $nome; ?></td>
                            <td><?php echo $admin; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($data_emp)); ?></td>
                            <td <?php if($atrasado) echo 'class="red"'; ?>><?php echo date('d/m/Y', strtotime($data_prev_dev));?></td>
                            <td class="<?php if($dev) echo 'green'; else echo 'red'; ?>">
                                <?php if($dev) echo 'Sim'; else echo 'Não';
                                if($data_dev == null) echo ''; else echo ' ('.date('d/m/Y', strtotime($data_dev)).')'; ?>
                            </td>
                            <td class="action">
                                <a onclick="changeParentLocation('visemp.php?id=<?php echo $id ?>')" target="_blank" class="a">Visualizar</a>
                                <span <?php if($dev || $exc || $atrasado) echo "style='display: none'"; ?>>|</span>
                                <a href="?dev=<?php echo $id ?>" class="a" <?php if($dev || $exc || $atrasado) echo "style='display: none'"; ?>>Devolver</a>
                                |
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
            <th>Livro</th>
            <th>Usuário/Contato</th>
            <th>Autorizado por</th>
            <th>Emprestado em</th>
            <th>Devolução prevista</th>
            <th>Devolvido?</th>
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
    
    if(isset($_GET['print']))
    {
        echo "<script>
            imprimir();
        </script>";
    }
?>

<script src="admin.js"></script>