<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl" style="background-image: url('../assets/img/curved-images/back-est.png'); background-position-y: 50%;">
    </div>
    
    <div class="row">
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
    <!-- Lista de usuários -->
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
                    <strong><?php echo $show->total_alunos; ?></strong> Inscritos
                  </p>
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
        <hr><hr>
        <!-- Link Gerenciar Usuário -->
        <div class="col-lg-12" style="padding-left:36px;">
        <a class="btn btn-success btn-lg" href="home.php?saep=cursos">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            Gerenciar Cursos
        </a>
        </div>     
    </div>
    <hr>
    <div class="row">
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
    <hr><hr>
        <!-- Link Gerenciar Usuário -->
        <div class="col-lg-12" style="padding-left:36px;">
        <a class="btn btn-success btn-lg" href="home.php?saep=usuarios">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            Gerenciar Usuários
        </a>
        </div> 
    </div>    
</div>