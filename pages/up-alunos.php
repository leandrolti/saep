<?php
// Verifica se o ID foi passado via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: home.php?esc=listarAlunos");
    exit();
}

$idAluno = $_GET['id'];

// Buscar dados do aluno
include_once('../config/conexao.php');

try {
    // Buscar informações do aluno
    $selectAluno = $conexao->prepare("
        SELECT 
            idtb_aluno,
            nome_aluno,
            ano,
            curso,
            escolaridae,
            proximidade,
            deficiente
        FROM tb_saep_aluno 
        WHERE idtb_aluno = ?
    ");
    $selectAluno->execute([$idAluno]);
    
    if ($selectAluno->rowCount() == 0) {
        echo "<div class='alert alert-danger'>Aluno não encontrado!</div>";
        exit();
    }
    
    $aluno = $selectAluno->fetch(PDO::FETCH_OBJ);
    
    // Buscar notas do aluno
    $selectNotas = $conexao->prepare("
        SELECT ano, port, mat, hist, geo, cien, art, rel, edf, ing
        FROM tb_saep_notas
        WHERE tb_aluno_idtb_aluno = ?
        ORDER BY ano ASC
    ");
    $selectNotas->execute([$idAluno]);
    $notas = $selectNotas->fetchAll(PDO::FETCH_OBJ);
    
    // Organizar notas por ano
    $notasPorAno = [];
    foreach ($notas as $nota) {
        $notasPorAno[$nota->ano] = [
            'port' => $nota->port,
            'mat' => $nota->mat,
            'hist' => $nota->hist,
            'geo' => $nota->geo,
            'cien' => $nota->cien,
            'art' => $nota->art,
            'rel' => $nota->rel,
            'edf' => $nota->edf,
            'ing' => $nota->ing
        ];
    }
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erro ao carregar dados: {$e->getMessage()}</div>";
    exit();
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Cursos para Seleção -->
        <?php
        $select = "SELECT 
            tb_saep_cursos.id_cursos,
            tb_saep_cursos.id_curso_escola_cursos,
            tb_saep_cursos.status_curso,
            tb_saep_cursos.ano_cursos,
            tb_saep_cursosEscola.idCursos,
            tb_saep_cursosEscola.nomeCurso,
            tb_saep_cursosEscola.imgCurso,
            COUNT(tb_saep_aluno.idtb_aluno) AS total_alunos
        FROM 
            tb_saep_cursos
        INNER JOIN 
            tb_saep_cursosEscola 
            ON tb_saep_cursos.id_curso_escola_cursos = tb_saep_cursosEscola.idCursos
        LEFT JOIN 
            tb_saep_aluno 
            ON tb_saep_aluno.curso = tb_saep_cursos.id_cursos
        GROUP BY 
            tb_saep_cursos.id_cursos, 
            tb_saep_cursos.id_curso_escola_cursos, 
            tb_saep_cursos.status_curso, 
            tb_saep_cursos.ano_cursos, 
            tb_saep_cursosEscola.idCursos, 
            tb_saep_cursosEscola.nomeCurso, 
            tb_saep_cursosEscola.imgCurso
        ORDER BY 
            tb_saep_cursosEscola.nomeCurso ASC";
        
        try {
            $result = $conexao->prepare($select);
            $result->execute();
            $contar = $result->rowCount();

            if ($contar > 0) {
                while ($show = $result->FETCH(PDO::FETCH_OBJ)) {
        ?>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold"><?php echo $show->nomeCurso; ?></p>
                                <h5 class="font-weight-bolder mb-0">
                                    <?php echo $show->total_alunos; ?>
                                    <span class="text-success text-sm font-weight-bolder">Inscritos</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape shadow text-center border-radius-md">
                                <img src="../assets/img/icones/cursos/<?php echo $show->imgCurso; ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
                }
            } else {
                echo "
                <div class='col-md-6 col-sm-12'>
                    <div class='alert alert-danger'>
                        <strong>Ops!</strong> Não há registros em nossa base de dados :(
                    </div>
                </div>";
            }
        } catch (PDOException $e) {
            echo $e;
        }
        ?>
        <!-- Fim Cursos para Seleção -->
    </div>
    <!-- Fim Reader -->

    <div class="row mt-4">
        <h4>Atualizar Dados do Aluno(a)</h4>
        <hr>
        <div class="col-lg-12 mb-lg-0 mb-4">
            <?php
            if (isset($_POST["atualizar"])) {
                $nome = $_POST['nome'];
                $ano = $_POST['ano'];
                $curso = $_POST['curso'];
                $ano6 = $_POST['ano6'] ?? [];
                $ano7 = $_POST['ano7'] ?? [];
                $ano8 = $_POST['ano8'] ?? [];
                $ano9 = $_POST['ano9'] ?? [];

                // Função para calcular a média de um array de notas
                function calcularMedia($notas) {
                    $notasValidas = array_filter($notas);
                    return $notasValidas ? array_sum($notasValidas) / count($notasValidas) : 0;
                }

                // Calcula as médias dos anos
                $media6 = calcularMedia($ano6);
                $media7 = calcularMedia($ano7);
                $media8 = calcularMedia($ano8);
                $media9 = calcularMedia($ano9);

                $mediaF = ($media6 + $media7 + $media8 + $media9) / 4;

                try {
                    // Verificar se o curso existe
                    $verificarCurso = $conexao->prepare("SELECT id_cursos FROM tb_saep_cursos WHERE id_cursos = ?");
                    $verificarCurso->execute([$curso]);
                    
                    if ($verificarCurso->rowCount() == 0) {
                        echo "<div class='alert alert-danger'>Erro: O curso selecionado não existe!</div>";
                        exit();
                    }

                    // Atualizar dados do aluno
                    $updateAluno = $conexao->prepare("
                        UPDATE tb_saep_aluno 
                        SET nome_aluno = :nome,
                            ano = :ano,
                            curso = :curso,
                            media = :media,
                            escolaridae = :esc,
                            proximidade = :prox,
                            deficiente = :deficiente
                        WHERE idtb_aluno = :id
                    ");
                    
                    $updateAluno->execute([
                        ':nome' => $nome,
                        ':ano' => $ano,
                        ':curso' => $curso,
                        ':media' => $mediaF,
                        ':esc' => $_POST['escolaridade'],
                        ':prox' => $_POST['proximidade'],
                        ':deficiente' => $_POST['deficiente'],
                        ':id' => $idAluno
                    ]);

                    if ($updateAluno->rowCount() > 0 || $updateAluno->rowCount() == 0) {
                        // Matriz de notas por ano
                        $anosNotas = [
                            '6' => $ano6,
                            '7' => $ano7,
                            '8' => $ano8,
                            '9' => $ano9
                        ];

                        // Atualizar ou inserir notas por ano
                        foreach ($anosNotas as $anoEscolar => $notas) {
                            // Verificar se já existe nota para esse ano
                            $verificarNota = $conexao->prepare("
                                SELECT * FROM tb_saep_notas 
                                WHERE tb_aluno_idtb_aluno = ? AND ano = ?
                            ");
                            $verificarNota->execute([$idAluno, $anoEscolar]);

                            // Limpar notas vazias
                            $notasLimpa = array_map(function($n) {
                                return ($n === '' || $n === null) ? 0 : $n;
                            }, $notas);

                            // Preenche até 9 posições
                            $notasLimpa = array_pad($notasLimpa, 9, 0);

                            if ($verificarNota->rowCount() > 0) {
                                // UPDATE
                                $updateNotas = $conexao->prepare("
                                    UPDATE tb_saep_notas 
                                    SET port = :port, 
                                        mat = :mat, 
                                        hist = :hist, 
                                        geo = :geo, 
                                        cien = :cien, 
                                        art = :art, 
                                        rel = :rel, 
                                        edf = :edf, 
                                        ing = :ing
                                    WHERE tb_aluno_idtb_aluno = :id AND ano = :ano
                                ");
                                
                                $updateNotas->execute(array_merge(
                                    [':id' => $idAluno, ':ano' => $anoEscolar],
                                    array_combine(
                                        [':port', ':mat', ':hist', ':geo', ':cien', ':art', ':rel', ':edf', ':ing'],
                                        $notasLimpa
                                    )
                                ));
                            } else {
                                // INSERT
                                $insertNotas = $conexao->prepare("
                                    INSERT INTO tb_saep_notas (tb_aluno_idtb_aluno, ano, port, mat, hist, geo, cien, art, rel, edf, ing)
                                    VALUES (:id, :ano, :port, :mat, :hist, :geo, :cien, :art, :rel, :edf, :ing)
                                ");
                                
                                $insertNotas->execute(array_merge(
                                    [':id' => $idAluno, ':ano' => $anoEscolar],
                                    array_combine(
                                        [':port', ':mat', ':hist', ':geo', ':cien', ':art', ':rel', ':edf', ':ing'],
                                        $notasLimpa
                                    )
                                ));
                            }
                        }

                        echo "<div class='alert alert-success'>Dados atualizados com sucesso!</div>";
                        header("Refresh: 2; url=home.php?esc=lista");
                    } else {
                        echo "<div class='alert alert-warning'>Nenhuma alteração foi realizada!</div>";
                    }
                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger'>Erro ao atualizar: {$e->getMessage()}</div>";
                }
            }
            ?>

            <form method="post" action="">
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="col-lg-1">Curso</label>
                        <select class="form-control select" name="curso" required="required">
                            <option value="" disabled>Selecione um Curso</option>
                            <?php
                            $select = "SELECT tb_saep_cursos.id_cursos,tb_saep_cursos.id_curso_escola_cursos,tb_saep_cursos.status_curso,tb_saep_cursos.ano_cursos,tb_saep_cursosEscola.idCursos,tb_saep_cursosEscola.nomeCurso,tb_saep_cursosEscola.imgCurso FROM tb_saep_cursos INNER JOIN tb_saep_cursosEscola ON tb_saep_cursos.id_curso_escola_cursos=tb_saep_cursosEscola.idCursos WHERE tb_saep_cursos.id_curso_escola_cursos=tb_saep_cursosEscola.idCursos ORDER BY nomeCurso ASC";
                            
                            try {
                                $result = $conexao->prepare($select);
                                $result->execute();
                                $contar = $result->rowCount();

                                if ($contar > 0) {
                                    while ($show = $result->FETCH(PDO::FETCH_OBJ)) {
                                        $selected = ($show->id_cursos == $aluno->curso) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $show->id_cursos; ?>" <?php echo $selected; ?>><?php echo $show->nomeCurso; ?></option>
                            <?php
                                    }
                                } else {
                                    echo "<option disabled>Nenhum curso disponível</option>";
                                }
                            } catch (PDOException $e) {
                                echo $e;
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label class="col-lg-1">Ano</label>
                        <select class="form-control select" name="ano">
                            <?php
                            $currentYear = date("Y");
                            for ($i = $currentYear - 1; $i <= $currentYear + 1; $i++) {
                                $selected = ($i == $aluno->ano) ? 'selected' : '';
                                echo "<option value='{$i}' {$selected}>{$i}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-7">
                        <label class="col-lg-1">Nome</label>
                        <div class="col-lg-11 form-group">
                            <input name="nome" id="nome" class="form-control" type="text" required="required" value="<?php echo $aluno->nome_aluno; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <label class="col-lg-12">Qual a escolaridade?</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="radio" name="escolaridade" value="esc1" id="public" <?php echo ($aluno->escolaridae == 'esc1') ? 'checked' : ''; ?>> <label for="public">Pública</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="radio" name="escolaridade" value="esc2" id="private" <?php echo ($aluno->escolaridae == 'esc2') ? 'checked' : ''; ?>><label for="private">Privada</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="col-lg-12">Tem proximidade?</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="radio" name="proximidade" value="prox1" id="proxsim" <?php echo ($aluno->proximidade == 'prox1') ? 'checked' : ''; ?>> <label for="proxsim">Sim</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="radio" name="proximidade" value="prox2" id="proxnao" <?php echo ($aluno->proximidade == 'prox2') ? 'checked' : ''; ?>><label for="proxnao">Não</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="col-lg-12">Pessoa com deficiência ou neurodivergência?</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="radio" name="deficiente" value="defSim" id="defsim" <?php echo ($aluno->deficiente == 'defSim') ? 'checked' : ''; ?>> <label for="defsim">Sim</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="radio" name="deficiente" value="defNao" id="defnao" <?php echo ($aluno->deficiente == 'defNao') ? 'checked' : ''; ?>><label for="defnao">Não</label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="form-group tabelaresp">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-responsive" id="example">
                        <thead>
                            <tr>
                                <th class="padrao2">Ano</th>
                                <th class="padrao2">Português</th>
                                <th class="padrao2">Matemática</th>
                                <th class="padrao2">História</th>
                                <th class="padrao2">Geografia</th>
                                <th class="padrao2">Ciências</th>
                                <th class="padrao2">Artes</th>
                                <th class="padrao2">Religião</th>
                                <th class="padrao2">Ed. Física</th>
                                <th class="padrao2">Inglês</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $anos = [6, 7, 8, 9];
                            foreach ($anos as $anoEscolar) {
                                $notasAno = isset($notasPorAno[$anoEscolar]) ? $notasPorAno[$anoEscolar] : array_fill_keys(['port', 'mat', 'hist', 'geo', 'cien', 'art', 'rel', 'edf', 'ing'], '');
                            ?>
                            <tr>
                                <td><?php echo $anoEscolar; ?>º</td>
                                <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano<?php echo $anoEscolar; ?>[]" value="<?php echo $notasAno['port']; ?>" maxlength="5"></td>
                                <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano<?php echo $anoEscolar; ?>[]" value="<?php echo $notasAno['mat']; ?>" maxlength="5"></td>
                                <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano<?php echo $anoEscolar; ?>[]" value="<?php echo $notasAno['hist']; ?>" maxlength="5"></td>
                                <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano<?php echo $anoEscolar; ?>[]" value="<?php echo $notasAno['geo']; ?>" maxlength="5"></td>
                                <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano<?php echo $anoEscolar; ?>[]" value="<?php echo $notasAno['cien']; ?>" maxlength="5"></td>
                                <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano<?php echo $anoEscolar; ?>[]" value="<?php echo $notasAno['art']; ?>" maxlength="5"></td>
                                <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano<?php echo $anoEscolar; ?>[]" value="<?php echo $notasAno['rel']; ?>" maxlength="5"></td>
                                <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano<?php echo $anoEscolar; ?>[]" value="<?php echo $notasAno['edf']; ?>" maxlength="5"></td>
                                <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano<?php echo $anoEscolar; ?>[]" value="<?php echo $notasAno['ing']; ?>" maxlength="5"></td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-success" type="submit" name="atualizar">Atualizar Dados do Aluno(a)</button>
                <a href="home.php?esc=lista" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>