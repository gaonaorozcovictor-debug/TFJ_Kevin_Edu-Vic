-- phpMyAdmin SQL Dump
-- Versión corregida con contraseña hasheada
-- Servidor: 134.0.14.185
-- Base de datos: `asignaciones`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Estructura de tabla `distribucion`
-- --------------------------------------------------------

CREATE TABLE `distribucion` (
  `id` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla `modulos`
-- --------------------------------------------------------

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `profesor_id` int(11) DEFAULT NULL,
  `grado` varchar(50) DEFAULT NULL,
  `nombre_modulo` varchar(100) DEFAULT NULL,
  `curso` varchar(10) DEFAULT NULL,
  `horas` int(11) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla `profesores`
-- --------------------------------------------------------

CREATE TABLE `profesores` (
  `orden` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `departamento` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla `usuarios`
-- --------------------------------------------------------

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','profesor') NOT NULL,
  `profesor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Datos de la tabla `usuarios` (contraseña: 1234 — hasheada con bcrypt)
-- --------------------------------------------------------

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `rol`, `profesor_id`) VALUES
(1, 'admin', '$2y$10$5/IuHveraoNYZXuwhD4pGeJcqHF4uBEQqx5oUa379A8dADcQxHCOC', 'admin', NULL);

-- --------------------------------------------------------
-- Índices
-- --------------------------------------------------------

ALTER TABLE `distribucion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_profesor_modulo` (`profesor_id`,`modulo_id`),
  ADD KEY `fk_distribucion_modulo` (`modulo_id`);

ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profesor_id` (`profesor_id`);

ALTER TABLE `profesores`
  ADD PRIMARY KEY (`orden`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `fk_usuarios_profesor` (`profesor_id`);

-- --------------------------------------------------------
-- AUTO_INCREMENT
-- --------------------------------------------------------

ALTER TABLE `distribucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=739;

ALTER TABLE `profesores`
  MODIFY `orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------
-- Claves foráneas
-- --------------------------------------------------------

ALTER TABLE `distribucion`
  ADD CONSTRAINT `fk_distribucion_modulo` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_distribucion_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`orden`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `modulos`
  ADD CONSTRAINT `fk_modulos_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`orden`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`orden`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
