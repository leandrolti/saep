<?php
include('../config/conexao.php');
session_start();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Debug - PDF Selecionados</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug-section { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        pre { background: #eee; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>Debug - Sistema de Seleção</h1>";

// DEBUG 1: Verificar Banco de Dados
echo "<div class='debug-section'>
        <h2>1. Verificação do Banco de Dados</h2>";

$sql_quantidade = "SELECT qtd_aluno FROM tb_saep_edital ORDER BY id_edital DESC LIMIT 1";
$resultado = $conexao->query($sql_quantidade);

if (!$resultado) {
    echo "<p class='error'>❌ ERRO na query: " . print_r($conexao->errorInfo(), true) . "</p>";
} else {
    echo "<p class='success'>✅ Query executada com sucesso</p>";
    
    $config = $resultado->fetch(PDO::FETCH_ASSOC);
    
    if ($config) {
        echo "<p class='success'>✅ Registro encontrado:</p>";
        echo "<pre>";
        print_r($config);
        echo "</pre>";
        
        $quantidade_selecionados = (int)$config['qtd_aluno'];
        echo "<p class='info'>Quantidade de selecionados (qtd_aluno): <strong>" . $quantidade_selecionados . "</strong></p>";
    } else {
        $quantidade_selecionados = 40;
        echo "<p class='error'>❌ Nenhum registro encontrado na tabela tb_saep_edital</p>";
        echo "<p class='info'>Usando valor padrão: <strong>" . $quantidade_selecionados . "</strong></p>";
    }
}
echo "</div>";

// DEBUG 2: Verificar Sessão
echo "<div class='debug-section'>
        <h2>2. Verificação da Sessão</h2>";

if (isset($_SESSION['todos'])) {
    echo "<p class='success'>✅ Sessão 'todos' existe</p>";
    echo "<p class='info'>Quantidade de turmas: " . count($_SESSION['todos']) . "</p>";
    
    foreach ($_SESSION['todos'] as $nome => $turma) {
        echo "<p class='info'>Turma: <strong>" . $nome . "</strong> - Quantidade de alunos: " . count($turma) . "</p>";
    }
} else {
    echo "<p class='error'>❌ Sessão 'todos' NÃO existe</p>";
    echo "<p class='info'>Dica: Volte para a página anterior e gere a lista novamente</p>";
}
echo "</div>";

// DEBUG 3: Verificar todos os editais na tabela
echo "<div class='debug-section'>
        <h2>3. Todos os Editais na Tabela</h2>";

$sql_all = "SELECT id_edital, qtd_aluno FROM tb_saep_edital ORDER BY id_edital";
$result_all = $conexao->query($sql_all);

if ($result_all) {
    $editais = $result_all->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($editais) > 0) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>
                <tr><th>ID Edital</th><th>Qtd Alunos</th></tr>";
        foreach ($editais as $edital) {
            echo "<tr><td>" . $edital['id_edital'] . "</td><td>" . $edital['qtd_aluno'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>Nenhum edital encontrado na tabela</p>";
    }
} else {
    echo "<p class='error'>Erro ao buscar editais</p>";
}

echo "</div>";

// DEBUG 4: Resumo Final
echo "<div class='debug-section'>
        <h2>4. Resumo Final</h2>";

echo "<p class='info'>Quantidade de selecionados que será usada: <strong>" . $quantidade_selecionados . "</strong></p>";

if (isset($_SESSION['todos']) && $quantidade_selecionados > 0) {
    echo "<p class='success'>✅ Tudo pronto para gerar o PDF!</p>";
    echo "<p><a href='dompdf_geral_3.php' target='_blank'>👉 Clique aqui para Gerar PDF</a></p>";
} else {
    echo "<p class='error'>❌ Há problemas que impedem a geração do PDF</p>";
    
    if (!isset($_SESSION['todos'])) {
        echo "<p class='error'>- Sessão 'todos' não carregada</p>";
    }
    if ($quantidade_selecionados <= 0) {
        echo "<p class='error'>- Quantidade de selecionados inválida</p>";
    }
}

echo "</div>";

echo "</body></html>";
?>