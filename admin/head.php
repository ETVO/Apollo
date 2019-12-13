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
    <script src="../config/js/jquery.min.js"></script>
    <script src="../js/admin.js"></script>
    <link rel="shortcut icon" href="../favicon.ico"> 
</head>

<?php
    $nome = '';

    $selected = '0';

    if(isset($_GET['sel'])){
        $selected = $_GET['sel'];
    }
    else
        $selected = '';

    // if(isset($_GET['exc']))
    // {
    //     $success = false;
    //     $onscript = "";
    //     $ontext = "";
    //     $id = $_GET['exc'];
    //     if($selected == 'a')//block de admin
    //     {
    //         try {
    //             include "../config/php/connect.php";
    //             $sql = "SELECT bloqueado FROM user WHERE id_user=$id";
                
    //             $res = mysqli_query($conn, $sql);
                
    //             $bloq = false;
            
    //             if(mysqli_affected_rows($conn) > 0)
    //             {
    //                 $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
    //                 $bloq = ($row['bloqueado']);
    //             }
    //             else
    //             {
    //                 echo "<script>
    //                 alert('Não foi possível realizar a operação!');
    //                 </script>";
    //                 header("Location: ?sel=$selected");
    //             }

    //             if($bloq == 1) $newbloq = 0; else $newbloq = 1;

    //             $sql = "UPDATE user SET bloqueado = $newbloq WHERE id_user = $id";

    //             $res = mysqli_query($conn, $sql);

    //             if($newbloq == 1) $popup = "bloqueado"; else $popup = "desbloqueado";
                
    //             if(mysqli_affected_rows($conn) > 0){
    //                 echo "<script>
    //                 alert('Administrador $popup com sucesso!');
    //                 </script>";

    //                 $success = true;
    //             } 
    //             else {
    //             }
    //         } catch(Exception $e) {

    //         }
    //         if($success)
    //         {
    //             if($bloq) $word = "desbloqueou"; else $word = "bloqueou";
    //             $append = "Usuário \"$login\" $word o administrador id $id.<br>";
    //             $file = 'log.html';
    //             date_default_timezone_set("America/Sao_Paulo");

    //             $append = '['.date('d/m/Y H:i:s').'] '.$append;
                
    //             if(file_get_contents($file) != '')
    //                 $append = file_get_contents($file).$append;

    //             file_put_contents($file, $append);
    //         }
    //     }
    //     else // exclusão de livro
    //     {
    //         try {
    //             include "../config/php/connect.php";

    //             $sql = "SELECT COUNT(1) FROM emprestimo WHERE id_livro = $id";
                
    //             $res = mysqli_query($conn, $sql);
                
    //             if(mysqli_affected_rows($conn) > 0)
    //             {
    //                 $row = mysqli_fetch_array($res, MYSQLI_NUM);
    //                 $qtde_emp = ($row[0]);
    //             }
    //             else
    //             {
    //                 echo "<script>
    //                 alert('Não foi possível realizar a operação!');
    //                 </script>";
    //                 header("Location: ?sel=$selected");
    //             }

    //             $sql = "DELETE FROM emprestimo WHERE id_livro=$id";
                
    //             $res = mysqli_query($conn, $sql);
                
    //             if(mysqli_affected_rows($conn) > 0)
    //             {
    //                 echo "<script>
    //                 alert('$qtde_emp empréstimo(s) excluído(s)');
    //                 </script>";
    //             }
    //             else
    //             {
    //                 echo "<script>
    //                 alert('Não foi possível realizar a operação!');
    //                 </script>";
    //                 header("Location: ?sel=$selected");
    //             }

    //             $sql = "SELECT titulo FROM livro WHERE id_livro = $id";
                
    //             $res = mysqli_query($conn, $sql);
                
    //             if(mysqli_affected_rows($conn) > 0)
    //             {
    //                 $row = mysqli_fetch_array($res, MYSQLI_NUM);
    //                 $titulo = ($row[0]);
    //             }

    //             $sql = "DELETE FROM livro WHERE id_livro=$id";
                
    //             $res = mysqli_query($conn, $sql);
                
    //             if(mysqli_affected_rows($conn) > 0)
    //             {
    //                 echo "<script>
    //                 alert('Livro excluído com sucesso');
    //                 </script>";
    //             }
    //             else
    //             {
    //                 echo "<script>
    //                 alert('Não foi possível realizar a operação!');
    //                 </script>";
    //                 header("Location: ?sel=$selected");
    //             }

    //             $success = true;
    //         } catch(Exception $e) {

    //         }
    //         if($success)
    //         {
    //             $append = "Usuário \"$login\" excluiu o livro id $id ('$titulo') e todos os empréstimos relacionados.<br>";
    //             $file = 'log.html';
    //             date_default_timezone_set("America/Sao_Paulo");

    //             $append = '['.date('d/m/Y H:i:s').'] '.$append;
                
    //             if(file_get_contents($file) != '')
    //                 $append = file_get_contents($file).$append;

    //             file_put_contents($file, $append);
    //         }
    //     }

    //     header("Location: ?sel=$selected");
    // }
    
    
    $filter = "1 ";
    $filter_dev = 3;

    if(isset($_GET['filter_dev']))
    {
        $filter_dev = $_GET['filter_dev'];
        if($filter_dev == 1)
        {
            $filter = "devolvido = 1 ";
            // echo "<script>
            //     alert('Mostrando apenas os empréstimos devolvidos!');
            //     </script>";
        }
        else if ($filter_dev == 2)
        {
            $filter = "devolvido = 0 ";
            // echo "<script>
            //     alert('Mostrando apenas os empréstimos ainda não devolvidos!');
            //     </script>";
        }
        else
        {
            $filter_dev = 3;
            $filter = "1 ";
        }
    }

    if(isset($_GET['dev']))
    {
        $id_livro = $_GET['dev'];

        try {
            include '../config/php/connect.php';

            if(isset($_GET['devemp']))
            {
                $id_emp = $_GET['devemp'];

                $data_dev = date('Y-m-d');

                $sql = "UPDATE emprestimo SET
                devolvido = 1,
                data_dev = '$data_dev'
                WHERE id_emprestimo=$id_emp";
                
                if($res = mysqli_query($conn, $sql))
                {   
                    $sql = "UPDATE livro SET
                    disponivel = 1 
                    WHERE id_livro = $id_livro";

                    if($res = mysqli_query($conn, $sql))
                    {
                        echo '<script>
                        alert("Livro devolvido com sucesso!");
                        window.location.href="main.php?sel=e";
                        </script>';
                    }
                    else {    
                        $error = mysqli_error($conn);
                        echo '<script>
                            alert("Algo deu errado!\nMais detalhes:'.$error.'");
                            window.location.href="main.php?sel=e";
                            </script>';
                    }
                }
                else {
                    $error = mysqli_error($conn);
                    echo '<script>
                        alert("Algo deu errado!\nMais detalhes:'.$error.'");
                        window.location.href="main.php?sel=e";
                        </script>';
                }
            }
            
            mysqli_close($conn);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
?>