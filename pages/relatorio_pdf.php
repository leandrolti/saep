<?php
require('fpdf/fpdf.php');

class PDF extends FPDF {
    // Cabeçalho
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'RESULTADO PRELIMINAR: EDITAL Nº 01/2024 – PROCESSO SELETIVO/ ANO LETIVO 2025', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'CURSO TÉCNICO EM ADMINISTRAÇÃO', 0, 1, 'C');
        $this->Ln(5);
    }
    
    // Rodapé
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    
    // Tabela de resultados
    function ResultTable($header, $data, $titulo, $categoria) {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, $titulo, 0, 1, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, $categoria, 0, 1, 'L');
        $this->Ln(2);
        
        if (empty($data)) {
            $this->SetFont('Arial', 'I', 10);
            $this->Cell(0, 10, 'Nenhum estudante encontrado nesta categoria.', 0, 1, 'C');
            $this->Ln(5);
            return;
        }
        
        // Cabeçalho da tabela
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 7);
        
        // Larguras ajustadas para landscape
        $w = array(12, 15, 50, 10, 10, 10, 10, 10, 10, 10, 10, 15);
        
        for($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Dados
        $this->SetFont('Arial', '', 6);
        $fill = false;
        
        foreach($data as $row) {
            // Verificar se precisa de nova página
            if($this->GetY() > 190) {
                $this->AddPage();
                // Recriar cabeçalho da tabela
                $this->SetFillColor(200, 200, 200);
                $this->SetFont('Arial', 'B', 7);
                for($i = 0; $i < count($header); $i++) {
                    $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
                }
                $this->Ln();
                $this->SetFont('Arial', '', 6);
            }
            
            // Preencher células
            $this->Cell($w[0], 6, $row['classificacao'] ?? '', 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $row['inscricao'] ?? '', 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, substr($row['nome'] ?? '', 0, 30), 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['port'] ?? '', 'LR', 0, 'C', $fill);
            $this->Cell($w[4], 6, $row['art'] ?? '', 'LR', 0, 'C', $fill);
            $this->Cell($w[5], 6, $row['edf'] ?? '', 'LR', 0, 'C', $fill);
            $this->Cell($w[6], 6, $row['ing'] ?? '', 'LR', 0, 'C', $fill);
            $this->Cell($w[7], 6, $row['mat'] ?? '', 'LR', 0, 'C', $fill);
            $this->Cell($w[8], 6, $row['cien'] ?? '', 'LR', 0, 'C', $fill);
            $this->Cell($w[9], 6, $row['media'] ?? '', 'LR', 0, 'C', $fill);
            $this->Cell($w[10], 6, $row['resultado'] ?? '', 'LR', 0, 'C', $fill);
            $this->Ln();
            
            $fill = !$fill;
        }
        
        // Fechar a tabela
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln(10);
    }
}

