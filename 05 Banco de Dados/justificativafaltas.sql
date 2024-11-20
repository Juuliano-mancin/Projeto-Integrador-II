-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13-Nov-2024 às 00:20
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `justificativafaltas`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_categoria_faltas`
--

CREATE TABLE `tb_categoria_faltas` (
  `idcategoria` int(11) NOT NULL,
  `codcategoria` varchar(10) NOT NULL,
  `categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_categoria_faltas`
--

INSERT INTO `tb_categoria_faltas` (`idcategoria`, `codcategoria`, `categoria`) VALUES
(1, 'Cat01', 'Licença e Falta Médica'),
(2, 'Cat02', 'Falta Injustificada'),
(3, 'Cat03', 'Falta Justificada'),
(4, 'Cat04', 'Faltas Previstas na Legislação Trabalhista');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_cursos`
--

CREATE TABLE `tb_cursos` (
  `idcurso` int(11) NOT NULL,
  `curso` varchar(255) NOT NULL,
  `siglacurso` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_cursos`
--

INSERT INTO `tb_cursos` (`idcurso`, `curso`, `siglacurso`) VALUES
(1, 'Desenvolvimento Software Multiplataforma', 'DSM'),
(2, 'Gestão da Produção Industrial', 'GPI'),
(3, 'Gestão Industrial', 'GE');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_disciplinas`
--

CREATE TABLE `tb_disciplinas` (
  `iddisciplina` int(11) NOT NULL,
  `disciplina` varchar(255) NOT NULL,
  `idcurso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_disciplinas`
--

INSERT INTO `tb_disciplinas` (`iddisciplina`, `disciplina`, `idcurso`) VALUES
(1, 'Sistemas Operacionais', 1),
(2, 'Redes de Computadores', 1),
(3, 'Design Digital', 1),
(4, 'Algoritmo e Lógica de Programação', 1),
(5, 'Desenvolvimento Web I', 1),
(6, 'Modelagem de Banco de Dados', 1),
(7, 'Engenharia de Softwares I', 1),
(8, 'Banco de Dados Relacional', 1),
(9, 'Engenharia de Softwares II', 1),
(10, 'Matemática para Computação', 1),
(11, 'Desenvolvimento Web II', 1),
(12, 'Técnicas de Programação', 1),
(13, 'Estrutura de Dados', 1),
(14, 'Fundamentos de Administração', 2),
(15, 'Logística e Cadeia de Suprimentos', 2),
(16, 'Gestão de Projetos', 2),
(17, 'Qualidade Total', 2),
(18, 'Processos de Produção', 3),
(19, 'Planejamento e Controle da Produção', 3),
(20, 'Ergonomia e Segurança do Trabalho', 3),
(21, 'Administração da Produção', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_justificativa_faltas`
--

CREATE TABLE `tb_justificativa_faltas` (
  `idjustificativa` int(11) NOT NULL,
  `justificativa` varchar(255) NOT NULL,
  `idcategoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_justificativa_faltas`
--

INSERT INTO `tb_justificativa_faltas` (`idjustificativa`, `justificativa`, `idcategoria`) VALUES
(1, 'Falta Médica', 1),
(2, 'Comparecimento ao Médico', 1),
(3, 'Licença-Saúde', 1),
(4, 'Licença-Maternidade', 1),
(5, 'Atraso ou Saída Antecipada', 2),
(6, 'Falta Injustificada', 2),
(7, 'Atraso ou Saída Antecipada', 3),
(8, 'Falta Justificada', 3),
(9, 'Falecimento de cônjuge, pai, mãe, filho.', 4),
(10, 'Falecimento ascendente, descendente, irmão ou pessoa dependente.', 4),
(11, 'Casamento', 4),
(12, 'Nascimento de filho', 4),
(13, 'Acompanhar esposa em consultas médicas', 4),
(14, 'Acompanhar filho em consulta médica', 4),
(15, 'Doação voluntária de sangue', 4),
(16, 'Alistamento como eleitor', 4),
(17, 'Convocação para depoimento judicial', 4),
(18, 'Comparecimento como jurado no Tribunal do Júri', 4),
(19, 'Convocação para serviço eleitoral', 4),
(20, 'Dispensa por nomeação para mesas receptoras nas eleições', 4),
(21, 'Realização de Prova de Vestibular', 4),
(22, 'Comparecimento na Justiça do Trabalho', 4),
(23, 'Atrasos devido a acidentes de transporte', 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_professores`
--

CREATE TABLE `tb_professores` (
  `idprofessor` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `sobrenome` varchar(255) NOT NULL,
  `matricula` int(11) NOT NULL,
  `emailinstitucional` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `funcao` enum('professor','administrativo','coordenacao') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_professores`
--

INSERT INTO `tb_professores` (`idprofessor`, `nome`, `sobrenome`, `matricula`, `emailinstitucional`, `senha`, `funcao`) VALUES
(1, 'Maria', 'Silva', 12345, 'maria.silva@fatec.sp.gov.br', 'fatecitapira', 'professor'),
(2, 'João', 'Oliveira', 54321, 'joao.oliveira@fatec.sp.gov.br', 'fatecitapira', 'coordenacao'),
(3, 'Ana', 'Souza', 67890, 'ana.souza@fatec.sp.gov.br', 'fatecitapira', 'administrativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_reposicao_aulas`
--

CREATE TABLE `tb_reposicao_aulas` (
  `idreposicao` int(11) NOT NULL,
  `tiporeposicao` enum('Aula Ministrada','Atividades','Outros') NOT NULL,
  `cxjustificativa` varchar(255) DEFAULT NULL,
  `numeroaulas` int(11) NOT NULL,
  `datareposicao` date NOT NULL,
  `horainicioreposicao` time DEFAULT NULL,
  `horafinalreposicao` time DEFAULT NULL,
  `idprofessor` int(11) DEFAULT NULL,
  `idcurso` int(11) DEFAULT NULL,
  `iddisciplina` int(11) DEFAULT NULL,
  `statusreposicao` enum('pendente','finalizado','aprovado','reprovado') DEFAULT 'pendente',
  `idrequisicao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_reposicao_aulas`
--

INSERT INTO `tb_reposicao_aulas` (`idreposicao`, `tiporeposicao`, `cxjustificativa`, `numeroaulas`, `datareposicao`, `horainicioreposicao`, `horafinalreposicao`, `idprofessor`, `idcurso`, `iddisciplina`, `statusreposicao`, `idrequisicao`) VALUES
(2, 'Atividades', '', 1, '2024-11-20', '20:16:00', '21:17:00', 1, 1, 1, 'finalizado', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_requisicao_faltas`
--

CREATE TABLE `tb_requisicao_faltas` (
  `idrequisicao` int(11) NOT NULL,
  `idprofessor` int(11) NOT NULL,
  `idcurso` int(11) NOT NULL,
  `data_inicial` date NOT NULL,
  `data_final` date NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `idjustificativa` int(11) NOT NULL,
  `comentarioprofessor` text DEFAULT NULL,
  `comentariocoordenacao` text DEFAULT NULL,
  `statusrequisicao` enum('aprovado','reprovado','pendente','finalizado') DEFAULT 'pendente',
  `arquivo01` varchar(255) DEFAULT NULL,
  `arquivo02` varchar(255) DEFAULT NULL,
  `arquivo03` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `iddisciplina` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tb_requisicao_faltas`
--

INSERT INTO `tb_requisicao_faltas` (`idrequisicao`, `idprofessor`, `idcurso`, `data_inicial`, `data_final`, `idcategoria`, `idjustificativa`, `comentarioprofessor`, `comentariocoordenacao`, `statusrequisicao`, `arquivo01`, `arquivo02`, `arquivo03`, `data_criacao`, `data_atualizacao`, `iddisciplina`) VALUES
(5, 1, 1, '2024-11-13', '2024-11-14', 1, 1, '', '', 'finalizado', NULL, NULL, NULL, '2024-11-12 23:15:22', '2024-11-12 23:19:09', NULL);

--
-- Acionadores `tb_requisicao_faltas`
--
DELIMITER $$
CREATE TRIGGER `atualizar_statusreposicao` AFTER UPDATE ON `tb_requisicao_faltas` FOR EACH ROW BEGIN
    -- Verifica se o statusrequisicao foi alterado
    IF OLD.statusrequisicao != NEW.statusrequisicao THEN
        -- Atualiza o statusreposicao nas reposições relacionadas
        UPDATE tb_reposicao_aulas
        SET statusreposicao = NEW.statusrequisicao
        WHERE idrequisicao = NEW.idrequisicao;
    END IF;
END
$$
DELIMITER ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_categoria_faltas`
--
ALTER TABLE `tb_categoria_faltas`
  ADD PRIMARY KEY (`idcategoria`),
  ADD UNIQUE KEY `codcategoria` (`codcategoria`);

--
-- Índices para tabela `tb_cursos`
--
ALTER TABLE `tb_cursos`
  ADD PRIMARY KEY (`idcurso`),
  ADD UNIQUE KEY `siglacurso` (`siglacurso`);

--
-- Índices para tabela `tb_disciplinas`
--
ALTER TABLE `tb_disciplinas`
  ADD PRIMARY KEY (`iddisciplina`),
  ADD KEY `idcurso` (`idcurso`);

--
-- Índices para tabela `tb_justificativa_faltas`
--
ALTER TABLE `tb_justificativa_faltas`
  ADD PRIMARY KEY (`idjustificativa`),
  ADD KEY `idcategoria` (`idcategoria`);

--
-- Índices para tabela `tb_professores`
--
ALTER TABLE `tb_professores`
  ADD PRIMARY KEY (`idprofessor`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD UNIQUE KEY `emailinstitucional` (`emailinstitucional`);

--
-- Índices para tabela `tb_reposicao_aulas`
--
ALTER TABLE `tb_reposicao_aulas`
  ADD PRIMARY KEY (`idreposicao`),
  ADD KEY `idprofessor` (`idprofessor`),
  ADD KEY `idcurso` (`idcurso`),
  ADD KEY `iddisciplina` (`iddisciplina`),
  ADD KEY `fk_reposicao_requisicao` (`idrequisicao`);

--
-- Índices para tabela `tb_requisicao_faltas`
--
ALTER TABLE `tb_requisicao_faltas`
  ADD PRIMARY KEY (`idrequisicao`),
  ADD KEY `idprofessor` (`idprofessor`),
  ADD KEY `idcurso` (`idcurso`),
  ADD KEY `idcategoria` (`idcategoria`),
  ADD KEY `idjustificativa` (`idjustificativa`),
  ADD KEY `fk_iddisciplina` (`iddisciplina`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_categoria_faltas`
--
ALTER TABLE `tb_categoria_faltas`
  MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_cursos`
--
ALTER TABLE `tb_cursos`
  MODIFY `idcurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_disciplinas`
--
ALTER TABLE `tb_disciplinas`
  MODIFY `iddisciplina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `tb_justificativa_faltas`
--
ALTER TABLE `tb_justificativa_faltas`
  MODIFY `idjustificativa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `tb_professores`
--
ALTER TABLE `tb_professores`
  MODIFY `idprofessor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_reposicao_aulas`
--
ALTER TABLE `tb_reposicao_aulas`
  MODIFY `idreposicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_requisicao_faltas`
--
ALTER TABLE `tb_requisicao_faltas`
  MODIFY `idrequisicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_disciplinas`
--
ALTER TABLE `tb_disciplinas`
  ADD CONSTRAINT `tb_disciplinas_ibfk_1` FOREIGN KEY (`idcurso`) REFERENCES `tb_cursos` (`idcurso`);

--
-- Limitadores para a tabela `tb_justificativa_faltas`
--
ALTER TABLE `tb_justificativa_faltas`
  ADD CONSTRAINT `tb_justificativa_faltas_ibfk_1` FOREIGN KEY (`idcategoria`) REFERENCES `tb_categoria_faltas` (`idcategoria`);

--
-- Limitadores para a tabela `tb_reposicao_aulas`
--
ALTER TABLE `tb_reposicao_aulas`
  ADD CONSTRAINT `fk_reposicao_requisicao` FOREIGN KEY (`idrequisicao`) REFERENCES `tb_requisicao_faltas` (`idrequisicao`),
  ADD CONSTRAINT `tb_reposicao_aulas_ibfk_1` FOREIGN KEY (`idprofessor`) REFERENCES `tb_professores` (`idprofessor`),
  ADD CONSTRAINT `tb_reposicao_aulas_ibfk_2` FOREIGN KEY (`idcurso`) REFERENCES `tb_cursos` (`idcurso`),
  ADD CONSTRAINT `tb_reposicao_aulas_ibfk_3` FOREIGN KEY (`iddisciplina`) REFERENCES `tb_disciplinas` (`iddisciplina`);

--
-- Limitadores para a tabela `tb_requisicao_faltas`
--
ALTER TABLE `tb_requisicao_faltas`
  ADD CONSTRAINT `fk_iddisciplina` FOREIGN KEY (`iddisciplina`) REFERENCES `tb_disciplinas` (`iddisciplina`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_requisicao_faltas_ibfk_1` FOREIGN KEY (`idprofessor`) REFERENCES `tb_professores` (`idprofessor`),
  ADD CONSTRAINT `tb_requisicao_faltas_ibfk_2` FOREIGN KEY (`idcurso`) REFERENCES `tb_cursos` (`idcurso`),
  ADD CONSTRAINT `tb_requisicao_faltas_ibfk_3` FOREIGN KEY (`idcategoria`) REFERENCES `tb_categoria_faltas` (`idcategoria`),
  ADD CONSTRAINT `tb_requisicao_faltas_ibfk_4` FOREIGN KEY (`idjustificativa`) REFERENCES `tb_justificativa_faltas` (`idjustificativa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
