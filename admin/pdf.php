<?php
    require('../config/fpdf/fpdf.php');
    
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
                    
                    $text = utf8_decode($count.' livro'.$s. ' registrado'.$s);
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
                    
                    $text = utf8_decode($count.' administrador'.$es. ' registrado'.$s);
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