// Função para gerar o relatório
function gerarRelatorioPDF($pdo, $filtro_rede = null, $filtro_concorrencia = null) {
    // Consulta corrigida com base na estrutura real do banco
    $sql = "SELECT 
                a.idtb_aluno as inscricao,
                a.nome_aluno as nome,
                n.port,
                n.art,
                n.edf,
                n.ing,
                n.mat,
                n.cien,
                a.media,
                'CLASSIFICADO(A)' as resultado,
                a.escolaridae as rede,
                CASE 
                    WHEN a.deficiente = 'defSim' THEN 'PCD'
                    WHEN a.proximidade = 'prox1' THEN 'PROXIMO'
                    ELSE 'AMPLA'
                END as concorrencia
            FROM tb_saep_aluno a
            INNER JOIN tb_saep_notas n ON a.idtb_aluno = n.tb_aluno_idtb_aluno
            INNER JOIN tb_saep_cursosEscola c ON a.curso = c.idCursos
            WHERE c.nomeCurso = 'Administração'";
    
    $params = array();
    
    // Mapear valores dos filtros para os valores reais do banco
    if ($filtro_rede && $filtro_rede != '') {
        $sql .= " AND a.escolaridae = ?";
        // Mapear PUBLICA/PRIVADA para os valores reais (esc1, esc2)
        $valor_rede = ($filtro_rede == 'PUBLICA') ? 'esc1' : 'esc2';
        $params[] = $valor_rede;
    }
    
    if ($filtro_concorrencia && $filtro_concorrencia != '') {
        if ($filtro_concorrencia == 'PCD') {
            $sql .= " AND a.deficiente = 'defSim'";
        } elseif ($filtro_concorrencia == 'PROXIMO') {
            $sql .= " AND a.proximidade = 'prox1'";
        } else {
            $sql .= " AND (a.deficiente = 'defNao' OR a.deficiente IS NULL) 
                     AND (a.proximidade = 'prox2' OR a.proximidade IS NULL)";
        }
    }
    
    $sql .= " ORDER BY a.media DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Criar PDF
    $pdf = new PDF('L');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);
    
    // Texto introdutório
    $pdf->MultiCell(0, 8, "A diretora da Escola Estadual de Educação Profissional José Vidal Alves, Kátia Romilda Silva do Nascimento, no uso de suas atribuições e em conformidade com o referido Edital, que estabelece as normas para o ingresso dos(as) novos(as) estudantes na 1ª série do Ensino Médio Integrado à Educação Profissional para o ano letivo de 2025, torna público o resultado PRELIMINAR do processo seletivo.");
    $pdf->Ln(5);
    
    if (empty($alunos)) {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'NENHUM ALUNO ENCONTRADO COM OS FILTROS SELECIONADOS', 0, 1, 'C');
        $pdf->Output('I', 'resultado_preliminar_administracao.pdf');
        return;
    }
    
    // Adicionar classificação
    $classificacao = 1;
    foreach ($alunos as &$aluno) {
        $aluno['classificacao'] = $classificacao++;
    }
    
    // Cabeçalhos da tabela
    $header = array('CLASS.', 'INSC.', 'NOME', 'POR', 'ART', 'EDF', 'ING', 'MAT', 'CIE', 'MF', 'RESULT.');
    
    // Separar alunos por categoria
    $categorias = array();
    foreach ($alunos as $aluno) {
        $chave = ($aluno['rede'] ?? '') . '|' . ($aluno['concorrencia'] ?? '');
        if (!isset($categorias[$chave])) {
            $categorias[$chave] = array();
        }
        $categorias[$chave][] = $aluno;
    }
    
    // Gerar tabelas para cada categoria
    foreach ($categorias as $chave => $dados) {
        list($rede, $concorrencia) = explode('|', $chave);
        
        // Traduzir valores do banco para o relatório
        $rede_texto = ($rede == 'esc1') ? 'PÚBLICA' : (($rede == 'esc2') ? 'PRIVADA' : $rede);
        $concorrencia_texto = ($concorrencia == 'PCD') ? 'PESSOA COM DEFICIÊNCIA (PCD)' : 
                              (($concorrencia == 'PROXIMO') ? 'PRÓXIMO AO TERRITÓRIO' : 'AMPLA');
        
        $titulo = "ESTUDANTES CONVOCADOS(AS) PARA MATRÍCULA";
        $categoria = "REDE: " . $rede_texto . " | CONCORRÊNCIA: " . $concorrencia_texto;
        
        $pdf->ResultTable($header, $dados, $titulo, $categoria);
    }
    
    // Observações
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 8, "OBSERVAÇÕES:\n\n- Os pais ou responsáveis pelos(as) estudantes CLASSIFICADOS(AS) devem comparecer à EEEP José Vidal Alves para participar do Seminário de Apresentação da Filosofia e Rotina da Escola, realizar a assinatura do Termo de Adesão e efetivar a matrícula no curso. A data e o horário do evento serão divulgados nas redes sociais da escola. A PRESENÇA DO(A) ESTUDANTE É OBRIGATÓRIA.");
    
    // Rodapé com informações da escola
    $pdf->SetY(-40);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Cell(0, 5, 'Escola Estadual de Educação Profissional José Maria Falcão | crede', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Rua. Raimundo Correia Lima, s/n, Cruz das Almas, Pacajus CE – CEP: 62870000', 0, 1, 'C');
    $pdf->Cell(0, 5, 'CNPJ: 07.845.14077-0015 - 23047809', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Telefone: (85)3348-1599 | e-mail: josemaria@escola.ce.gov.br', 0, 1, 'C');
    
    // Data e assinatura
    $pdf->SetY(-20);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'CANINDÉ, ' . date('d') . ' DE ' . strtoupper(strftime('%B', time())) . ' DE ' . date('Y') . '.', 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, 'KATIA ROMILDA SILVA DO NASCIMENTO', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 5, 'DIRETORA ESCOLA', 0, 1, 'C');
    
    // Saída do PDF
    $pdf->Output('I', 'resultado_preliminar_administracao.pdf');
}

// Conexão e execução
try {
    $pdo = new PDO('mysql:host=localhost;dbname=saep', 'root', 'bdjmf');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $filtro_rede = $_GET['rede'] ?? null;
    $filtro_concorrencia = $_GET['concorrencia'] ?? null;
    
    // Verificar se os filtros estão vazios
    if ($filtro_rede === '') $filtro_rede = null;
    if ($filtro_concorrencia === '') $filtro_concorrencia = null;
    
    gerarRelatorioPDF($pdo, $filtro_rede, $filtro_concorrencia);
    
} catch(PDOException $e) {
    // Criar PDF de erro
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'ERRO NO BANCO DE DADOS: ' . $e->getMessage(), 0, 1);
    $pdf->Output('I', 'erro.pdf');
}