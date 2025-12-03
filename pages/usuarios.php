<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl" style="background-image: url('../assets/img/curved-images/back-est.png'); background-position-y: 50%;">
    <a style="margin:0 16px;" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">
      <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
      Cadastrar Novo Usuário
    </a>
    <?php
    include_once('../config/conexao.php');
    if (isset($_POST['cadUser'])) {
      // Recebe os dados do formulário
      $nome = $_POST['nome'];
      $email = $_POST['email'];
      $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Usando hash seguro para a senha
      $cargo = $_POST['cargo'];
      $nivel = $_POST['nivelUser'];
  
      // Verifica se foi enviado algum arquivo de imagem
      if (!empty($_FILES['img']['name'])) {
          // Tratamento da extensão da imagem de upload
          $formtP = array("png", "jpg", "jpeg", "gif"); // Formatos permitidos
          $extensao = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION); // Obtém a extensão do arquivo
  
          // Verifica se a extensão do arquivo está nos formatos permitidos
          if (in_array(strtolower($extensao), $formtP)) {
              // Diretório para upload da imagem do contato
              $pasta = "../assets/img/user/";
              // Endereço temporário da imagem
              $temporario = $_FILES['img']['tmp_name'];
              // Define um novo nome para a imagem
              $novoNome = uniqid() . ".{$extensao}";
  
              // Move o arquivo para o diretório de imagens
              if (!move_uploaded_file($temporario, $pasta . $novoNome)) {
                  $mensagem = "Erro, não foi possível fazer o upload do arquivo";
              }
          } else {
              echo '<div class="alert alert-danger" role="alert">Arquivo inválido</div>';
          }
      } else {
          // Define um nome padrão caso não seja enviado nenhum arquivo de imagem
          $novoNome = "user.png"; // Nome do arquivo padrão
      }
  
      // Prepara a consulta SQL para inserção dos dados do usuário
      $cadastro = "INSERT INTO tb_saep_usuario (email, nome, senha, nivel, cargo, img) VALUES (:email, :nome, :senha, :nivel, :cargo, :img)";
  
      try {
          // Preparar a conexão para fazer o insert
          $result = $conexao->prepare($cadastro);
          $result->bindParam(':email', $email, PDO::PARAM_STR);
          $result->bindParam(':nome', $nome, PDO::PARAM_STR);
          $result->bindParam(':senha', $senha, PDO::PARAM_STR);
          $result->bindParam(':nivel', $nivel, PDO::PARAM_STR);
          $result->bindParam(':cargo', $cargo, PDO::PARAM_STR);
          $result->bindParam(':img', $novoNome, PDO::PARAM_STR);
          $result->execute();
  
          // Resultado do cadastro
          $contar = $result->rowCount();
          if ($contar > 0) {
              echo '<div style="margin-top:10px" class="alert alert-success" role="alert">Usuário cadastrado com sucesso!!!</div>';
              header("Refresh: 3; url=home.php?saep=usuarios");
          } else {
              echo '<div style="margin-top:10px" class="alert alert-danger" role="alert">Usuário não cadastrado!!!</div>';
          }
      } catch (PDOException $e) {
          echo '<strong>ERRO DE CADASTRO = </strong>' . $e->getMessage();
      }
  }
  
            ?>
    </div>

    <div class="row">
    <?php

    $select = "SELECT * FROM tb_saep_usuario ORDER BY id_Usuario ASC";
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
<!-- Lista de usuários -->
<div class="col-lg-3" style="margin: 20px 0 ">
    <div class="card card-body blur shadow-blur overflow-hidden">
      <div class="row" style="align-items: center;">

          <div class="col-lg-4">
            <div class="avatar avatar-xl position-relative">
              <!-- <img src="../assets/img/user/<?php //echo $show->img; ?>" alt="Imagem de Avatar" class="w-100 border-radius-lg shadow-sm"> -->
              <?php
            // Verifica se a variável $foto_user é igual a 'avatar-padrao.png'
            if ($show->img == 'user.png') {
                // Exibe a imagem do avatar padrão
                echo '<img src="../assets/img/avatarp/' . $show->img . '" alt="' . $show->img . '" title="' . $show->img . '" class="w-100 border-radius-lg shadow-sm" />';
            } else {
                // Exibe a imagem do usuário
                echo '<img src="../assets/img/user/' . $show->img . '" alt="' . $show->img . '" title="' . $show->img . '" class="w-100 border-radius-lg shadow-sm" />';
            }
            ?>  
            </div>
          </div>
          <div class="col-lg-8">
            <div class="h-100">
              <h5 class="mb-1">
                <?php echo $show->nome; ?>
              </h5>
              <p class="mb-0 font-weight-bold text-sm">
              <?php echo $show->cargo; ?> | <?php if($show->nivel==0){echo '<strong style="color:#43a85e">Admin</strong>';}else{echo '<strong style="color:#fdca0a">Inscrição</strong>';} ?><br><?php echo $show->email; ?>
              </p>
            </div>
          </div>
          <hr>
          <div class="col-lg-12">
            <div>
              <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                <li class="nav-item">
                  <a class="btn btn-success" href="home.php?saep=upUser&idUp=<?php echo $show->id_Usuario; ?>">
                  <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Editar
                  </a>
                </li>
                <li class="nav-item">
                  <a class="btn btn-danger" href="home.php?saep=delUser&id=<?php echo $show->id_Usuario; ?>" onclick="return confirm('Deseja remover este usuário?')">
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
    <!-- Fim da Lista de usuários -->
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
    <div class="col-lg-4">
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5 maius" id="exampleModalLabel">Cadastrar novo usuário</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label>Foto</label>
              <input type="file" class="form-control" name="img">
            </div>
            <div class="mb-3">
              <label>Nome</label>
              <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="mb-3">
              <label>E-mail</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
              <label>Senha</label>
              <input type="password" class="form-control" name="senha" required>
            </div>
            <div class="mb-3">
              <label>Cargo</label>
              <select name="cargo" class="form-control">
                <option value="" disabled="" selected="">Selecione um cargo</options>
                <option value="Diretor">Diretor</options>
                <option value="Coordenador">Coordenador</options>
                <option value="Secretaria Escolar">Secretaria Escolar</options>
                <option value="Administrativo">Administrativo</options>
                <option value="Financeiro">Financeiro</options>
              </select>
            </div>
            <div class="mb-3">
              <label>Nível</label>
              <div class="radio radio-inline">
                  <input name="nivelUser" value="0" type="radio">
                  <label>
                      Admin
                  </label>
                  <input name="nivelUser" value="1" checked="" type="radio">
                  <label>
                      Incrição
                  </label>
              </div>
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-success" name="cadUser">Cadastrar Usuário</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fim do Modal Cad User-->


    </div>


</div>
