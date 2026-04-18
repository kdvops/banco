-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 03-04-2026 a las 14:07:35
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `perfil_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cripto_wallets`
--

DROP TABLE IF EXISTS `cripto_wallets`;
CREATE TABLE IF NOT EXISTS `cripto_wallets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `moneda` varchar(50) DEFAULT NULL,
  `red` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `imagen` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cripto_wallets`
--

INSERT INTO `cripto_wallets` (`id`, `usuario_id`, `moneda`, `red`, `direccion`, `imagen`) VALUES
(5, 1, 'BTC', 'BTC', 'http://localhost/banco/index.php', 'btc.png'),
(4, 1, 'ETHER', 'ERC20', 'sss', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_bancarias`
--

DROP TABLE IF EXISTS `cuentas_bancarias`;
CREATE TABLE IF NOT EXISTS `cuentas_bancarias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `banco` enum('BHD','Ademi','Ban Reservas','Santa Cruz') DEFAULT NULL,
  `tipo_cuenta` enum('Ahorro','Corriente') DEFAULT NULL,
  `numero_cuenta` varchar(50) DEFAULT NULL,
  `imagen` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cuentas_bancarias`
--

INSERT INTO `cuentas_bancarias` (`id`, `usuario_id`, `banco`, `tipo_cuenta`, `numero_cuenta`, `imagen`) VALUES
(5, 1, 'BHD', 'Ahorro', '11111111', 'bhd.jpg'),
(7, 1, 'Ademi', 'Corriente', '8497071192', 'ademi.png'),
(9, 1, 'BHD', 'Ahorro', 'sss', 'bhd.png'),
(11, 1, 'Santa Cruz', 'Ahorro', '1111111', 'santa cruz.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_online`
--

DROP TABLE IF EXISTS `pagos_online`;
CREATE TABLE IF NOT EXISTS `pagos_online` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `plataforma` varchar(100) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pagos_online`
--

INSERT INTO `pagos_online` (`id`, `usuario_id`, `plataforma`, `enlace`) VALUES
(3, 1, 'PayPal', 'http://localhost/banco/index.php');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

DROP TABLE IF EXISTS `servicios`;
CREATE TABLE IF NOT EXISTS `servicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `nombre_servicio` varchar(100) DEFAULT NULL,
  `resena` text,
  `enlace` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `usuario_id`, `imagen`, `nombre_servicio`, `resena`, `enlace`) VALUES
(14, 1, 'uploads/1775010298_acuario.jpg', 'sss', 'sss', 'http://localhost/banco/index.php'),
(4, 2, 'uploads/1774579291_RobloxScreenShot20260213_230006931.png', 'ddd', 'eeee', 'http://localhost/banco/index.php'),
(5, 3, 'uploads/1774581751_RobloxScreenShot20260213_230006931.png', 'ddd', 'ddd', 'http://localhost/banco/index.php'),
(6, 3, 'uploads/1774581842_RobloxScreenShot20260213_230031511.png', 'ddd', 'sss', 'http://localhost/banco/index.php');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` varchar(200) NOT NULL,
  `cedula` varchar(11) NOT NULL,
  `resena_personal` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `email`, `numero`, `password`, `fecha_registro`, `imagen`, `cedula`, `resena_personal`) VALUES
(1, 'Sheyla', 'Medina ogando', 'sheyla@gmail.com', '8497071192', '$2y$10$RmFU70UY3ucwVMq/YzKiT.NNzXHA5FRz/kuLHFOwUhgs8h4RL/0nW', '2026-03-18 23:20:09', '1774664573_1.jpg', '22300904566', 'jjjj'),
(2, 'juan miguel', 'alcala grassal', 'klindo002@gmail.com', '8294908199', '$2y$10$HyiUssiqIALqc5q7F0l9huaxw6eFd1bRB/B5AodsQfLfZU1h6KdoG', '2026-03-27 02:19:59', 'default.png', '00000000000', 'buena gente'),
(3, 'juan miguel', 'alcala grassal', 'jmalcala@gmail.com', '8294908199', '$2y$10$bcy9wTaZBe0dr7akXwerqu0ZPCZcYaLnwT2nx8yItpomao5mrmQPa', '2026-03-27 03:02:54', '1774582111_3.png', '22300904566', 'buena gente');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
