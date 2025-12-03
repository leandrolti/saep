<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl" style="background-image: url('../assets/img/curved-images/back-est.png'); background-position-y: 50%;">
    </div>
    
    <div class="row">
    <?php
    include_once('../config/conexao.php');
    if(!isset($_GET['idUp'])){
        header("Location: home.php?saep=regras");
        exit;
      }
    $id = filter_input(INPUT_GET, 'idUp',FILTER_DEFAULT);
    $select = "SELECT * FROM tb_saep_edital WHERE id_edital=:id";
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
            $id_edital=$show->id_edital;
            $qtd_aluno=$show->qtd_aluno;
            $aluno_publica=$show->aluno_publica;
            $aluno_prox_pul=$show->aluno_prox_pul;
            $aluno_particular=$show->aluno_particular;
            $aluno_prox_part=$show->aluno_prox_part;
            $com_laudo=$show->com_laudo;
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
        
        <hr>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-lg-4"></div>
                  <div class="col-lg-4"><img style="width:100%; border-radius:100%; padding: 15px 0" src="../assets/img/icones/cursos/<?php echo $img_curso; ?>" alt=""></div>
                  <div class="col-lg-4"></div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label>Quantidade de Alunos por Turma</label>
                            <input type="text" class="form-control" name="qtd_aluno" value="<?php echo $qtd_aluno; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Quant. Aluno de Esc. Pública</label>
                            <input type="text" class="form-control" name="aluno_publica" value="<?php echo $aluno_publica; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Quant. Aluno de Esc. Pública com Proximidade</label>
                            <input type="text" class="form-control" name="aluno_prox_pul" value="<?php echo $aluno_prox_pul; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success" name="upCurso">Editar Curso</button>
                    </div>
                    <div class="col-lg-6">
                        
                        
                        <div class="mb-3">
                            <label>Quant. Aluno de Esc. Particular</label>
                            <input type="text" class="form-control" name="aluno_particular" value="<?php echo $aluno_particular; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Quant. Aluno de Esc. Particular com Proximidade</label>
                            <input type="text" class="form-control" name="aluno_prox_part" value="<?php echo $aluno_prox_part; ?>" required>
                        </div>
                         <div class="mb-3">
                            <label>Quant. Aluno com laudo médico</label>
                            <input type="text" class="form-control" name="com_laudo" value="<?php echo $com_laudo; ?>" required>
                        </div>
                        <?php
                            if(isset($_POST['upCurso'])){
                                $qtd_aluno = $_POST['qtd_aluno'];
                                $aluno_publica = $_POST['aluno_publica'];
                                $aluno_prox_pul = $_POST['aluno_prox_pul'];
                                $aluno_particular = $_POST['aluno_particular'];
                                $aluno_prox_part = $_POST['aluno_prox_part'];
                                $com_laudo = $_POST['com_laudo'];
                                
                                //Query de banco de dados
                                $update = "UPDATE tb_saep_edital SET qtd_aluno=:qtd_aluno,aluno_publica=:qtd_aluno_ep,aluno_prox_pul=:qtd_aluno_epp,aluno_particular=:qtd_aluno_epa,aluno_prox_part=:qtd_aluno_epap,com_laudo=:claudo WHERE id_edital=:id";

                                try {
                                    //Preparar a conexão para fazer o insert
                                    $result = $conexao->prepare($update);
                                    $result->bindParam(':id',$id,PDO::PARAM_INT);
                                    $result->bindParam(':qtd_aluno',$qtd_aluno,PDO::PARAM_INT);
                                    $result->bindParam(':qtd_aluno_ep',$aluno_publica,PDO::PARAM_INT);
                                    $result->bindParam(':qtd_aluno_epp',$aluno_prox_pul,PDO::PARAM_INT);
                                    $result->bindParam(':qtd_aluno_epa',$aluno_particular,PDO::PARAM_INT);
                                    $result->bindParam(':qtd_aluno_epap',$aluno_prox_part,PDO::PARAM_INT);
                                    $result->bindParam(':claudo',$com_laudo,PDO::PARAM_INT);
                                    $result->execute();
                                    //Resultado do cadastro
                                    $contar = $result->rowCount();
                                        if ($contar>0) {
                                        echo '<div style="margin-top:10px" class="alert alert-success" role="alert">
                                                    Edital editado com sucesso!!!
                                                </div>';
                                        header("Refresh: 3, home.php?saep=regras");
                                        }else{
                                        echo '<div style="margin-top:10px" class="alert       alert-danger" role="alert">
                                                    Edital não editado!!!
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