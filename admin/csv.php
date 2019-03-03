<?php
    session_start();

    $login = $_SESSION['login'];
    $senha = $_SESSION['senha'];
    $nome = '';

    if(!isset($_SESSION['login']) || !isset($_SESSION['senha'])) {
        session_destroy();
        header("Location: index.php");
    }
    else if($login == 'root' && $senha == '632f4902f2afb597923c18ea897eefa7'){
    }
    else {
        try 
        {
            include "../config/php/connect.php";

            $sql = "SELECT nome FROM user WHERE login = '$login' AND bloqueado = 0";

            $res = mysqli_query($conn, $sql);
            
            if(mysqli_affected_rows($conn) > 0){
                $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
                $nome = utf8_encode($row['nome']);
                $nome = explode(" ", $nome)[0];
            } 
            else {
                session_destroy();
                header("Location: index.php");
            }

            mysqli_close($conn);
        } catch (Exception $e){

        }
    }

    $a_csv = array();

    if(isset($_GET['ent']))
    {
        $ent = $_GET['ent'];
        switch($ent)
        {
            case 'l':
                $onscript = 'livro';
                $a_csv = array('id_livro', 'titulo', 'genero', 'autor', 'editora', 'ano', 'edicao', 'disponivel', 'obs');
                break;

            case 'a':
                $onscript = 'user';
                $a_csv = array('id_user', 'nome', 'ra', 'login', 'senha', 'ano', 'tipo', 'telefone', 'bloqueado');
                break;
            
            case 'e':
                $onscript = 'emprestimo';
                $a_csv = array('id_emprestimo', 'titulo', 'nome', 'telefone', 'admin', 'data_emp', 
                'data_prev_dev', 'devolvido', 'data_dev');
                break;
            
            default:
                echo "<script>window.close();</script>";
        }
    }
    else
    {
        echo "<script>window.close();</script>";
    }

    try {
        include '../config/php/connect.php';

        header('Content-Type: text/csv; charset=utf-8');  
        header('Content-Disposition: attachment; filename='.$onscript.'.csv');  
        $output = fopen("php://output", "w");  
        fputcsv($output, $a_csv);  
        if($ent != 'e')
            $query = "SELECT * from $onscript ORDER BY id_$onscript DESC";  
        else 
            $query = "SELECT id_emprestimo, l.titulo, e.nome, e.telefone, a.nome AS admin, data_emp, 
            data_prev_dev, devolvido, data_dev FROM $onscript AS e 
            INNER JOIN livro AS l ON e.id_livro = l.id_livro 
            INNER JOIN user AS a ON e.id_admin = a.id_user OR e.id_admin = 0";
        $res = mysqli_query($conn, $query);  
        while($row = mysqli_fetch_assoc($res))  
        {  
            fputcsv($output, $row);  
        }  
        fclose($output); 
        
        mysqli_close($conn);
    } catch (Exception $e) {

    }
?>