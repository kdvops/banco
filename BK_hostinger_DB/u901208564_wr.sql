-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 18, 2026 at 11:22 PM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u901208564_wr`
--

-- --------------------------------------------------------

--
-- Table structure for table `citas`
--

DROP TABLE IF EXISTS `citas`;
CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `id_negocio` varchar(200) NOT NULL,
  `id_usuario` varchar(200) NOT NULL,
  `fecha` varchar(200) NOT NULL,
  `hora_programada` varchar(200) NOT NULL,
  `estado` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `citas`
--

TRUNCATE TABLE `citas`;
-- --------------------------------------------------------

--
-- Table structure for table `cripto_wallets`
--

DROP TABLE IF EXISTS `cripto_wallets`;
CREATE TABLE `cripto_wallets` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `moneda` varchar(50) DEFAULT NULL,
  `red` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `imagen` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `cripto_wallets`
--

TRUNCATE TABLE `cripto_wallets`;
--
-- Dumping data for table `cripto_wallets`
--

INSERT INTO `cripto_wallets` (`id`, `usuario_id`, `moneda`, `red`, `direccion`, `imagen`) VALUES
(5, 1, 'BTC', 'BTC', 'http://localhost/banco/index.php', 'btc.png'),
(4, 1, 'ETHER', 'ERC20', 'sss', '');

-- --------------------------------------------------------

--
-- Table structure for table `cuentas_bancarias`
--

DROP TABLE IF EXISTS `cuentas_bancarias`;
CREATE TABLE `cuentas_bancarias` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `banco` enum('BHD','Ademi','Ban Reservas','Santa Cruz','BPD') DEFAULT NULL,
  `tipo_cuenta` enum('Ahorro','Corriente') DEFAULT NULL,
  `numero_cuenta` varchar(50) DEFAULT NULL,
  `imagen` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `cuentas_bancarias`
--

TRUNCATE TABLE `cuentas_bancarias`;
--
-- Dumping data for table `cuentas_bancarias`
--

INSERT INTO `cuentas_bancarias` (`id`, `usuario_id`, `banco`, `tipo_cuenta`, `numero_cuenta`, `imagen`) VALUES
(5, 1, 'BHD', 'Ahorro', '11111111', 'bhd.jpg'),
(7, 1, 'Ademi', 'Corriente', '8497071192', 'ademi.png'),
(9, 1, 'BHD', 'Ahorro', 'sss', 'bhd.png'),
(11, 1, 'Santa Cruz', 'Ahorro', '1111111', 'santa cruz.png'),
(12, 4, 'BHD', 'Ahorro', '258258258258', 'bhd.png');

-- --------------------------------------------------------

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(200) NOT NULL,
  `id_negocio` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `favoritos`
--

TRUNCATE TABLE `favoritos`;
--
-- Dumping data for table `favoritos`
--

INSERT INTO `favoritos` (`id`, `id_usuario`, `id_negocio`) VALUES
(1, '2', '2'),
(2, '2', ''),
(3, '2', '1'),
(4, '3', '1'),
(5, '4', '1');

-- --------------------------------------------------------

--
-- Table structure for table `horarios`
--

DROP TABLE IF EXISTS `horarios`;
CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `dias` varchar(200) NOT NULL,
  `entrada` varchar(200) NOT NULL,
  `salida` varchar(200) NOT NULL,
  `id_negocio` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `horarios`
--

TRUNCATE TABLE `horarios`;
--
-- Dumping data for table `horarios`
--

INSERT INTO `horarios` (`id`, `dias`, `entrada`, `salida`, `id_negocio`) VALUES
(1, 'Lunes', '8:00 A.M A 12:00 P.M', '2:00 PM A 6:00PM', '1'),
(2, 'Martes', '8:00 A.M A 12:00 P.M', '12:00 P.M A 6:00 P.M', '1');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `id_negocio` varchar(200) NOT NULL,
  `cantidad_total` varchar(200) NOT NULL,
  `cantidad_por_turno` varchar(200) NOT NULL,
  `tiempo_entre_servicio` varchar(200) NOT NULL,
  `abierto_cerrado` varchar(200) NOT NULL,
  `hora_abre` varchar(200) NOT NULL,
  `hora_cierra` varchar(200) NOT NULL,
  `turnos_abiertos_cerrados` varchar(200) NOT NULL,
  `max_diario` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `menu`
--

TRUNCATE TABLE `menu`;
--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `id_negocio`, `cantidad_total`, `cantidad_por_turno`, `tiempo_entre_servicio`, `abierto_cerrado`, `hora_abre`, `hora_cierra`, `turnos_abiertos_cerrados`, `max_diario`) VALUES
(1, '1', '10', '3', '05', 'abierto', '8:00', '24:30', '1', '2');

-- --------------------------------------------------------

--
-- Table structure for table `negocios`
--

DROP TABLE IF EXISTS `negocios`;
CREATE TABLE `negocios` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(200) NOT NULL,
  `nombre_neg` varchar(200) NOT NULL,
  `eslogan` varchar(200) NOT NULL,
  `logo` varchar(200) NOT NULL,
  `telefono_neg` varchar(20) NOT NULL,
  `direccion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `negocios`
