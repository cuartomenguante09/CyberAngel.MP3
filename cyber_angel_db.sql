-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-10-2026 a las 15:40:32
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cyber_angel_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `nombre_contacto` varchar(100) NOT NULL,
  `usuario_id` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `preview` text NOT NULL,
  `hora` varchar(20) NOT NULL,
  `clase_color` varchar(50) DEFAULT 'name-sofia',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `nombre_contacto`, `usuario_id`, `avatar`, `preview`, `hora`, `clase_color`, `fecha`) VALUES
(1, 'Maiiiittteee', 'maiti', '../img/maitena.jpg', 'olaaaaaaa', '1:11', 'name-maiti', '2026-09-19 11:05:33'),
(2, 'Lauuurraaa', 'laura', '../img/laura.jpg', 'hola, no', '4:44', 'name-laura', '2026-09-19 11:05:33'),
(3, 'Azzzuuuuuumyiyy', 'azu', '../img/azumy.jpg', 'quieroqege', '17:17', 'name-azu', '2026-09-19 11:05:33'),
(4, 'Sooofiiiiiaaaaa', 'sofia', '../img/sofia.jpg', '★ Sticker', '10:10', 'name-sofia', '2026-09-19 11:05:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `caption` text DEFAULT NULL,
  `mood` varchar(50) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `imagen`, `caption`, `mood`, `fecha`) VALUES
(1, '34a9a229be9f6183352c3fd6f50e70ec.jpg', '', 'chilling', '2026-09-19 11:00:05'),
(2, 'cb7218b30212617019e456579afee771.jpg', '', 'chilling', '2026-09-19 11:43:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT '../img/perfil.jfif',
  `mood` varchar(100) DEFAULT 'divaa <3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `nombre`, `edad`, `bio`, `session_token`, `avatar`, `mood`) VALUES
(1, 'sofia', 'holi', 'jjj', 3, 'holi', 'cyber_6ad613df37d8c6.87649639', '../img/perfil.jfif', 'en esa'),
(2, 'laurA', '1234', NULL, NULL, NULL, NULL, '../img/perfil.jfif', 'divaa <3'),
(3, 'sofia', 'holi', NULL, NULL, NULL, NULL, '../img/perfil.jfif', 'divaa <3'),
(4, 'sofia', 'holi', NULL, NULL, NULL, NULL, '../img/perfil.jfif', 'divaa <3'),
(5, 'sofia', 'holi', NULL, NULL, NULL, NULL, '../img/perfil.jfif', 'divaa <3'),
(6, 'sofia', 'holi', NULL, NULL, NULL, NULL, '../img/perfil.jfif', 'divaa <3'),
(7, 'sofia', 'holi', NULL, NULL, NULL, NULL, '../img/perfil.jfif', 'divaa <3'),
(8, 'sofia', 'holi', NULL, NULL, NULL, NULL, '../img/perfil.jfif', 'divaa <3');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
