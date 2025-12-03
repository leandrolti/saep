<?php

include('../config/conexao.php');
session_start();
if (isset($_SESSION['todos'])) {
    $todos = $_SESSION['todos'];
    $cont = 1;
    $qtdTurmas = count($todos);
    $html = '<!DOCTYPE html>
        <html lang="pt_br">
        <head>
            <meta charset = "utf-8">
            <style>
            *{font-family: sans-serif;}
                p{
                    text-align:justify;
                }
                header{
                    text-align: center;
                    text-transform: uppercase;
                }
                .tabela, th, td{
                    border-collapse: collapse;
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
                .categoria-titulo {
                    background-color: #f0f0f0;
                    padding: 10px;
                    margin-top: 20px;
                    border-left: 5px solid #333;
                    font-weight: bold;
                    font-size: 16px;
                }
                .subcategoria-titulo {
                    background-color: #e8e8e8;
                    padding: 8px;
                    margin-top: 15px;
                    border-left: 4px solid #666;
                    font-weight: bold;
                    font-size: 14px;
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
                        PRÉ-SELEÇÃO ' . $nome . '
                    </h4>
                </header>
                <br>';

            // Separar candidatos por categoria
            $publica = [];
            $particular = [];
            $pcd = [];
            $proximidade_publica = [];
            $proximidade_particular = [];
            $geral = [];

            // Classificar os candidatos
            foreach ($turma as $candidato) {
                if ($candidato[7] == 'Sim') { // PCD
                    $pcd[] = $candidato;
                } elseif ($candidato[6] == 'Sim') { // Proximidade
                    if ($candidato[3] == 'Pública') {
                        $proximidade_publica[] = $candidato;
                    } elseif ($candidato[3] == 'Particular') {
                        $proximidade_particular[] = $candidato;
                    }
                } elseif ($candidato[3] == 'Pública') { // Escola pública
                    $publica[] = $candidato;
                } elseif ($candidato[3] == 'Particular') { // Escola particular
                    $particular[] = $candidato;
                }
                $geral[] = $candidato; // Todos os candidatos
            }

            // Ordenar por média (decrescente) dentro de cada categoria
            usort($publica, function($a, $b) { return $b[2] <=> $a[2]; });
            usort($particular, function($a, $b) { return $b[2] <=> $a[2]; });
            usort($pcd, function($a, $b) { return $b[2] <=> $a[2]; });
            usort($proximidade_publica, function($a, $b) { return $b[2] <=> $a[2]; });
            usort($proximidade_particular, function($a, $b) { return $b[2] <=> $a[2]; });

            // CLASSIFICADOS GERAIS (primeiros 45)
            $html .= '<div class="categoria-titulo">CLASSIFICADOS GERAIS</div>';
            $html .= '
                <table border="1" class="tabela">
                    <tr>
                        <th>Nº</th>
                        <th>NOME</th>
                        <th>MÉDIA</th>
                        <th>CURSO</th>
                        <th>ESC.</th>
                        <th>PROXI.</th>
                        <th>DEF.</th>
                    </tr>';

            for ($i = 0; $i < min(45, count($geral)); $i++) {
                $linha = $geral[$i];
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

            // CANDIDATOS PCD
            if (count($pcd) > 0) {
                $html .= '<pagebreak></pagebreak>';
                $html .= '<div class="categoria-titulo">CANDIDATOS PCD - ' . $nome . '</div>';
                $html .= '
                    <table border="1" class="tabela">
                        <tr>
                            <th>Nº</th>
                            <th>NOME</th>
                            <th>MÉDIA</th>
                            <th>CURSO</th>
                            <th>ESC.</th>
                            <th>PROXI.</th>
                            <th>DEF.</th>
                        </tr>';

                foreach ($pcd as $index => $linha) {
                    $html .= '
                        <tr>
                            <td>' . ($index + 1) . '</td>
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
            }

            // CANDIDATOS POR PROXIMIDADE - ESCOLA PÚBLICA
            if (count($proximidade_publica) > 0) {
                $html .= '<pagebreak></pagebreak>';
                $html .= '<div class="categoria-titulo">CANDIDATOS POR PROXIMIDADE - ' . $nome . '</div>';
                $html .= '<div class="subcategoria-titulo">ESCOLA PÚBLICA</div>';
                $html .= '
                    <table border="1" class="tabela">
                        <tr>
                            <th>Nº</th>
                            <th>NOME</th>
                            <th>MÉDIA</th>
                            <th>CURSO</th>
                            <th>ESC.</th>
                            <th>PROXI.</th>
                            <th>DEF.</th>
                        </tr>';

                foreach ($proximidade_publica as $index => $linha) {
                    $html .= '
                        <tr>
                            <td>' . ($index + 1) . '</td>
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
            }

            // CANDIDATOS POR PROXIMIDADE - ESCOLA PARTICULAR
            if (count($proximidade_particular) > 0) {
                $html .= '<pagebreak></pagebreak>';
                $html .= '<div class="subcategoria-titulo">ESCOLA PARTICULAR</div>';
                $html .= '
                    <table border="1" class="tabela">
                        <tr>
                            <th>Nº</th>
                            <th>NOME</th>
                            <th>MÉDIA</th>
                            <th>CURSO</th>
                            <th>ESC.</th>
                            <th>PROXI.</th>
                            <th>DEF.</th>
                        </tr>';

                foreach ($proximidade_particular as $index => $linha) {
                    $html .= '
                        <tr>
                            <td>' . ($index + 1) . '</td>
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
            }

            // CANDIDATOS DE ESCOLA PÚBLICA (SEM PROXIMIDADE)
            if (count($publica) > 0) {
                $html .= '<pagebreak></pagebreak>';
                $html .= '<div class="categoria-titulo">CANDIDATOS DE ESCOLA PÚBLICA - ' . $nome . '</div>';
                $html .= '
                    <table border="1" class="tabela">
                        <tr>
                            <th>Nº</th>
                            <th>NOME</th>
                            <th>MÉDIA</th>
                            <th>CURSO</th>
                            <th>ESC.</th>
                            <th>PROXI.</th>
                            <th>DEF.</th>
                        </tr>';

                foreach ($publica as $index => $linha) {
                    $html .= '
                        <tr>
                            <td>' . ($index + 1) . '</td>
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
            }

            // CANDIDATOS DE ESCOLA PARTICULAR (SEM PROXIMIDADE)
            if (count($particular) > 0) {
                $html .= '<pagebreak></pagebreak>';
                $html .= '<div class="categoria-titulo">CANDIDATOS DE ESCOLA PARTICULAR - ' . $nome . '</div>';
                $html .= '
                    <table border="1" class="tabela">
                        <tr>
                            <th>Nº</th>
                            <th>NOME</th>
                            <th>MÉDIA</th>
                            <th>CURSO</th>
                            <th>ESC.</th>
                            <th>PROXI.</th>
                            <th>DEF.</th>
                        </tr>';

                foreach ($particular as $index => $linha) {
                    $html .= '
                        <tr>
                            <td>' . ($index + 1) . '</td>
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
            }

            $html .= '<br><br><br><br><br>';

            $cont++;
            if ($cont <= $qtdTurmas) {
                $html .= '<pagebreak></pagebreak>';
            }
        }
    }
    $html .= '
            </body>
        </html>';
} else {
    echo "Não foi possivel acessar";
}

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

//IMPRIMI CONTEÚDO DO ARQUIVO PDF NA TELA
header('Content-type: application/pdf');
echo $dompdf->output();
?>