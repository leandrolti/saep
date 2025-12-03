-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 03/12/2025 às 12:35
-- Versão do servidor: 8.0.44-0ubuntu0.24.04.1
-- Versão do PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `saep2025`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_saep_aluno`
--

CREATE TABLE `tb_saep_aluno` (
  `idtb_aluno` int NOT NULL,
  `nome_aluno` varchar(100) NOT NULL,
  `ano` varchar(45) NOT NULL,
  `tel_responsavel` varchar(20) DEFAULT NULL,
  `tipo_user` varchar(20) DEFAULT NULL,
  `media` float NOT NULL,
  `escolaridae` varchar(45) NOT NULL,
  `proximidade` varchar(45) NOT NULL,
  `deficiente` varchar(45) NOT NULL,
  `curso` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_saep_cursos`
--

CREATE TABLE `tb_saep_cursos` (
  `id_cursos` int NOT NULL,
  `id_curso_escola_cursos` int NOT NULL,
  `status_curso` int NOT NULL,
  `ano_cursos` decimal(4,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Acionadores `tb_saep_cursos`
--
DELIMITER $$
CREATE TRIGGER `before_delete_curso` BEFORE DELETE ON `tb_saep_cursos` FOR EACH ROW 
BEGIN
    -- Deletar as notas dos alunos associados ao curso
    DELETE n FROM tb_saep_notas n
    INNER JOIN tb_saep_aluno a ON n.tb_aluno_idtb_aluno = a.idtb_aluno
    WHERE a.curso = OLD.id_cursos;
END$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_saep_cursosEscola`
--

CREATE TABLE `tb_saep_cursosEscola` (
  `idCursos` int NOT NULL,
  `nomeCurso` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `imgCurso` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tb_saep_cursosEscola`
--

INSERT INTO `tb_saep_cursosEscola` (`idCursos`, `nomeCurso`, `imgCurso`) VALUES
(1, 'Administração', '645d142568e9f.png'),
(2, 'Contabilidade', '645d153c232b8.png'),
(3, 'Enfermagem', '645d159d13351.png'),
(5, 'Informática', '645d15ed413f1.png'),
(7, 'Secretaria Escolar', '645d1643dbd37.png'),
(8, 'Comércio', '691b2c765d48f.png'),
(13, 'Agronegócio', '691b2b52ce177.png'),
(14, 'Logística', '691b2c99959d7.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_saep_edital`
--

CREATE TABLE `tb_saep_edital` (
  `id_edital` int NOT NULL,
  `qtd_aluno` int NOT NULL,
  `aluno_publica` int NOT NULL,
  `aluno_prox_pul` int NOT NULL,
  `aluno_particular` int NOT NULL,
  `aluno_prox_part` int NOT NULL,
  `com_laudo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tb_saep_edital`
--

INSERT INTO `tb_saep_edital` (`id_edital`, `qtd_aluno`, `aluno_publica`, `aluno_prox_pul`, `aluno_particular`, `aluno_prox_part`, `com_laudo`) VALUES
(8, 45, 36, 10, 9, 3, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_saep_notas`
--

CREATE TABLE `tb_saep_notas` (
  `idtb_notas` int NOT NULL,
  `tb_aluno_idtb_aluno` int NOT NULL,
  `ano` int NOT NULL,
  `port` float DEFAULT NULL,
  `mat` float DEFAULT NULL,
  `hist` float DEFAULT NULL,
  `geo` float DEFAULT NULL,
  `cien` float DEFAULT NULL,
  `art` float DEFAULT NULL,
  `rel` float DEFAULT NULL,
  `edf` float DEFAULT NULL,
  `ing` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_saep_usuario`
--

CREATE TABLE `tb_saep_usuario` (
  `id_Usuario` int NOT NULL,
  `email` varchar(150) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `senha` varchar(75) NOT NULL,
  `nivel` varchar(150) NOT NULL,
  `cargo` varchar(150) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `tb_saep_usuario`
--

INSERT INTO `tb_saep_usuario` (`id_Usuario`, `email`, `nome`, `senha`, `nivel`, `cargo`, `img`) VALUES
(122, 'admin@gmail.com', 'Admin', '$2y$10$ynUdwOPkpqX6Sxh7/tMpgODbnS2h3tMcH.l4mmArTmIG.dcEPf1Rm', '0', 'Diretor', 'user.png'),
(123, 'ins@gmail.com', 'Inscrição ', '$2y$10$gW6kqgRQt95Fnbl7aivxqevlA.xOUFjpReUspPVjzmsPCGKMcGbKK', '1', 'Secretaria Escolar', 'user.png');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_saep_aluno`
--
ALTER TABLE `tb_saep_aluno`
  ADD PRIMARY KEY (`idtb_aluno`),
  ADD KEY `fk_aluno_curso` (`curso`);

--
-- Índices de tabela `tb_saep_cursos`
--
ALTER TABLE `tb_saep_cursos`
  ADD PRIMARY KEY (`id_cursos`);

--
-- Índices de tabela `tb_saep_cursosEscola`
--
ALTER TABLE `tb_saep_cursosEscola`
  ADD PRIMARY KEY (`idCursos`);

--
-- Índices de tabela `tb_saep_edital`
--
ALTER TABLE `tb_saep_edital`
  ADD PRIMARY KEY (`id_edital`);

--
-- Índices de tabela `tb_saep_notas`
--
ALTER TABLE `tb_saep_notas`
  ADD PRIMARY KEY (`idtb_notas`),
  ADD KEY `fk_notas_aluno` (`tb_aluno_idtb_aluno`);

--
-- Índices de tabela `tb_saep_usuario`
--
ALTER TABLE `tb_saep_usuario`
  ADD PRIMARY KEY (`id_Usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_saep_aluno`
--
ALTER TABLE `tb_saep_aluno`
  MODIFY `idtb_aluno` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de tabela `tb_saep_cursos`
--
ALTER TABLE `tb_saep_cursos`
  MODIFY `id_cursos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tb_saep_cursosEscola`
--
ALTER TABLE `tb_saep_cursosEscola`
  MODIFY `idCursos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `tb_saep_edital`
--
ALTER TABLE `tb_saep_edital`
  MODIFY `id_edital` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tb_saep_notas`
--
ALTER TABLE `tb_saep_notas`
  MODIFY `idtb_notas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT de tabela `tb_saep_usuario`
--
ALTER TABLE `tb_saep_usuario`
  MODIFY `id_Usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tb_saep_aluno`
--
ALTER TABLE `tb_saep_aluno`
  ADD CONSTRAINT `fk_aluno_curso` FOREIGN KEY (`curso`) REFERENCES `tb_saep_cursos` (`id_cursos`) ON DELETE CASCADE;

--
-- Restrições para tabelas `tb_saep_notas`
--
ALTER TABLE `tb_saep_notas`
  ADD CONSTRAINT `fk_notas_aluno` FOREIGN KEY (`tb_aluno_idtb_aluno`) REFERENCES `tb_saep_aluno` (`idtb_aluno`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
