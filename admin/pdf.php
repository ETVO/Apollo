<?php
    require('../config/fpdf/fpdf.php');
    include '../config/php/util.php';

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
    
    if(isset($_GET['ent']))
    {
        $selected = 'doc';
        $ent = $_GET['ent'];
        $pdf = new FPDF();

        $doc_font = "Arial";

        $pdf->AddPage('P', 'A4', 'pt');
        
        $pdf->SetAutoPageBreak(false, 0.5);
        
        $y = $pdf->GetY();

        $pdf->SetFont($doc_font,'',8);
        $pdf->SetY(280);
        $text = utf8_decode('Página ');
        $pdf->Cell(190, 10, $text.$pdf->PageNo(), 0, 1, 'C');
        
        $pdf->SetY($y);

        $pdf->SetFont($doc_font,'B',22);
        $text = utf8_decode('Apolo');
        $pdf->Cell(190,10,$text,0,1,'C');
        
        $pdf->SetFont($doc_font,'B',24);
        $text = utf8_decode('Biblioteca - CTI Bauru');
        $pdf->Cell(190,10,$text,0,1,'C');
        
        switch($ent)
        {
            case 'l':
                $selected = 'rel_livros';
                $pdf->SetFont($doc_font,'',18);
                $text = utf8_decode('Relação de Livros');
                $pdf->Cell(190,10,$text,0,1,'C');
        
                $pdf->SetFont($doc_font,'B',12);
                $text = utf8_decode('('.date('d/m/Y').')');
                $pdf->Cell(190,3,$text,0,1,'C');
        
                $pdf->Ln(5);
        
                $table_font = "Courier";
                $table_fontsize = 9;
                
                $pdf->SetFont($table_font,'B',$table_fontsize);

                try {
                    
                    include "../config/php/connect.php";

                    $sql_count = 'SELECT COUNT(1) FROM livro';

                    $res = mysqli_query($conn, $sql_count);

                    $row = mysqli_fetch_array($res, MYSQLI_NUM);

                    $count = $row[0];
                    $s  = '';
                    if($count > 1) $s = 's';
                    
                    if($count > 0)
                        $text = utf8_decode($count.' livro'.$s. ' registrado'.$s);
                    else 
                        $text = utf8_decode('Nenhum livro registrado');
                    $pdf->Cell(190,3,$text,0,1,'C');

                    $pdf->Ln(5);
                    
                    $pdf->SetFont($table_font,'',$table_fontsize);

                    $current_y = $pdf->GetY();
                    $current_x = $pdf->GetX();
                    $ch_head = 5;
                    $cb_head = '';
                    $ca_head = '';
                    $cf_head = false;
                    
                    $cw_0 = 10;
                    $cw_1 = 35;
                    $cw_2 = 27;
                    $cw_3 = 30;
                    $cw_4 = 30;
                    $cw_5 = 15;
                    $cw_6 = 20;
                    $cw_7 = 25;
                    

                    $text = utf8_decode('Id');
                    $pdf->MultiCell($cw_0, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_0, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Título');
                    $pdf->MultiCell($cw_1, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_1, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Gênero ');
                    $pdf->MultiCell($cw_2, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_2, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Autor(es)');
                    $pdf->MultiCell($cw_3, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_3, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Editora');
                    $pdf->MultiCell($cw_4, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_4, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Ano');
                    $pdf->MultiCell($cw_5, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_5, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Edição');
                    $pdf->MultiCell($cw_6, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_6, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Disponível');
                    $pdf->MultiCell($cw_7, $ch_head, $text, $cb_head, $ca_head, $cf_head);
                    
                    $pdf->SetFont($table_font,'B',$table_fontsize);
                //------------------------------------------------------------------- END OF HEADER
                    $pdf->Ln(1);
                    
                    $sql = "SELECT id_livro, titulo, genero, autor, editora, ano, edicao, disponivel
                    FROM livro ORDER BY genero ASC";
                    
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

                            if($disp)
                                $disp = 'Sim';
                            else
                                $disp = 'Não';

                                $mj = 0;
                                $j = $mj;

                                if($current_y >= 250){
                                    $pdf->AddPage();
                                    $pdf->SetY(10);
                                    
                                    $y = $pdf->GetY();

                                    $pdf->SetFont($doc_font,'',8);
                                    $pdf->SetY(280);
                                    $text = utf8_decode('Página ');
                                    $pdf->Cell(190, 10, $text.$pdf->PageNo(), 0, 1, 'C');
                                    
                                    $pdf->SetY($y);
                                }

                                
                
                               $pdf->SetFont($table_font,'B',$table_fontsize);

                                $current_y = $pdf->GetY();
                                $current_x = $pdf->GetX();
                                $ch_row = 4;
                                $cb_row = '';
                                $ca_row = 'L';
                                $cf_row = false;

                                $pdf->setDrawColor(60, 60, 60);
                                $pdf->Line(5, $current_y, 205, $current_y);

                                $current_y = $current_y + 3;

                                $pdf->SetXY($current_x, $current_y);

                                $text = utf8_decode($id);
                                $pdf->MultiCell($cw_0, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $mj = $j;

                                $pdf->SetXY($current_x + $cw_0, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($titulo);
                                $pdf->MultiCell($cw_1, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_1, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_1, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($genero);
                                $pdf->MultiCell($cw_2, $ch_row, $text, $cb_row,$ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_2, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_2, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($autor);
                                $pdf->MultiCell($cw_3, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_3, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_3, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($editora);
                                $pdf->MultiCell($cw_4, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_4, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_4, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($ano);
                                $pdf->MultiCell($cw_5, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_5, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_5, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($edicao.'ª');
                                $pdf->MultiCell($cw_6, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_6, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_6, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($disp);
                                $pdf->MultiCell($cw_7, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_7, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->Ln($mj);
                        }
                    } 
                    else {
                        
                    }

                    mysqli_close($conn);
                } catch (Exception $e) {
                    
                }
                break;

            
            case 'a':
                $selected = 'rel_admins';
                $pdf->SetFont($doc_font,'',18);
                $text = utf8_decode('Relação de Administradores');
                $pdf->Cell(190,10,$text,0,1,'C');
        
                $pdf->SetFont($doc_font,'B',12);
                $text = utf8_decode('('.date('d/m/Y').')');
                $pdf->Cell(190,3,$text,0,1,'C');
        
                $pdf->Ln(5);
        
                $table_font = "Courier";
                $table_fontsize = 9;
                
                $pdf->SetFont($table_font,'B',$table_fontsize);

                try {
                    
                    include "../config/php/connect.php";

                    $sql_count = 'SELECT COUNT(1) FROM user';

                    $res = mysqli_query($conn, $sql_count);

                    $row = mysqli_fetch_array($res, MYSQLI_NUM);

                    $count = $row[0];
                    $es = '';
                    $s  = '';
                    if($count > 1) {
                        $es = 'es'; $s = 's';
                    }
                    
                    if($count > 0)
                        $text = utf8_decode($count.' administrador'.$es. ' registrado'.$s);
                    else
                        $text = utf8_decode('Nenhum administrador registrado');
                    $pdf->Cell(190,3,$text,0,1,'C');

                    $pdf->Ln(5);
                    
                    $pdf->SetFont($table_font,'',$table_fontsize);

                    $current_y = $pdf->GetY();
                    $current_x = $pdf->GetX();
                    $ch_head = 5;
                    $cb_head = '';
                    $ca_head = '';
                    $cf_head = false;
                    
                    $cw_0 = 10;
                    $cw_1 = 55;
                    $cw_2 = 20;
                    $cw_3 = 30;
                    $cw_4 = 30;
                    $cw_5 = 15;
                    $cw_6 = 20;

                    $text = utf8_decode('Id');
                    $pdf->MultiCell($cw_0, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_0, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Nome');
                    $pdf->MultiCell($cw_1, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_1, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('RA');
                    $pdf->MultiCell($cw_2, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_2, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Login');
                    $pdf->MultiCell($cw_3, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_3, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Tipo');
                    $pdf->MultiCell($cw_4, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_4, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Série');
                    $pdf->MultiCell($cw_5, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_5, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Bloqueado');
                    $pdf->MultiCell($cw_6, $ch_head, $text, $cb_head, $ca_head, $cf_head);
                    
                    $pdf->SetFont($table_font,'B',$table_fontsize);
                //------------------------------------------------------------------- END OF HEADER
                    $pdf->Ln(1);
                    
                    $sql = "SELECT id_user, nome, ra, login, tipo, ano, bloqueado
                    FROM user ORDER BY tipo ASC";
                    
                    $res = mysqli_query($conn, $sql);

                    if(mysqli_affected_rows($conn) > 0){
                        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                        {
                            $id = $row['id_user'];
                            $nome = utf8_encode($row['nome']);
                            $ra = utf8_encode($row['ra']);
                            if($ra == '') $ra = '-';
                            $login = utf8_encode($row['login']);
                            // $turma = utf8_encode($row['turma']);
                            $tipo = utf8_encode($row['tipo']);
                            $ano = utf8_encode($row['ano']);
                            if($ano == '')  $ano = '-'; else $ano = $ano.'º';
                            $bloq = utf8_encode($row['bloqueado']);

                            if($bloq)
                                $bloq = 'Sim';
                            else
                                $bloq = 'Não';

                            $mj = 0;
                            $j = $mj;

                            if($current_y >= 250){
                                $pdf->AddPage();
                                $pdf->SetY(10);
                                
                                $y = $pdf->GetY();

                                $pdf->SetFont($doc_font,'',8);
                                $pdf->SetY(280);
                                $text = utf8_decode('Página ');
                                $pdf->Cell(190, 10, $text.$pdf->PageNo(), 0, 1, 'C');
                                
                                $pdf->SetY($y);
                            }

                            
            
                            $pdf->SetFont($table_font,'B',$table_fontsize);

                            $current_y = $pdf->GetY();
                            $current_x = $pdf->GetX();
                            $ch_row = 4;
                            $cb_row = '';
                            $ca_row = 'L';
                            $cf_row = false;

                            $pdf->setDrawColor(60, 60, 60);
                            $pdf->Line(5, $current_y, 205, $current_y);

                            $current_y = $current_y + 3;

                            $pdf->SetXY($current_x, $current_y);

                            $text = utf8_decode($id);
                            $pdf->MultiCell($cw_0, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $mj = $j;

                            $pdf->SetXY($current_x + $cw_0, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($nome);
                            $pdf->MultiCell($cw_1, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_1, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_1, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($ra);
                            $pdf->MultiCell($cw_2, $ch_row, $text, $cb_row,$ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_2, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_2, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($login);
                            $pdf->MultiCell($cw_3, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_3, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_3, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($tipo);
                            $pdf->MultiCell($cw_4, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_4, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_4, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($ano);
                            $pdf->MultiCell($cw_5, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_5, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_5, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($bloq);
                            $pdf->MultiCell($cw_6, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_6, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->Ln($mj);
                        }
                    } 
                    else {
                        
                    }

                    mysqli_close($conn);
                } catch (Exception $e) {
                    
                }

                break;

            case 'e':
                $selected = 'rel_emprestimos';
                $pdf->SetFont($doc_font,'',18);
                $text = utf8_decode('Relação de Empréstimos');
                $pdf->Cell(190,10,$text,0,1,'C');
        
                $pdf->SetFont($doc_font,'B',12);
                $text = utf8_decode('('.date('d/m/Y').')');
                $pdf->Cell(190,3,$text,0,1,'C');
        
                $pdf->Ln(5);
        
                $table_font = "Courier";
                $table_fontsize = 9;
                
                $pdf->SetFont($table_font,'B',$table_fontsize);

                try {
                    
                    include "../config/php/connect.php";

                    $sql_count = 'SELECT COUNT(1) FROM emprestimo';

                    $res = mysqli_query($conn, $sql_count);

                    $row = mysqli_fetch_array($res, MYSQLI_NUM);

                    $count = $row[0];
                    $s  = '';
                    if($count > 1) $s = 's';
                    
                    if($count > 0)
                        $text = utf8_decode($count.' empréstimo'.$s. ' registrado'.$s);
                    else
                        $text = utf8_decode('Nenhum empréstimo registrado');
                    $pdf->Cell(190,3,$text,0,1,'C');

                    $pdf->Ln(5);
                    
                    $pdf->SetFont($table_font,'',$table_fontsize);

                    $current_y = $pdf->GetY();
                    $current_x = $pdf->GetX();
                    $ch_head = 5;
                    $cb_head = '';
                    $ca_head = 'L';
                    $cf_head = false;
                    
                    $cw_0 = 6; // Id
                    $cw_1 = 32; // Livro
                    $cw_2 = 27; // Usuário
                    $cw_3 = 32; // Autorizado por 
                    $cw_4 = 28; // Emprestado em 
                    $cw_5 = 30; // Devolução prevista
                    $cw_6 = 20; // Devolvido 
                    $cw_7 = 23; // Devolução 
                    

                    $text = utf8_decode('Id');
                    $pdf->MultiCell($cw_0, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_0, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Livro');
                    $pdf->MultiCell($cw_1, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_1, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Usuário');
                    $pdf->MultiCell($cw_2, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_2, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Autorizado por');
                    $pdf->MultiCell($cw_3, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_3, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Emprestado em');
                    $pdf->MultiCell($cw_4, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_4, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Dev. prevista');
                    $pdf->MultiCell($cw_5, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_5, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Devolvido');
                    $pdf->MultiCell($cw_6, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_6, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Devolução');
                    $pdf->MultiCell($cw_7, $ch_head, $text, $cb_head, $ca_head, $cf_head);
                    
                    $pdf->SetFont($table_font,'B',$table_fontsize);
                //------------------------------------------------------------------- END OF HEADER
                    $pdf->Ln(1);
                    
                    $sql = "SELECT id_emprestimo, l.titulo, e.nome, e.telefone, a.nome AS admin, data_emp, 
                    data_prev_dev, devolvido, data_dev FROM emprestimo AS e 
                    INNER JOIN livro AS l ON e.id_livro = l.id_livro 
                    INNER JOIN user AS a ON e.id_admin = a.id_user OR e.id_admin = 0 ORDER BY e.devolvido ASC";
                    
                    $res = mysqli_query($conn, $sql);

                    if(mysqli_affected_rows($conn) > 0){
                        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                        {
                            $id = $row['id_emprestimo'];
                            $titulo = utf8_encode($row['titulo']);
                            $nome = utf8_encode($row['nome']);
                            $nome = flname($nome, ' ');  
                            $telefone = utf8_encode($row['telefone']);
                            $admin = utf8_encode($row['admin']);
                            $admin = flname($admin, ' ');
                            $data_emp = utf8_encode($row['data_emp']); $data_emp = date('d/m/Y', strtotime($data_emp));
                            $data_prev_dev = utf8_encode($row['data_prev_dev']); $data_prev_dev = date('d/m/Y', strtotime($data_prev_dev));
                            $devolvido = utf8_encode($row['devolvido']);
                            if($devolvido == '1') $devolvido = 'Sim'; else $devolvido = 'Não';
                            $data_dev = utf8_encode($row['data_dev']); if($data_dev == null) $data_dev = '-'; else $data_dev = date('d/m/Y', strtotime($data_dev));

                            $atrasado = false;

                            if(strtotime(date('Y-m-d')) > strtotime($data_prev_dev) && !$devolvido) $atrasado = true;


                            $mj = 0;
                            $j = $mj;

                            if($current_y >= 250){
                                $pdf->AddPage();
                                $pdf->SetY(10);
                                
                                $y = $pdf->GetY();

                                $pdf->SetFont($doc_font,'',8);
                                $pdf->SetY(280);
                                $text = utf8_decode('Página ');
                                $pdf->Cell(190, 10, $text.$pdf->PageNo(), 0, 1, 'C');
                                
                                $pdf->SetY($y);
                            }

                            
            
                            $pdf->SetFont($table_font,'B',$table_fontsize);

                            $current_y = $pdf->GetY();
                            $current_x = $pdf->GetX();
                            $ch_row = 4;
                            $cb_row = '';
                            $ca_row = 'L';
                            $cf_row = false;

                            $pdf->setDrawColor(60, 60, 60);
                            $pdf->Line(5, $current_y, 205, $current_y);

                            $current_y = $current_y + 3;

                            $pdf->SetXY($current_x, $current_y);

                            $text = utf8_decode($id);
                            $pdf->MultiCell($cw_0, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $mj = $j;

                            $pdf->SetXY($current_x + $cw_0, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($titulo);
                            $pdf->MultiCell($cw_1, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_1, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_1, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($nome);
                            $pdf->MultiCell($cw_2, $ch_row, $text, $cb_row,$ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_2, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_2, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($admin);
                            $pdf->MultiCell($cw_3, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_3, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_3, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($data_emp);
                            $pdf->MultiCell($cw_4, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_4, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_4, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($data_prev_dev);
                            $pdf->MultiCell($cw_5, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_5, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_5, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($devolvido);
                            $pdf->MultiCell($cw_6, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_6, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->SetXY($current_x + $cw_6, $current_y);
                            $current_x = $pdf->GetX();
                            $text = utf8_decode($data_dev);
                            $pdf->MultiCell($cw_7, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                            $j = $pdf->GetMultiCellHeight($cw_7, $ch_row, $text, $cb_row, $ca_row);
                            if($j > $mj){
                                $mj = $j;
                            }

                            $pdf->Ln($mj);
                        }
                    } 
                    else {
                        
                    }

                    mysqli_close($conn);
                } catch (Exception $e) {
                    
                }
                break;

            case 'ea':
                $selected = 'rel_atrasados';
                $pdf->SetFont($doc_font,'',18);
                $text = utf8_decode('Relação de Empréstimos Atrasados');
                $pdf->Cell(190,10,$text,0,1,'C');
        
                $pdf->SetFont($doc_font,'B',12);
                $text = utf8_decode('('.date('d/m/Y').')');
                $pdf->Cell(190,3,$text,0,1,'C');
        
                $pdf->Ln(5);
        
                $table_font = "Courier";
                $table_fontsize = 9;
                
                $pdf->SetFont($table_font,'B',$table_fontsize);

                try {
                    
                    include "../config/php/connect.php";

                    $hoje = date('Y-m-d');

                    $sql_count = "SELECT COUNT(1) FROM emprestimo WHERE '$hoje' > data_prev_dev AND devolvido = 0";

                    $res = mysqli_query($conn, $sql_count);

                    $row = mysqli_fetch_array($res, MYSQLI_NUM);

                    $count = $row[0];
                    $s  = '';
                    if($count > 1) $s = 's';
                    
                    if($count > 0)
                        $text = utf8_decode($count.' empréstimo'.$s. ' atrasado'.$s);
                    else
                        $text = utf8_decode('Nenhum empréstimo atrasado');
                    $pdf->Cell(190,3,$text,0,1,'C');

                    $pdf->Ln(5);
                    
                    $pdf->SetFont($table_font,'',$table_fontsize);

                    $current_y = $pdf->GetY();
                    $current_x = $pdf->GetX();
                    $ch_head = 5;
                    $cb_head = '';
                    $ca_head = 'L';
                    $cf_head = false;
                    
                    $cw_0 = 6; // Id
                    $cw_1 = 32; // Livro
                    $cw_2 = 27; // Usuário
                    $cw_3 = 32; // Autorizado por 
                    $cw_4 = 28; // Emprestado em 
                    $cw_5 = 30; // Devolução prevista
                    $cw_6 = 20; // Devolvido 
                    $cw_7 = 23; // Devolução 
                    

                    $text = utf8_decode('Id');
                    $pdf->MultiCell($cw_0, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_0, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Livro');
                    $pdf->MultiCell($cw_1, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_1, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Usuário');
                    $pdf->MultiCell($cw_2, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_2, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Autorizado por');
                    $pdf->MultiCell($cw_3, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_3, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Emprestado em');
                    $pdf->MultiCell($cw_4, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_4, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Dev. prevista');
                    $pdf->MultiCell($cw_5, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_5, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Devolvido');
                    $pdf->MultiCell($cw_6, $ch_head, $text, $cb_head, $ca_head, $cf_head);
            
                    $pdf->SetXY($current_x + $cw_6, $current_y);
                    $current_x = $pdf->GetX();
                    $text = utf8_decode('Devolução');
                    $pdf->MultiCell($cw_7, $ch_head, $text, $cb_head, $ca_head, $cf_head);
                    
                    $pdf->SetFont($table_font,'B',$table_fontsize);
                //------------------------------------------------------------------- END OF HEADER
                    $pdf->Ln(1);
                    
                    $sql = "SELECT id_emprestimo, l.titulo, e.nome, e.telefone, a.nome AS admin, data_emp, 
                    data_prev_dev, devolvido, data_dev FROM emprestimo AS e 
                    INNER JOIN livro AS l ON e.id_livro = l.id_livro 
                    INNER JOIN user AS a ON e.id_admin = a.id_user OR e.id_admin = 0 ORDER BY e.devolvido ASC WHERE '$hoje' > data_prev_dev AND devolvido = 0";
                    
                    $res = mysqli_query($conn, $sql);

                    if(mysqli_affected_rows($conn) > 0){
                        while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
                        {
                            $id = $row['id_emprestimo'];
                            $titulo = utf8_encode($row['titulo']);
                            $nome = utf8_encode($row['nome']);
                            $nome = flname($nome, ' ');  
                            $telefone = utf8_encode($row['telefone']);
                            $admin = utf8_encode($row['admin']);
                            $admin = flname($admin, ' ');
                            $data_emp = utf8_encode($row['data_emp']); $data_emp = date('d/m/Y', strtotime($data_emp));
                            $data_prev_dev = utf8_encode($row['data_prev_dev']); $data_prev_dev = date('d/m/Y', strtotime($data_prev_dev));
                            $devolvido = utf8_encode($row['devolvido']);
                            if($devolvido == '1') $devolvido = 'Sim'; else $devolvido = 'Não';
                            $data_dev = utf8_encode($row['data_dev']); if($data_dev == null) $data_dev = '-'; else $data_dev = date('d/m/Y', strtotime($data_dev));

                            $atrasado = false;

                            if(strtotime(date('Y-m-d')) > strtotime($data_prev_dev) && !$devolvido) $atrasado = true;

                            if($atrasado)
                            {

                                $mj = 0;
                                $j = $mj;

                                if($current_y >= 250){
                                    $pdf->AddPage();
                                    $pdf->SetY(10);
                                    
                                    $y = $pdf->GetY();

                                    $pdf->SetFont($doc_font,'',8);
                                    $pdf->SetY(280);
                                    $text = utf8_decode('Página ');
                                    $pdf->Cell(190, 10, $text.$pdf->PageNo(), 0, 1, 'C');
                                    
                                    $pdf->SetY($y);
                                }

                                
                
                                $pdf->SetFont($table_font,'B',$table_fontsize);

                                $current_y = $pdf->GetY();
                                $current_x = $pdf->GetX();
                                $ch_row = 4;
                                $cb_row = '';
                                $ca_row = 'L';
                                $cf_row = false;

                                $pdf->setDrawColor(60, 60, 60);
                                $pdf->Line(5, $current_y, 205, $current_y);

                                $current_y = $current_y + 3;

                                $pdf->SetXY($current_x, $current_y);

                                $text = utf8_decode($id);
                                $pdf->MultiCell($cw_0, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $mj = $j;

                                $pdf->SetXY($current_x + $cw_0, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($titulo);
                                $pdf->MultiCell($cw_1, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_1, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_1, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($nome);
                                $pdf->MultiCell($cw_2, $ch_row, $text, $cb_row,$ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_2, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_2, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($admin);
                                $pdf->MultiCell($cw_3, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_3, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_3, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($data_emp);
                                $pdf->MultiCell($cw_4, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_4, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_4, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($data_prev_dev);
                                $pdf->MultiCell($cw_5, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_5, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_5, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($devolvido);
                                $pdf->MultiCell($cw_6, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_6, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->SetXY($current_x + $cw_6, $current_y);
                                $current_x = $pdf->GetX();
                                $text = utf8_decode($data_dev);
                                $pdf->MultiCell($cw_7, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                                $j = $pdf->GetMultiCellHeight($cw_7, $ch_row, $text, $cb_row, $ca_row);
                                if($j > $mj){
                                    $mj = $j;
                                }

                                $pdf->Ln($mj);
                            }
                        }
                    } 
                    else {
                        
                    }

                    mysqli_close($conn);
                } catch (Exception $e) {
                    
                }
                break;

            
            
            case 'log':

                $selected = 'adm_weblog';
                $pdf->SetFont($doc_font,'',18);
                $text = utf8_decode('Log de Administração');
                $pdf->Cell(190,10,$text,0,1,'C');
        
                $pdf->SetFont($doc_font,'B',12);
                $text = utf8_decode('('.date('d/m/Y').')');
                $pdf->Cell(190,3,$text,0,1,'C');
        
                $pdf->Ln(5);
        
                $table_font = "Courier";
                $table_fontsize = 9;
                
                $pdf->SetFont($table_font,'',$table_fontsize);

                
                $file = file_get_contents('log.html');
                $file = str_replace('<!DOCTYPE html> <head> <meta charset="utf-8"> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta http-equiv="X-UA-Compatible" content="ie=edge"> <title>Log - Administração</title> <link rel="stylesheet" href="../css/main.css"> <link rel="shortcut icon" href="../favicon.ico"> </head> <h1>Log de Administração</h1> <hr>', "", $file);

                $file = str_replace('<br>', "\n", $file);

                $cw_row = 190;
                $ch_row = 4;
                $cb_row = '';
                $ca_row = 'L';
                $cf_row = false;

                $text = utf8_decode($file);
                $pdf->MultiCell($cw_row, $ch_row, $text, $cb_row, $ca_row, $cf_row);
                
                break;
            
            default:
                echo "<script>window.close();</script>";
        }
        
        $pdf->Output("I", "$selected.pdf");
    }
    else
    {
        echo "<script>window.close();</script>";
    }
?>