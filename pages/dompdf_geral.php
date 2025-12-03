<?php

include('../config/conexao.php');
session_start();

// BUSCAR O QUANTITATIVO DE SELECIONADOS - CORRIGIDO PARA PDO
$sql_quantidade = "SELECT qtd_aluno FROM tb_saep_edital ORDER BY id_edital DESC LIMIT 1";
$resultado = $conexao->query($sql_quantidade);

if ($resultado) {
    $config = $resultado->fetch(PDO::FETCH_ASSOC);
    if ($config) {
        $quantidade_selecionados = (int)$config['qtd_aluno'];
    } else {
        $quantidade_selecionados = 40;
    }
} else {
    $quantidade_selecionados = 40;
}

// DEBUG TEMPORÁRIO - remover depois de testar
// echo "<!-- DEBUG: Quantidade de selecionados = " . $quantidade_selecionados . " -->";

// Agora continua com o código normal
if (isset($_SESSION['todos'])) {
    $todos = $_SESSION['todos'];
    $cont = 1;
    $qtdTurmas = count($todos);
    $html = '<!DOCTYPE html>
        <html lang="pt_br">
        <head>
            <meta charset = "utf-8">
            <style>
            *{font-family: sans-serif;            }
                p{
                    text-align:justify;
                }
                header{
                    text-align: center;
                    text-transform: uppercase;
                }
                .tabela, th, td{
                    border-collapse: collapse; /*define a separação entre as bordar*/
                    padding: 3px;
                    text-align: left;
                    font-size:13px;
                }
                .tabela{
                    width: 100%;
                }
                th{
                    background-color: lightgray;
                }
            </style>
        </head>
        <body>
            ';

    foreach ($todos as $nome => $turma) {
        if (count($turma) > 0) {
            $html .= '
                <header>
                    <h4>
                        SELECIONADOS AO CURSO DE ' . $nome . '
                    </h4>
                </header>

                <br>

                <table border="1" class="tabela">
                    <tr>
                        <th>Nº</th>
                        <th>NOME</th>
                        <th>MÉDIA</th>
                        <th>CURSO</th>
                        <th>ESC.</th>
                        <th>PROXI.</th>
                        <th>DEF.</th>
                    </tr>
                    
                    ';

            // USANDO O VALOR DINÂMICO DO BANCO (qtd_aluno)
            for ($i = 0; $i < min($quantidade_selecionados, count($turma)); $i++) {
                $linha = $turma[$i];
                $html .= '
                        <tr>
                            <td>' . $linha[0] . '</td>
                            <td>' . $linha[1] . '</td>
                            <td>' . number_format($linha[2], '3', '.', '') . '</td>
                            <td>' . $linha[5] . '</td>
                            <td>' . $linha[3] . '</td>
                            <td>' . $linha[6] . '</td>
                            <td>' . $linha[7] . '</td>
                        </tr>
                        ';
            }
            $html .= '</table>';

            // USANDO O VALOR DINÂMICO DO BANCO (qtd_aluno)
            if (count($turma) > $quantidade_selecionados) {
                $i = $quantidade_selecionados;

                // CLASSIFICÁVEIS DE ESCOLA PÚBLICA
                if ($i < count($turma) && $turma[$i][3] == 'Pública') {

                    $html .= '<pagebreak></pagebreak><br><header><h4> CLASSIFICÁVEIS(NÃO SELECIONADOS) DE ESCOLA PÚBLICA - ' . $turma[$i][5] . ' </h4></header><br>'
                        . '<table style = "width:100%;border-collapse: collapse; text-align: center;" border = "1">
                        <tr>
                        <th>Nº</th>
                        <th>NOME</th>
                        <th>MÉDIA</th>
                        <th>CURSO</th>
                        <th>ESC.</th>
                        <th>PROXI.</th>
                    </tr>';
                    for (; $i < count($turma); $i++) {
                        if ($turma[$i][3] != 'Pública') break;
                        $linha = $turma[$i];
                        $html .= '
                        <tr>
                            <td>' . ($linha[0] - $quantidade_selecionados) . '</td>
                            <td>' . $linha[1] . '</td>
                            <td>' . number_format($linha[2], '3', '.', '')  . '</td>
                            <td>' . $linha[5] . '</td>
                            <td>' . $linha[3] . '</td>
                            <td>' . $linha[6] . '</td>
                        </tr>
                        ';
                    }
                    $html .= '</table>';
                }


                // CLASSIFICÁVEIS DE ESCOLA PARTICULAR
                if ($i < count($turma) && $turma[$i][3] == 'Particular') {

                    $html .= '<pagebreak></pagebreak><br><header><h4> CLASSIFICÁVEIS(NÃO SELECIONADOS) DE ESCOLA PARTICULAR - ' . $turma[$i][5] . ' </h4></header><br>'
                        . '<table style = "width:100%;border-collapse: collapse; text-align: center;" border = "1">
                        <tr>
                        <th>Nº</th>
                        <th>NOME</th>
                        <th>MÉDIA</th>
                        <th>CURSO</th>
                        <th>ESC.</th>
                        <th>PROXI.</th>
                    </tr>';

                    $j = $i;

                    for (; $i < count($turma); $i++) {
                        $linha = $turma[$i];
                        $html .= '
                        <tr>
                            <td>' . ($linha[0] - $j) . '</td>
                            <td>' . $linha[1] . '</td>
                            <td>' . number_format($linha[2], '3', '.', '')  . '</td>
                            <td>' . $linha[5] . '</td>
                            <td>' . $linha[3] . '</td>
                            <td>' . $linha[6] . '</td>
                        </tr>
                        ';
                    }
                    $html .= '</table>';
                }
            }

            $html .= '
                
                <br><br><br><br><br>';

            $cont++;
            if ($cont <= $qtdTurmas) {
                $html .= '<pagebreak> 
                ';
            }
        }
    }
    $html .= '
            </body>
        </html>';
} else {
    // header("Location: ../home.php?acao=welcome");
    echo "Não foi possivel acessar";
}

// ... resto do código do Dompdf

//AUTOLOAD DO COMPOSER
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

//INSTANCIA DE OPTIONS
$options = new Options();
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(true);

//INSTANCIA DE DOMPDF
$dompdf = new Dompdf($options);

//CARREGA O HTML
$dompdf->loadHtml($html);

//PAGINA
$dompdf->setPaper('A4', 'portrait');

//REDERIZAR PDF
$dompdf->render();

//IMPRIMI COMTEUDO DO ARQUIVO PDF NA TELA
header('Content-type: application/pdf');
echo $dompdf->output();