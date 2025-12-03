    <div class="container-fluid py-4">
      <div class="row">
        <!-- Cursos para Seleção -->
        <?php
    include_once('../config/conexao.php');
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
    ON tb_saep_aluno.curso = tb_saep_cursos.id_cursos  -- CORREÇÃO AQUI!
GROUP BY 
    tb_saep_cursos.id_cursos, 
    tb_saep_cursos.id_curso_escola_cursos, 
    tb_saep_cursos.status_curso, 
    tb_saep_cursos.ano_cursos, 
    tb_saep_cursosEscola.idCursos, 
    tb_saep_cursosEscola.nomeCurso, 
    tb_saep_cursosEscola.imgCurso
ORDER BY 
    tb_saep_cursosEscola.nomeCurso ASC;";
    
    try {
      //PROTEÇÃO SQL INJECT;
      $result = $conexao->prepare($select);
      $result->execute();
      //CONTAR REGISTROS
      $contar = $result->rowCount();

      //Se houver algum User
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
        <h4>Inscrever Aluno(a)</h4>
        <hr>
        <div class="col-lg-12 mb-lg-0 mb-4">
        <?php
          if (isset($_POST["cad"])) {
              $nome = $_POST['nome'];
              $ano = $_POST['ano'];
              $curso = $_POST['curso'];
              $ano6 = $_POST['ano6'] ?? [];
              $ano7 = $_POST['ano7'] ?? [];
              $ano8 = $_POST['ano8'] ?? [];
              $ano9 = $_POST['ano9'] ?? [];

              // Função para calcular a média de um array de notas
              function calcularMedia($notas) {
                  $notasValidas = array_filter($notas); // Filtra valores não vazios
                  return $notasValidas ? array_sum($notasValidas) / count($notasValidas) : 0;
              }

              // Calcula as médias dos anos
              $media6 = calcularMedia($ano6);
              $media7 = calcularMedia($ano7);
              $media8 = calcularMedia($ano8);
              $media9 = calcularMedia($ano9);

              $mediaF = ($media6 + $media7 + $media8 + $media9) / 4;

              $fone = "(99)99999-9999";
              $nivel = '1';

              include('../config/conexao.php');

              try {

                // **VERIFICAR SE O CURSO EXISTE ANTES DE INSERIR**
                $verificarCurso = $conexao->prepare("SELECT id_cursos FROM tb_saep_cursos WHERE id_cursos = ?");
                $verificarCurso->execute([$curso]);
                
                if ($verificarCurso->rowCount() == 0) {
                    echo "<div class='alert alert-danger'>Erro: O curso selecionado não existe! Curso ID: $curso</div>";
                    
                    // Debug: mostrar cursos disponíveis
                    $cursosDisponiveis = $conexao->query("SELECT id_cursos FROM tb_saep_cursos")->fetchAll(PDO::FETCH_COLUMN);
                    echo "<div class='alert alert-info'>Cursos disponíveis: " . implode(', ', $cursosDisponiveis) . "</div>";
                    
                    exit();
                }
                  // Inserção do aluno
                  $insertAluno = $conexao->prepare("
                      INSERT INTO tb_saep_aluno (nome_aluno, ano, curso, media, tipo_user, tel_responsavel, escolaridae, proximidade, deficiente)
                      VALUES (:nome, :ano, :curso, :media, :tipo_user, :telefone_responsavel, :esc, :prox, :deficiente)
                  ");
                  $insertAluno->execute([
                      ':nome' => $nome,
                      ':ano' => $ano,
                      ':curso' => $curso,
                      ':media' => $mediaF,
                      ':tipo_user' => $nivel,
                      ':telefone_responsavel' => $fone,
                      ':esc' => $_POST['escolaridade'],
                      ':prox' => $_POST['proximidade'],
                      ':deficiente' => $_POST['deficiente']
                  ]);

                  if ($insertAluno->rowCount() > 0) {
                      $idAluno = $conexao->lastInsertId();

                      // Matriz de notas por ano
                      $anosNotas = [
                          '6' => $ano6,
                          '7' => $ano7,
                          '8' => $ano8,
                          '9' => $ano9
                      ];

                      // Inserção das notas por ano
                      foreach ($anosNotas as $ano => $notas) {
                          $insertNotas = $conexao->prepare("
                              INSERT INTO tb_saep_notas (tb_aluno_idtb_aluno, ano, port, mat, hist, geo, cien, art, rel, edf, ing)
                              VALUES (:id, :ano, :port, :mat, :hist, :geo, :cien, :art, :rel, :edf, :ing)
                          ");
                          /* Código de correçao para notas vazias */
                            // Garante que todas as notas sejam numéricas, substituindo "" ou null por 0
                            $notasLimpa = array_map(function($n) {
                                return ($n === '' || $n === null) ? 0 : $n;
                            }, $notas);

                            // Preenche até 9 posições
                            $notasLimpa = array_pad($notasLimpa, 9, 0);

                            $insertNotas->execute(array_merge(
                                [':id' => $idAluno, ':ano' => $ano],
                                array_combine(
                                    [':port', ':mat', ':hist', ':geo', ':cien', ':art', ':rel', ':edf', ':ing'],
                                    $notasLimpa
                                )
                            ));

                          /* FIM Código de correçao para notas vazias */
                      }

                      echo "<div class='alert alert-success'>Cadastrado com sucesso!</div>";
                      header("Refresh: 3; url=home.php?esc=cadAluno");
                  } else {
                      echo "<div class='alert alert-danger'>Erro ao cadastrar!</div>";
                  }
              } catch (PDOException $e) {
                  echo "<div class='alert alert-danger'>Erro: {$e->getMessage()}</div>";

                  // Debug adicional
        echo "<div class='alert alert-warning'>Valor do curso enviado: $curso</div>";
              }
          }
          ?>

         <form method="post" action="">
                        <div class="row">

                                <div class="form-group col-lg-3">
                                <label class="col-lg-1">Curso</label>
                                    <select class="form-control select" name="curso" required="required">
                                    <option value="" selected disabled require>Selecione um Curso</option>
                                    <?php
                                    include_once('../config/conexao.php');
                                    $select = "SELECT tb_saep_cursos.id_cursos,tb_saep_cursos.id_curso_escola_cursos,tb_saep_cursos.status_curso,tb_saep_cursos.ano_cursos,tb_saep_cursosEscola.idCursos,tb_saep_cursosEscola.nomeCurso,tb_saep_cursosEscola.imgCurso FROM tb_saep_cursos INNER JOIN tb_saep_cursosEscola ON tb_saep_cursos.id_curso_escola_cursos=tb_saep_cursosEscola.idCursos WHERE tb_saep_cursos.id_curso_escola_cursos=tb_saep_cursosEscola.idCursos ORDER BY nomeCurso ASC";
                                    
                                    try {
                                      //PROTEÇÃO SQL INJECT;
                                      $result = $conexao->prepare($select);
                                      $result->execute();
                                      //CONTAR REGISTROS
                                      $contar = $result->rowCount();

                                      //Se houver algum User
                                      if ($contar > 0) {

                                          while ($show = $result->FETCH(PDO::FETCH_OBJ)) {
                                    ?>
                                      <option value="<?php echo $show->id_cursos; ?>"><?php echo $show->nomeCurso; ?></option>
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
                                    </select>
                                </div>
                                <div class="form-group col-lg-2">
                                <label class="col-lg-1">Ano</label>
                                    <select class="form-control select" name="ano">
                                        <?php
                                            // PHP para gerar os anos dinamicamente
                                            $currentYear = date("Y"); // Ano atual

                                            // Gera os anos: ano anterior, ano atual e próximo ano
                                            for ($i = $currentYear - 1; $i <= $currentYear + 1; $i++) {
                                                // Adiciona a opção como 'selected' se o ano for o próximo ano
                                                $selected = ($i == $currentYear + 1) ? 'selected' : '';
                                                echo "<option value='{$i}' {$selected}>{$i}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-7">
                                    <label class="col-lg-1">Nome</label>
                                    <div class="col-lg-11 form-group">
                                        <input name="nome" id="nome" class="form-control" type="text" required="required">
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-4">
                            <label class="col-lg-12">Qual a escolaridade?</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="radio" name="escolaridade" value="esc1" id="public" checked="checked"> <label for="public">Pública</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="radio" name="escolaridade" value="esc2" id="private"><label for="private">Privada</label>
                                </div>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <label class="col-lg-12">Tem proximidade?</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="radio" name="proximidade" value="prox1" id="proxsim" > <label for="proxsim">Sim</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="radio" name="proximidade" value="prox2" id="proxnao" checked="checked"><label for="proxnao">Não</label>
                                </div>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <label class="col-lg-12">Pessoa com deficiência ou neurodivergência?</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="radio" name="deficiente" value="defSim" id="defsim" > <label for="proxsim">Sim</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="radio" name="deficiente" value="defNao" id="defnao" checked="checked"><label for="proxnao">Não</label>
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

                                    <tr>
                                        <td>6º</td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano6[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano6[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano6[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano6[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano6[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano6[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano6[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano6[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano6[]" maxlength="5"></td>
                                    </tr>
                                    <tr>
                                        <td>7º</td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano7[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano7[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano7[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano7[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano7[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano7[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano7[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano7[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano7[]" maxlength="5"></td>
                                    </tr>
                                    <tr>
                                        <td>8º</td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano8[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano8[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano8[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano8[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano8[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano8[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano8[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano8[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano8[]" maxlength="5"></td>
                                    </tr>
                                    <tr>
                                        <td>9º</td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano9[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano9[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano9[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano9[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano9[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano9[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano9[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano9[]" maxlength="5"></td>
                                        <td><input class="form-control input-sm padrao" ng-model="numero.valor" onkeyup="validarNota(this);" onblur="validarNotaFinal(this);" type="text" name="ano9[]" maxlength="5"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-success" type="submit" name="cad">Inscrever Aluno(a)</button>
                    </form>
        </div>
        
      </div>