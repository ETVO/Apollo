<?php
    include 'login.php';
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
    <link rel="stylesheet" href="../css/codigos.css">
    <script src="../config/js/sweetalert.min.js"></script>
    <script src="../config/js/jquery.min.js"></script>
    <script src="../config/js/jscolor.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<body>    
    <div id="opts">
        <a href="main.php" class="a voltaInicio">Voltar à Administração</a>
        <div class="textcenter">
            <h4>Apolo</h4>
            <h2>Imprimir códigos</h2>
        </div>
    </div>

    <div id="settings">
        <div class="setsContent">
            <div class="g1x1x1">
                <div>
                    <label for="padding">Preenchimento </label><a id="rpadding" class="reset a">redefinir</a><br>
                    <input type="range" id="padding" min="0" max="20" step="1" value="10">
                    <br><br>
                    <label for="margin">Margem </label><a id="rmargin" class="reset a">redefinir</a><br>
                    <input type="range" id="margin" min="0" max="10" step="1" value="5">
                    <br><br>
                </div>
                <div>
                    <label for="fontsize">Tamanho da fonte </label><a id="rfontsize" class="reset a">redefinir</a><br>
                    <input type="range" id="fontsize" min="0.8" max="1.6" step=".1" value="1.2">
                    <br><br>
                    <label for="cellback">Cor das células </label><a id="rcellback" class="reset a">redefinir</a><br>
                    <input class="jscolor {onFineChange:'cellback.onchange()', uppercase:false}" id="cellback" value="#ffffff">
                    <br><br>
                </div>
                <div>
                    <label for="color">Cor do texto </label><a id="rcolor" class="reset a">redefinir</a><br>
                    <input  class="jscolor {onFineChange:'color.onchange()', uppercase:false}" id="color" value="#242424">
                    <br><br>
                    <label for="border">Cor das bordas </label><a id="rborder" class="reset a">redefinir</a><br>
                    <input class="jscolor {onFineChange:'border.onchange()', uppercase:false}" id="border" value="#dddddd">
                    <br><br>
                    <!-- <label for="background">Cor do fundo </label><a id="rbackground" class="reset a">redefinir</a><br>
                    <input class="jscolor {onFineChange:'background.onchange()', uppercase:false}" id="background" value="#ffffff">
                    <br><br> -->
                </div>
            </div>
            <div class="textcenter">
                <div style="margin-bottom: 1em">
                    <a onclick="imprimir()" id="btn">Imprimir</a>
                    <!-- <small> -->
                        <a onclick="rtudo()" class="a" id="reset">Redefinir tudo</a>
                    <!-- </small> -->
                </div>
            </div>
        </div>
    </div>
    
    <div class="dyntable">
        <?php 
            try {
                include "../config/php/connect.php";
                
                $sql = "SELECT id_livro, 
                                codigo, 
                                titulo
                        FROM livro WHERE excluido = 0";

                $sql .= " ORDER BY codigo ASC";

                $res = mysqli_query($conn, $sql);

                if(mysqli_affected_rows($conn) > 0){
                    while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                    {
                        $id = $row['id_livro'];
                        $codigo = ($row['codigo']);
                        $titulo = ($row['titulo']);

                        ?>
                        <div class="cell"><?php echo $codigo; ?></div>
                        <?php
                    }
                }

                mysqli_close($conn);
            } catch (Exception $e) {
                ?>
                    Não há nenhum registro!
                <?php
            }
                
            ?>
        </div>
    
</body>

<script src="../js/main.js">
</script>

<script src="../js/codigos.js">
</script>
</html>