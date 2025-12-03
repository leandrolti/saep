<?php
// arquivo: gerar_relatorio.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerar Relatório de Resultados</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { text-align: center; color: #0066cc; }
        form { background: #f9f9f9; padding: 20px; border-radius: 8px; }
        label { display: block; margin: 10px 0 5px; }
        select, button { width: 100%; padding: 10px; margin: 5px 0 15px; }
        button { background: #0066cc; color: white; border: none; cursor: pointer; }
        button:hover { background: #0055aa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gerar Relatório de Resultados</h1>
        <form action="relatorio_pdf.php" method="get">
            <label for="rede">Rede de Ensino:</label>
            <select id="rede" name="rede">
                <option value="">Todas as Redes</option>
                <option value="PUBLICA">Rede Pública</option>
                <option value="PRIVADA">Rede Privada</option>
            </select>
            
            <label for="concorrencia">Tipo de Concorrência:</label>
            <select id="concorrencia" name="concorrencia">
                <option value="">Todas as Concorrências</option>
                <option value="AMPLA">Ampla Concorrência</option>
                <option value="PROXIMO">Próximo ao Território</option>
                <option value="PCD">Pessoa com Deficiência (PCD)</option>
            </select>
            
            <button type="submit">Gerar Relatório em PDF</button>
        </form>
    </div>
</body>
</html>