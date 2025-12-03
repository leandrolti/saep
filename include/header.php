<!--
===============================================================================
* Soft UI Dashboard - v1.0.7 - SAEP - Seleção de Alunos da Escola Profissional
===============================================================================
* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative
* Editado por Leandro Costa
=========================================================
-->
<?php
//Iniciar Cache
ob_start();
session_start();

if (!isset($_SESSION['loginuser']) && (!isset($_SESSION['senhauser'])) && (!isset($_SESSION['nivel']))) {
    header('Location: ../index.php?acao=negado');
}
//include_once("./adminInclude/conect/conexao.php");
include_once("logout.php");
?>
<!DOCTYPE html>
<html lang="pt_br">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   Sistema | Seleção de Alunos da Escola Profissional
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/font-icons/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/css/saep.css">
</head>
<?php
// Inclui o arquivo de configuração de conexão com o banco de dados
include_once('../config/conexao.php');

// Obtém o email do usuário logado a partir da sessão
$usuarioLogado = $_SESSION['loginuser'];

// Define a consulta SQL para selecionar todos os campos do usuário com base no email
$selectUser = "SELECT * FROM tb_saep_usuario WHERE email=:emailUserLogado";

try {
    // Prepara a consulta SQL
    $resultadoUser = $conexao->prepare($selectUser);
    
    // Vincula o parâmetro :emailUserLogado ao valor da variável $usuarioLogado
    $resultadoUser->bindParam(':emailUserLogado', $usuarioLogado, PDO::PARAM_STR);
    
    // Executa a consulta preparada
    $resultadoUser->execute();

    // Conta o número de linhas retornadas pela consulta
    $contar = $resultadoUser->rowCount();
    
    // Se houver uma ou mais linhas retornadas
    if ($contar > 0) {
        // Obtém a próxima linha do conjunto de resultados como um objeto
        $show = $resultadoUser->fetch(PDO::FETCH_OBJ);
        
        // Atribui os valores dos campos do usuário às variáveis PHP
        $id_user = $show->id_Usuario;
        $foto_user = $show->img;
        $nome_user = $show->nome;
        $email_user = $show->email;
    } else {
        // Exibe uma mensagem de aviso se não houver dados de perfil
        echo '<div class="alert alert-danger"><strong>Aviso!</strong> Não há dados de perfil :(</div>';
    }
} catch (PDOException $e) {
    // Registra a mensagem de erro no log do servidor em vez de exibi-la ao usuário
    error_log("ERRO DE LOGIN DO PDO: " . $e->getMessage());
    
    // Exibe uma mensagem de erro genérica para o usuário
    echo '<div class="alert alert-danger"><strong>Aviso!</strong> Ocorreu um erro ao tentar acessar os dados do perfil.</div>';
}
?>
<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#">
        <img src="../assets/img/logos/logo-saep.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">SAEP</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="sidenav-header" style="padding: 0 25px;">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="" href="../index.php">
      <?php
            // Verifica se a variável $foto_user é igual a 'avatar-padrao.png'
            if ($show->img == 'user.png') {
                // Exibe a imagem do avatar padrão
                echo '<img src="../assets/img/avatarp/' . $show->img . '" alt="' . $show->img . '" title="' . $show->img . '" style="width:50px; border-radius:100%" />';
            } else {
                // Exibe a imagem do usuário
                echo '<img src="../assets/img/user/' . $show->img . '" alt="' . $show->img . '" title="' . $show->img . '" style="width:50px; border-radius:100%" />';
            }
          ?>
        <span class="ms-1 font-weight-bold"><a href="home.php?saep=perfil"><?php echo $nome_user; ?></a></span>
      </a>
    </div>
    <div class="col-md-12">
    <div class="col-md-4">
            
    </div>
    <div class="col-md-6">
      <a href="home.php?acao=perfil" class="d-block"></a>
    </div>
    </div>
    
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <?php
          if($_SESSION['nivel']==0){
        ?>
          <!-- Nível Admin -->
          <li class="nav-item">
            <a class="nav-link  active" href="home.php?saep=painel">
              <div class="icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <img style="width: 100%" src="../assets/img/icones/painel.png" alt="">
              </div>
              <span class="nav-link-text ms-1">Painel Principal</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  " href="home.php?saep=usuarios">
              <div class="icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <img style="width: 100%" src="../assets/img/icones/usuarios.png" alt="">
              </div>
              <span class="nav-link-text ms-1">Gerenciar Usuários</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  " href="home.php?saep=cursos">
              <div class="icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <img style="width: 100%" src="../assets/img/icones/graduando.png" alt="">
              </div>
              <span class="nav-link-text ms-1">Gerenciar Cursos</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  " href="home.php?saep=regras">
              <div class="icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <img style="width: 100%" src="../assets/img/icones/relatorio_c.png" alt="">
              </div>
              <span class="nav-link-text ms-1">Gerenciar Edital</span>
            </a>
          </li>
          <!-- Fim do nível Admin -->
        <?php
          }else{   
        ?>
        <!-- Nível Seleção Início-->
        <li class="nav-item">
          <a class="nav-link  active" href="home.php?esc=cadAluno">
            <div class="icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img style="width: 100%" src="../assets/img/icones/painel.png" alt="">
            </div>
            <span class="nav-link-text ms-1">Cadastro de Aluno(a)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="home.php?esc=lista">
            <div class="icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img style="width: 100%" src="../assets/img/icones/lista.png" alt="">
            </div>
            <span class="nav-link-text ms-1">Lista de Alunos</span>
          </a>
        </li>
       
        <!-- Nível Seleção Início-->
        <?php
          }
        ?>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Ações</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="home.php?saep=perfil">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>customer-support</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(1.000000, 0.000000)">
                        <path class="color-background opacity-6" d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z"></path>
                        <path class="color-background" d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z"></path>
                        <path class="color-background" d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Meu Perfil</span>
          </a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link  " href="?sair" onclick="return confirm('Deseja sair do sistema de seleção de novos alunos?')">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="20px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>spaceship</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1720.000000, -592.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(4.000000, 301.000000)">
                        <path class="color-background" d="M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z"></path>
                        <path class="color-background opacity-6" d="M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z"></path>
                        <path class="color-background opacity-6" d="M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z"></path>
                        <path class="color-background opacity-6" d="M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Sair do SAEP</span>
          </a>
        </li>
      </ul>
    </div>
    
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Seleção de Alunos da Escola Profissional</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">SAEP</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            
          </div>
          <ul class="navbar-nav  justify-content-end">
            
            <li class="nav-item d-flex align-items-center">
              <a href="?sair" class="nav-link text-body font-weight-bold px-0" onclick="return confirm('Deseja sair do sistema de seleção de novos alunos?')">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">Sair do SAEP</span>
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            
            
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->