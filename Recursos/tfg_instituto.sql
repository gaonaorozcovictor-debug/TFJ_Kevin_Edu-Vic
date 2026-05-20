-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2026 at 03:57 PM
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
-- Database: `tfg_instituto`
--

-- --------------------------------------------------------

--
-- Table structure for table `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `profesor_id` int(11) DEFAULT NULL,
  `nombre_modulo` varchar(100) DEFAULT NULL,
  `grado` varchar(50) DEFAULT NULL,
  `curso` varchar(10) DEFAULT NULL,
  `horas` int(11) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modulos`
--

INSERT INTO `modulos` (`id`, `profesor_id`, `nombre_modulo`, `grado`, `curso`, `horas`, `categoria`) VALUES
(83, 11, 'Jefatura de departamento', NULL, NULL, 3, 'INF'),
(84, 9, 'Tutoría 1º + FFE', 'ASIR', '1', 3, 'INF'),
(85, 11, 'Fundamentos de hardware', 'ASIR', '1', 3, 'SAI'),
(86, 9, 'Gestión de bases de datos', 'ASIR', '1', 6, 'INF'),
(87, 11, 'Implantación de sistemas operativos', 'ASIR', '1', 7, 'SAI'),
(88, NULL, 'Fundamentos de Programación', 'ASIR', '1', 2, 'INF'),
(89, 2, 'Lenguajes de marcas y sistemas de gestión de información', 'ASIR', '1', 3, 'INF'),
(90, 2, 'Planificación y administración de redes', 'ASIR', '1', 6, 'INF'),
(91, NULL, 'Tutoría 2º + FFE', 'ASIR', '2', 4, 'INF'),
(92, NULL, 'Administración de sistemas gestores de bases de datos', 'ASIR', '2', 3, 'INF'),
(93, 1, 'Administración de sistemas operativos', 'ASIR', '2', 5, 'SAI'),
(94, NULL, 'Implantación de aplicaciones web', 'ASIR', '2', 3, 'INF'),
(95, NULL, 'Seguridad y alta disponibidad', 'ASIR', '2', 5, 'INF'),
(96, NULL, 'Arquitectura en Nube', 'ASIR', '2', 3, 'INF'),
(97, 9, 'Servicios de red e internet', 'ASIR', '2', 5, 'INF'),
(98, 1, 'Sostenibilidad', 'ASIR', '2', 1, 'SAI'),
(99, 6, 'Digitalización', 'ASIR', '2', 1, 'SAI'),
(100, 2, 'Tutoría 1º + FFE', 'DAM', '1', 3, 'INF'),
(101, NULL, 'Bases de datos', 'DAM', '1', 6, 'INF'),
(102, NULL, 'Entornos de desarrollo', 'DAM', '1', 2, 'INF'),
(103, NULL, 'Lenguajes de marcas y sistemas de gestión de información', 'DAM', '1', 3, 'INF'),
(104, NULL, 'Programación', 'DAM', '1', 8, 'INF'),
(105, NULL, 'Fundamentos de Computación en nube', 'DAM', '1', 2, 'INF'),
(106, 1, 'Sistemas informáticos', 'DAM', '1', 6, 'SAI'),
(107, NULL, 'Tutoría 2º + FFE', 'DAM', '2', 4, 'INF'),
(108, NULL, 'Acceso a datos', 'DAM', '2', 5, 'INF'),
(109, 7, 'Desarrollo de interfaces', 'DAM', '2', 5, 'SAI'),
(110, NULL, 'Programación de servicios y procesos', 'DAM', '2', 4, 'INF'),
(111, NULL, 'Programación multimedia y dispositivos móviles', 'DAM', '2', 4, 'INF'),
(112, 7, 'Sistemas de gestión empresarial', 'DAM', '2', 3, 'SAI'),
(113, 2, 'Ciberseguridad', 'DAM', '2', 3, 'INF'),
(114, 1, 'Sostenibilidad', 'DAM', '2', 1, 'SAI'),
(115, 1, 'Digitalización', 'DAM', '2', 1, 'SAI'),
(116, 17, 'Tutoría 1º + FFE', 'DAW', '1', 3, 'SAI'),
(117, NULL, 'Bases de datos', 'DAW', '1', 6, 'INF'),
(118, NULL, 'Entornos de desarrollo', 'DAW', '1', 2, 'INF'),
(119, NULL, 'Lenguajes de marcas y sistemas de gestión de información', 'DAW', '1', 3, 'INF'),
(120, NULL, 'Programación', 'DAW', '1', 8, 'INF'),
(121, NULL, 'Fundamentos de Computación en nube', 'DAW', '1', 2, 'INF'),
(122, 17, 'Sistemas informáticos', 'DAW', '1', 6, 'SAI'),
(123, NULL, 'Tutoría 2º + FFE', 'DAW', '2', 3, 'INF'),
(124, NULL, 'Desarrollo web en entorno cliente', 'DAW', '2', 6, 'SAI'),
(125, NULL, 'Desarrollo web en entorno servidor', 'DAW', '2', 8, 'INF'),
(126, NULL, 'Despliegue de aplicaciones web', 'DAW', '2', 3, 'INF'),
(127, NULL, 'Diseño de interfaces web', 'DAW', '2', 4, 'SAI'),
(128, NULL, 'Ciberseguridad', 'DAW', '2', 3, 'INF'),
(129, 17, 'Sostenibilidad', 'DAW', '2', 1, 'SAI'),
(130, 17, 'Digitalización', 'DAW', '2', 1, 'SAI'),
(131, 17, 'Tutoría 1º + FFE', 'SMR', '1A', 3, 'SAI'),
(132, NULL, 'Aplicaciones ofimáticas', 'SMR', '1A', 7, 'SAI'),
(133, 17, 'Montaje y mantenimiento de equipos', 'SMR', '1A', 6, 'SAI'),
(134, NULL, 'Apoyo de montaje y mantenimiento de equipos', 'SMR', '1A', 2, 'SAI'),
(135, NULL, 'Redes locales', 'SMR', '1A', 7, 'INF'),
(136, 9, 'Apoyo de redes locales', 'SMR', '1A', 2, 'INF'),
(137, NULL, 'Fundamentos de Bases de Datos ', 'SMR', '1A', 2, 'SAI'),
(138, NULL, 'Sistemas operativo monopuesto', 'SMR', '1A', 5, 'SAI'),
(139, 12, 'Tutoría 1º + FFE', 'SMR', '1B', 3, 'SAI'),
(140, 12, 'Aplicaciones ofimáticas', 'SMR', '1B', 7, 'SAI'),
(141, NULL, 'Montaje y mantenimiento de equipos', 'SMR', '1B', 6, 'SAI'),
(142, NULL, 'Apoyo de montaje y mantenimiento de equipos', 'SMR', '1B', 2, 'SAI'),
(143, NULL, 'Redes locales', 'SMR', '1B', 7, 'INF'),
(144, 9, 'Apoyo de redes locales', 'SMR', '1B', 2, 'INF'),
(145, 12, 'Fundamentos de Bases de Datos ', 'SMR', '1B', 2, 'SAI'),
(146, 12, 'Sistemas operativo monopuesto', 'SMR', '1B', 5, 'SAI'),
(147, NULL, 'Tutoría 2º + FFE', 'SMR', '2A', 3, 'SAI'),
(148, NULL, 'Aplicaciones web', 'SMR', '2A', 4, 'INF'),
(149, NULL, 'Seguridad Informática', 'SMR', '2A', 3, 'INF'),
(150, 9, 'Servicios en red', 'SMR', '2A', 7, 'INF'),
(151, NULL, 'Sistemas operativos en red', 'SMR', '2A', 7, 'SAI'),
(152, 9, 'Arquitectura en Nube', 'SMR', '2A', 3, 'INF'),
(153, NULL, 'Sostenibilidad', 'SMR', '2A', 1, 'SAI'),
(154, NULL, 'Digitalización', 'SMR', '2A', 1, 'SAI'),
(155, NULL, 'Tutoría 2º + FFE', 'SMR', '2B', 3, 'SAI'),
(156, NULL, 'Aplicaciones web', 'SMR', '2B', 4, 'INF'),
(157, NULL, 'Seguridad Informática', 'SMR', '2B', 3, 'INF'),
(158, NULL, 'Servicios en red', 'SMR', '2B', 7, 'INF'),
(159, NULL, 'Sistemas operativos en red', 'SMR', '2B', 7, 'SAI'),
(160, NULL, 'Arquitectura en Nube', 'SMR', '2B', 3, 'INF'),
(161, NULL, 'Sostenibilidad', 'SMR', '2B', 1, 'SAI'),
(162, NULL, 'Digitalización', 'SMR', '2B', 1, 'SAI'),
(163, 1, 'Ámbito 1º', 'BAS1', '2B', 4, 'SAI'),
(164, 12, 'Ámbito 2º', 'BAS2', '2B', 4, 'SAI');

