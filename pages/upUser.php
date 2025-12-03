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
    $select = "SELECT * FROM tb_saep_usuario WHERE id_Usuario=:id";
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
            $id_user=$show->id_Usuario;
            $email_user=$show->email;
            $nome_user=$show->nome;
            $senha_user=$show->senha;
            $nivel_user=$show->nivel;
            $cargo_user=$show->cargo;
            $img_user=$show->img;
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
                  <div class="col-lg-4"><img style="width:100%; border-radius:100%; padding: 15px 0" src="../assets/img/user/<?php echo $img_user; ?>" alt=""></div>
                  <div class="col-lg-4"></div>
                    <div class="col-lg-6">
                        
                        <div class="mb-3">
                            <label>Foto do usuário</label>
                            <input type="file" class="form-control" name="img_user">
                        </div>
                        <div class="mb-3">
                            <label>E-mail</label>
                            <input type="text" class="form-control" name="email_user" value="<?php echo $email_user; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Cargo</label>
                            <select name="cargo_user" class="form-control">
                               
                                <option value="<?php echo $cargo_user; ?>" selected=""><?php echo $cargo_user; ?></options>
                                
                                <option value="Diretor">Diretor</options>
                                <option value="Coordenador">Coordenador</options>
                                <option value="Secretaria Escolar">Secretaria Escolar</options>
                                <option value="Administrativo">Administrativo</options>
                                <option value="Financeiro">Financeiro</options>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success" name="upUser">Editar Usuário</button>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome_user" value="<?php echo $nome_user; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Senha</label>
                            <div class="col-12">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">Editar Senha</button>
                            </div>
                            <input type="hidden" class="form-control" name="senha_user" value="<?php echo $senha_user; ?>" required>
                        </div>
                        <label>Nível</label>
                        <?php if($nivel_user==0){ ?>
                        <div class="radio radio-inline">
                            <input name="nivel_User" value="0" checked="" type="radio">
                            <label>
                                Admin
                            </label>
                            <input name="nivel_User" value="1" type="radio">
                            <label>
                                Incrição
                            </label>
                        </div>
                        <?php }else{ ?>
                        <div class="radio radio-inline">
                            <input name="nivel_User" value="0"  type="radio">
                            <label>
                                Admin
                            </label>
                            <input name="nivel_User" value="1" checked="" type="radio">
                            <label>
                                Incrição
                            </label>
                        </div>
                        <?php } ?>
                        
                        <?php
                          if(isset($_POST['upUser'])){
                            $nome_user = $_POST['nome_user'];
                            $email_user = $_POST['email_user'];
                            $senha_user = $_POST['senha_user'];
                            $cargo_user = $_POST['cargo_user'];
                            $nivel_user = $_POST['nivel_User'];
                            
                            //Upload de imagem
                            if (!empty($_FILES['img_user']['name'])) {
                              //Tratamento da extensão da imagem de upload
                              $formtP = array("png","jpg","jpeg","gif");
                              $extensao = pathinfo($_FILES['img_user']['name'],PATHINFO_EXTENSION);

                              if(in_array($extensao,$formtP)){
                                //Diretório para upload da imagem do contato
                                $pasta = "../assets/img/user/";
                                //Endereço temporario da imagem
                                $temporario = $_FILES['img_user']['tmp_name'];
                                //Definir um novo nome para a imagem
                                $novoNome = uniqid().".{$extensao}";

                                if(move_uploaded_file($temporario,$pasta.$novoNome)){
                                  $arquivo = "../assets/img/user/".$img_user;
                                  unlink($arquivo);
                                }else{
                                  $mensagem = "Erro, não foi possível fazer o upload do arquivo";
                                }

                              }else{
                                echo "Arquivo inválido";
                              }
                            }else{
                              $novoNome = $img_user;
                            }
                            //Query de banco de dados
                            $update = "UPDATE tb_saep_usuario SET email=:email_user,nome=:nome_user,senha=:senha_user,nivel=:nivel_user,cargo=:cargo_user,img=:img_user WHERE id_Usuario=:id";

                            try {
                              //Preparar a conexão para fazer o insert
                            $result = $conexao->prepare($update);
                            $result->bindParam(':id',$id,PDO::PARAM_INT);
                            $result->bindParam(':email_user',$email_user,PDO::PARAM_STR);
                            $result->bindParam(':nome_user',$nome_user,PDO::PARAM_STR);
                            $result->bindParam(':senha_user',$senha_user,PDO::PARAM_STR);
                            $result->bindParam(':nivel_user',$nivel_user,PDO::PARAM_STR);
                            $result->bindParam(':cargo_user',$cargo_user,PDO::PARAM_STR);
                            $result->bindParam(':img_user',$novoNome,PDO::PARAM_STR);
                            $result->execute();
                            //Resultado do cadastro
                            $contar = $result->rowCount();
                            if ($contar>0) {
                              echo '<div style="margin-top:10px" class="alert alert-success" role="alert">
                                      Usuário editado com sucesso!!!
                                    </div>';
                            header("Refresh: 3, home.php?saep=usuarios");
                            }else{
                              echo '<div style="margin-top:10px" class="alert alert-danger" role="alert">
                                        Usuário não editado!!!
                                    </div>';
                            }

                            } catch (PDOException $e) {
                              echo '<strong>ERRO DE CADASTRO = </strong>'.$e->getMessage();
                            }
                          }
                        ?>

                        <?php
                          if(isset($_POST['upSenha'])){
                           
                            // $senha_user =md5(trim(strip_tags($_POST['senha_user'])));
                            $senha_user = password_hash($_POST['senha_user'], PASSWORD_DEFAULT); // Usando hash seguro para a senha
                            
                            //Query de banco de dados
                            $update = "UPDATE tb_saep_usuario SET senha=:senha_user WHERE id_Usuario=:id";

                            try {
                              //Preparar a conexão para fazer o insert
                            $result = $conexao->prepare($update);
                            $result->bindParam(':id',$id,PDO::PARAM_INT);
                            $result->bindParam(':senha_user',$senha_user,PDO::PARAM_STR);
                            $result->execute();
                            //Resultado do cadastro
                            $contar = $result->rowCount();
                            if ($contar>0) {
                              echo '<div style="margin-top:10px" class="alert alert-success" role="alert">
                                      Senha alterada com sucesso!!!
                                    </div>';
                            header("Refresh: 3, home.php?saep=usuarios");
                            }else{
                              echo '<div style="margin-top:10px" class="alert alert-danger" role="alert">
                                      Senha não alterada!!!
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
<div class="col-lg-4"></div>
<!-- Modal Up Senha -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5 maius" id="exampleModalLabel">Cadastrar nova Senha</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="" method="post">
            
            <div class="mb-3">
            <label>Nova Senha</label>
            <input type="password" class="form-control" name="senha_user" required>
            </div>    
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-success" name="upSenha">Cadastrar Nova Senha</button>
            </form>
        </div>
        </div>
    </div>
</div>
<!-- Fim do Modal Up Senha -->