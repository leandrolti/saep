<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl" style="background-image: url('../assets/img/curved-images/back-est.png'); background-position-y: 50%;">
    </div>
    
    <div class="row">
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
  
<!-- Lista de seleção -->
<div class="col-lg-3">
  
    <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
      <div class="row" style="align-items: center;">
        
          <div class="col-lg-4">
            <div class="avatar avatar-xl position-relative">
              <img src="../assets/img/icones/cursos/<?php echo $show->imgCurso; ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          <div class="col-lg-8">
            <div class="h-100">
              <h5 class="mb-1">
                <?php echo $show->nomeCurso; ?>
              </h5>
              <p class="mb-0 font-weight-bold text-sm">
                <?php echo $show->ano_cursos; ?> | <?php if($show->status_curso==1){echo '<strong style="color:#43a85e">Ativo</strong>';}else{echo '<strong style="color:#ea0606">Inativo</strong>';} ?>
              </p>
            </div>
          </div>
          <hr>
          <div class="col-lg-12">
            <div>
              <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                <li class="nav-item">
                  <a class="btn btn-success" href="home.php?saep=upCursoSelecao&idUp=<?php echo $show->id_cursos; ?>">
                  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Editar
                  </a>
                </li>
                <li class="nav-item">
                  <a class="btn btn-danger" href="home.php?saep=deleteSelecao&id=<?php echo $show->id_cursos; ?>" onclick="return confirm('Deseja remover este curso da seleção?')">
                  <i class="fa fa-trash-o" aria-hidden="true"></i>
                   Remover
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Fim da Lista de seleção -->
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
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-6">
        <div class="card card-body blur shadow-blur mx-4 overflow-hidden">
        <div class="row" style="align-items: center;">
        <h4 class="maius">Curso para nova seleção</h4>
        <hr>
            <form action="" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        
                        <div class="mb-3">
                            <label>Nome do curso</label>
                            <select name="nomeCurso" class="form-control" required>
                            <option value="" selected disabled>Selecione um Curso</option>
                              <?php
                                 $select = "SELECT * FROM tb_saep_cursosEscola";

                                try {
                                 $result = $conexao->prepare($select);
                                 $result->execute();
          
                                 $contar = $result->rowCount();
                                 if($contar>0){
                                  while($show = $result->FETCH(PDO::FETCH_OBJ)){
                              ?>
                              
                              <option value="<?php echo $show->idCursos; ?>"><?php echo $show->nomeCurso; ?></option>
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
                        <div class="mb-3">
                          <label class="">Status do curso</label>
                         
                            <div class="radio radio-inline">
                                <input name="status_curso" id="radio7" value="0" type="radio">
                                <label for="radio7">
                                    Inativo
                                </label>
                                <input name="status_curso" id="radio8" value="1" checked="" type="radio">
                                <label for="radio8">
                                    Ativo
                                </label>
                            </div>
                          
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label>Ano do Curso</label>
                            <input type="text" class="form-control" name="ano_curso" required>
                        </div>
                        <button type="submit" class="btn btn-success" name="cadNovaSelecao">Inserir Nova Seleção</button>
                    </div>
                </div>                
            </form>
            <?php
            if (isset($_POST['cadNovaSelecao'])):
                $curso = trim(strip_tags($_POST['nomeCurso']));
                $status_curso = trim(strip_tags($_POST['status_curso']));
                $ano_curso = trim(strip_tags($_POST['ano_curso']));

                $insert = "INSERT INTO tb_saep_cursos(id_curso_escola_cursos, status_curso, ano_cursos) VALUES (:nome_curso, :status_curso, :ano_curso)";
                try {
                    $result = $conexao->prepare($insert);
                    $result->bindParam(':nome_curso', $curso, PDO::PARAM_INT);
                    $result->bindParam(':status_curso', $status_curso, PDO::PARAM_INT);
                    $result->bindParam(':ano_curso', $ano_curso, PDO::PARAM_INT);
                    $result->execute();
                    $contar = $result->rowCount();
                    if ($contar > 0):
                        echo "<div class='row'>
                                <div class='col-md-6 col-sm-12'>
                                    <div class='alert alert-success' role='alert'>
                                      <strong>Sucesso!</strong> Seleção Cadastrada :)
                                    </div>
                                </div>
                              </div>";
                        header("Refresh: 3, home.php?saep=cursos");
                    else:
                        echo "<div class='row'>
                                <div class='col-md-6 col-sm-12'>
                                  <div class='alert alert-danger' role='alert'>
                                    <strong>Erro!</strong> Seleção Não Cadastrada :(
                                  </div>
                                </div>
                              </div>";
                        header("Refresh: 3, home.php?saep=cursos");
                    endif;
                } catch (PDOException $e) {
                    echo $e;
                }
            endif;
            ?>
        </div>
        </div>
        </div>

        <div class="col-lg-6">
        <div class="card card-body blur shadow-blur mx-4 overflow-hidden">
        <a class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            Cadastrar Novo Curso na Escola
        </a>
        <?php
              if(isset($_POST['cadCursos'])){
                $nome = $_POST['nomeCurso'];
                //Upload de imagem
                if (!empty($_FILES['imgCurso']['name'])) {
                  //Tratamento da extensão da imagem de upload
                  $formtP = array("png","jpg","jpeg","gif");
                  $extensao = pathinfo($_FILES['imgCurso']['name'],PATHINFO_EXTENSION);

                  if(in_array($extensao,$formtP)){
                    //Diretório para upload da imagem do contato
                    $pasta = "../assets/img/icones/cursos/";
                    //Endereço temporario da imagem
                    $temporario = $_FILES['imgCurso']['tmp_name'];
                    //Definir um novo nome para a imagem
                    $novoNome = uniqid().".{$extensao}";

                    if(move_uploaded_file($temporario,$pasta.$novoNome)){

                    }else{
                      $mensagem = "Erro, não foi possível fazer o upload do arquivo";
                    }

                  }else{
                    echo "Arquivo inválido";
                  }
                }else{
                  $novoNome = "cursoIcone.png";
                }
                //Query de banco de dados
                $cadastro = "INSERT INTO tb_saep_cursosEscola (nomeCurso,imgCurso) VALUES (:nome,:img)";

                try {
                  //Preparar a conexão para fazer o insert
                 $result = $conexao->prepare($cadastro);
                 $result->bindParam(':nome',$nome,PDO::PARAM_STR);
                 $result->bindParam(':img',$novoNome,PDO::PARAM_STR);
                 $result->execute();
                 //Resultado do cadastro
                 $contar = $result->rowCount();
                 if ($contar>0) {
                  echo '<div style="margin-top:10px" class="alert alert-success" role="alert">
                  Cadastro Realizado com sucesso!!!
                </div>';
                header("Refresh: 3, home.php?saep=cursos");
                 }else{
                  echo '<div style="margin-top:10px" class="alert alert-danger" role="alert">
                  Cadastro Não Realizado!!!
              </div>';
                 }

                } catch (PDOException $e) {
                  echo '<strong>ERRO DE CADASTRO = </strong>'.$e->getMessage();
                }
              }
            ?>
        <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Curso</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Editar</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Remover</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $select = "SELECT * FROM tb_saep_cursosEscola ORDER BY idCursos DESC";

                      try {
                       $result = $conexao->prepare($select);
                       $result->execute();

                       $contar = $result->rowCount();
                       if($contar>0){
                        while($show = $result->FETCH(PDO::FETCH_OBJ)){
                          
                        
                    ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2">
                          <div>
                            <img src="../assets/img/icones/cursos/<?php echo $show->imgCurso; ?>" class="avatar avatar-sm rounded-circle me-2" alt="spotify">
                          </div>
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm"><?php echo $show->nomeCurso; ?></h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <a class="btn btn-success" href="home.php?saep=upCurso&idUp=<?php echo $show->idCursos; ?>">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                          
                        </a>
                      </td>
                      <td>
                        <a class="btn btn-danger" href="delCurso.php?idDel=<?php echo $show->idCursos; ?>" onclick="return confirm('Deseja remover este curso da seleção?')">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                        
                        </a>
                      </td>
                    </tr>
                    <?php
                        }
                      }else{
                        echo '<div style="margin-top:10px" class="alert  alert-danger" role="alert">
                        Não há dados de contato!!!
                        </div>';
                      }

                    } catch (PDOException $e) {
                        echo '<strong>ERRO DE LEITURA = </strong>'.$e->getMessage();
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
        
        </div>
        </div>
    </div>
    
    
    
</div>
<!-- Modal Cad Curso -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 maius" id="exampleModalLabel">Cadastrar novo Curso</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                <label>Icone do Curso</label>
                <input type="file" class="form-control" name="imgCurso" required>
                <small>Tamanho <a href="#">ideal</a> de imagem 400x400, cor de fundo: #43a85e</small>
                </div>
                <div class="mb-3">
                <label>Nome do curso</label>
                <input type="text" class="form-control" name="nomeCurso" required>
                </div>    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-success" name="cadCursos">Cadastrar Curso</button>
                </form>
            </div>
            </div>
        </div>
        </div>
        <!-- Fim do Modal -->