--

TRUNCATE TABLE `negocios`;
--
-- Dumping data for table `negocios`
--

INSERT INTO `negocios` (`id`, `id_usuario`, `nombre_neg`, `eslogan`, `logo`, `telefono_neg`, `direccion`) VALUES
(1, '2', 'Glamour Beauty Center', 'Mas que belleza trabajamos el interios', 'glamour.jpg', '8497071192', 'jerusalen');

-- --------------------------------------------------------

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(200) NOT NULL,
  `ofertas` varchar(200) NOT NULL,
  `promociones` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `notificaciones`
--

TRUNCATE TABLE `notificaciones`;
-- --------------------------------------------------------

--
-- Table structure for table `pagos_online`
--

DROP TABLE IF EXISTS `pagos_online`;
CREATE TABLE `pagos_online` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `plataforma` varchar(100) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `pagos_online`
--

TRUNCATE TABLE `pagos_online`;
--
-- Dumping data for table `pagos_online`
--

INSERT INTO `pagos_online` (`id`, `usuario_id`, `plataforma`, `enlace`) VALUES
(3, 1, 'PayPal', 'http://localhost/banco/index.php'),
(4, 2, 'Zelle', 'https://www.facebook.com/share/r/1A2spR8n6y/');

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(200) NOT NULL,
  `id_negocio` varchar(200) NOT NULL,
  `fecha` varchar(200) NOT NULL,
  `estado` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `reservas`
--

TRUNCATE TABLE `reservas`;
-- --------------------------------------------------------

--
-- Table structure for table `seguros`
--

DROP TABLE IF EXISTS `seguros`;
CREATE TABLE `seguros` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(2) NOT NULL,
  `titular_seguro` varchar(200) NOT NULL,
  `tipo_de_seguro` varchar(200) NOT NULL,
  `poliza` varchar(200) NOT NULL,
  `vencimiento` varchar(200) NOT NULL,
  `aseguradora` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `seguros`
--

TRUNCATE TABLE `seguros`;
-- --------------------------------------------------------

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `nombre_servicio` varchar(100) DEFAULT NULL,
  `resena` text DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `servicios`
--

TRUNCATE TABLE `servicios`;
--
-- Dumping data for table `servicios`
--

INSERT INTO `servicios` (`id`, `usuario_id`, `imagen`, `nombre_servicio`, `resena`, `enlace`) VALUES
(14, 1, 'uploads/1775010298_acuario.jpg', 'sss', 'sss', 'http://localhost/banco/index.php'),
(4, 2, 'uploads/1774579291_RobloxScreenShot20260213_230006931.png', 'ddd', 'eeee', 'http://localhost/banco/index.php'),
(5, 3, 'uploads/1774581751_RobloxScreenShot20260213_230006931.png', 'ddd', 'ddd', 'http://localhost/banco/index.php'),
(6, 3, 'uploads/1774581842_RobloxScreenShot20260213_230031511.png', 'ddd', 'sss', 'http://localhost/banco/index.php'),
(15, 2, 'uploads/1776435167_1000624804.jpg', 'Glamour', 'Salón de belleza', 'https://www.facebook.com/share/r/1A2spR8n6y/');

