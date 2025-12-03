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
                
                
                <li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#" role="tab" aria-selected="false">
                    <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <title>settings</title>
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                          <g transform="translate(1716.000000, 291.000000)">
                            <g transform="translate(304.000000, 151.000000)">
                              <polygon class="color-background" opacity="0.596981957" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                              </polygon>
                              <path class="color-background" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z" opacity="0.596981957"></path>
                              <path class="color-background" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                              </path>
                            </g>
                          </g>
                        </g>
                      </g>
                    </svg>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- form start -->
        <form role="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" required value="<?php echo $nome_user; ?>">
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputEmail1">Endereço de E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" required value="<?php echo $email_user; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Senha</label>
                    <input type="password" class="form-control" name="senha" id="telefone" value="" placeholder="**************************">
                  </div>
                  <div class="form-group">
                    <label for="foto" class="form-label">Avatar do usuário</label>
                    <div class="input-group">
                      <div class="custom-file">
                       <input class="form-control" type="file"  name="foto" id="foto">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="upPerfil" class="btn btn-success">Alterar dados do usuário</button>
                </div>
              </form>
              <?php
                include('../config/conexao.php'); // Inclui o arquivo de conexão com o banco de dados

                // Verifica se o formulário foi enviado
                if (isset($_POST['upPerfil'])) {
                  // Recebe os dados do formulário
                  $nome = $_POST['nome'];
                  $email = $_POST['email'];
                  $senha_nova = $_POST['senha'];

                  // Obter os valores antigos do banco de dados
                  $query = "SELECT email,nome,senha FROM tb_saep_usuario WHERE id_Usuario=:id";
                  $stmt = $conexao->prepare($query); // Prepara a consulta SQL
                  $stmt->bindParam(':id', $id_user, PDO::PARAM_STR); // Vincula o parâmetro ID do usuário
                  $stmt->execute(); // Executa a consulta
                  $row = $stmt->fetch(PDO::FETCH_ASSOC); // Busca os resultados como um array associativo
                  $email_antigo = $row['email']; // Armazena o email antigo
                  $senha_antiga = $row['senha']; // Armazena a senha antiga

                  // Verificar se existe imagem para fazer o upload
                  if (!empty($_FILES['foto']['name'])) {
                    $formatP = array("png", "jpg", "jpeg", "gif"); // Formatos permitidos para upload
                    $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); // Obtém a extensão do arquivo

                    // Verifica se a extensão do arquivo está nos formatos permitidos
                    if (in_array($extensao, $formatP)) {
                      $pasta = "../assets/img/user/"; // Define o diretório de upload
                      $temporario = $_FILES['foto']['tmp_name']; // Caminho temporário do arquivo
                      $novoNome = uniqid() . ".{$extensao}"; // Gera um nome único para o arquivo

                      // Excluir a imagem antiga se ela existir
                      if (file_exists($pasta . $foto_user)) {
                        unlink($pasta . $foto_user); // Remove o arquivo antigo
                      }
                      // Move o novo arquivo para o diretório de upload
                      if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                        // Sucesso no upload da nova imagem
                      } else {
                        $mensagem = "Erro, não foi possível fazer o upload do arquivo!"; // Mensagem de erro
                      }
                    }else {
                            echo "Formato inválido"; // Mensagem de erro para formato de arquivo inválido
                        }
                  }else{
                    $novoNome = $foto_user;
                  }

                  // Verificar se a senha foi alterada
                  if (!empty($senha_nova)) {
                    $senha = password_hash($senha_nova, PASSWORD_DEFAULT); // Hash seguro para a nova senha
                  }else{
                    $senha = $senha_antiga; // Mantém a senha antiga
                  }

                  // Atualizar o banco de dados
                  $update = "UPDATE tb_saep_usuario SET email=:email,nome=:nome,senha=:senha,img=:foto   WHERE id_Usuario=:id";
                  try {
                    // Prepara a consulta de atualização
                    $result = $conexao->prepare($update);
                    $result->bindParam(':id', $id_user, PDO::PARAM_STR); // Vincula o ID do usuário
                    $result->bindParam(':foto', $novoNome, PDO::PARAM_STR); // Vincula o novo nome da foto
                    $result->bindParam(':nome', $nome, PDO::PARAM_STR); // Vincula o nome do usuário
                    $result->bindParam(':email', $email, PDO::PARAM_STR); // Vincula o email do usuário
                    $result->bindParam(':senha', $senha, PDO::PARAM_STR); // Vincula a senha codificada do usuário
                    $result->execute(); // Executa a consulta

                    $contar = $result->rowCount(); // Conta o número de linhas afetadas
                    if($contar > 0){
                      echo '<div class="container">
                      <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Ok !!!</h5>
                        Perfil atualizado com sucesso.
                      </div>
                    </div>';

                    // Verificar se tanto o email quanto a senha foram alterados
                    if ($email !== $email_antigo || $senha !== $senha_antiga) {
                      header("Location: ?sair"); // Redireciona para sair se email ou senha foram alterados
                      exit(); // Garante que o redirecionamento ocorra
                  } else {
                      header("Refresh: 3; home.php?saep=perfil"); // Redireciona de volta ao perfil após 3 segundos
                      exit(); // Garante que o redirecionamento ocorra
                  }
                    }else{
                      // Mensagem de erro se nenhum dado foi atualizado
                      echo '<div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-times"></i> Erro !!!</h5>
                      Perfil não foi atualizado.
                    </div>';
                    }
                  } catch (PDOException $e) {
                    // Mensagem de erro para exceções PDO
                    echo "<strong>ERRO DE PDO= </strong>" . $e->getMessage();
                  }
                }
             ?>
      </div>
      
    </div>
    <div class="row">
        <hr>

    </div>
    <hr>
    
    
    
</div>