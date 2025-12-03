<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl" style="background-image: url('../assets/img/curved-images/back-est.png'); background-position-y: 50%;">
    </div>
    <div class="row">
    <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="../assets/img/user/<?php echo $show->img; ?>"" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                <?php echo $nome_user; ?>
              </h5>
              <p class="mb-0 font-weight-bold text-sm">
              <?php echo $show->cargo; ?> | <?php if($show->nivel==0){echo '<strong style="color:#43a85e">Admin</strong>';}else{echo '<strong style="color:#fdca0a">Inscrição</strong>';} ?>
              </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
            <div class="nav-wrapper position-relative end-0">
              <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                
                
                
              </ul>
            </div>
          </div>
        </div>
       <!-- Lista de Alunos Inscritos -->
        <!-- CONSULTAR TURMAS -->
<?php
$select = "SELECT tb_saep_cursos.id_cursos,tb_saep_cursos.id_curso_escola_cursos,tb_saep_cursos.status_curso,tb_saep_cursos.ano_cursos,tb_saep_cursosEscola.idCursos,tb_saep_cursosEscola.nomeCurso,tb_saep_cursosEscola.imgCurso FROM tb_saep_cursos INNER JOIN tb_saep_cursosEscola ON tb_saep_cursos.id_curso_escola_cursos=tb_saep_cursosEscola.idCursos WHERE tb_saep_cursos.id_curso_escola_cursos=tb_saep_cursosEscola.idCursos ORDER BY nomeCurso ASC";
$resultado = $conexao->prepare($select);
$resultado->execute();
?>
<section id="team">
    <div class="container">
        <h2 style="text-align: center;padding-top: 50px">Lista geral dos alunos inscritos</h2>
        <hr>
        
        <!-- SISTEMA DE BUSCA -->
        <div class="row mb-4">
            <div class="col-lg-8 offset-lg-2">
                <form method="GET" action="">
                    <style>
    .grupof {
        display: flex;
        gap: 8px;
        align-items: stretch;
        flex-wrap: wrap;
    }
    
    .inputf {
        flex: 1;
        min-width: 200px;
        padding: 10px 14px;
        color: #444;
        border: 1px solid #48a963;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    
    .inputf:focus {
        outline: none;
        border-color: #3a8c50;
        box-shadow: 0 0 0 3px rgba(72, 169, 99, 0.1);
    }
    
    .botaof {
        background: #48a963;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s ease, transform 0.1s ease;
        white-space: nowrap;
    }
    
    .botaof:hover {
        background: #3a8c50;
    }
    
    .botaof:active {
        transform: scale(0.98);
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s ease, transform 0.1s ease;
        white-space: nowrap;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
    }
    
    .btn-secondary:active {
        transform: scale(0.98);
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .grupof {
            flex-direction: column;
        }
        
        .inputf {
            width: 100%;
            min-width: 100%;
        }
        
        .botaof,
        .btn-secondary {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .inputf,
        .botaof,
        .btn-secondary {
            padding: 12px 16px;
            font-size: 15px;
        }
    }
</style>

<div class="grupof">
    <input type="hidden" name="esc" value="lista">
    <input type="text" class="inputf" name="busca" placeholder="Digite o nome do aluno para buscar..." value="<?php echo isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : ''; ?>">
    <button class="botaof" type="submit">
        <i class="fa fa-search"></i> Buscar
    </button>
    <?php if(isset($_GET['busca']) && !empty($_GET['busca'])): ?>
    <a href="home.php?esc=lista" class="btn-secondary">
        <i class="fa fa-times"></i> Limpar
    </a>
    <?php endif; ?>
</div>
                </form>
                <?php if(isset($_GET['busca']) && !empty($_GET['busca'])): ?>
                <div class="alert alert-info mt-2">
                    <strong>Buscando por:</strong> "<?php echo htmlspecialchars($_GET['busca']); ?>"
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- FIM SISTEMA DE BUSCA -->
        
        <div class="row wow fadeInUp" data-wow-delay=".3s">
            <?php
$todos = array(); //ARRAY 'TODOS' QUE VAI RECEBER OS AS TABELAS DE CADA TURMA
$contar = $resultado->rowCount();

// Captura o termo de busca
$termoBusca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$filtrandoBusca = !empty($termoBusca);

if ($contar > 0) {

    while ($variaveis = $resultado->FETCH(PDO::FETCH_OBJ)) {
?>
        <div class="row">
            <div class="team">
                <h4 style="text-align: center; text-transform:uppercase; font-weight:bold; padding:50px 0 50px 0">Pré seleção <?php echo $variaveis->nomeCurso ?> </h4>
                
                <div class="team-social-icons">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nome</th>
                                <th>Média</th>
                                <th>Curso</th>
                                <th>Esc.</th>
                                <th>Proxi.</th>
                                <th>Defic.</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <?php
                        //DELETARA ALUNOS
                        if (!empty(filter_input(INPUT_GET, 'id', FILTER_DEFAULT))):
                            $id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);

                            $delete = "DELETE FROM tb_aluno WHERE idtb_aluno=?";
                            $del = "DELETE FROM tb_notas WHERE tb_aluno_idtb_aluno=?";
                            $selecionar = "SELECT nome_aluno FROM tb_aluno WHERE idtb_aluno=:id";
                            try {
                                $resu = $conexao->prepare($selecionar);
                                $resu->bindParam(':id', $id);
                                $resu->execute();
                                $contar = $resu->rowCount();
                                if ($contar > 0) {
                                    while ($show = $resu->FETCH(PDO::FETCH_OBJ)) {
                                        try {
                                            $result = $conexao->prepare($delete);
                                            $result->bindValue(1, $id, PDO::PARAM_INT);
                                            $result->execute();

                                            //CONTAR REGISTROS
                                            $contar = $result->rowCount();
                                            if ($contar > 0) {
                                                try {
                                                    $res = $conexao->prepare($del);
                                                    $res->bindValue(1, $id, PDO::PARAM_INT);
                                                    $res->execute();
                                                    echo "<div class='row'><div class='col-md-12 col-sm-12'><div class='alert alert-success' role='alert'>Aluno $show->nome_aluno deletado com <strong>sucesso!</strong>.</div></div></div>";
                                                    header("Refresh: 1, #team");
                                                } catch (PDOException $e) {
                                                    echo $e;
                                                }
                                            } else {
                                                echo "<div class='row'><div class='col-md-12 col-sm-12'><div class='alert alert-danger' role='alert'> <strong>Erro</strong> ao deletar.</div></div></div>";
                                                header("Refresh: 1, #team");
                                            }
                                        } catch (PDOException $e) {
                                            echo $e;
                                        }
                                    }
                                }
                            } catch (PDOException $e) {
                                echo $e;
                            }

                        endif;
                        //FIM DELETE
                        $turma = array(); //ARRAY QUE VAI RECEBER AS LINHAS DA TABELA
                        
                        //SELECIONAR DADOS DO EDITAL
                        $selectEdital = "SELECT * FROM tb_saep_edital LIMIT 1";
                        $resultEdital = $conexao->prepare($selectEdital);
                        $resultEdital->execute();
                        $edital = $resultEdital->fetch(PDO::FETCH_OBJ);
                        
                        // ========== CÁLCULOS DAS VAGAS (AGORA ANTES DAS CONSULTAS) ==========
                        $total_vagas = $edital->qtd_aluno; // 45

                        $vagas_deficiente = $edital->com_laudo; // 2
                        $vagas_publica_prox = $edital->aluno_prox_pul; // 10
                        $vagas_particular_prox = $edital->aluno_prox_part; // 3

                        $vagas_restantes = $total_vagas - $vagas_deficiente - $vagas_publica_prox - $vagas_particular_prox; // 45 - 2 - 10 - 3 = 30

                        $proporcao_publica = $edital->aluno_publica / ($edital->aluno_publica + $edital->aluno_particular); // 36/45 = 0.8
                        $proporcao_particular = $edital->aluno_particular / ($edital->aluno_publica + $edital->aluno_particular); // 9/45 = 0.2

                        $vagas_publica_normal = round($vagas_restantes * $proporcao_publica); // 30 * 0.8 = 24
                        $vagas_particular_normal = $vagas_restantes - $vagas_publica_normal; // 30 - 24 = 6

                        // AGORA DEFINE AS VARIÁVEIS PARA AS CONSULTAS
                        $qtdComLaudo = $vagas_deficiente; // 2
                        $qtdAlunoProxPub = $vagas_publica_prox; // 10
                        $qtdAlunoProxPart = $vagas_particular_prox; // 3

                        // ========== MODIFICAÇÃO: ADICIONA FILTRO DE BUSCA NAS CONSULTAS ==========
                        $filtroNome = "";
                        if ($filtrandoBusca) {
                            $filtroNome = " AND nome_aluno LIKE :termoBusca ";
                        }

                        // AGORA SIM, FAZ AS CONSULTAS SQL
                        //CONSULTA OS ALUNOS PORTADORES DE DEFICIÊNCIA - Para pegar os IDs
                        $selectIds = "SELECT idtb_aluno FROM tb_saep_aluno 
                                    WHERE curso = :curso 
                                    AND deficiente = 'defSim' 
                                    $filtroNome
                                    ORDER BY media DESC 
                                    LIMIT :qtdComLaudo";
                        $resultIds = $conexao->prepare($selectIds);
                        $resultIds->bindParam(':curso', $variaveis->id_cursos, PDO::PARAM_INT);
                        if ($filtrandoBusca) {
                            $buscaParam = '%' . $termoBusca . '%';
                            $resultIds->bindParam(':termoBusca', $buscaParam, PDO::PARAM_STR);
                        }
                        $resultIds->bindParam(':qtdComLaudo', $qtdComLaudo, PDO::PARAM_INT);
                        $resultIds->execute();

                        // Monta a string de IDs
                        $d = $resultIds->fetchColumn(0);
                        while($c = $resultIds->fetchColumn(0)){
                            $d .= ',' . $c;
                        }
                        if($d == '') $d = '0';

                        //CONSULTA OS ALUNOS PORTADORES DE DEFICIÊNCIA - Para exibir os dados completos
                        $select5 = "SELECT * FROM tb_saep_aluno 
                                    WHERE curso = :curso 
                                    AND deficiente = 'defSim' 
                                    $filtroNome
                                    ORDER BY media DESC 
                                    LIMIT :qtdComLaudo";
                        $result5 = $conexao->prepare($select5);
                        $result5->bindParam(':curso', $variaveis->id_cursos, PDO::PARAM_INT);
                        if ($filtrandoBusca) {
                            $result5->bindParam(':termoBusca', $buscaParam, PDO::PARAM_STR);
                        }
                        $result5->bindParam(':qtdComLaudo', $qtdComLaudo, PDO::PARAM_INT);
                        $result5->execute();
                        $contar5 = $result5->rowCount();

                        //PUBLICA
                        //CONSULTA DOS ALUNOS DE ESCOLA PÚBLICA C/ PROXIMIDADE
                        $select = "SELECT * FROM tb_saep_aluno 
                                WHERE curso = :curso 
                                AND escolaridae = 'esc1' 
                                AND proximidade = 'prox1' 
                                AND idtb_aluno NOT IN ($d) 
                                $filtroNome
                                ORDER BY media DESC 
                                LIMIT :qtdAlunoProxPub";
                        $result = $conexao->prepare($select);
                        $result->bindParam(':curso', $variaveis->id_cursos, PDO::PARAM_INT);
                        if ($filtrandoBusca) {
                            $result->bindParam(':termoBusca', $buscaParam, PDO::PARAM_STR);
                        }
                        $result->bindParam(':qtdAlunoProxPub', $qtdAlunoProxPub, PDO::PARAM_INT);
                        $result->execute();
                        $contar = $result->rowCount();

                        $queryLista = "SELECT idtb_aluno FROM tb_saep_aluno 
                                      WHERE curso = $variaveis->id_cursos 
                                      AND escolaridae = 'esc1' 
                                      AND proximidade = 'prox1' 
                                      AND idtb_aluno NOT IN ($d)";
                        if ($filtrandoBusca) {
                            $queryLista .= " AND nome_aluno LIKE " . $conexao->quote($buscaParam);
                        }
                        $queryLista .= " ORDER BY media DESC LIMIT 10";
                        
                        $lista = $conexao->query($queryLista);
                        $s = $lista->fetchColumn(0);
                        while($c = $lista->fetchColumn(0)){$s.=','.$c;}
                        if($s == '') $s = '0' ;

                        //CONSULTA DOS ALUNOS DE ESCOLA PÚBLICA S/ PROXIMIDADE
                        $select3 = "SELECT * FROM tb_saep_aluno 
                                    WHERE curso = :curso 
                                    AND escolaridae = 'esc1' 
                                    AND idtb_aluno NOT IN ($s) 
                                    AND idtb_aluno NOT IN ($d) 
                                    $filtroNome
                                    ORDER BY media DESC";
                        $result3 = $conexao->prepare($select3);
                        $result3->bindParam(':curso', $variaveis->id_cursos, PDO::PARAM_INT);
                        if ($filtrandoBusca) {
                            $result3->bindParam(':termoBusca', $buscaParam, PDO::PARAM_STR);
                        }
                        $result3->execute();
                        $contar3 = $result3->rowCount();

                        //PARTICULAR
                        //CONSULTA DOS ALUNOS DE ESCOLA PARTICULAR C/ PROXIMIDADE - Para pegar IDs
                        $selectIdsPartProx = "SELECT idtb_aluno FROM tb_saep_aluno 
                                            WHERE curso = :curso 
                                            AND escolaridae = 'esc2' 
                                            AND proximidade = 'prox1' 
                                            AND idtb_aluno NOT IN ($d) 
                                            $filtroNome
                                            ORDER BY media DESC 
                                            LIMIT :limite";
                        $resultIdsPartProx = $conexao->prepare($selectIdsPartProx);
                        $resultIdsPartProx->bindParam(':curso', $variaveis->id_cursos, PDO::PARAM_INT);
                        if ($filtrandoBusca) {
                            $resultIdsPartProx->bindParam(':termoBusca', $buscaParam, PDO::PARAM_STR);
                        }
                        $resultIdsPartProx->bindParam(':limite', $qtdAlunoProxPart, PDO::PARAM_INT);
                        $resultIdsPartProx->execute();

                        // Monta a string de IDs
                        $s = $resultIdsPartProx->fetchColumn(0);
                        while($c = $resultIdsPartProx->fetchColumn(0)){
                            $s .= ',' . $c;
                        }
                        if($s == '') $s = '0';

                        //CONSULTA DOS ALUNOS DE ESCOLA PARTICULAR C/ PROXIMIDADE - Para exibir
                        $select2 = "SELECT * FROM tb_saep_aluno 
                                    WHERE curso = :curso 
                                    AND escolaridae = 'esc2' 
                                    AND proximidade = 'prox1' 
                                    AND idtb_aluno NOT IN ($d) 
                                    $filtroNome
                                    ORDER BY media DESC 
                                    LIMIT :limite";
                        $result2 = $conexao->prepare($select2);
                        $result2->bindParam(':curso', $variaveis->id_cursos, PDO::PARAM_INT);
                        if ($filtrandoBusca) {
                            $result2->bindParam(':termoBusca', $buscaParam, PDO::PARAM_STR);
                        }
                        $result2->bindParam(':limite', $qtdAlunoProxPart, PDO::PARAM_INT);
                        $result2->execute();
                        $contar2 = $result2->rowCount();

                        //CONSULTA DOS ALUNOS DE ESCOLA PARTICULAR S/ PROXIMIDADE
                        $select4 = "SELECT * FROM tb_saep_aluno 
                                    WHERE curso = :curso 
                                    AND escolaridae = 'esc2' 
                                    AND idtb_aluno NOT IN ($s) 
                                    AND idtb_aluno NOT IN ($d) 
                                    $filtroNome
                                    ORDER BY media DESC";
                        $result4 = $conexao->prepare($select4);
                        $result4->bindParam(':curso', $variaveis->id_cursos, PDO::PARAM_INT);
                        if ($filtrandoBusca) {
                            $result4->bindParam(':termoBusca', $buscaParam, PDO::PARAM_STR);
                        }
                        $result4->execute();
                        $contar4 = $result4->rowCount();

                        $i = 1;
                        if ($contar + $contar2 + $contar3 + $contar4 + $contar5 == 0) {
                            if ($filtrandoBusca) {
                                echo '<div class="alert alert-warning">
                                        <strong>Nenhum aluno encontrado com o termo de busca "' . htmlspecialchars($termoBusca) . '" neste curso.</strong>
                                      </div>';
                            } else {
                                echo '<div class="alert alert-warning">
                                        <strong> Não há registros de alunos inscritos neste curso :(</strong>
                                      </div>';
                            }
                        }

                        // 1. DEFICIENTES (2 vagas)
                        if ($contar5 > 0) {
                            $contador = 0;
                            while ($contador < $vagas_deficiente && $var = $result5->FETCH(PDO::FETCH_OBJ)) {
                                $dados = array();
                                array_push($dados, $i);
                                array_push($dados, $var->nome_aluno);
                                array_push($dados, $var->media);
                                array_push($dados, $var->escolaridae == "esc1" ? "Pública" : "Particular");
                                array_push($dados, $var->idtb_aluno);
                                array_push($dados, $variaveis->nomeCurso);
                                array_push($dados, $var->proximidade == "prox1" ? "Sim" : "Não");
                                array_push($dados, $var->deficiente == "defSim" ? "Sim" : "Não");
                                array_push($turma, $dados);
                                $i++;
                                $contador++;
                            }
                        }

                        // 2. PÚBLICA COM PROXIMIDADE (10 vagas)
                        if ($contar > 0) {
                            $contador = 0;
                            while ($contador < $vagas_publica_prox && $var = $result->FETCH(PDO::FETCH_OBJ)) {
                                $dados = array();
                                array_push($dados, $i);
                                array_push($dados, $var->nome_aluno);
                                array_push($dados, $var->media);
                                array_push($dados, "Pública");
                                array_push($dados, $var->idtb_aluno);
                                array_push($dados, $variaveis->nomeCurso);
                                array_push($dados, 'Sim');
                                array_push($dados, $var->deficiente == "defSim" ? "Sim" : "Não");
                                array_push($turma, $dados);
                                $i++;
                                $contador++;
                            }
                        }

                        // 3. PÚBLICA SEM PROXIMIDADE (24 vagas)
                        if ($contar3 > 0) {
                            $contador = 0;
                            while ($contador < $vagas_publica_normal && $var = $result3->FETCH(PDO::FETCH_OBJ)) {
                                $dados = array();
                                array_push($dados, $i);
                                array_push($dados, $var->nome_aluno);
                                array_push($dados, $var->media);
                                array_push($dados, "Pública");
                                array_push($dados, $var->idtb_aluno);
                                array_push($dados, $variaveis->nomeCurso);
                                array_push($dados, 'Não');
                                array_push($dados, $var->deficiente == "defSim" ? "Sim" : "Não");
                                array_push($turma, $dados);
                                $i++;
                                $contador++;
                            }
                        }

                        // 4. PARTICULAR COM PROXIMIDADE (3 vagas)
                        $i2 = $i;
                        if ($contar2 > 0) {
                            $contador = 0;
                            while ($contador < $vagas_particular_prox && $var = $result2->FETCH(PDO::FETCH_OBJ)) {
                                $dados = array();
                                array_push($dados, $i2);
                                array_push($dados, $var->nome_aluno);
                                array_push($dados, $var->media);
                                array_push($dados, "Particular");
                                array_push($dados, $var->idtb_aluno);
                                array_push($dados, $variaveis->nomeCurso);
                                array_push($dados, 'Sim');
                                array_push($dados, $var->deficiente == "defSim" ? "Sim" : "Não");
                                array_push($turma, $dados);
                                $i2++;
                                $contador++;
                            }
                        }

                        // 5. PARTICULAR SEM PROXIMIDADE (6 vagas)
                        if ($contar4 > 0) {
                            $contador = 0;
                            while ($contador < $vagas_particular_normal && $var = $result4->FETCH(PDO::FETCH_OBJ)) {
                                $dados = array();
                                array_push($dados, $i2);
                                array_push($dados, $var->nome_aluno);
                                array_push($dados, $var->media);
                                array_push($dados, "Particular");
                                array_push($dados, $var->idtb_aluno);
                                array_push($dados, $variaveis->nomeCurso);
                                array_push($dados, 'Não');
                                array_push($dados, $var->deficiente == "defSim" ? "Sim" : "Não");
                                array_push($turma, $dados);
                                $i2++;
                                $contador++;
                            }
                        }

                        // CLASSIFICÁVEIS - PÚBLICOS RESTANTES (a partir da posição 46)
                        if ($contar3 > 0) {
                            while ($var = $result3->FETCH(PDO::FETCH_OBJ)) {
                                $dados = array();
                                array_push($dados, $i2);
                                array_push($dados, $var->nome_aluno);
                                array_push($dados, $var->media);
                                array_push($dados, "Pública");
                                array_push($dados, $var->idtb_aluno);
                                array_push($dados, $variaveis->nomeCurso);
                                array_push($dados, $var->proximidade == "prox1" ? "Sim" : "Não");
                                array_push($dados, $var->deficiente == "defSim" ? "Sim" : "Não");
                                array_push($turma, $dados);
                                $i2++;
                            }
                        }

                        // CLASSIFICÁVEIS - PARTICULARES RESTANTES
                        if ($contar4 > 0) {
                            while ($var = $result4->FETCH(PDO::FETCH_OBJ)) {
                                $dados = array();
                                array_push($dados, $i2);
                                array_push($dados, $var->nome_aluno);
                                array_push($dados, $var->media);
                                array_push($dados, "Particular");
                                array_push($dados, $var->idtb_aluno);
                                array_push($dados, $variaveis->nomeCurso);
                                array_push($dados, $var->proximidade == "prox1" ? "Sim" : "Não");
                                array_push($dados, $var->deficiente == "defSim" ? "Sim" : "Não");
                                array_push($turma, $dados);
                                $i2++;
                            }
                        }

                        //PERCORRER TODAS AS LINHAS E GERAR A TABELA DAS TURMAS
                        foreach ($turma as $linha) {
                            ?>
                            <tr>
                                <td><?php echo $linha[0]; ?></td>
                                <td><?php echo $linha[1]; ?></td>
                                <td><?php echo number_format($linha[2],'3','.',''); ?></td>
                                <td><?php echo $linha[5]; ?></td>
                                <td><?php echo $linha[3]; ?></td>
                                <td><?php echo $linha[6]; ?></td>
                                <td><?php echo $linha[7]; ?></td>
                                <td class="center">
                                    <!--Butoes DELATAR // ALTERRA -->
                                    <form method="post" action="">
                                        <div class="udtt">
                                            <a href="home.php?esc=upAluno&id=<?= $linha[4] ?>" class="btn btn-success"> <i class="fa fa-pencil"></i></a>
                                            <a href="deleteAluno.php?idDel=<?= $linha[4] ?>" onclick="return confirm('Deseja remover este Aluno?')" type="submit" class="btn btn-danger" name="deletar"><i class="fa fa-close"  aria-hidden="true"></i></a>
                                        </div>
                                    </form>
                                    <!--fIM Butoes DELATAR // ALTERRA -->
                                    
                                </td>
                            </tr>

                            <?php
                        }
                        //ASSOCIA NOME DA TURMA COM A TABELA DA TURMA (VETOR TRIDIMENSIONAL)
                        $todos[$variaveis->nomeCurso] = $turma;
                        ?>

                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    //IMPRESSÃO DA ORGANIZAÇÃO DOS ARRAYS
    //echo "<pre>"; print_r($todos); echo "</pre>";

}
?>
<!-- Final da Lista de Inscritos -->
              
      </div>
        <form method="post" action="" target="blank" style="text-align:center; padding-top:60px">
            <button type="submit" class="btn btn-Secondary" name="gerar">Gerar Arquivo PDF Com Cotas Separadas &ensp;<i class="fa fa-file-pdf-o" style="font-size:20px"></i></button>
            <button type="submit" class="btn btn-success" name="gerarG">Gerar Arquivo PDF Classificados e Classificaveis &ensp;<i class="fa fa-file-pdf-o" style="font-size:20px"></i></button>
        </form>
        

        <?php
        if (isset($_POST['gerar'])) {
            session_start();
            $_SESSION['todos'] = $todos;
            header("Location: dompdf.php");
        }else if(isset($_POST['gerarG'])) {
            session_start();
            $_SESSION['todos'] = $todos;
            header("Location: dompdf_geral.php");
        }
        ?>
    </div>
    <div class="row">
        <hr>

    </div>
    <hr>
    
    
    
</div>