-- --------------------------------------------------------

--
-- Table structure for table `tarjetas`
--

DROP TABLE IF EXISTS `tarjetas`;
CREATE TABLE `tarjetas` (
  `id` int(11) NOT NULL,
  `n_tarjeta` varchar(200) NOT NULL,
  `fecha` varchar(200) NOT NULL,
  `cvv` varchar(200) NOT NULL,
  `id_usuario` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `tarjetas`
--

TRUNCATE TABLE `tarjetas`;
-- --------------------------------------------------------

--
-- Table structure for table `turnos`
--

DROP TABLE IF EXISTS `turnos`;
CREATE TABLE `turnos` (
  `id` int(11) NOT NULL,
  `id_negocio` varchar(200) NOT NULL,
  `id_usuario` varchar(200) NOT NULL,
  `fecha` varchar(200) NOT NULL,
  `hora_programada` varchar(200) NOT NULL,
  `estado` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `turnos`
--

TRUNCATE TABLE `turnos`;
--
-- Dumping data for table `turnos`
--

INSERT INTO `turnos` (`id`, `id_negocio`, `id_usuario`, `fecha`, `hora_programada`, `estado`) VALUES
(38, '1', '3', '2025-10-08', '', 'confirmado');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT current_timestamp(),
  `imagen` varchar(200) NOT NULL,
  `cedula` varchar(11) NOT NULL,
  `resena_personal` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `usuarios`
--

TRUNCATE TABLE `usuarios`;
--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `email`, `numero`, `password`, `fecha_registro`, `imagen`, `cedula`, `resena_personal`) VALUES
(1, 'Sheyla', 'Medina ogando', 'sheyla@gmail.com', '8497071192', '$2y$10$RmFU70UY3ucwVMq/YzKiT.NNzXHA5FRz/kuLHFOwUhgs8h4RL/0nW', '2026-03-18 23:20:09', '1774664573_1.jpg', '22300904566', 'jjjj'),
(2, 'juan miguel', 'alcala grassal', 'klindo002@gmail.com', '8294908199', '$2y$10$HyiUssiqIALqc5q7F0l9huaxw6eFd1bRB/B5AodsQfLfZU1h6KdoG', '2026-03-27 02:19:59', 'default.png', '22300904566', 'buena gente'),
(3, 'juan miguel', 'alcala grassal', 'jmalcala@gmail.com', '8294908199', '$2y$10$bcy9wTaZBe0dr7akXwerqu0ZPCZcYaLnwT2nx8yItpomao5mrmQPa', '2026-03-27 03:02:54', '1774582111_3.png', '22300904566', 'buena gente'),
(4, 'Kelvin', 'Grassal', 'kelvin.ag89@gmail.com', '8493504723', '$2y$10$CGOuxUHI15taS.aLPCbehulU2Jgm37ZF1nqY8V8GK.P2gzqJxhD2K', '2026-04-17 05:16:52', 'perfil.png', '00000000000', 'Sin descripción personal.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cripto_wallets`
--
ALTER TABLE `cripto_wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `cuentas_bancarias`
--
ALTER TABLE `cuentas_bancarias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `negocios`
--
ALTER TABLE `negocios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pagos_online`
--
ALTER TABLE `pagos_online`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seguros`
--
ALTER TABLE `seguros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cripto_wallets`
--
ALTER TABLE `cripto_wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cuentas_bancarias`
--
ALTER TABLE `cuentas_bancarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `negocios`
--
ALTER TABLE `negocios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagos_online`
--
ALTER TABLE `pagos_online`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seguros`
--
ALTER TABLE `seguros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tarjetas`
--
ALTER TABLE `tarjetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
