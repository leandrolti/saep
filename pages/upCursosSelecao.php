<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl" style="background-image: url('../assets/img/curved-images/back-est.png'); background-position-y: 50%;">
    </div>
    
    <div class="row">
    <?php
    include_once('../config/conexao.php');
    if(!isset($_GET['idUp'])){
        header("Location: home.php?saep=cursos");
        exit;
      }
    $id = filter_input(INPUT_GET, 'idUp',FILTER_DEFAULT);
    $select = "SELECT tb_saep_cursos.id_cursos,tb_saep_cursos.id_curso_escola_cursos,tb_saep_cursos.status_curso,tb_saep_cursos.ano_cursos,tb_saep_cursosEscola.idCursos,tb_saep_cursosEscola.nomeCurso,tb_saep_cursosEscola.imgCurso FROM tb_saep_cursos INNER JOIN tb_saep_cursosEscola ON tb_saep_cursos.id_curso_escola_cursos=tb_saep_cursosEscola.idCursos WHERE tb_saep_cursos.id_curso_escola_cursos=tb_saep_cursosEscola.idCursos AND tb_saep_cursos.id_cursos=:id";
    try {
      //PROTEÇÃO SQL INJECT;
      $result = $conexao->prepare($select);
      $result->bindParam(':id',$id,PDO::PARAM_INT);
      $result->execute();
      //CONTAR REGISTROS
      $contar = $result->rowCount();

      //Se houver algum User
      if ($contar > 0) {

          while ($show = $result->FETCH(PDO::FETCH_OBJ)) {
            $id_curso=$show->idCursos;
            $nome_curso=$show->nomeCurso;
            $ano_selecao=$show->ano_cursos;
            $status=$show->status_curso;
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
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
        <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row" style="align-items: center;">
        <h4 class="maius">Editar Curso para nova seleção</h4>
        <hr>
            <form action="" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        
                        <div class="mb-3">
                            <label>Nome do curso</label>
                            <select name="nomeCurso" class="form-control" required>
                            <option value="<?php echo $id_curso; ?>" selected><?php echo $nome_curso; ?></option>
                              <?php
                                 $select = "SELECT * FROM tb_saep_cursosEscola";

                                try {
                                 $result = $conexao->prepare($select);
                                 $result->execute();
          
                                 $contar = $result->rowCount();
                                 if($contar>0){
                                  while($show = $result->FETCH(PDO::FETCH_OBJ)){
                                    $nome_curso_select=$show->nomeCurso;
                              ?>
                              
                              <option value="<?php echo $show->idCursos; ?>"><?php echo $nome_curso_select; ?></option>
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
                         <?php if($status==1){ ?>
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
                         <?php }else{ ?>
                            <div class="radio radio-inline">
                                <input name="status_curso" value="0" type="radio" checked="">
                                <label>
                                    Inativo
                                </label>
                           
                                <input name="status_curso" value="1" type="radio">
                                <label>
                                    Ativo
                                </label>
                            </div>
                         <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label>Ano do Curso</label>
                            <input type="text" class="form-control" name="ano_curso" value="<?php echo $ano_selecao; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success" name="upNovaSelecao">Editar Seleção</button>
                        <?php
                            if(isset($_POST['upNovaSelecao'])){
                                $nome_curso_selecao = $_POST['nomeCurso'];
                                $status_curso = $_POST['status_curso'];
                                $ano_curso_selecao = $_POST['ano_curso'];
                                
                                //Query de banco de dados
                                $update = "UPDATE tb_saep_cursos SET id_curso_escola_cursos=:nome_curso,status_curso=:status_curso,ano_cursos=:ano_cursos WHERE id_cursos=:id";

                                try {
                                    //Preparar a conexão para fazer o insert
                                    $result = $conexao->prepare($update);
                                    $result->bindParam(':id',$id,PDO::PARAM_INT);
                                    $result->bindParam(':nome_curso',$nome_curso_selecao,PDO::PARAM_INT);
                                    $result->bindParam(':status_curso',$status_curso,PDO::PARAM_INT);
                                    $result->bindParam(':ano_cursos',$ano_curso_selecao,PDO::PARAM_STR);
                                    $result->execute();
                                    //Resultado do cadastro
                                    $contar = $result->rowCount();
                                        if ($contar>0) {
                                        echo '<div style="margin-top:10px" class="alert alert-success" role="alert">
                                                    Seleção editada com sucesso!!!
                                                </div>';
                                        header("Refresh: 3, home.php?saep=cursos");
                                        }else{
                                        echo '<div style="margin-top:10px" class="alert       alert-danger" role="alert">
                                                    Seleção não editado!!!
                                                </div>';
                                        }

                                } catch (PDOException $e) {
                                    echo '<strong>ERRO DE CADASTRO = </strong>'.$e->getMessage();
                                }
                            }
                            ?>
                    </div>
                </div>                
            </form>
            
        
        
        
        </div>
    </div>
    
    
    
</div>
<div class="col-lg-3"></div>