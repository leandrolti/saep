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
    $select = "SELECT * FROM tb_saep_cursosEscola WHERE idCursos=:id";
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
            $img_curso=$show->imgCurso;
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
                            <label>Icone do curso</label>
                            <input type="file" class="form-control" name="img_curso">
                        </div>
                        <button type="submit" class="btn btn-success" name="upCurso">Editar Curso</button>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label>Nome do Curso</label>
                            <input type="text" class="form-control" name="nome_curso" value="<?php echo $nome_curso; ?>" required>
                        </div>
                        
                        <?php
                          if(isset($_POST['upCurso'])){
                            $nome_curso = $_POST['nome_curso'];
                            
                            //Upload de imagem
                            if (!empty($_FILES['img_curso']['name'])) {
                              //Tratamento da extensão da imagem de upload
                              $formtP = array("png","jpg","jpeg","gif");
                              $extensao = pathinfo($_FILES['img_curso']['name'],PATHINFO_EXTENSION);

                              if(in_array($extensao,$formtP)){
                                //Diretório para upload da imagem do contato
                                $pasta = "../assets/img/icones/cursos/";
                                //Endereço temporario da imagem
                                $temporario = $_FILES['img_curso']['tmp_name'];
                                //Definir um novo nome para a imagem
                                $novoNome = uniqid().".{$extensao}";

                                if(move_uploaded_file($temporario,$pasta.$novoNome)){
                                  $arquivo = "../assets/img/icones/cursos/".$img_curso;
                                  unlink($arquivo);
                                }else{
                                  $mensagem = "Erro, não foi possível fazer o upload do arquivo";
                                }

                              }else{
                                echo "Arquivo inválido";
                              }
                            }else{
                              $novoNome = $img_curso;
                            }
                            //Query de banco de dados
                            $update = "UPDATE tb_saep_cursosEscola SET nomeCurso=:nome_curso,imgCurso=:img_curso WHERE idCursos=:id";

                            try {
                              //Preparar a conexão para fazer o insert
                            $result = $conexao->prepare($update);
                            $result->bindParam(':id',$id,PDO::PARAM_INT);
                            $result->bindParam(':nome_curso',$nome_curso,PDO::PARAM_STR);
                            $result->bindParam(':img_curso',$novoNome,PDO::PARAM_STR);
                            $result->execute();
                            //Resultado do cadastro
                            $contar = $result->rowCount();
                            if ($contar>0) {
                              echo '<div style="margin-top:10px" class="alert alert-success" role="alert">
                                      Curso editado com sucesso!!!
                                    </div>';
                            header("Refresh: 3, home.php?saep=cursos");
                            }else{
                              echo '<div style="margin-top:10px" class="alert alert-danger" role="alert">
                                        Curso não editado!!!
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