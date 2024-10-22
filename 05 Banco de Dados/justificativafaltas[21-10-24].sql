-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2024 at 04:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `justificativafaltas`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_professores`
--

CREATE TABLE `tb_professores` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `sobrenome` varchar(255) NOT NULL,
  `matricula` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `função` enum('professor','coordenacao','administrativo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_professores`
--

INSERT INTO `tb_professores` (`id`, `nome`, `sobrenome`, `matricula`, `email`, `senha`, `função`) VALUES
(1, 'Ana', 'Silva', 10001, 'ana.silva@fatec.sp.gov.br', 'ana', 'professor'),
(2, 'Carlos', 'Souza', 10002, 'carlos.souza@fatec.sp.gov.br', 'carlos', 'professor'),
(3, 'Fernanda', 'Oliveira', 10003, 'fernanda.oliveira@fatec.sp.gov.br', 'fernanda', 'professor'),
(4, 'João', 'Pereira', 10004, 'joao.pereira@fatec.sp.gov.br', 'joao', 'professor'),
(5, 'Mariana', 'Costa', 10005, 'mariana.costa@fatec.sp.gov.br', 'mariana', 'professor'),
(6, 'Rafael', 'Lima', 10006, 'rafael.lima@fatec.sp.gov.br', 'rafael', 'administrativo'),
(7, 'Gabriela', 'Mendes', 10007, 'gabriela.mendes@fatec.sp.gov.br', 'gabriela', 'administrativo'),
(8, 'Lucas', 'Araujo', 10008, 'lucas.araujo@fatec.sp.gov.br', 'lucas', 'administrativo'),
(9, 'Paula', 'Freitas', 10009, 'paula.freitas@fatec.sp.gov.br', 'paula', 'coordenacao'),
(10, 'Bruno', 'Alves', 10010, 'bruno.alves@fatec.sp.gov.br', 'bruno', 'coordenacao');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_professores`
--
ALTER TABLE `tb_professores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_professores`
--
ALTER TABLE `tb_professores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
