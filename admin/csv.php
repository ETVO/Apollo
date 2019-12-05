<?php
    include "login.php";

    $a_csv = array();

    if(isset($_GET['ent']))
    {
        $ent = $_GET['ent'];
        switch($ent)
        {
            case 'l':
                $onscript = 'livro';
                $a_csv = array('id_livro', 'codigo', 'titulo', 'genero', 'autor', 'editora', 'ano', 'edicao', 'disponivel', 'qtde', 'obs', 'excluido');
                break;

            case 'u':
                $onscript = 'user';
                $a_csv = array('id_user', 'nome', 'ra', 'login', 'senha', 'turma', 'tipo', 'email', 'telefone', 'admin', 'bloqueado');
                break;
            
            case 'e':
                $onscript = 'emprestimo';
                $a_csv = array('id_emprestimo', 'id_livro', 'id_admin', 'id_user', 'data_emp', 'data_prev_dev', 'data_dev', 'obs', 'devolvido', 'excluido');
                break;
            
            case 'c':
                $onscript = 'caixa';
                $a_csv = array('id', 'valor', 'descricao', 'tipo', 'data', 'excluido');
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
        if($ent == 'c')
        {
            $query = "SELECT * from $onscript ORDER BY id DESC";
        }
        else if($ent != 'e')
            $query = "SELECT * from $onscript ORDER BY id_$onscript DESC";  
        else 
            $query = "SELECT id_emprestimo, 
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