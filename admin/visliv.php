<?php

    $edit = false;
    $exc = false;
    
    include 'head.php';
    include 'login.php';

    include "../config/php/util.php";

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        try {
            include "../config/php/connect.php";

            $sql = "SELECT id_livro, codigo, titulo, genero, autor, editora, ano, edicao, qtde, obs, disponivel, excluido
            FROM livro WHERE id_livro=$id";

            $res = mysqli_query($conn, $sql);

            if(mysqli_affected_rows($conn) > 0)
            {
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                
                $codigo = ($row['codigo']);
                $titulo = ($row['titulo']);
                $genero = ($row['genero']);
                $autor = ($row['autor']);

                $a_autor = explode("; ", $autor);

                if(sizeof($a_autor) > 2)
                {
                    $autor = $a_autor[0]."; ".$a_autor[1]."; et al.";
                }

                $editora = ($row['editora']);
                $ano = ($row['ano']);
                $edicao = ($row['edicao']);
                $obs = ($row['obs']);
                $disp = ($row['disponivel']);
                $exc = ($row['excluido']);
                $qtde = ($row['qtde']);
            }
            else {
                header("Location: main.php");
            }

        } catch (Exception $e) {

        }
    }

    if(isset($_GET['edit']))
    {
        $edit = $_GET['edit'];

        if($edit == 'true')
            $edit = true;
        else 
            $edit = false;
    }

    if(isset($_POST['cancelEdit']))
    {
        header("Location: ?id=$id");
    }

    if(isset($_POST['subUpLivro']))
    {
        $success = false;
        try {

            include "../config/php/connect.php";

            $titulo = (mysqli_real_escape_string($conn, $_POST['titulo']));
            $abrev = (mysqli_real_escape_string($conn, $_POST['genero']));
            $genero = getGenero($abrev);
            $autor = (mysqli_real_escape_string($conn, $_POST['autor']));
            $editora = (mysqli_real_escape_string($conn, $_POST['editora']));            
            $ano = (mysqli_real_escape_string($conn, $_POST['ano']));
            $edicao = (mysqli_real_escape_string($conn, $_POST['edicao']));
            $obs = (mysqli_real_escape_string($conn, $_POST['obs']));
            $disp = (mysqli_real_escape_string($conn, $_POST['disp']));
            $qtde = (mysqli_real_escape_string($conn, $_POST['qtde']));

            $codigo = getCodigo($abrev, $id);
            
            $sql = "UPDATE livro SET
            codigo = '$codigo', titulo = '$titulo', genero = '$genero',
            autor = '$autor', editora = '$editora',
            ano = $ano, edicao = $edicao, obs = '$obs', qtde = $qtde 
            WHERE id_livro=$id;";

            $res = mysqli_query($conn, $sql);

            echo $sql;

            $titulo = ($titulo);
            if(mysqli_affected_rows($conn) > 0)
            {
                echo '<script>
                alert("Livro \"'.$titulo.'\" editado com sucesso!");
                </script>';
                $success = true;
            } 
            else {
                // echo 'b';
                $erro = mysqli_error($conn);
                echo '<script>
                alert("Falha ao alterar livro \"'.$titulo.'\"!\nMais detalhes: '.$erro.'");
                </script>';
            }

            mysqli_close($conn);
            if($success)
            {
                $append = "Usuário \"$login\" alterou o livro id $id.<br>";
                $file = 'log.html';
                date_default_timezone_set("America/Sao_Paulo");
    
                $append = '['.date('d/m/Y H:i:s').'] '.$append;
                
                if(file_get_contents($file) != '')
                    $append = file_get_contents($file).$append;
    
                file_put_contents($file, $append);
            }
            header("Location: ?id=$id");
        } catch (Exception $e) {

        }
    }

    if(isset($_GET['exc']))
    {
        $nexc = $_GET['exc'];

        if($nexc == 0) $nexc = 1; else $nexc = 0;

        include '../config/php/connect.php';
        
        $sql = "UPDATE livro SET excluido = $nexc WHERE id_livro = $id";

        $res = mysqli_query($conn, $sql);

        header("Location: ?id=$id");
    }
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
    <link rel="stylesheet" href="../css/vis.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>    
    <a href="main.php?sel=l" class="a voltaInicio">Voltar à Administração</a>
    <div class="textcenter">
        <h3>Visualizar <a href="main.php?sel=l" class="a">Livro</a> (<b id="codigo"><?php echo $codigo; ?></b>)</h3>
    </div>
    
    <div class="visualizar">
        <form action="" method="post" class="visualizarFrm" <?php if($edit) echo 'style="display:block"'; ?>>
            
            <label for="titulo">Título</label><br>
            <input type="text" name="titulo" id="titulo" required autofocus value="<?php echo $titulo; ?>">
            <br><br>
            <label for="genero">Gênero</label><br>
            <select name="genero" id="genero" required>
                <option value="" selected disabled>-- Selecione uma opção --</option>
                <option value="LE" <?php if($id != 0) if(getAbrev($genero) == 'LE') echo "selected"; ?>>Literatura Estrangeira</option>
                <option value="LB" <?php if($id != 0) if(getAbrev($genero) == 'LB') echo "selected"; ?>>Literatura Brasileira</option>
                <option value="HQ" <?php if($id != 0) if(getAbrev($genero) == 'HQ') echo "selected"; ?>>História em Quadrinhos</option>
                <option value="CN" <?php if($id != 0) if(getAbrev($genero) == 'CN') echo "selected"; ?>>Ciências Naturais</option>
                <option value="CS" <?php if($id != 0) if(getAbrev($genero) == 'CS') echo "selected"; ?>>Ciências Sociais</option>
                <option value="LD" <?php if($id != 0) if(getAbrev($genero) == 'LD') echo "selected"; ?>>Literatura Didática</option>
            </select>
            <br><br>
            <label for="autor">Autor</label><br>
            <input type="text" name="autor" id="autor" required value="<?php echo $autor; ?>">
            <br><br>
            <label for="editora">Editora</label><br>
            <input type="text" name="editora" id="editora" required value="<?php echo $editora; ?>">
            <br><br>
            <label for="ano">Ano</label><br>
            <input type="number" name="ano" id="ano" required min="-12000" max="<?php echo date('Y'); ?>" value="<?php echo $ano; ?>">
            <br><br>
            <label for="edicao">Edição</label><br>
            <input type="number" name="edicao" id="edicao" required min="1" max="500" value="<?php echo $edicao; ?>">
            <br><br>
            <label for="qtde">Quantidade</label><br>
            <input type="number" name="qtde" id="qtde" required min="0" max="100" value="<?php echo $qtde; ?>">
            <br><br>
            <label for="obs">Obs.</label><br>
            <textarea type="text" name="obs" id="obs" class="resize_v"><?php echo $obs; ?></textarea>
            <br><br>
            <button type="submit" name="subUpLivro" class="cadBtn">Salvar</button>
            <button type="submit" name="cancelEdit" class="cadBtn reset" formnovalidate>Cancelar Edição</button>
        </form>

        <div class="visualizarContent" <?php if($edit) echo 'style="display:none"'; ?>>    

            <div class="visualizarInfo">
                <label for="">Título</label>
                <h3><?php echo $titulo; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Gênero</label>
                <h3><?php echo $genero; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Autor</label>
                <h3><?php echo $autor; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Editora</label>
                <h3><?php echo $editora; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Ano</label>
                <h3><?php echo $ano; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Edição</label>
                <h3><?php echo $edicao; ?>ᵃ</h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Quantidade</label>
                <h3><?php echo $qtde; ?></h3>
            </div>   
            
            <div class="visualizarInfo">
                <label for="">Observação</label>
                <p>
                <?php if($obs != '') echo $obs; else echo "-"; ?>
                </p>
            </div>  

            <div class="visualizarInfo">
                <label for="">Disponível</label>
                <h3 class="<?php echo ($disp) ? 'green' : 'red'; ?>"><?php echo ($disp) ? 'Sim' : 'Não'; ?></h3>
            </div>

            <div class="visualizarOptions">
                <button onclick="<?php echo "window.location.href = '?id=$id&edit=true';" ?>" class="btnEditar" <?php echo ($exc) ? 'disabled' : ''; ?>>
                    Editar
                </button>

                <button onclick="<?php echo "window.location.href = '?id=$id&exc=$exc';" ?>" class="btnExcluir">
                    <?php echo ($exc) ? 'Restaurar' : 'Excluir';?>
                </button>
            </div>
        </div>
    </div>
    
</body>

<script src="../js/main.js">
</script>
</html>