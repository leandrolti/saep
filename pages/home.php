<?php
    include_once("../include/header.php");
    if(isset($_GET['saep'])){
        $acao= $_GET['saep']; //Pega o parametro que está sendo passado pela página
          #Páginas do usuário Admin
          if ($acao == 'painel'){
            include_once('painel.php');
          }elseif ($acao == 'usuarios'){
            include_once('usuarios.php');
          }elseif ($acao == 'cursos'){
            include_once('cursos.php');
          }elseif ($acao == 'regras'){
            include_once('regras.php');
          }elseif ($acao == 'perfil'){
            include_once('perfil.php');
            #Ações de Delete
          }elseif ($acao == 'deleteSelecao'){
            include_once('deleteSelecao.php');
          }elseif ($acao == 'delUser'){
            include_once('delUser.php');
          }elseif ($acao == 'upUser'){
            include_once('upUser.php');
          }elseif ($acao == 'upCursoSelecao'){
            include_once('upCursosSelecao.php');
          }elseif ($acao == 'upCurso'){
            include_once('upCursos.php');
          }elseif ($acao == 'perfil'){
            include_once('perfil.php');
          }elseif ($acao == 'upRegras'){
            include_once('upRegras.php');
          }else{
            include_once("erro404.php");
          }
    }elseif(isset($_GET['esc'])){
      #Páginas do usuário Seleção
        $acao= $_GET['esc'];
        if($acao == 'cadAluno'){
            include_once('cadAluno.php');
          }elseif ($acao == 'lista'){
            include_once('listarAlunos.php');
          }elseif ($acao == 'upAluno'){
            include_once('up-alunos.php');
          }else{
            include_once("erro404.php");
          }
    }else{
        include_once("erro404.php");
    }
    include_once("../include/footer.php");