-- --------------------------------------------------------

--
-- Table structure for table `profesores`
--

CREATE TABLE `profesores` (
  `orden` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `departamento` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profesores`
--

INSERT INTO `profesores` (`orden`, `nombre`, `categoria`, `departamento`) VALUES
(1, 'Alba Moreno Tejeda', 'PS', ''),
(2, 'Agustín González-Quel Lombardo', 'PS', ''),
(3, 'Lourdes Fernández Montoro', 'PS', ''),
(4, 'Salvador Sánchez Fernández', 'PS', ''),
(5, 'José Sala Gutiérrez', 'PS', ''),
(6, 'M Victoria Gonzalez López', 'PS', ''),
(7, 'Ángel Luis Marinas Díaz', 'PS', ''),
(8, 'Laura Sacristán Matesanz', 'PS', ''),
(9, 'Beatriz del Carmen Correa Fojo', 'PT', ''),
(10, 'Maria Rita Letona Rica', 'PT', ''),
(11, 'Carlos Rodriguez Muñoz', 'PT', ''),
(12, 'Eulalia Zaragoza Lázaro', 'PT', ''),
(13, 'Vanessa Martín Delgado', 'PT', ''),
(14, 'Llanos Soro Moralla', 'PT', ''),
(15, 'Óscar Zaera Navares', 'PT', ''),
(16, 'Sonia López Saus', 'PT', ''),
(17, 'Eva Amaral Castro', 'PT', '');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','profesor') NOT NULL,
  `profesor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `rol`, `profesor_id`) VALUES
(1, 'admin', '$2y$10$LUeHst/edDN2fFPt.OLpCeYFK90gvR477RV69u0CA3sXf3ZRgiDZ2', 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- Indexes for table `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`orden`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `profesores`
--
ALTER TABLE `profesores`
  MODIFY `orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`orden`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`orden`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
